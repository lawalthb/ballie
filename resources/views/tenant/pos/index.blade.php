@extends('layouts.tenant')

@section('title', 'Point of Sale')
@section('page-title', 'Point of Sale')
@section('page-description', 'Process sales transactions quickly and efficiently')

@push('styles')
<style>
    .pos-grid {
        height: calc(100vh - 200px);
    }
    .product-grid {
        max-height: 400px;
        overflow-y: auto;
    }
    .cart-section {
        max-height: 500px;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<div class="pos-grid grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Product Selection -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Search and Categories -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                <div class="flex-1 relative">
                    <input type="text" id="product-search" placeholder="Search products..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors category-btn active" data-category="all">
                        All
                    </button>
                    <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors category-btn" data-category="electronics">
                        Electronics
                    </button>
                    <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors category-btn" data-category="office">
                        Office
                    </button>
                    <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors category-btn" data-category="furniture">
                        Furniture
                    </button>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="product-grid grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="products-grid">
                @php
                $products = [
                    ['id' => 1, 'name' => 'Wireless Mouse', 'price' => 2500, 'stock' => 45, 'category' => 'electronics'],
                    ['id' => 2, 'name' => 'Office Chair', 'price' => 25000, 'stock' => 12, 'category' => 'furniture'],
                    ['id' => 3, 'name' => 'A4 Paper', 'price' => 1200, 'stock' => 5, 'category' => 'office'],
                    ['id' => 4, 'name' => 'Laptop Stand', 'price' => 8500, 'stock' => 23, 'category' => 'electronics'],
                    ['id' => 5, 'name' => 'Desk Lamp', 'price' => 4500, 'stock' => 18, 'category' => 'office'],
                    ['id' => 6, 'name' => 'Keyboard', 'price' => 3500, 'stock' => 32, 'category' => 'electronics'],
                    ['id' => 7, 'name' => 'Filing Cabinet', 'price' => 15000, 'stock' => 8, 'category' => 'furniture'],
                    ['id' => 8, 'name' => 'Printer Paper', 'price' => 800, 'stock' => 50, 'category' => 'office'],
                ];
                @endphp

                @foreach($products as $product)
                <div class="product-card border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow cursor-pointer"
                     data-product-id="{{ $product['id'] }}"
                     data-product-name="{{ $product['name'] }}"
                     data-product-price="{{ $product['price'] }}"
                     data-product-stock="{{ $product['stock'] }}"
                     data-category="{{ $product['category'] }}">
                    <div class="w-full h-24 bg-gray-100 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-medium text-gray-900 mb-1">{{ $product['name'] }}</h4>
                    <p class="text-lg font-bold text-primary-600">₦{{ number_format($product['price']) }}</p>
                    <p class="text-xs text-gray-500">Stock: {{ $product['stock'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Cart and Checkout -->
    <div class="space-y-6">
        <!-- Cart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Cart</h3>
                <button id="clear-cart" class="text-sm text-red-600 hover:text-red-700">Clear All</button>
            </div>

            <div class="cart-section space-y-3" id="cart-items">
                <!-- Cart items will be dynamically added here -->
                <div class="text-center py-8 text-gray-500" id="empty-cart">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6M7 13l-1.5-6m0 0h15M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"></path>
                    </svg>
                    <p>Your cart is empty</p>
                    <p class="text-sm">Add products to get started</p>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>

            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-medium" id="subtotal">₦0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tax (7.5%):</span>
                    <span class="font-medium" id="tax">₦0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Discount:</span>
                    <span class="font-medium text-green-600" id="discount">₦0</span>
                </div>
                <hr class="border-gray-200">
                <div class="flex justify-between text-lg font-bold">
                    <span>Total:</span>
                    <span id="total">₦0</span>
                </div>
            </div>

            <!-- Customer Selection -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Walk-in Customer</option>
                    <option value="1">John Doe</option>
                    <option value="2">Jane Smith</option>
                    <option value="3">Mike Johnson</option>
                </select>
            </div>

            <!-- Payment Method -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <div class="grid grid-cols-2 gap-2">
                    <button class="payment-method px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors active" data-method="cash">
                        Cash
                    </button>
                    <button class="payment-method px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors" data-method="card">
                        Card
                    </button>
                    <button class="payment-method px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors" data-method="transfer">
                        Transfer
                    </button>
                    <button class="payment-method px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors" data-method="pos">
                        POS
                    </button>
                </div>
            </div>

            <!-- Checkout Button -->
            <button id="checkout-btn" class="w-full mt-6 px-4 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-semibold disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                Process Sale
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let cart = [];
    let selectedPaymentMethod = 'cash';

    // DOM elements
    const cartItemsContainer = document.getElementById('cart-items');
    const emptyCartMessage = document.getElementById('empty-cart');
    const subtotalElement = document.getElementById('subtotal');
    const taxElement = document.getElementById('tax');
    const discountElement = document.getElementById('discount');
    const totalElement = document.getElementById('total');
    const checkoutBtn = document.getElementById('checkout-btn');

    // Product selection
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const productPrice = parseFloat(this.dataset.productPrice);
            const productStock = parseInt(this.dataset.productStock);

            addToCart(productId, productName, productPrice, productStock);
        });
    });

    // Category filtering
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;

            // Update active button
            document.querySelectorAll('.category-btn').forEach(b => {
                b.classList.remove('active', 'bg-primary-600', 'text-white');
                b.classList.add('bg-gray-200', 'text-gray-700');
            });
            this.classList.add('active', 'bg-primary-600', 'text-white');
            this.classList.remove('bg-gray-200', 'text-gray-700');

            // Filter products
            filterProducts(category);
        });
    });

    // Payment method selection
    document.querySelectorAll('.payment-method').forEach(btn => {
        btn.addEventListener('click', function() {
            selectedPaymentMethod = this.dataset.method;

            // Update active button
            document.querySelectorAll('.payment-method').forEach(b => {
                b.classList.remove('active', 'bg-primary-100', 'border-primary-500', 'text-primary-700');
            });
            this.classList.add('active', 'bg-primary-100', 'border-primary-500', 'text-primary-700');
        });
    });

    // Clear cart
    document.getElementById('clear-cart').addEventListener('click', function() {
        cart = [];
        updateCartDisplay();
        updateOrderSummary();
    });

    // Checkout
    checkoutBtn.addEventListener('click', function() {
        if (cart.length === 0) return;

        processSale();
    });

    // Product search
    document.getElementById('product-search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        searchProducts(searchTerm);
    });

    function addToCart(productId, productName, productPrice, productStock) {
        const existingItem = cart.find(item => item.id === productId);

        if (existingItem) {
            if (existingItem.quantity < productStock) {
                existingItem.quantity += 1;
            } else {
                alert('Insufficient stock!');
                return;
            }
        } else {
            cart.push({
                id: productId,
                name: productName,
                price: productPrice,
                quantity: 1,
                stock: productStock
            });
        }

        updateCartDisplay();
        updateOrderSummary();
    }

    function removeFromCart(productId) {
        cart = cart.filter(item => item.id !== productId);
        updateCartDisplay();
        updateOrderSummary();
    }

    function updateQuantity(productId, newQuantity) {
        const item = cart.find(item => item.id === productId);
        if (item) {
            if (newQuantity <= 0) {
                removeFromCart(productId);
            } else if (newQuantity <= item.stock) {
                item.quantity = newQuantity;
                updateCartDisplay();
                updateOrderSummary();
            } else {
                alert('Insufficient stock!');
            }
        }
    }

    function updateCartDisplay() {
        if (cart.length === 0) {
            emptyCartMessage.style.display = 'block';
            cartItemsContainer.innerHTML = '';
            checkoutBtn.disabled = true;
            return;
        }

        emptyCartMessage.style.display = 'none';
        checkoutBtn.disabled = false;

        cartItemsContainer.innerHTML = cart.map(item => `
            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-gray-900">${item.name}</h4>
                    <p class="text-sm text-gray-600">₦${item.price.toLocaleString()} each</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="updateQuantity('${item.id}', ${item.quantity - 1})"
                            class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded-full hover:bg-gray-300">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                    </button>
                    <span class="w-8 text-center text-sm font-medium">${item.quantity}</span>
                    <button onclick="updateQuantity('${item.id}', ${item.quantity + 1})"
                            class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded-full hover:bg-gray-300">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </button>
                    <button onclick="removeFromCart('${item.id}')"
                            class="w-6 h-6 flex items-center justify-center text-red-600 hover:bg-red-50 rounded-full">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `).join('');
    }

    function updateOrderSummary() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const tax = subtotal * 0.075; // 7.5% VAT
        const discount = 0; // Can be implemented later
        const total = subtotal + tax - discount;

        subtotalElement.textContent = `₦${subtotal.toLocaleString()}`;
        taxElement.textContent = `₦${tax.toLocaleString()}`;
        discountElement.textContent = `₦${discount.toLocaleString()}`;
        totalElement.textContent = `₦${total.toLocaleString()}`;
    }

    function filterProducts(category) {
        const products = document.querySelectorAll('.product-card');
        products.forEach(product => {
            if (category === 'all' || product.dataset.category === category) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });
    }

    function searchProducts(searchTerm) {
        const products = document.querySelectorAll('.product-card');
        products.forEach(product => {
            const productName = product.dataset.productName.toLowerCase();
            if (productName.includes(searchTerm)) {
                product.style.display = 'block';
            } else {
                product.style.display = 'none';
            }
        });
    }

    function processSale() {
        const saleData = {
            items: cart,
            payment_method: selectedPaymentMethod,
            subtotal: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0),
            tax: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0) * 0.075,
            total: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0) * 1.075
        };

        // Show loading state
        checkoutBtn.disabled = true;
        checkoutBtn.textContent = 'Processing...';

        // Simulate API call
        setTimeout(() => {
            // Reset form
            cart = [];
            updateCartDisplay();
            updateOrderSummary();

            // Reset button
            checkoutBtn.disabled = false;
            checkoutBtn.textContent = 'Process Sale';

            // Show success message
            alert('Sale processed successfully!');

            // In a real app, you would:
            // 1. Send data to server
            // 2. Print receipt
            // 3. Update inventory
            // 4. Record transaction
        }, 2000);
    }

    // Make functions global for onclick handlers
    window.updateQuantity = updateQuantity;
    window.removeFromCart = removeFromCart;
});
</script>
@endpush