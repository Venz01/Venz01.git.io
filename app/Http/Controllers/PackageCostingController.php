<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageCosting;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCostingRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PackageCostingController extends Controller
{
    // ── Standalone Costing Dashboard ─────────────────────────────────────────

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
                    'is_default_template' => $costing?->is_default_template ?? false,
                    'template_name'    => $costing?->template_name,
                ];
            });

        // The caterer's current default template
        $defaultTemplate = PackageCosting::getDefaultForCaterer($catererId);

        // All available templates (costings with filled data)
        $availableTemplates = PackageCosting::templatesForCaterer($catererId);

        $stats = [
            'total_packages'  => $packages->count(),
            'costed_packages' => $packages->where('has_costing', true)->count(),
            'avg_margin'      => $packages->whereNotNull('margin_percent')->avg('margin_percent'),
            'avg_price'       => $packages->avg('current_price'),
        ];

        return view('caterer.costing.index', compact(
            'packages', 'stats', 'defaultTemplate', 'availableTemplates'
        ));
    }

    // ── Show/Edit Single Package Costing ─────────────────────────────────────

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

        // For the "set as default" toggle: pass all templates so the view
        // can show which one is currently default
        $defaultTemplate    = PackageCosting::getDefaultForCaterer(auth()->id());
        $availableTemplates = PackageCosting::templatesForCaterer(auth()->id());

        return view('caterer.costing.show', compact(
            'package', 'costing', 'bookingHistory', 'defaultTemplate', 'availableTemplates'
        ));
    }

    // ── Save / Update Costing ─────────────────────────────────────────────────

    public function store(StoreCostingRequest $request, Package $package)
    {
        $this->authorizePackage($package);

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $totalCost = collect([
                'ingredient_cost', 'labor_cost', 'equipment_cost',
                'consumables_cost', 'overhead_cost', 'transport_cost',
            ])->sum(fn ($key) => (float) ($validated[$key] ?? 0));

            $profitAmount   = $totalCost * ($validated['profit_margin_percent'] / 100);
            $suggestedPrice = ceil(($totalCost + $profitAmount) / 5) * 5;

            $costing = PackageCosting::updateOrCreate(
                ['package_id' => $package->id],
                array_merge(
                    $validated,
                    [
                        'user_id'         => auth()->id(),
                        'suggested_price' => $suggestedPrice,
                    ]
                )
            );

            // Handle "set as default template"
            if ($request->boolean('set_as_default')) {
                $costing->setAsDefault();
            }

            if ($request->boolean('apply_to_package') && !is_null($validated['final_price'])) {
                $package->update(['price' => $validated['final_price']]);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success'            => true,
                    'total_cost'         => $totalCost,
                    'suggested_price'    => $suggestedPrice,
                    'costing'            => $costing,
                    'is_default'         => $costing->is_default_template,
                ]);
            }

            return back()->with('success', 'Costing saved successfully!' .
                ($costing->is_default_template ? ' This is now your default template.' : ''));

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('PackageCosting save failed', ['error' => $e->getMessage()]);

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to save costing.'], 500);
            }

            return back()->with('error', 'Failed to save costing. Please try again.');
        }
    }

    // ── Set Default Template (standalone AJAX/form action) ───────────────────

    /**
     * POST /caterer/costing/{costing}/set-default
     * Marks one costing row as the default template for the authenticated caterer.
     */
    public function setDefault(PackageCosting $costing)
    {
        if ($costing->user_id !== auth()->id()) {
            abort(403);
        }

        $costing->setAsDefault();

        if (request()->expectsJson()) {
            return response()->json([
                'success'     => true,
                'message'     => "Default template set to \"{$costing->package->name}\".",
                'costing_id'  => $costing->id,
            ]);
        }

        return back()->with('success', "Default template set to \"{$costing->package->name}\".");
    }

    /**
     * POST /caterer/costing/clear-default
     * Removes the default flag from all costings for the authenticated caterer.
     */
    public function clearDefault()
    {
        PackageCosting::where('user_id', auth()->id())
            ->update(['is_default_template' => false]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Default template cleared.']);
        }

        return back()->with('success', 'Default costing template cleared.');
    }

    // ── Get Costing Data (used by edit-package modal) ─────────────────────────

    public function getCostingData(Package $package)
    {
        $this->authorizePackage($package);

        $costing = $package->costing;

        if (!$costing) {
            return response()->json(['has_costing' => false]);
        }

        return response()->json([
            'has_costing'           => true,
            'ingredient_cost'       => (float) ($costing->ingredient_cost ?? 0),
            'labor_cost'            => (float) ($costing->labor_cost ?? 0),
            'equipment_cost'        => (float) ($costing->equipment_cost ?? 0),
            'consumables_cost'      => (float) ($costing->consumables_cost ?? 0),
            'overhead_cost'         => (float) ($costing->overhead_cost ?? 0),
            'transport_cost'        => (float) ($costing->transport_cost ?? 0),
            'profit_margin_percent' => (float) ($costing->profit_margin_percent ?? 25),
            'total_cost'            => $costing->total_cost,
            'profit_margin'         => $costing->profit_amount,
            'total_per_head'        => $package->price,
            'is_default_template'   => $costing->is_default_template,
            'template_name'         => $costing->template_name,
        ]);
    }

    // ── Live Calculate (AJAX) ─────────────────────────────────────────────────

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

        $breakdown = [];
        $totalCost = 0;

        foreach ($components as $key) {
            $amount     = (float) ($data[$key] ?? 0);
            $totalCost += $amount;
            $breakdown[$key] = $amount;
        }

        $marginPercent  = (float) ($data['profit_margin_percent'] ?? 25);
        $profitAmount   = $totalCost * ($marginPercent / 100);
        $suggestedPrice = $totalCost > 0 ? ceil(($totalCost + $profitAmount) / 5) * 5 : 0;

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

    public function generateQuotation(Booking $booking)
    {
        if ($booking->caterer_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['package.items.category', 'caterer', 'customer']);
        $costing = PackageCosting::where('package_id', $booking->package_id)->first();

        // Reuse the existing package quotation template to avoid duplication
        $guestCount  = (int) ($booking->guests ?? $booking->number_of_guests ?? 0);
        $totalAmount = (float) $booking->total_price;
        $generatedAt = Carbon::now();
        $validUntil  = $generatedAt->copy()->addDays(7);

        $data = [
            'package'        => $booking->package,
            'costing'        => $costing,
            'guest_count'    => $guestCount,
            'customer_name'  => $booking->customer_name ?? $booking->customer->name ?? 'Valued Customer',
            'event_type'     => $booking->event_type ?? 'Special Event',
            'event_date'     => $booking->event_date ? Carbon::parse($booking->event_date) : null,
            'valid_until'    => $validUntil,
            'total_amount'   => $totalAmount,
            'deposit_amount' => (float) ($booking->deposit_amount ?? ($totalAmount * 0.25)),
            'reference_no'   => 'QT-' . ($booking->booking_number ?? strtoupper(substr(md5($booking->id), 0, 8))),
            'caterer'        => $booking->caterer,
            'generated_at'   => $generatedAt,
            'is_pdf'         => true,
        ];

        $html = view('caterer.costing.package-quotation-pdf', $data)->render();

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4', 'portrait');
            return $pdf->stream("quotation-{$booking->booking_number}.pdf");
        }

        return view('caterer.costing.package-quotation-pdf', $data);
    }

    public function generatePackageQuotation(Request $request, Package $package)
    {
        $this->authorizePackage($package);

        $validated = $request->validate([
            'guest_count'   => 'required|integer|min:1',
            'customer_name' => 'nullable|string|max:255',
            'event_type'    => 'nullable|string|max:255',
            'event_date'    => 'nullable|date',
            'validity_days' => 'nullable|integer|min:1|max:90',
        ]);

        $validityDays = (int) ($validated['validity_days'] ?? 7);

        $package->load(['items.category', 'user', 'costing']);
        $costing     = $package->costing;
        $guestCount  = (int) $validated['guest_count'];
        $totalAmount = $package->price * $guestCount;

        $quoteData = [
            'package'       => $package,
            'costing'       => $costing,
            'guest_count'   => $guestCount,
            'customer_name' => $validated['customer_name'] ?? 'Valued Customer',
            'event_type'    => $validated['event_type'] ?? 'Special Event',
            'event_date'    => isset($validated['event_date']) ? Carbon::parse($validated['event_date']) : null,
            'valid_until'   => Carbon::now()->addDays($validityDays),
            'total_amount'  => $totalAmount,
            'deposit_amount'=> $totalAmount * 0.25,
            'reference_no'  => 'QT-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            'caterer'       => $package->user,
            'generated_at'  => Carbon::now(),
            'is_pdf'        => false,
        ];

        return view('caterer.costing.package-quotation-pdf', $quoteData);
    }

    // ── Clone Costing Template ────────────────────────────────────────────────

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
                [
                    'user_id'          => $catererId,
                    'final_price'      => null,
                    'suggested_price'  => null,
                    'is_default_template' => false, // clones are never auto-default
                ]
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