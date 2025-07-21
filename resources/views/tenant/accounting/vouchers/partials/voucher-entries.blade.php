 <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Voucher Entries</h3>
                    <button type="button"
                            @click="addEntry()"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-primary-700 bg-primary-100 hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Entry
                    </button>
                </div>
            </div>
            <div class="p-6">
                @error('entries')
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    </div>
                @enderror

                <!-- Entries Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ledger Account <span class="text-red-500">*</span>
                                </th>
                                <th class="text-left py-3 px-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Particulars
                                </th>
                                <th class="text-right py-3 px-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Debit Amount
                                </th>
                                <th class="text-right py-3 px-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Credit Amount
                                </th>
                                <th class="text-center py-3 px-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(entry, index) in entries" :key="index">
                                <tr class="border-b border-gray-100">
                                    <td class="py-3 px-2">
                                        <select :name="`entries[${index}][ledger_account_id]`"
                                                x-model="entry.ledger_account_id"
                                                @change="updateEntryAccount(index)"
                                                class="block w-full pl-3 pr-10 py-2 text-sm border border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 rounded-md"
                                                required>
                                            <option value="">Select Account</option>
                                            @foreach($ledgerAccounts as $account)
                                                <option value="{{ $account->id }}">
                                                    {{ $account->name }} ({{ $account->accountGroup->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-3 px-2">
                                        <input type="text"
                                               :name="`entries[${index}][particulars]`"
                                               x-model="entry.particulars"
                                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                               placeholder="Entry description">
                                    </td>
                                    <td class="py-3 px-2">
                                        <input type="number"
                                               :name="`entries[${index}][debit_amount]`"
                                               x-model="entry.debit_amount"
                                               @input="updateTotals(); clearCredit(index)"
                                               step="0.01"
                                               min="0"
                                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-right"
                                               placeholder="0.00">
                                    </td>
                                    <td class="py-3 px-2">
                                        <input type="number"
                                               :name="`entries[${index}][credit_amount]`"
                                               x-model="entry.credit_amount"
                                               @input="updateTotals(); clearDebit(index)"
                                               step="0.01"
                                               min="0"
                                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-right"
                                               placeholder="0.00">
                                    </td>
                                    <td class="py-3 px-2 text-center">
                                        <button type="button"
                                                @click="removeEntry(index)"
                                                x-show="entries.length > 2"
                                                class="text-red-600 hover:text-red-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-300 bg-gray-50">
                                <td colspan="2" class="py-3 px-2 text-sm font-medium text-gray-900">
                                    Total
                                </td>
                                <td class="py-3 px-2 text-right text-sm font-medium text-gray-900">
                                    ₦<span x-text="formatNumber(totalDebits)"></span>
                                </td>
                                <td class="py-3 px-2 text-right text-sm font-medium text-gray-900">
                                    ₦<span x-text="formatNumber(totalCredits)"></span>
                                </td>
                                <td class="py-3 px-2"></td>
                            </tr>
                            <tr x-show="!isBalanced" class="bg-red-50">
                                <td colspan="5" class="py-2 px-2 text-center text-sm text-red-600">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    Voucher is not balanced. Difference: ₦<span x-text="formatNumber(Math.abs(totalDebits - totalCredits))"></span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Quick Entry Templates -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg" x-show="voucherTypeId">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Quick Entry Templates</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <template x-for="template in quickTemplates" :key="template.name">
                            <button type="button"
                                    @click="applyTemplate(template)"
                                    class="text-left p-3 border border-gray-200 rounded-lg hover:bg-white hover:shadow-sm transition-all">
                                <div class="text-sm font-medium text-gray-900" x-text="template.name"></div>
                                <div class="text-xs text-gray-500" x-text="template.description"></div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-500">
                    <span x-show="isBalanced" class="text-green-600">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Voucher is balanced
                    </span>
                    <span x-show="!isBalanced" class="text-red-600">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Voucher must be balanced to save
                    </span>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('tenant.accounting.vouchers.index', ['tenant' => $tenant->slug]) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Cancel
                </a>
                <button type="submit"
                        :disabled="!isBalanced || entries.length < 2"
                        :class="{ 'opacity-50 cursor-not-allowed': !isBalanced || entries.length < 2 }"
                        class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create Voucher
                </button>
            </div>
        </div>
    </form>