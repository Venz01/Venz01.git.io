<!-- Approve Modal -->
<div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl p-6 md:p-8 max-w-md w-full">
        <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-4">Approve Review</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
            This review will be made visible to the public.
        </p>
        <form id="approveForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Admin Notes (optional)
                </label>
                <textarea 
                    name="admin_notes"
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Add any notes about this approval..."
                ></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeApproveModal()" 
                        class="px-4 py-2 border border-gray-300 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Approve Review
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Flag Modal -->
<div id="flagModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl p-6 md:p-8 max-w-md w-full">
        <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-4">Flag Review as Inappropriate</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
            This review will be hidden from public view and marked for review.
        </p>
        <form id="flagForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Reason for Flagging <span class="text-red-500">*</span>
                </label>
                <textarea 
                    name="flagged_reason"
                    rows="3"
                    required
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Explain why this review is inappropriate..."
                ></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Admin Notes (optional)
                </label>
                <textarea 
                    name="admin_notes"
                    rows="2"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Internal notes..."
                ></textarea>
            </div>
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="warn_caterer" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Send warning notification to caterer</span>
                </label>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeFlagModal()" 
                        class="px-4 py-2 border border-gray-300 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Flag Review
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Remove Modal -->
<div id="removeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl p-6 md:p-8 max-w-md w-full">
        <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-4">Remove Review</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
            This review will be permanently hidden from public view.
        </p>
        <form id="removeForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Reason for Removal <span class="text-red-500">*</span>
                </label>
                <textarea 
                    name="removal_reason"
                    rows="3"
                    required
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-gray-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Explain why this review is being removed..."
                ></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Admin Notes (optional)
                </label>
                <textarea 
                    name="admin_notes"
                    rows="2"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-gray-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Internal notes..."
                ></textarea>
            </div>
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="warn_caterer" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Send warning notification to caterer</span>
                </label>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeRemoveModal()" 
                        class="px-4 py-2 border border-gray-300 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    Remove Review
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Restore Modal -->
<div id="restoreModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg md:rounded-xl p-6 md:p-8 max-w-md w-full">
        <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-4">Restore Review</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
            This review will be restored and made visible to the public.
        </p>
        <form id="restoreForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Admin Notes (optional)
                </label>
                <textarea 
                    name="admin_notes"
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Add any notes about this restoration..."
                ></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeRestoreModal()" 
                        class="px-4 py-2 border border-gray-300 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    Restore Review
                </button>
            </div>
        </form>
    </div>
</div>