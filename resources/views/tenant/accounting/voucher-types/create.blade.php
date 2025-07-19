@extends('layouts.tenant')

@section('title', 'Create Voucher Type')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Voucher Type</h1>
            <p class="mt-2 text-gray-600">Set up a new voucher type for your accounting transactions</p>
        </div>
        <a href="{{ route('tenant.accounting.voucher-types.index', ['tenant' => $tenant->slug]) }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to List
        </a>
    </div>

    <form action="{{ route('tenant.accounting.voucher-types.store', ['tenant' => $tenant->slug]) }}"
          method="POST"
          x-data="voucherTypeForm()"
          class="space-y-6">
        @csrf
              <!-- Primary Voucher Type Selection -->
              <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                  <div class="px-6 py-4 border-b border-gray-200">
                      <h3 class="text-lg font-medium text-gray-900">Primary Type</h3>
                  </div>
                  <div class="p-6">
                      <div class="space-y-4">
                          <div>
                              <label for="primary_type" class="block text-sm font-medium text-gray-700">
                                  Select a Primary Type
                              </label>
                              <select x-model="selectedPrimary"
                                      @change="selectedPrimary ? applyPrimaryType(selectedPrimary) : clearPrimaryType()"
                                      class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-lg">
                                  <option value="">Custom Type</option>
                                  <template x-for="(type, key) in primaryTypes" :key="key">
                                      <option :value="key" x-text="type.name"></option>
                                  </template>
                              </select>
                              <p class="mt-2 text-sm text-gray-500">Choose a predefined type or create a custom one</p>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- Basic Information -->
              <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                  <div class="px-6 py-4 border-b border-gray-200">
                      <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                  </div>
                  <div class="p-6">
                      <div class="grid grid-cols-1 gap-6">
                          <!-- Name -->
                          <div>
                              <label for="name" class="block text-sm font-medium text-gray-700">
                                  Name
                              </label>
                              <input type="text"
                                   name="name"
                                   id="name"
                                   x-model="form.name"
                                   @input="generateCode"
                                   value="{{ old('name') }}"
                                   class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg"
                                   required>
                          </div>

                          <!-- Code -->
                          <div>
                              <label for="code" class="block text-sm font-medium text-gray-700">
                                  Code
                              </label>
                              <input type="text"
                                   name="code"
                                   id="code"
                                   x-model="form.code"
                                   value="{{ old('code') }}"
                                   class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg"
                                   required>
                          </div>

                          <!-- Abbreviation -->
                          <div>
                              <label for="abbreviation" class="block text-sm font-medium text-gray-700">
                                  Abbreviation
                              </label>
                              <input type="text"
                                   name="abbreviation"
                                   id="abbreviation"
                                   x-model="form.abbreviation"
                                   value="{{ old('abbreviation') }}"
                                   class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg"
                                   required>
                          </div>

                          <!-- Description -->
                          <div>
                              <label for="description" class="block text-sm font-medium text-gray-700">
                                  Description
                              </label>
                              <textarea name="description"
                                        id="description"
                                        x-model="form.description"
                                        rows="3"
                                        class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg">{{ old('description') }}</textarea>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- Numbering -->
              <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                  <div class="px-6 py-4 border-b border-gray-200">
                      <h3 class="text-lg font-medium text-gray-900">Numbering</h3>
                  </div>
                  <div class="p-6">
                      <div class="space-y-6">
                          <!-- Numbering Method -->
                          <div>
                              <label class="block text-sm font-medium text-gray-700">
                                  Numbering Method
                              </label>
                              <div class="mt-2 space-y-4">
                                  <div class="flex items-center">
                                      <input type="radio"
                                           name="numbering_method"
                                           id="numbering_method_auto"
                                           x-model="form.numbering_method"
                                           value="auto"
                                           {{ old('numbering_method', 'auto') === 'auto' ? 'checked' : '' }}
                                           class="form-radio text-primary-600">
                                      <label for="numbering_method_auto" class="ml-3 text-sm text-gray-700">
                                          Automatic
                                      </label>
                                  </div>
                                  <div class="flex items-center">
                                      <input type="radio"
                                           name="numbering_method"
                                           id="numbering_method_manual"
                                           x-model="form.numbering_method"
                                           value="manual"
                                           {{ old('numbering_method') === 'manual' ? 'checked' : '' }}
                                           class="form-radio text-primary-600">
                                      <label for="numbering_method_manual" class="ml-3 text-sm text-gray-700">
                                          Manual
                                      </label>
                                  </div>
                              </div>
                          </div>

                          <!-- Prefix -->
                          <div>
                              <label for="prefix" class="block text-sm font-medium text-gray-700">
                                  Prefix
                              </label>
                              <input type="text"
                                   name="prefix"
                                   id="prefix"
                                   x-model="form.prefix"
                                   value="{{ old('prefix') }}"
                                   class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg">
                          </div>

                          <!-- Starting Number -->
                          <div>
                              <label for="starting_number" class="block text-sm font-medium text-gray-700">
                                  Starting Number
                              </label>
                              <input type="number"
                                   name="starting_number"
                                   id="starting_number"
                                   x-model="form.starting_number"
                                   value="{{ old('starting_number', 1) }}"
                                   min="1"
                                   class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg">
                          </div>

                          <!-- Has Reference -->
                          <div class="flex items-start">
                              <div class="flex items-center h-5">
                                  <input type="checkbox"
                                       name="has_reference"
                                       id="has_reference"
                                       x-model="form.has_reference"
                                       value="1"
                                       {{ old('has_reference') ? 'checked' : '' }}
                                       class="form-checkbox text-primary-600 rounded">
                              </div>
                              <div class="ml-3">
                                  <label for="has_reference" class="text-sm font-medium text-gray-700">
                                      Has Reference
                                  </label>
                                  <p class="text-xs text-gray-500">Check if this voucher type requires a reference number</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- Additional Settings -->
              <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                  <div class="px-6 py-4 border-b border-gray-200">
                      <h3 class="text-lg font-medium text-gray-900">Additional Settings</h3>
                  </div>
                  <div class="p-6">
                      <div class="space-y-6">
                          <!-- Affects Inventory -->
                          <div class="flex items-start">
                              <div class="flex items-center h-5">
                                  <input type="checkbox"
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">