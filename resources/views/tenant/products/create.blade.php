@extends('layouts.tenant')

@section('title', 'Add Product')
@section('page-title', 'Add New Product')
@section('page-description', 'Add a new product or service to your inventory.')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <form action="{{ route('tenant.products.store', ['tenant' => $tenant->slug]) }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf

            <!-- Product Type Selection -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-4">Product Type</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <input type="radio" name="type" value="item" id="type_item" class="sr-only peer" {{ old('type', 'item') === 'item' ? 'checked' : '' }}>
                        <label for="type_item" class="flex items-center justify-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                            <div class="text-center">
                                <div class="w-12 h-12 mx-auto mb-2 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <h3 class="font-medium text-gray-900">Item</h3>
                                <p class="text-sm text-gray-500">Physical products with inventory</p>
                            </div>
                        </label>
                    </div>

                    <div>
                        <input type="radio" name="type" value="service" id="type_service" class="sr-only peer" {{ old('type') === 'service' ? 'checked' : '' }}>
                        <label for="type_service" class="flex items-center justify-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                            <div class="text-center">
                                <div class="w-12 h-12 mx-auto mb-2 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="font-medium text-gray-900">Service</h3>
                                <p class="text-sm text-gray-500">Non-physical services</p>
                            </div>
                        </label>
                    </div>
                </div>
                @error('type')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                    <p class="mt-1 text-xs text-gray-500">Leave empty to auto-generate</p>
                    @error('sku')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <input type="text" name="category" id="category" value="{{ old('category') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="hsn_code" class="block text-sm font-medium text-gray-700 mb-2">HSN/SAC Code</label>
                    <input type="text" name="hsn_code" id="hsn_code" value="{{ old('hsn_code') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                    @error('hsn_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="bg-gray-50 rounded-xl p-6 mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Pricing Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="purchase_rate" class="block text-sm font-medium text-gray-700 mb-2">Purchase Rate *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">₦</span>
                            <input type="number" name="purchase_rate" id="purchase_rate" value="{{ old('purchase_rate', 0) }}" step="0.01" min="0" required
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        </div>
                        @error('purchase_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sales_rate" class="block text-sm font-medium text-gray-700 mb-2">Sales Rate *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">₦</span>
                            <input type="number" name="sales_rate" id="sales_rate" value="{{ old('sales_rate', 0) }}" step="0.01" min="0" required
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        </div>
                        @error('sales_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mrp" class="block text-sm font-medium text-gray-700 mb-2">MRP</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">₦</span>
                            <input type="number" name="mrp" id="mrp" value="{{ old('mrp') }}" step="0.01" min="0"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        </div>
                        @error('mrp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="minimum_selling_price" class="block text-sm font-medium text-gray-700 mb-2">Minimum Selling Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">₦</span>
                            <input type="number" name="minimum_selling_price" id="minimum_selling_price" value="{{ old('minimum_selling_price') }}" step="0.01" min="0"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        </div>
                        @error('minimum_selling_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Units and Inventory (for items only) -->
            <div id="inventory-section" class="bg-blue-50 rounded-xl p-6 mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Units & Inventory</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="primary_unit" class="block text-sm font-medium text-gray-700 mb-2">Primary Unit *</label>
                        <select name="primary_unit" id="primary_unit" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            <option value="Nos" {{ old('primary_unit') === 'Nos' ? 'selected' : '' }}>Nos</option>
                            <option value="Kg" {{ old('primary_unit') === 'Kg' ? 'selected' : '' }}>Kg</option>
                            <option value="Ltr" {{ old('primary_unit') === 'Ltr' ? 'selected' : '' }}>Ltr</option>
                            <option value="Mtr" {{ old('primary_unit') === 'Mtr' ? 'selected' : '' }}>Mtr</option>
                            <option value="Box" {{ old('primary_unit') === 'Box' ? 'selected' : '' }}>Box</option>
                            <option value="Pcs" {{ old('primary_unit') === 'Pcs' ? 'selected' : '' }}>Pcs</option>
                            <option value="Dozen" {{ old('primary_unit') === 'Dozen' ? 'selected' : '' }}>Dozen</option>
                        </select>
                        @error('primary_unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="secondary_unit" class="block text-sm font-medium text-gray-700 mb-2">Secondary Unit</label>
                        <select name="secondary_unit" id="secondary_unit"
                                class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            <option value="">Select Secondary Unit</option>
                            <option value="Nos" {{ old('secondary_unit') === 'Nos' ? 'selected' : '' }}>Nos</option>
                            <option value="Kg" {{ old('secondary_unit') === 'Kg' ? 'selected' : '' }}>Kg</option>
                            <option value="Ltr" {{ old('secondary_unit') === 'Ltr' ? 'selected' : '' }}>Ltr</option>
                            <option value="Mtr" {{ old('secondary_unit') === 'Mtr' ? 'selected' : '' }}>Mtr</option>
                            <option value="Box" {{ old('secondary_unit') === 'Box' ? 'selected' : '' }}>Box</option>
                            <option value="Pcs" {{ old('secondary_unit') === 'Pcs' ? 'selected' : '' }}>Pcs</option>
                            <option value="Dozen" {{ old('secondary_unit') === 'Dozen' ? 'selected' : '' }}>Dozen</option>
                        </select>
                        @error('secondary_unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center mb-6">
                    <input type="checkbox" name="maintain_stock" id="maintain_stock" value="1" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" {{ old('maintain_stock', 1) ? 'checked' : '' }}>
                    <label for="maintain_stock" class="ml-2 block text-sm text-gray-900">Maintain Stock</label>
                </div>

                <div id="stock-fields" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="opening_stock" class="block text-sm font-medium text-gray-700 mb-2">Opening Stock</label>
                        <input type="number" name="opening_stock" id="opening_stock" value="{{ old('opening_stock', 0) }}" step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('opening_stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="reorder_level" class="block text-sm font-medium text-gray-700 mb-2">Reorder Level</label>
                        <input type="number" name="reorder_level" id="reorder_level" value="{{ old('reorder_level') }}" step="0.01" min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('reorder_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="maximum_stock" class="block text-sm font-medium text-gray-700 mb-2">Maximum Stock</label>
                        <input type="number" name="maximum_stock" id="maximum_stock" value="{{ old('maximum_stock') }}" step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('maximum_stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Taxation -->
            <div class="bg-yellow-50 rounded-xl p-6 mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Taxation</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%)</label>
                        <input type="number" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', 0) }}" step="0.01" min="0" max="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('tax_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tax_type" class="block text-sm font-medium text-gray-700 mb-2">Tax Type</label>
                        <select name="tax_type" id="tax_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            <option value="GST" {{ old('tax_type') === 'GST' ? 'selected' : '' }}>GST</option>
                            <option value="VAT" {{ old('tax_type') === 'VAT' ? 'selected' : '' }}>VAT</option>
                            <option value="Service Tax" {{ old('tax_type') === 'Service Tax' ? 'selected' : '' }}>Service Tax</option>
                        </select>
                        @error('tax_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="tax_inclusive" id="tax_inclusive" value="1" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" {{ old('tax_inclusive') ? 'checked' : '' }}>
                        <label for="tax_inclusive" class="ml-2 block text-sm text-gray-900">Tax Inclusive Pricing</label>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-green-50 rounded-xl p-6 mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <input type="text" name="brand" id="brand" value="{{ old('brand') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('brand')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                        <input type="text" name="model" id="model" value="{{ old('model') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="size" class="block text-sm font-medium text-gray-700 mb-2">Size</label>
                        <input type="text" name="size" id="size" value="{{ old('size') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('size')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                        <input type="text" name="color" id="color" value="{{ old('color') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Weight</label>
                        <div class="flex space-x-2">
                            <input type="number" name="weight" id="weight" value="{{ old('weight') }}" step="0.001" min="0"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            <select name="weight_unit" id="weight_unit"
                                    class="px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                                <option value="kg" {{ old('weight_unit') === 'kg' ? 'selected' : '' }}>Kg</option>
                                <option value="g" {{ old('weight_unit') === 'g' ? 'selected' : '' }}>g</option>
                                <option value="lbs" {{ old('weight_unit') === 'lbs' ? 'selected' : '' }}>lbs</option>
                            </select>
                        </div>
                        @error('weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="barcode" class="block text-sm font-medium text-gray-700 mb-2">Barcode</label>
                        <input type="text" name="barcode" id="barcode" value="{{ old('barcode') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                        @error('barcode')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Product Image -->
            <div class="bg-gray-50 rounded-xl p-6 mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Product Image</h3>
                <div class="flex items-center justify-center w-full">
                    <label for="image" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500">PNG, JPG or GIF (MAX. 2MB)</p>
                        </div>
                        <input id="image" name="image" type="file" class="hidden" accept="image/*">
                    </label>
                </div>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Options -->
            <div class="bg-gray-50 rounded-xl p-6 mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Status & Options</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_saleable" id="is_saleable" value="1" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" {{ old('is_saleable', 1) ? 'checked' : '' }}>
                            <label for="is_saleable" class="ml-2 block text-sm text-gray-900">Saleable</label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_purchasable" id="is_purchasable" value="1" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" {{ old('is_purchasable', 1) ? 'checked' : '' }}>
                            <label for="is_purchasable" class="ml-2 block text-sm text-gray-900">Purchasable</label>
                        </div>
                    </div>

                    <div class="space-y-4" id="tracking-options">
                        <div class="flex items-center">
                            <input type="checkbox" name="track_serial_numbers" id="track_serial_numbers" value="1" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" {{ old('track_serial_numbers') ? 'checked' : '' }}>
                            <label for="track_serial_numbers" class="ml-2 block text-sm text-gray-900">Track Serial Numbers</label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="track_batch_numbers" id="track_batch_numbers" value="1" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" {{ old('track_batch_numbers') ? 'checked' : '' }}>
                            <label for="track_batch_numbers" class="ml-2 block text-sm text-gray-900">Track Batch Numbers</label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="perishable" id="perishable" value="1" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" {{ old('perishable') ? 'checked' : '' }}>
                            <label for="perishable" class="ml-2 block text-sm text-gray-900">Perishable Item</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('tenant.products.index', ['tenant' => $tenant->slug]) }}"
                   class="px-6 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                    Cancel
                </a>

                <button type="submit"
                        class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                    Create Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemRadio = document.getElementById('type_item');
    const serviceRadio = document.getElementById('type_service');
    const inventorySection = document.getElementById('inventory-section');
    const stockFields = document.getElementById('stock-fields');
    const maintainStockCheckbox = document.getElementById('maintain_stock');
    const trackingOptions = document.getElementById('tracking-options');

    function toggleSections() {
        if (serviceRadio.checked) {
            inventorySection.style.display = 'none';
            trackingOptions.style.display = 'none';
        } else {
            inventorySection.style.display = 'block';
            trackingOptions.style.display = 'block';
        }
    }

    function toggleStockFields() {
        if (maintainStockCheckbox.checked) {
            stockFields.style.display = 'grid';
        } else {
            stockFields.style.display = 'none';
        }
    }

    // Initial setup
    toggleSections();
    toggleStockFields();

    // Event listeners
    itemRadio.addEventListener('change', toggleSections);
    serviceRadio.addEventListener('change', toggleSections);
    maintainStockCheckbox.addEventListener('change', toggleStockFields);

    // Image preview
    const imageInput = document.getElementById('image');
    const imageLabel = imageInput.parentElement;

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imageLabel.innerHTML = `
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <img src="${e.target.result}" alt="Preview" class="w-32 h-32 object-cover rounded-lg mb-4">
                        <p class="text-sm text-gray-500"><span class="font-semibold">Click to change</span> or drag and drop</p>
                        <p class="text-xs text-gray-500">PNG, JPG or GIF (MAX. 2MB)</p>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }
    });

    // Form validation
    const form = document.getElementById('productForm');
    form.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const purchaseRate = document.getElementById('purchase_rate').value;
        const salesRate = document.getElementById('sales_rate').value;

        if (!name) {
            e.preventDefault();
            alert('Product name is required');
            return false;
        }

        if (purchaseRate <= 0 || salesRate <= 0) {
            e.preventDefault();
            alert('Purchase rate and sales rate must be greater than 0');
            return false;
        }

        if (parseFloat(salesRate) < parseFloat(purchaseRate)) {
            if (!confirm('Sales rate is less than purchase rate. Are you sure you want to continue?')) {
                e.preventDefault();
                return false;
            }
        }
    });
});
</script>
@endsection
