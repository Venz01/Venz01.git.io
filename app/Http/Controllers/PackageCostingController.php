<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageCosting;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PackageCostingController extends Controller
{
    // ── Standalone Costing Dashboard ─────────────────────────────────────────

    /**
     * List all packages with their costing status for the authenticated caterer.
     */
    public function index()
    {
        $catererId = auth()->id();

        $packages = Package::where('user_id', $catererId)
            ->with(['costing', 'items'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($package) {
                $costing = $package->costing;

                return [
                    'id'               => $package->id,
                    'name'             => $package->name,
                    'status'           => $package->status,
                    'current_price'    => $package->price,
                    'pax'              => $package->pax,
                    'items_count'      => $package->items->count(),
                    'has_costing'      => !is_null($costing),
                    'costing_id'       => $costing?->id,
                    'total_cost'       => $costing?->total_cost ?? 0,
                    'suggested_price'  => $costing?->calculated_price ?? 0,
                    'final_price'      => $costing?->final_price ?? $package->price,
                    'margin_percent'   => $costing?->actual_margin_percent,
                    'components_count' => $costing?->filled_components_count ?? 0,
                    'image_path'       => $package->image_path,
                ];
            });

        // Summary stats
        $stats = [
            'total_packages'    => $packages->count(),
            'costed_packages'   => $packages->where('has_costing', true)->count(),
            'avg_margin'        => $packages->whereNotNull('margin_percent')->avg('margin_percent'),
            'avg_price'         => $packages->avg('current_price'),
        ];

        return view('caterer.costing.index', compact('packages', 'stats'));
    }

    // ── Show/Edit Single Package Costing ─────────────────────────────────────

    /**
     * Show the costing tool for a specific package.
     * Accessible from: package card, booking flow, costing dashboard.
     */
    public function show(Package $package)
    {
        $this->authorizePackage($package);

        $costing = PackageCosting::firstOrNew(
            ['package_id' => $package->id],
            [
                'user_id'               => auth()->id(),
                'profit_margin_percent' => 25.00,
            ]
        );

        $package->load('items.category');

        // Historical booking revenue for this package
        $bookingHistory = Booking::where('caterer_id', auth()->id())
            ->where('package_id', $package->id)
            ->whereIn('booking_status', ['confirmed', 'completed'])
            ->selectRaw('
                COUNT(*) as total_bookings,
                AVG(price_per_head) as avg_price_per_head,
                SUM(total_price) as total_revenue,
                AVG(guests) as avg_guests
            ')
            ->first();

        return view('caterer.costing.show', compact('package', 'costing', 'bookingHistory'));
    }

    // ── Save / Update Costing ─────────────────────────────────────────────────

    public function store(Request $request, Package $package)
    {
        $this->authorizePackage($package);

        $validated = $request->validate([
            'ingredient_cost'       => 'nullable|numeric|min:0',
            'labor_cost'            => 'nullable|numeric|min:0',
            'equipment_cost'        => 'nullable|numeric|min:0',
            'consumables_cost'      => 'nullable|numeric|min:0',
            'overhead_cost'         => 'nullable|numeric|min:0',
            'transport_cost'        => 'nullable|numeric|min:0',
            'profit_margin_percent' => 'required|numeric|min:0|max:100',
            'final_price'           => 'nullable|numeric|min:0',
            'notes'                 => 'nullable|string|max:1000',
            'apply_to_package'      => 'boolean', // sync final_price → packages.price
        ]);

        DB::beginTransaction();
        try {
            // Calculate suggested price
            $totalCost = collect([
                'ingredient_cost', 'labor_cost', 'equipment_cost',
                'consumables_cost', 'overhead_cost', 'transport_cost',
            ])->sum(fn ($key) => (float) ($validated[$key] ?? 0));

            $profitAmount    = $totalCost * ($validated['profit_margin_percent'] / 100);
            $suggestedPrice  = ceil(($totalCost + $profitAmount) / 5) * 5;

            $costing = PackageCosting::updateOrCreate(
                ['package_id' => $package->id],
                array_merge(
                    $validated,
                    [
                        'user_id'        => auth()->id(),
                        'suggested_price' => $suggestedPrice,
                    ]
                )
            );

            // Optionally push the final_price back to the package
            if ($request->boolean('apply_to_package') && !is_null($validated['final_price'])) {
                $package->update(['price' => $validated['final_price']]);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success'         => true,
                    'total_cost'      => $totalCost,
                    'suggested_price' => $suggestedPrice,
                    'costing'         => $costing,
                ]);
            }

            return back()->with('success', 'Costing saved successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('PackageCosting save failed', ['error' => $e->getMessage()]);

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to save costing.'], 500);
            }

            return back()->with('error', 'Failed to save costing. Please try again.');
        }
    }

    // ── Live Calculate (AJAX) ─────────────────────────────────────────────────

    /**
     * Real-time calculation endpoint — called on every keystroke in the UI.
     * No DB writes; just returns computed values.
     */
    public function calculate(Request $request)
    {
        $data = $request->validate([
            'ingredient_cost'       => 'nullable|numeric|min:0',
            'labor_cost'            => 'nullable|numeric|min:0',
            'equipment_cost'        => 'nullable|numeric|min:0',
            'consumables_cost'      => 'nullable|numeric|min:0',
            'overhead_cost'         => 'nullable|numeric|min:0',
            'transport_cost'        => 'nullable|numeric|min:0',
            'profit_margin_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $components = [
            'ingredient_cost', 'labor_cost', 'equipment_cost',
            'consumables_cost', 'overhead_cost', 'transport_cost',
        ];

        $breakdown   = [];
        $totalCost   = 0;

        foreach ($components as $key) {
            $amount = (float) ($data[$key] ?? 0);
            $totalCost += $amount;
            $breakdown[$key] = $amount;
        }

        $marginPercent  = (float) ($data['profit_margin_percent'] ?? 25);
        $profitAmount   = $totalCost * ($marginPercent / 100);
        $suggestedPrice = $totalCost > 0 ? ceil(($totalCost + $profitAmount) / 5) * 5 : 0;

        // Percentage breakdown of each component
        foreach ($breakdown as $key => $amount) {
            $breakdown[$key] = [
                'amount'  => $amount,
                'percent' => $totalCost > 0 ? round(($amount / $totalCost) * 100, 1) : 0,
            ];
        }

        return response()->json([
            'total_cost'      => round($totalCost, 2),
            'profit_amount'   => round($profitAmount, 2),
            'suggested_price' => $suggestedPrice,
            'breakdown'       => $breakdown,
        ]);
    }

    // ── Quotation PDF ─────────────────────────────────────────────────────────

    /**
     * Generate and stream a printable quotation PDF for a booking.
     * Route: GET /caterer/bookings/{booking}/quotation
     */
    public function generateQuotation(Booking $booking)
    {
        // Ensure this booking belongs to the authenticated caterer
        if ($booking->caterer_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['package.items.category', 'caterer', 'customer']);
        $costing = PackageCosting::where('package_id', $booking->package_id)->first();

        // Build the PDF using the Blade-based approach (weasyprint / dompdf via view)
        $html = view('caterer.costing.quotation-pdf', compact('booking', 'costing'))->render();

        // Use DomPDF if available, otherwise return HTML for browser print
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)
                ->setPaper('a4', 'portrait');

            return $pdf->stream("quotation-{$booking->booking_number}.pdf");
        }

        // Fallback: return the HTML view (caterer can Ctrl+P)
        return view('caterer.costing.quotation-pdf', compact('booking', 'costing'));
    }

    /**
     * Generate a standalone package quotation (not tied to a booking yet).
     * Used from the costing dashboard / package detail page.
     */
    public function generatePackageQuotation(Request $request, Package $package)
    {
        $this->authorizePackage($package);

        $validated = $request->validate([
            'guest_count'    => 'required|integer|min:1',
            'customer_name'  => 'nullable|string|max:255',
            'event_type'     => 'nullable|string|max:255',
            'event_date'     => 'nullable|date',
            'validity_days'  => 'nullable|integer|min:1|max:90',
        ]);

        $validityDays = (int) ($validated['validity_days'] ?? 7);

        $package->load(['items.category', 'user', 'costing']);
        $costing = $package->costing;

        $guestCount  = (int) $validated['guest_count'];
        $totalAmount = $package->price * $guestCount;

        $quoteData = [
            'package'        => $package,
            'costing'        => $costing,
            'guest_count'    => $guestCount,
            'customer_name'  => $validated['customer_name'] ?? 'Valued Customer',
            'event_type'     => $validated['event_type'] ?? 'Special Event',
            'event_date'     => isset($validated['event_date'])
                                    ? Carbon::parse($validated['event_date'])
                                    : null,
            'valid_until'    => Carbon::now()->addDays($validityDays),
            'total_amount'   => $totalAmount,
            'deposit_amount' => $totalAmount * 0.25,
            'reference_no'   => 'QT-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            'caterer'        => $package->user,
            'generated_at'   => Carbon::now(),
        ];

        return view('caterer.costing.package-quotation-pdf', $quoteData);
    }


    // ── Clone Costing Template ────────────────────────────────────────────────

    /**
     * Copy costing from one package to another (useful for similar packages).
     */
    public function cloneCosting(Request $request)
    {
        $request->validate([
            'source_package_id' => 'required|exists:packages,id',
            'target_package_id' => 'required|exists:packages,id|different:source_package_id',
        ]);

        $catererId = auth()->id();

        $source = Package::where('user_id', $catererId)->findOrFail($request->source_package_id);
        $target = Package::where('user_id', $catererId)->findOrFail($request->target_package_id);

        $sourceCosting = PackageCosting::where('package_id', $source->id)->first();

        if (!$sourceCosting) {
            return back()->with('error', 'Source package has no costing to clone.');
        }

        PackageCosting::updateOrCreate(
            ['package_id' => $target->id],
            array_merge(
                $sourceCosting->only([
                    'ingredient_cost', 'labor_cost', 'equipment_cost',
                    'consumables_cost', 'overhead_cost', 'transport_cost',
                    'profit_margin_percent',
                ]),
                ['user_id' => $catererId, 'final_price' => null, 'suggested_price' => null]
            )
        );

        return back()->with('success', "Costing template cloned from \"{$source->name}\" to \"{$target->name}\".");
    }

    // ── Private Helpers ───────────────────────────────────────────────────────

    private function authorizePackage(Package $package): void
    {
        if ($package->user_id !== auth()->id()) {
            abort(403, 'Unauthorized: This package does not belong to you.');
        }
    }
}
