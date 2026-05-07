<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CatererDocumentUpdateController extends Controller
{
    use HandlesImageUploads;

    public function edit(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user || $user->role !== 'caterer') {
            abort(403, 'Unauthorized access.');
        }

        if (! $user->document_update_requested) {
            return redirect()->route('dashboard');
        }

        return view('caterer.document-update', [
            'user' => $user,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user || $user->role !== 'caterer') {
            abort(403, 'Unauthorized access.');
        }

        if (! $user->document_update_requested) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'business_permit_file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'business_permit_file.required' => 'Please upload your updated BIR/business permit document.',
            'business_permit_file.mimes' => 'The document must be a PDF, JPG, JPEG, or PNG file.',
            'business_permit_file.max' => 'The document must not exceed 5MB.',
        ]);

        $newPath = $this->handleImageUpload(
            $request->file('business_permit_file'),
            'business-permits'
        );

        $user->update([
            'business_permit_file_path' => $newPath,
            'document_update_requested' => false,
            'document_update_reason' => null,
            'document_update_requested_at' => null,
            'document_update_resolved_at' => now(),
            'status' => 'pending',
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Your updated document has been submitted successfully. Please wait for admin review before logging in again.');
    }
}
