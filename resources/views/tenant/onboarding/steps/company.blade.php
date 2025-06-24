@extends('layouts.tenant-onboarding')

@section('title', 'Company Information - Ballie Setup')

@section('content')
<!-- Progress Steps -->
<div class="mb-8">
    <div class="flex items-center justify-center space-x-8">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-brand-blue text-white rounded-full flex items-center justify-center font-semibold">1</div>
            <span class="ml-3 text-sm font-medium text-brand-blue">Company Info</span>
        </div>
        <div class="w-16 h-1 bg-brand-blue rounded"></div>
        <div class="flex items-center">
            <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-semibold">2</div>
            <span class="ml-3 text-sm font-medium text-gray-500">Preferences</span>
        </div>
        <div class="w-16 h-1 bg-gray-200 rounded"></div>
        <div class="flex items-center">
            <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-semibold">3</div>
            <span class="ml-3 text-sm font-medium text-gray-500">Team Setup</span>
        </div>
        <div class="w-16 h-1 bg-gray-200 rounded"></div>
        <div class="flex items-center">
            <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-semibold">4</div>
            <span class="ml-3 text-sm font-medium text-gray-500">Complete</span>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-gradient-to-br from-brand-gold to-brand-teal rounded-lg flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h2M7 7h10M7 11h10M7 15h10"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Tell us about your business</h2>
        <p class="text-gray-600">This information will be used on your invoices and official documents.</p>
    </div>

    <form method="POST" action="{{ route('tenant.onboarding.save-step', ['tenant' => $tenant->slug, 'step' => 'company']) }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Company Logo -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Company Logo</label>
                <div class="flex items-center space-x-6">
                    <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                        <div id="logo-preview" class="hidden">
                            <img id="logo-image" src="" alt="Logo Preview" class="w-full h-full object-contain rounded-lg">
                        </div>
                        <div id="logo-placeholder" class="text-center">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-xs text-gray-500">Upload Logo</p>
                        </div>
                    </div>
                    <div>
                        <input type="file" id="logo" name="logo" accept="image/*" class="hidden" onchange="previewLogo(this)">
                        <label for="logo" class="bg-brand-blue text-white px-4 py-2 rounded-lg hover:bg-brand-dark-purple transition-colors cursor-pointer">
                            Choose File
                        </label>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 2MB</p>
                    </div>
                </div>
            </div>

            <!-- Company Name -->
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                <input type="text" id="company_name" name="company_name"
                       value="{{ old('company_name', $tenant->name ?? '') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent"
                       placeholder="Enter your company name">
                @error('company_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Business Type -->
            <div>
                <label for="business_type" class="block text-sm font-medium text-gray-700 mb-2">Business Type</label>
                <select id="business_type" name="business_type"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent">
                    <option value="">Select business type</option>
                    <option value="retail" {{ old('business_type') == 'retail' ? 'selected' : '' }}>Retail</option>
                    <option value="wholesale" {{ old('business_type') == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                    <option value="service" {{ old('business_type') == 'service' ? 'selected' : '' }}>Service</option>
                    <option value="manufacturing" {{ old('business_type') == 'manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                    <option value="restaurant" {{ old('business_type') == 'restaurant' ? 'selected' : '' }}>Restaurant/Food</option>
                    <option value="consulting" {{ old('business_type') == 'consulting' ? 'selected' : '' }}>Consulting</option>
                    <option value="other" {{ old('business_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Business Email *</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email', $tenant->email ?? '') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent"
                       placeholder="business@example.com">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="tel" id="phone" name="phone"
                       value="{{ old('phone', $tenant->phone ?? '') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent"
                       placeholder="+234 xxx xxx xxxx">
            </div>

            <!-- Address -->
            <div class="md:col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Business Address</label>
                <textarea id="address" name="address" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent"
                          placeholder="Enter your business address">{{ old('address', $tenant->address ?? '') }}</textarea>
            </div>

            <!-- City -->
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                <input type="text" id="city" name="city"
                       value="{{ old('city', $tenant->city ?? '') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent"
                       placeholder="Lagos">
            </div>

            <!-- State -->
            <div>
                <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                <select id="state" name="state"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent">
                    <option value="">Select state</option>
                    <option value="Abia" {{ old('state') == 'Abia' ? 'selected' : '' }}>Abia</option>
                    <option value="Adamawa" {{ old('state') == 'Adamawa' ? 'selected' : '' }}>Adamawa</option>
                    <option value="Akwa Ibom" {{ old('state') == 'Akwa Ibom' ? 'selected' : '' }}>Akwa Ibom</option>
                    <option value="Anambra" {{ old('state') == 'Anambra' ? 'selected' : '' }}>Anambra</option>
                    <option value="Bauchi" {{ old('state') == 'Bauchi' ? 'selected' : '' }}>Bauchi</option>
                    <option value="Bayelsa" {{ old('state') == 'Bayelsa' ? 'selected' : '' }}>Bayelsa</option>
                    <option value="Benue" {{ old('state') == 'Benue' ? 'selected' : '' }}>Benue</option>
                    <option value="Borno" {{ old('state') == 'Borno' ? 'selected' : '' }}>Borno</option>
                    <option value="Cross River" {{ old('state') == 'Cross River' ? 'selected' : '' }}>Cross River</option>
                    <option value="Delta" {{ old('state') == 'Delta' ? 'selected' : '' }}>Delta</option>
                    <option value="Ebonyi" {{ old('state') == 'Ebonyi' ? 'selected' : '' }}>Ebonyi</option>
                    <option value="Edo" {{ old('state') == 'Edo' ? 'selected' : '' }}>Edo</option>
                    <option value="Ekiti" {{ old('state') == 'Ekiti' ? 'selected' : '' }}>Ekiti</option>
                    <option value="Enugu" {{ old('state') == 'Enugu' ? 'selected' : '' }}>Enugu</option>
                    <option value="FCT" {{ old('state') == 'FCT' ? 'selected' : '' }}>Federal Capital Territory</option>
                    <option value="Gombe" {{ old('state') == 'Gombe' ? 'selected' : '' }}>Gombe</option>
                    <option value="Imo" {{ old('state') == 'Imo' ? 'selected' : '' }}>Imo</option>
                    <option value="Jigawa" {{ old('state') == 'Jigawa' ? 'selected' : '' }}>Jigawa</option>
                    <option value="Kaduna" {{ old('state') == 'Kaduna' ? 'selected' : '' }}>Kaduna</option>
                    <option value="Kano" {{ old('state') == 'Kano' ? 'selected' : '' }}>Kano</option>
                    <option value="Katsina" {{ old('state') == 'Katsina' ? 'selected' : '' }}>Katsina</option>
                    <option value="Kebbi" {{ old('state') == 'Kebbi' ? 'selected' : '' }}>Kebbi</option>
                    <option value="Kogi" {{ old('state') == 'Kogi' ? 'selected' : '' }}>Kogi</option>
                    <option value="Kwara" {{ old('state') == 'Kwara' ? 'selected' : '' }}>Kwara</option>
                    <option value="Lagos" {{ old('state') == 'Lagos' ? 'selected' : '' }}>Lagos</option>
                    <option value="Nasarawa" {{ old('state') == 'Nasarawa' ? 'selected' : '' }}>Nasarawa</option>
                    <option value="Niger" {{ old('state') == 'Niger' ? 'selected' : '' }}>Niger</option>
                    <option value="Ogun" {{ old('state') == 'Ogun' ? 'selected' : '' }}>Ogun</option>
                    <option value="Ondo" {{ old('state') == 'Ondo' ? 'selected' : '' }}>Ondo</option>
                    <option value="Osun" {{ old('state') == 'Osun' ? 'selected' : '' }}>Osun</option>
                    <option value="Oyo" {{ old('state') == 'Oyo' ? 'selected' : '' }}>Oyo</option>
                    <option value="Plateau" {{ old('state') == 'Plateau' ? 'selected' : '' }}>Plateau</option>
                    <option value="Rivers" {{ old('state') == 'Rivers' ? 'selected' : '' }}>Rivers</option>
                    <option value="Sokoto" {{ old('state') == 'Sokoto' ? 'selected' : '' }}>Sokoto</option>
                    <option value="Taraba" {{ old('state') == 'Taraba' ? 'selected' : '' }}>Taraba</option>
                    <option value="Yobe" {{ old('state') == 'Yobe' ? 'selected' : '' }}>Yobe</option>
                    <option value="Zamfara" {{ old('state') == 'Zamfara' ? 'selected' : '' }}>Zamfara</option>
                </select>
            </div>

            <!-- Tax ID -->
            <div>
                <label for="tax_id" class="block text-sm font-medium text-gray-700 mb-2">Tax Identification Number (TIN)</label>
                <input type="text" id="tax_id" name="tax_id"
                       value="{{ old('tax_id', $tenant->tax_id ?? '') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent"
                       placeholder="Enter your TIN">
            </div>

            <!-- RC Number -->
            <div>
                <label for="rc_number" class="block text-sm font-medium text-gray-700 mb-2">RC Number</label>
                <input type="text" id="rc_number" name="rc_number"
                       value="{{ old('rc_number', $tenant->rc_number ?? '') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-blue focus:border-transparent"
                       placeholder="Company registration number">
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('tenant.onboarding', ['tenant' => $tenant->slug]) }}"
               class="text-gray-600 hover:text-brand-blue font-medium">
                ← Back to Overview
            </a>

            <div class="flex space-x-4">
                <button type="button" onclick="skipStep()"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                    Skip This Step
                </button>
                <button type="submit"
                        class="px-8 py-3 bg-brand-blue text-white rounded-lg hover:bg-brand-dark-purple font-medium">
                    Continue →
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logo-image').src = e.target.result;
            document.getElementById('logo-preview').classList.remove('hidden');
            document.getElementById('logo-placeholder').classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function skipStep() {
    if (confirm('Are you sure you want to skip this step? You can always update this information later in settings.')) {
        window.location.href = "{{ route('tenant.onboarding.step', ['tenant' => $tenant->slug, 'step' => 'preferences']) }}";
    }
}
</script>
@endsection