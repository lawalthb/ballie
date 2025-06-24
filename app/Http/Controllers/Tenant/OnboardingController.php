<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class OnboardingController extends Controller
{
    public function index(Tenant $tenant)
    {
        // Check if onboarding is already completed
        if ($tenant->onboarding_completed_at) {
            return redirect()->route('tenant.dashboard', ['tenant' => $tenant->slug]);
        }

        return view('tenant.onboarding.index', compact('tenant'));
    }

    public function showStep(Tenant $tenant, $step)
    {
        // Check if onboarding is already completed
        if ($tenant->onboarding_completed_at) {
            return redirect()->route('tenant.dashboard', ['tenant' => $tenant->slug]);
        }

        $validSteps = ['company', 'preferences', 'team', 'complete'];

        if (!in_array($step, $validSteps)) {
            return redirect()->route('tenant.onboarding', ['tenant' => $tenant->slug]);
        }

        return view("tenant.onboarding.steps.{$step}", compact('tenant'));
    }

    public function saveStep(Request $request, Tenant $tenant, $step)
    {
        switch ($step) {
            case 'company':
                return $this->saveCompanyStep($request, $tenant);
            case 'preferences':
                return $this->savePreferencesStep($request, $tenant);
            case 'team':
                return $this->saveTeamStep($request, $tenant);
            default:
                return redirect()->route('tenant.onboarding', ['tenant' => $tenant->slug]);
        }
    }

    private function saveCompanyStep(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
            'industry' => 'required|string|max:100',
            'employee_count' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:50',
            'rc_number' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['logo']);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('tenant-logos', 'public');
            $data['logo'] = $logoPath;
        }

        // Update tenant information
        $tenant->update($data);

        // Update onboarding progress
        $progress = $tenant->onboarding_progress ?? [];
        $progress['company'] = true;
        $tenant->update(['onboarding_progress' => $progress]);

        return redirect()->route('tenant.onboarding.step', [
            'tenant' => $tenant->slug,
            'step' => 'preferences'
        ])->with('success', 'Company information saved successfully!');
    }

    private function savePreferencesStep(Request $request, Tenant $tenant)
    {
        $request->validate([
            'currency' => 'required|string|size:3',
            'timezone' => 'required|string|max:50',
            'date_format' => 'required|string|max:20',
            'time_format' => 'required|string|max:10',
            'fiscal_year_start' => 'required|string|max:10',
            'invoice_prefix' => 'nullable|string|max:10',
            'quote_prefix' => 'nullable|string|max:10',
            'payment_terms' => 'required|integer|min:0|max:365',
            'default_tax_rate' => 'required|numeric|min:0|max:100',
            'tax_inclusive' => 'required|boolean',
            'enable_withholding_tax' => 'nullable|boolean',
            'features' => 'nullable|array',
            'features.*' => 'string|in:inventory,invoicing,customers,payroll,pos,reports',
        ]);

        $data = $request->all();
        $data['enable_withholding_tax'] = $request->boolean('enable_withholding_tax');
        $data['features'] = $request->input('features', []);

        // Save preferences to tenant settings
        $settings = $tenant->settings ?? [];
        $settings = array_merge($settings, $data);
        $tenant->update(['settings' => $settings]);

        // Update onboarding progress
        $progress = $tenant->onboarding_progress ?? [];
        $progress['preferences'] = true;
        $tenant->update(['onboarding_progress' => $progress]);

        return redirect()->route('tenant.onboarding.step', [
            'tenant' => $tenant->slug,
            'step' => 'team'
        ])->with('success', 'Preferences saved successfully!');
    }

    private function saveTeamStep(Request $request, Tenant $tenant)
    {
        $request->validate([
            'team_members' => 'nullable|array',
            'team_members.*.name' => 'required_with:team_members|string|max:255',
            'team_members.*.email' => 'required_with:team_members|email|max:255',
            'team_members.*.role' => 'required_with:team_members|string|in:admin,manager,accountant,sales,employee',
        ]);

        $teamMembers = $request->input('team_members', []);
        $invitationsSent = 0;

        foreach ($teamMembers as $memberData) {
            // Check if user already exists
            $existingUser = User::where('email', $memberData['email'])->first();

            if ($existingUser) {
                // Add existing user to tenant if not already added
                if (!$existingUser->tenants()->where('tenant_id', $tenant->id)->exists()) {
                    $existingUser->tenants()->attach($tenant->id, [
                        'role' => $memberData['role'],
                        'is_active' => true,
                        'invited_at' => now(),
                        'invited_by' => Auth::id(),
                    ]);
                    $invitationsSent++;
                }
            } else {
                // Create new user and send invitation
                $newUser = User::create([
                    'name' => $memberData['name'],
                    'email' => $memberData['email'],
                    'password' => Hash::make(str()->random(12)), // Temporary password
                    'email_verified_at' => null,
                ]);

                // Attach to tenant
                $newUser->tenants()->attach($tenant->id, [
                    'role' => $memberData['role'],
                    'is_active' => false, // Will be activated when they accept invitation
                    'invited_at' => now(),
                    'invited_by' => Auth::id(),
                ]);

                // Send invitation email
                try {
                    Mail::to($newUser->email)->send(new \App\Mail\TeamInvitation($tenant, $newUser, $memberData['role']));
                    $invitationsSent++;
                } catch (\Exception $e) {
                    \Log::error('Failed to send team invitation: ' . $e->getMessage());
                }
            }
        }

        // Update onboarding progress
        $progress = $tenant->onboarding_progress ?? [];
        $progress['team'] = true;
        $tenant->update(['onboarding_progress' => $progress]);

        $message = $invitationsSent > 0
            ? "Team setup completed! {$invitationsSent} invitation(s) sent."
            : 'Team setup completed!';

        return redirect()->route('tenant.onboarding.step', [
            'tenant' => $tenant->slug,
            'step' => 'complete'
        ])->with('success', $message);
    }

    public function complete(Tenant $tenant)
    {
        // Mark onboarding as completed
        $tenant->update([
            'onboarding_completed_at' => now(),
        ]);

        // Create default data for the tenant
        $this->createDefaultData($tenant);

        return view('tenant.onboarding.steps.complete', compact('tenant'));
    }

    private function createDefaultData(Tenant $tenant)
    {
        // Set tenant context for creating default data
        $tenant->makeCurrent();

        try {
            // Create default chart of accounts
            $this->createDefaultChartOfAccounts();

            // Create default tax rates
            $this->createDefaultTaxRates($tenant);

            // Create default payment methods
            $this->createDefaultPaymentMethods();

            // Create default invoice templates
            $this->createDefaultInvoiceTemplates($tenant);

        } catch (\Exception $e) {
            \Log::error('Failed to create default data for tenant ' . $tenant->id . ': ' . $e->getMessage());
        }
    }

    private function createDefaultChartOfAccounts()
    {
        $accounts = [
            // Assets
            ['code' => '1000', 'name' => 'Cash and Bank', 'type' => 'asset', 'parent_id' => null],
            ['code' => '1100', 'name' => 'Accounts Receivable', 'type' => 'asset', 'parent_id' => null],
            ['code' => '1200', 'name' => 'Inventory', 'type' => 'asset', 'parent_id' => null],
            ['code' => '1300', 'name' => 'Fixed Assets', 'type' => 'asset', 'parent_id' => null],

            // Liabilities
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'liability', 'parent_id' => null],
            ['code' => '2100', 'name' => 'VAT Payable', 'type' => 'liability', 'parent_id' => null],
            ['code' => '2200', 'name' => 'Accrued Expenses', 'type' => 'liability', 'parent_id' => null],

            // Equity
            ['code' => '3000', 'name' => 'Owner\'s Equity', 'type' => 'equity', 'parent_id' => null],
            ['code' => '3100', 'name' => 'Retained Earnings', 'type' => 'equity', 'parent_id' => null],

            // Revenue
            ['code' => '4000', 'name' => 'Sales Revenue', 'type' => 'revenue', 'parent_id' => null],
            ['code' => '4100', 'name' => 'Service Revenue', 'type' => 'revenue', 'parent_id' => null],

            // Expenses
            ['code' => '5000', 'name' => 'Cost of Goods Sold', 'type' => 'expense', 'parent_id' => null],
            ['code' => '5100', 'name' => 'Operating Expenses', 'type' => 'expense', 'parent_id' => null],
            ['code' => '5200', 'name' => 'Administrative Expenses', 'type' => 'expense', 'parent_id' => null],
        ];

        foreach ($accounts as $account) {
            \App\Models\Account::create($account);
        }
    }

    private function createDefaultTaxRates(Tenant $tenant)
    {
        $settings = $tenant->settings ?? [];
        $defaultRate = $settings['default_tax_rate'] ?? 7.5;

        $taxRates = [
            [
                'name' => 'VAT',
                'rate' => $defaultRate,
                'type' => 'percentage',
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Zero Rated',
                'rate' => 0,
                'type' => 'percentage',
                'is_default' => false,
                'is_active' => true,
            ],
        ];

        if ($settings['enable_withholding_tax'] ?? false) {
            $taxRates[] = [
                'name' => 'Withholding Tax',
                'rate' => 5,
                'type' => 'percentage',
                'is_default' => false,
                'is_active' => true,
            ];
        }

        foreach ($taxRates as $taxRate) {
            \App\Models\TaxRate::create($taxRate);
        }
    }

    private function createDefaultPaymentMethods()
    {
        $paymentMethods = [
            ['name' => 'Cash', 'is_active' => true],
            ['name' => 'Bank Transfer', 'is_active' => true],
            ['name' => 'Cheque', 'is_active' => true],
            ['name' => 'Card Payment', 'is_active' => true],
            ['name' => 'Mobile Money', 'is_active' => true],
        ];

        foreach ($paymentMethods as $method) {
            \App\Models\PaymentMethod::create($method);
        }
    }

    private function createDefaultInvoiceTemplates(Tenant $tenant)
    {
        $settings = $tenant->settings ?? [];

        $template = [
            'name' => 'Default Template',
            'is_default' => true,
            'settings' => [
                'show_logo' => true,
                'show_company_details' => true,
                'show_customer_details' => true,
                'show_payment_terms' => true,
                'show_notes' => true,
                'color_scheme' => '#2b6399',
                'font_family' => 'Inter',
                'invoice_prefix' => $settings['invoice_prefix'] ?? 'INV',
                'quote_prefix' => $settings['quote_prefix'] ?? 'QUO',
            ],
        ];

        \App\Models\InvoiceTemplate::create($template);
    }

    public function skip(Tenant $tenant, $step)
    {
        // Update onboarding progress for skipped step
        $progress = $tenant->onboarding_progress ?? [];
        $progress[$step] = true;
        $tenant->update(['onboarding_progress' => $progress]);

        // Determine next step
        $nextStep = $this->getNextStep($step);

        if ($nextStep) {
            return redirect()->route('tenant.onboarding.step', [
                'tenant' => $tenant->slug,
                'step' => $nextStep
            ]);
        }

        return redirect()->route('tenant.onboarding.step', [
            'tenant' => $tenant->slug,
            'step' => 'complete'
        ]);
    }

    private function getNextStep($currentStep)
    {
        $steps = ['company', 'preferences', 'team', 'complete'];
        $currentIndex = array_search($currentStep, $steps);

        if ($currentIndex !== false && $currentIndex < count($steps) - 1) {
            return $steps[$currentIndex + 1];
        }

        return null;
    }
}