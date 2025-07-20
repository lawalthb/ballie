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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Database\Seeders\AccountGroupSeeder;
use Database\Seeders\VoucherTypeSeeder;
use Database\Seeders\DefaultLedgerAccountsSeeder;

class OnboardingController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $tenant = $this->createTenant($request);
                $this->seedDefaultData($tenant);

                return response()->json([
                    'success' => true,
                    'message' => 'Tenant onboarded successfully with default data',
                    'tenant' => $tenant
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Onboarding failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function seedDefaultData($tenant)
    {
        AccountGroupSeeder::seedForTenant($tenant->id);
        VoucherTypeSeeder::seedForTenant($tenant->id);
        DefaultLedgerAccountsSeeder::seedForTenant($tenant->id);

        Log::info("Default data seeded for tenant: {$tenant->name} (ID: {$tenant->id})");
    }

    private function createTenant($request)
    {
        return Tenant::create([]);
    }

    public function checkOnboardingStatus($tenantId)
    {
        $accountGroupsCount = \App\Models\AccountGroup::where('tenant_id', $tenantId)->count();
        $voucherTypesCount = \App\Models\VoucherType::where('tenant_id', $tenantId)->count();
        $ledgerAccountsCount = \App\Models\LedgerAccount::where('tenant_id', $tenantId)->count();

        return response()->json([
            'onboarding_complete' => $accountGroupsCount > 0 && $voucherTypesCount > 0 && $ledgerAccountsCount > 0,
            'account_groups' => $accountGroupsCount,
            'voucher_types' => $voucherTypesCount,
            'ledger_accounts' => $ledgerAccountsCount,
        ]);
    }

    public function reseedDefaultData($tenantId)
    {
        try {
            $tenant = \App\Models\Tenant::findOrFail($tenantId);

            DB::transaction(function () use ($tenant) {
                $this->seedDefaultData($tenant);
            });

            return response()->json([
                'success' => true,
                'message' => 'Default data re-seeded successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Re-seeding failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index(Tenant $tenant)
    {
        if ($tenant->onboarding_completed_at) {
            return redirect()->route('tenant.dashboard', ['tenant' => $tenant->slug]);
        }

        return view('tenant.onboarding.index', compact('tenant'));
    }

    public function showStep(Tenant $tenant, $step)
    {
        if ($tenant->onboarding_completed_at) {
            return redirect()->route('tenant.dashboard', ['tenant' => $tenant->slug]);
        }

        $validSteps = ['company', 'preferences', 'team', 'complete'];

        if (!in_array($step, $validSteps)) {
            return redirect()->route('tenant.onboarding.index', ['tenant' => $tenant->slug]);
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
                return redirect()->route('tenant.onboarding.index', ['tenant' => $tenant->slug]);
        }
    }

    private function saveCompanyStep(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
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

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('tenant-logos', 'public');
            $data['logo'] = $logoPath;
        }

        $tenant->update($data);

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
            'payment_terms' => 'nullable|integer|min:0|max:365',
            'default_tax_rate' => 'required|numeric|min:0|max:100',
            'tax_inclusive' => 'required|boolean',
            'enable_withholding_tax' => 'nullable|boolean',
            'features' => 'nullable|array',
            'features.*' => 'string|in:inventory,invoicing,customers,payroll,pos,reports',
        ]);

        $data = $request->all();
        $data['enable_withholding_tax'] = $request->boolean('enable_withholding_tax');
        $data['features'] = $request->input('features', []);

        $settings = $tenant->settings ?? [];
        $settings = array_merge($settings, $data);
        $tenant->update(['settings' => $settings]);

        $progress = $tenant->onboarding_progress ?? [];
        $progress['preferences'] = true;
        $tenant->update(['onboarding_progress' => $progress]);

        return redirect()->route('tenant.onboarding.step', [
            'tenant' => $tenant->slug,
            'step' => 'team'
        ])->with('success', 'Preferences saved successfully!');
    }

    public function saveTeamStep(Request $request, Tenant $tenant)
    {
        if ($request->has('skip_team') && $request->skip_team == '1') {
            return redirect()->route('tenant.onboarding.step', [
                'tenant' => $tenant->slug,
                'step' => 'complete'
            ])->with('success', 'Team setup skipped. You can add team members later from your dashboard.');
        }

        $teamMembers = $request->input('team_members', []);

        $validTeamMembers = array_filter($teamMembers, function($member) {
            return !empty($member['name']) || !empty($member['email']) || !empty($member['role']);
        });

        if (!empty($validTeamMembers)) {
            $rules = [];
            $messages = [];

            foreach ($validTeamMembers as $index => $member) {
                $rules["team_members.{$index}.name"] = 'required|string|max:255';
                $rules["team_members.{$index}.email"] = 'required|email|max:255';
                $rules["team_members.{$index}.role"] = 'required|string|in:admin,manager,accountant,sales,employee';
                $rules["team_members.{$index}.department"] = 'nullable|string|max:255';

                $messages["team_members.{$index}.name.required"] = "Team Member " . ($index + 1) . ": Name is required";
                $messages["team_members.{$index}.email.required"] = "Team Member " . ($index + 1) . ": Email is required";
                $messages["team_members.{$index}.email.email"] = "Team Member " . ($index + 1) . ": Please enter a valid email address";
                $messages["team_members.{$index}.role.required"] = "Team Member " . ($index + 1) . ": Role is required";
            }

            $request->validate($rules, $messages);

            foreach ($validTeamMembers as $memberData) {
                $this->createTeamMemberInvitation($tenant, $memberData);
            }

            $memberCount = count($validTeamMembers);
            $successMessage = "Great! {$memberCount} team member" . ($memberCount > 1 ? 's' : '') . " invited successfully.";
        } else {
            $successMessage = "Team setup completed. You can add team members later from your dashboard.";
        }

        $progress = $tenant->onboarding_progress ?? [];
        $progress['team'] = true;
        $tenant->update(['onboarding_progress' => $progress]);

        return redirect()->route('tenant.onboarding.step', [
            'tenant' => $tenant->slug,
            'step' => 'complete'
        ])->with('success', $successMessage);
    }

    private function createTeamMemberInvitation($tenant, $memberData)
    {
        Log::info('Team member invitation created', [
            'tenant_id' => $tenant->id,
            'member_data' => $memberData
        ]);
    }

    private function getCurrentTenant()
    {
        $routeParameters = request()->route()->parameters();
        if (isset($routeParameters['tenant'])) {
            if ($routeParameters['tenant'] instanceof Tenant) {
                return $routeParameters['tenant'];
            } else {
                return Tenant::where('slug', $routeParameters['tenant'])->firstOrFail();
            }
        }

        if (function_exists('tenant') && tenant()) {
            return tenant();
        }

        if (auth()->check() && auth()->user()->tenant_id) {
            return Tenant::find(auth()->user()->tenant_id);
        }

        throw new \Exception('Could not determine the current tenant.');
    }

    public function complete(Request $request, Tenant $tenant)
    {
        $tenant = $request->route('tenant');

        AccountGroupSeeder::seedForTenant($tenant->id);
        VoucherTypeSeeder::seedForTenant($tenant->id);
        DefaultLedgerAccountsSeeder::seedForTenant($tenant->id);

        $tenant->update([
            'onboarding_completed_at' => now(),
            'onboarding_progress' => [
                'company' => true,
                'preferences' => true,
                'team' => true,
                'complete' => true
            ]
        ]);

        return redirect()->route('tenant.dashboard', ['tenant' => $tenant->slug])
            ->with('success', 'Welcome to Ballie! Your account is now fully set up and ready to use.');
    }
}