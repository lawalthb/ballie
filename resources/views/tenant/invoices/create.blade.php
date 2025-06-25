@extends('layouts.tenant')

@section('title', 'Create Invoice')
@section('page-title', 'Create New Invoice')
@section('page-description', 'Create a professional invoice for your customer')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Invoice Details</h2>
        </div>

        <form class="p-6 space-y-6">
            @csrf

            <!-- Customer Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer" class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                    <select id="customer" name="customer_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Select a customer</option>
                        <option value="1">Adebayo Enterprises</option>
                        <option value="2">Kemi Okafor Ltd</option>
                        <option value="3">Lagos Trading Co.</option>
                    </select>
                </div>

                <div>
                    <label for="invoice_date" class="block text-sm font-medium text-gray-700 mb-2">Invoice Date</label>
                    <input type="date" id="invoice_date" name="invoice_date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="invoice_number" class="block text-sm font-medium text-gray-700 mb-2">Invoice Number</label>
                    <input type="text" id="invoice_number" name="invoice_number" value="INV-{{ date('Y-m-d') }}-001" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                    <input type="date" id="due_date" name="due_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
            </div>

            <!-- Invoice Items -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Invoice Items</h3>
                    <button type="button" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors" onclick="addInvoiceItem()">
                        Add Item
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Description</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Quantity</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Rate (₦)</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Amount (₦)</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody id="invoice-items">
                            <tr>
                                <td class="px-4 py-3 border-t border-gray-200">
                                    <input type="text" name="items[0][description]" placeholder="Item description" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-1 focus:ring-primary-500">
                                </td>
                                <td class="px-4 py-3 border-t border-gray-200">
                                    <input type="number" name="items[0][quantity]" value="1" min="1" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-1 focus:ring-primary-500" onchange="calculateRowTotal(this)">
                                </td>
                                <td class="px-4 py-3 border-t border-gray-200">
                                    <input type="number" name="items[0][rate]" step="0.01" min="0" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-1 focus:ring-primary-500" onchange="calculateRowTotal(this)">
                                </td>
                                <td class="px-4 py-3 border-t border-gray-200">
                                    <span class="font-medium text-gray-900 row-total">₦0.00</span>
                                </td>
                                <td class="px-4 py-3 border-t border-gray-200">
                                    <button type="button" class="text-red-600 hover:text-red-800" onclick="removeInvoiceItem(this)">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Invoice Totals -->
            <div class="flex justify-end">
                <div class="w-full max-w-sm space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium" id="subtotal">₦0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">VAT (7.5%):</span>
                        <span class="font-medium" id="vat">₦0.00</span>
                    </div>
                    <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-2">
                        <span>Total:</span>
                        <span id="total">₦0.00</span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Additional notes or terms..."></textarea>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <button type="button" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Save as Draft
                </button>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    Create Invoice
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let itemIndex = 1;

function addInvoiceItem() {
    const tbody = document.getElementById('invoice-items');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td class="px-4 py-3 border-t border-gray-200">
            <input type="text" name="items[${itemIndex}][description]" placeholder="Item description" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-1 focus:ring-primary-500">
        </td>
        <td class="px-4 py-3 border-t border-gray-200">
            <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-1 focus:ring-primary-500" onchange="calculateRowTotal(this)">
        </td>
        <td class="px-4 py-3 border-t border-gray-200">
            <input type="number" name="items[${itemIndex}][rate]" step="0.01" min="0" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring-1 focus:ring-primary-500" onchange="calculateRowTotal(this)">
        </td>
        <td class="px-4 py-3 border-t border-gray-200">
            <span class="font-medium text-gray-900 row-total">₦0.00</span>
        </td>
        <td class="px-4 py-3 border-t border-gray-200">
            <button type="button" class="text-red-600 hover:text-red-800" onclick="removeInvoiceItem(this)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </td>
    `;
    tbody.appendChild(newRow);
    itemIndex++;
}

function removeInvoiceItem(button) {
    const row = button.closest('tr');
    row.remove();
    calculateInvoiceTotal();
}

function calculateRowTotal(input) {
    const row = input.closest('tr');
    const quantity = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
    const rate = parseFloat(row.querySelector('input[name*="[rate]"]').value) || 0;
    const total = quantity * rate;

    row.querySelector('.row-total').textContent = `₦${total.toLocaleString('en-NG', {minimumFractionDigits: 2})}`;
    calculateInvoiceTotal();
}

function calculateInvoiceTotal() {
    const rows = document.querySelectorAll('#invoice-items tr');
    let subtotal = 0;

    rows.forEach(row => {
        const quantity = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
        const rate = parseFloat(row.querySelector('input[name*="[rate]"]').value) || 0;
        subtotal += quantity * rate;
    });

    const vat = subtotal * 0.075; // 7.5% VAT
    const total = subtotal + vat;

    document.getElementById('subtotal').textContent = `₦${subtotal.toLocaleString('en-NG', {minimumFractionDigits: 2})}`;
    document.getElementById('vat').textContent = `₦${vat.toLocaleString('en-NG', {minimumFractionDigits: 2})}`;
    document.getElementById('total').textContent = `₦${total.toLocaleString('en-NG', {minimumFractionDigits: 2})}`;
}
</script>
@endsection