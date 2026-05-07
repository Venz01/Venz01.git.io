<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Update Required Document') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl overflow-hidden border border-amber-200 dark:border-amber-900/40">
                <div class="bg-amber-50 dark:bg-amber-900/20 px-6 py-5 border-b border-amber-200 dark:border-amber-900/40">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-800/50 flex items-center justify-center">
                            <svg class="w-7 h-7 text-amber-600 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Document update requested by admin</h3>
                            <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                                For security, your account access is temporarily limited to this page only until you submit the updated BIR/business permit document.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <div class="rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 p-5">
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Admin reason:</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
                            {{ $user->document_update_reason ?: 'Please upload a clearer or updated copy of your BIR/business permit document.' }}
                        </p>
                        @if($user->document_update_requested_at)
                            <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                Requested on {{ $user->document_update_requested_at->format('M d, Y h:i A') }}
                            </p>
                        @endif
                    </div>

                    @if($user->business_permit_file_url)
                        <div class="rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900/40 p-5">
                            <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">Current uploaded document</p>
                            <a href="{{ $user->business_permit_file_url }}" target="_blank" rel="noopener" class="inline-flex items-center mt-2 text-sm font-medium text-blue-700 dark:text-blue-300 hover:underline">
                                View current document
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('caterer.document-update.update') }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <div>
                            <label for="business_permit_file" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Upload new BIR/business permit document
                            </label>
                            <input id="business_permit_file" name="business_permit_file" type="file" accept=".pdf,.jpg,.jpeg,.png" required
                                class="mt-2 block w-full text-sm text-gray-700 dark:text-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 dark:file:bg-amber-900/40 dark:file:text-amber-200">
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Accepted file types: PDF, JPG, JPEG, PNG. Maximum size: 5MB.</p>
                            @error('business_permit_file')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                After submission, you will be logged out and your account will return to pending admin review.
                            </p>
                            <button type="submit" class="inline-flex justify-center items-center px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                                Submit Updated Document
                            </button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="pt-2">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 underline">
                            Logout instead
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
