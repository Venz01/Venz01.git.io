<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Review Caterer Application') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-4">
                @if(request()->query('from') === 'users')
                    <a href="{{ route('admin.users') }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to User Management
                    </a>
                @else
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Dashboard
                    </a>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Status Badge -->
                    <div class="mb-6">
                        @if($caterer->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Pending Approval
                            </span>
                        @elseif($caterer->status === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Approved
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                Rejected
                            </span>
                        @endif
                    </div>

                    <!-- Business Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Business Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Business Name</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $caterer->business_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Owner Full Name</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $caterer->owner_full_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $caterer->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Contact Name</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $caterer->name }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Business Address</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $caterer->business_address ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Business Permit Number</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $caterer->business_permit_number ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Application Date</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $caterer->created_at->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Business Permit Document -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Business Permit Document</h3>
                        @if($caterer->business_permit_file_path)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                @php
                                    $fileExtension = pathinfo($caterer->business_permit_file_path, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif']);
                                    $fileUrl = filter_var($caterer->business_permit_file_path, FILTER_VALIDATE_URL) ? $caterer->business_permit_file_path : asset('storage/' . ltrim($caterer->business_permit_file_path, '/'));
                                @endphp

                                @if($isImage)
                                    <div class="mb-4">
                                        <img src="{{ $fileUrl }}" 
                                             alt="Business Permit" 
                                             class="max-w-full h-auto rounded border border-gray-300 dark:border-gray-600"
                                             onerror="this.parentElement.innerHTML='<p class=\'text-red-600\'>Image failed to load. Path: {{ $caterer->business_permit_file_path }}</p>'">
                                    </div>
                                @else
                                    <div class="flex items-center mb-4">
                                        <svg class="w-12 h-12 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Business Permit PDF</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ basename($caterer->business_permit_file_path) }}</p>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <iframe src="{{ $fileUrl }}" 
                                                class="w-full h-96 border border-gray-300 dark:border-gray-600 rounded"
                                                onerror="this.style.display='none'">
                                        </iframe>
                                    </div>
                                @endif
                                
                                <div class="flex space-x-3">
                                    <a href="{{ $fileUrl }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View in New Tab
                                    </a>
                                    <a href="{{ $fileUrl }}" 
                                       download 
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                                
                                
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">No document uploaded</p>
                        @endif
                    </div>

                    @if($caterer->status === 'rejected' && !empty($caterer->rejection_reason))
                    <div class="mt-6 rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
                        <h3 class="text-sm font-semibold text-red-800 dark:text-red-200">Rejection Reason</h3>
                        <p class="mt-1 text-sm text-red-700 dark:text-red-300">{{ $caterer->rejection_reason }}</p>
                    </div>
                    @endif

                    @if($caterer->hasPendingDocumentUpdateRequest())
                    <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 p-4 dark:border-amber-800 dark:bg-amber-900/20">
                        <h3 class="text-sm font-semibold text-amber-900 dark:text-amber-100">Document Update Request</h3>
                        <p class="mt-1 text-sm text-amber-800 dark:text-amber-200">{{ $caterer->document_update_reason }}</p>
                        @if($caterer->document_update_requested_at)
                            <p class="mt-2 text-xs text-amber-700 dark:text-amber-300">Requested on {{ $caterer->document_update_requested_at->format('M d, Y h:i A') }}</p>
                        @endif
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button"
                                onclick="sessionStorage.setItem('lastDocumentUpdateAction', '{{ route('admin.caterers.request-document-update', $caterer->id) }}'); openDocumentUpdateModal('{{ route('admin.caterers.request-document-update', $caterer->id) }}')"
                                class="inline-flex items-center px-4 py-2 border border-amber-300 dark:border-amber-700 rounded-md shadow-sm text-sm font-medium text-amber-800 dark:text-amber-200 bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/40">
                            Request Document Update
                        </button>
                        <button type="button"
                                onclick="sessionStorage.setItem('lastRejectCatererAction', '{{ route('admin.caterers.reject', $caterer->id) }}'); openRejectCatererModal('{{ route('admin.caterers.reject', $caterer->id) }}')"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject Application
                        </button>
                        @if($caterer->status === 'pending')
                        <form action="{{ route('admin.caterers.approve', $caterer->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                    onclick="return confirm('Are you sure you want to approve this caterer?')">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Approve Application
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Request Document Update Modal -->
    <div id="documentUpdateModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="w-full max-w-lg rounded-xl bg-white dark:bg-gray-800 shadow-xl">
            <form id="documentUpdateForm" method="POST" class="p-6">
                @csrf
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900">
                        <svg class="h-6 w-6 text-amber-600 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Request Document Update</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            State why the caterer must upload a new BIR/business permit document.
                        </p>
                    </div>
                </div>

                <div class="mt-5">
                    <label for="documentUpdateReason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Update <span class="text-red-500">*</span>
                    </label>
                    <textarea id="documentUpdateReason" name="document_update_reason" rows="5" minlength="5" maxlength="1000" required
                        placeholder="Example: Uploaded BIR document is blurred/unreadable. Please upload a clearer copy."
                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-amber-500 focus:ring-amber-500">{{ old('document_update_reason') }}</textarea>
                    @error('document_update_reason')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeDocumentUpdateModal()" class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">Send Request</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Caterer Modal -->
    <div id="rejectCatererModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="w-full max-w-lg rounded-xl bg-white dark:bg-gray-800 shadow-xl">
            <form id="rejectCatererForm" method="POST" class="p-6">
                @csrf
                @method('POST')
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Reject Caterer Application</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Please provide a clear reason. This will be saved with the caterer application.
                        </p>
                    </div>
                </div>

                <div class="mt-5">
                    <label for="rejectionReason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Rejection Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="rejectionReason"
                        name="rejection_reason"
                        rows="5"
                        minlength="5"
                        maxlength="1000"
                        required
                        placeholder="Example: Business permit is expired or uploaded document is unreadable."
                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-red-500 focus:ring-red-500"
                    >{{ old('rejection_reason') }}</textarea>
                    @error('rejection_reason')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeRejectCatererModal()" class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Reject Application
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDocumentUpdateModal(actionUrl) {
            const modal = document.getElementById('documentUpdateModal');
            const form = document.getElementById('documentUpdateForm');
            if (!modal || !form) return;

            form.action = actionUrl;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                const textarea = document.getElementById('documentUpdateReason');
                if (textarea) textarea.focus();
            }, 50);
        }

        function closeDocumentUpdateModal() {
            const modal = document.getElementById('documentUpdateModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('documentUpdateModal')?.addEventListener('click', function (event) {
            if (event.target === this) closeDocumentUpdateModal();
        });

        function openRejectCatererModal(actionUrl) {
            const modal = document.getElementById('rejectCatererModal');
            const form = document.getElementById('rejectCatererForm');
            if (!modal || !form) return;

            form.action = actionUrl;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                const textarea = document.getElementById('rejectionReason');
                if (textarea) textarea.focus();
            }, 50);
        }

        function closeRejectCatererModal() {
            const modal = document.getElementById('rejectCatererModal');
            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('rejectCatererModal')?.addEventListener('click', function (event) {
            if (event.target === this) closeRejectCatererModal();
        });

        @if($errors->has('document_update_reason'))
            document.addEventListener('DOMContentLoaded', function () {
                const previousAction = sessionStorage.getItem('lastDocumentUpdateAction');
                if (previousAction) openDocumentUpdateModal(previousAction);
            });
        @endif

        @if($errors->has('rejection_reason'))
            document.addEventListener('DOMContentLoaded', function () {
                const previousAction = sessionStorage.getItem('lastRejectCatererAction');
                if (previousAction) openRejectCatererModal(previousAction);
            });
        @endif
    </script>

</x-app-layout>