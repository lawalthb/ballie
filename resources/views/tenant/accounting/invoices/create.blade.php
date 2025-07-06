@extends('layouts.tenant')

@section('title', 'Create Invoice')
@section('page-title', 'Create New Invoice')
@section('page-description', 'Create a new invoice for your customers.')

@section('content')
<div class="space-y-6">
    <form action="{{ route('tenant.accounting.invoices.store', ['tenant' => tenant()->slug]) }}" method="POST" id="invoice-form">
        @csrf

        <!-- Invoice Header -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Information</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="invoice_number" class="block text-sm font-medium text-gray-700">Invoice Number</label>
                            <input type="text" name="invoice_number" id="invoice_number" value="{{ $nextInvoiceNumber }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" readonly>
                        </div>

                        <div>
                            <label for="issue_date" class="block text-sm font-medium text-gray-700">Issue Date</label>
                            <input type="date" name="issue_date" id="issue_date" value="{{ date('Y-m-d') }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>

                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                            <input type="date" name="due_date" id="due_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>

                        <div>
                            <label for="payment_terms" class="block text-sm font-medium text-gray-700">Payment Terms</label>
                            <select name="payment_terms" id="payment_terms" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="net_30">Net 30 Days</option>
                                <option value="net_15">Net 15 Days</option>
                                <option value="net_7">Net 7 Days</option>
                                <option value="due_on_receipt">Due on Receipt</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700">Select Customer</label>
                            <select name="customer_id" id="customer_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                <option value="">-- Select Customer --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" data-email="{{ $customer->email }}" >
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center mt-4">
                            <span class="text-sm text-gray-500">or</span>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('tenant.crm.customers.create', ['tenant' => tenant()->slug]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add New Customer
                            </a>
                        </div>

                        <!-- Customer Details Display -->
                        <div id="customer-details" class="hidden mt-4 p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Customer Details</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <div id="customer-email"></div>
                                <div id="customer-phone"></div>
                                <div id="customer-address"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Invoice Items</h3>
                <button type="button" id="add-item" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Item
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="invoice-items-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Item
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Unit Price
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="invoice-items-body">
                        <tr class="invoice-item">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select name="items[0][product_id]" class="product-select block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">-- Select Product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-description="{{ $product->description }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="text" name="items[0][description]" class="item-description block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" name="items[0][quantity]" class="item-quantity block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="1" min="1" step="0.01">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" name="items[0][unit_price]" class="item-price block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" step="0.01" min="0">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" name="items[0][total]" class="item-total block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" readonly>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button type="button" class="remove-item text-red-600 hover:text-red-900">
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

        <!-- Invoice Summary -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="4" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Additional notes or terms..."></textarea>
                        </div>

                        <div>
                            <label for="footer_text" class="block text-sm font-medium text-gray-700">Footer Text</label>
                            <textarea name="footer_text" id="footer_text" rows="2" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Thank you for your business!"></textarea>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Summary</h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Subtotal:</span>
                            <span id="subtotal" class="text-sm font-medium text-gray-900">₦0.00</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <label for="discount_type" class="text-sm font-medium text-gray-700">Discount:</label>
                            <div class="flex items-center space-x-2">
                                <select name="discount_type" id="discount_type" class="py-1 px-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="percentage">%</option>
                                    <option value="fixed">₦</option>
                                </select>
                                <input type="number" name="discount_value" id="discount_value" class="w-20 py-1 px-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm" step="0.01" min="0" value="0">
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Discount Amount:</span>
                            <span id="discount-amount" class="text-sm font-medium text-gray-900">₦0.00</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <label for="tax_rate" class="text-sm font-medium text-gray-700">Tax Rate (%):</label>
                            <input type="number" name="tax_rate" id="tax_rate" class="w-20 py-1 px-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm" step="0.01" min="0" value="0">
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Tax Amount:</span>
                            <span id="tax-amount" class="text-sm font-medium text-gray-900">₦0.00</span>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">Total Amount:</span>
                                <span id="total-amount" class="text-lg font-bold text-blue-600">₦0.00</span>
                            </div>
                        </div>

                        <!-- Hidden inputs for calculated values -->
                        <input type="hidden" name="subtotal_amount" id="subtotal_amount" value="0">
                        <input type="hidden" name="discount_amount" id="discount_amount_input" value="0">
                        <input type="hidden" name="tax_amount" id="tax_amount_input" value="0">
                        <input type="hidden" name="total_amount" id="total_amount_input" value="0">
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <div class="flex justify-between items-center">
                <a href="{{ route('tenant.accounting.invoices.index', ['tenant' => tenant()->slug]) }}" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Cancel
                </a>

                <div class="flex space-x-3">
                    <button type="submit" name="action" value="draft" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="-ml-1 mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Save as Draft
                    </button>

                    <button type="submit" name="action" value="send" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Create & Send
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;

    // Customer selection handler
    const customerSelect = document.getElementById('customer_id');
    const customerDetails = document.getElementById('customer-details');

    customerSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (selectedOption.value) {
            const email = selectedOption.dataset.email;
            const phone = selectedOption.dataset.phone;
            const address = selectedOption.dataset.address;

            document.getElementById('customer-email').textContent = email ? `Email: ${email}` : '';
            document.getElementById('customer-phone').textContent = phone ? `Phone: ${phone}` : '';
            document.getElementById('customer-address').textContent = address ? `Address: ${address}` : '';

            customerDetails.classList.remove('hidden');
        } else {
            customerDetails.classList.add('hidden');
        }
    });

    // Add new item row
    document.getElementById('add-item').addEventListener('click', function() {
        const tbody = document.getElementById('invoice-items-body');
        const newRow = createItemRow(itemIndex);
        tbody.appendChild(newRow);
        itemIndex++;
        attachItemEventListeners(newRow);
    });

    // Create new item row
    function createItemRow(index) {
        const row = document.createElement('tr');
        row.className = 'invoice-item';
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <select name="items[${index}][product_id]" class="product-select block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-description="{{ $product->description }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="text" name="items[${index}][description]" class="item-description block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="number" name="items[${index}][quantity]" class="item-quantity block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="1" min="1" step="0.01">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="number" name="items[${index}][unit_price]" class="item-price block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" step="0.01" min="0">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="number" name="items[${index}][total]" class="item-total block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" readonly>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <button type="button" class="remove-item text-red-600 hover:text-red-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </td>
        `;
        return row;
    }

    // Attach event listeners to item row
    function attachItemEventListeners(row) {
        const productSelect = row.querySelector('.product-select');
        const descriptionInput = row.querySelector('.item-description');
        const quantityInput = row.querySelector('.item-quantity');
        const priceInput = row.querySelector('.item-price');
        const totalInput = row.querySelector('.item-total');
        const removeButton = row.querySelector('.remove-item');

        // Product selection
        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const price = selectedOption.dataset.price;
                const description = selectedOption.dataset.description;

                priceInput.value = price;
                descriptionInput.value = description;
                calculateItemTotal(row);
            }
        });

        // Quantity and price changes
        quantityInput.addEventListener('input', () => calculateItemTotal(row));
        priceInput.addEventListener('input', () => calculateItemTotal(row));

        // Remove item
        removeButton.addEventListener('click', function() {
            if (document.querySelectorAll('.invoice-item').length > 1) {
                row.remove();
                calculateInvoiceTotal();
            } else {
                alert('At least one item is required.');
            }
        });
    }

    // Calculate item total
    function calculateItemTotal(row) {
        const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const total = quantity * price;

        row.querySelector('.item-total').value = total.toFixed(2);
        calculateInvoiceTotal();
    }

    // Calculate invoice total
    function calculateInvoiceTotal() {
        let subtotal = 0;

        document.querySelectorAll('.item-total').forEach(function(input) {
            subtotal += parseFloat(input.value) || 0;
        });

        const discountType = document.getElementById('discount_type').value;
        const discountValue = parseFloat(document.getElementById('discount_value').value) || 0;
        const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;

        let discountAmount = 0;
        if (discountType === 'percentage') {
            discountAmount = (subtotal * discountValue) / 100;
        } else {
            discountAmount = discountValue;
        }

        const afterDiscount = subtotal - discountAmount;
        const taxAmount = (afterDiscount * taxRate) / 100;
        const totalAmount = afterDiscount + taxAmount;

        // Update display
        document.getElementById('subtotal').textContent = `₦${subtotal.toFixed(2)}`;
        document.getElementById('discount-amount').textContent = `₦${discountAmount.toFixed(2)}`;
        document.getElementById('tax-amount').textContent = `₦${taxAmount.toFixed(2)}`;
        document.getElementById('total-amount').textContent = `₦${totalAmount.toFixed(2)}`;

        // Update hidden inputs
        document.getElementById('subtotal_amount').value = subtotal.toFixed(2);
        document.getElementById('discount_amount_input').value = discountAmount.toFixed(2);
        document.getElementById('tax_amount_input').value = taxAmount.toFixed(2);
        document.getElementById('total_amount_input').value = totalAmount.toFixed(2);
    }

    // Attach event listeners to existing items
    document.querySelectorAll('.invoice-item').forEach(attachItemEventListeners);

    // Discount and tax change handlers
    document.getElementById('discount_type').addEventListener('change', calculateInvoiceTotal);
    document.getElementById('discount_value').addEventListener('input', calculateInvoiceTotal);
    document.getElementById('tax_rate').addEventListener('input', calculateInvoiceTotal);

    // Form validation
    document.getElementById('invoice-form').addEventListener('submit', function(e) {
        const customerSelect = document.getElementById('customer_id');
        const items = document.querySelectorAll('.invoice-item');

        if (!customerSelect.value) {
            e.preventDefault();
            alert('Please select a customer.');
            customerSelect.focus();
            return;
        }

        let hasValidItem = false;
        items.forEach(function(item) {
            const quantity = parseFloat(item.querySelector('.item-quantity').value) || 0;
            const price = parseFloat(item.querySelector('.item-price').value) || 0;

            if (quantity > 0 && price > 0) {
                hasValidItem = true;
            }
        });

        if (!hasValidItem) {
            e.preventDefault();
            alert('Please add at least one valid item with quantity and price.');
            return;
        }
    });

    // Initialize calculations
    calculateInvoiceTotal();
});
</script>
@endpush

@push('styles')
<style>
.invoice-item:hover {
    background-color: #f9fafb;
}

.remove-item:hover {
    transform: scale(1.1);
}

#customer-details {
    transition: all 0.3s ease;
}

.product-select:focus,
.item-description:focus,
.item-quantity:focus,
.item-price:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Responsive table */
@media (max-width: 768px) {
    #invoice-items-table {
        font-size: 0.875rem;
    }

    #invoice-items-table th,
    #invoice-items-table td {
        padding: 0.5rem;
    }
}
</style>
@endpush
@endsection
