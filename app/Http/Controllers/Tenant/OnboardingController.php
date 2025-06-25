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

    public function index()
    {



        $tenant = app('currentTenant');

        // If onboarding is already completed, redirect to dashboard
        if ($tenant && $tenant->onboarding_completed) {
            return redirect()->route('tenant.dashboard');
        }



        // Start with the first step
        return redirect()->route('onboarding.step', ['step' => 'company']);
    }


    public function showStep($step)
    {



        $tenant = app('currentTenant');

        // If onboarding is already completed, redirect to dashboard
        if ($tenant && $tenant->onboarding_completed) {
            return redirect()->route('tenant.dashboard');
        }


        $validSteps = ['company', 'preferences', 'team', 'complete'];


        if (!in_array($step, $validSteps)) {

            return redirect()->route('onboarding.step', ['step' => 'company']);
        }



        $data = [
            'tenant' => $tenant,
            'currentStep' => $step,
            'steps' => $validSteps,
            'progress' => $this->calculateProgress($step)
        ];

        return view("tenant.onboarding.{$step}", $data);
    }


    public function saveStep(Request $request, $step)
    {
        $tenant = app('currentTenant');

        switch ($step) {
            case 'company':
                return $this->saveCompanyStep($request, $tenant);
            case 'preferences':
                return $this->savePreferencesStep($request, $tenant);
            case 'team':
                return $this->saveTeamStep($request, $tenant);
            default:


                return redirect()->route('onboarding.step', ['step' => 'company']);
        }
    }

    private function saveCompanyStep(Request $request, Tenant $tenant)
    {
        $request->validate([


            'company_name' => 'required|string|max:255',
            'business_type' => 'required|string',
            'phone' => 'nullable|string|max:20',


            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',





            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);


        $data = $request->only([
            'phone', 'address', 'city', 'state', 'website', 'business_type'
        ]);

        // Update company name if provided
        if ($request->filled('company_name')) {
            $data['name'] = $request->company_name;
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {

            $logoPath = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $logoPath;
        }


        $tenant->update($data);










        return redirect()->route('onboarding.step', ['step' => 'preferences'])
                        ->with('success', 'Company information saved successfully!');
    }

    private function savePreferencesStep(Request $request, Tenant $tenant)
    {
        $request->validate([

            'currency' => 'required|string|max:3',
            'timezone' => 'required|string|max:50',
            'date_format' => 'required|string|max:20',


            'invoice_prefix' => 'nullable|string|max:10',
            'quote_prefix' => 'nullable|string|max:10',






            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'apply_vat' => 'boolean'
        ]);




        $settings = $request->only([
            'currency', 'timezone', 'date_format',
            'invoice_prefix', 'quote_prefix', 'vat_rate', 'apply_vat'
        ]);





        $tenant->update(['settings' => array_merge($tenant->settings ?? [], $settings)]);










        return redirect()->route('onboarding.step', ['step' => 'team'])
                        ->with('success', 'Preferences saved successfully!');
    }

    public function saveTeamStep(Request $request, Tenant $tenant)
    {
























































        // Team setup is optional, so we can skip validation
        // Just proceed to completion
        return redirect()->route('onboarding.step', ['step' => 'complete']);
    }

    private function createTeamMemberInvitation($tenant, $memberData)
    {
        // Here you would typically:
        // 1. Create a user invitation record
        // 2. Send invitation email
        // 3. Store team member data

        // For now, let's just log it
        \Log::info('Team member invitation created', [
            'tenant_id' => $tenant->id,
            'member_data' => $memberData
        ]);

        // You can implement the actual invitation logic here
        // Example:
        // TeamInvitation::create([
        //     'tenant_id' => $tenant->id,
        //     'email' => $memberData['email'],
        //     'name' => $memberData['name'],
        //     'role' => $memberData['role'],
        //     'department' => $memberData['department'] ?? null,
        //     'invited_by' => auth()->id(),
        //     'token' => Str::random(32),
        //     'expires_at' => now()->addDays(7),
        // ]);

        // Send invitation email
        // Mail::to($memberData['email'])->send(new TeamInvitationMail($invitation));
    }

    /**
     * Get the current tenant
     *
     * @return \App\Models\Tenant
     */
    private function getCurrentTenant()
    {
        // Get the current tenant from the route parameter
        $routeParameters = request()->route()->parameters();
        if (isset($routeParameters['tenant'])) {
            if ($routeParameters['tenant'] instanceof Tenant) {
                return $routeParameters['tenant'];
            } else {
                return Tenant::where('slug', $routeParameters['tenant'])->firstOrFail();
            }
        }

        // Fallback to the tenant from the subdomain/domain if using that approach
        if (function_exists('tenant') && tenant()) {
            return tenant();
        }

        // If all else fails, try to get the tenant from the authenticated user
        if (auth()->check() && auth()->user()->tenant_id) {
            return Tenant::find(auth()->user()->tenant_id);
        }

        throw new \Exception('Could not determine the current tenant.');
    }

    /**
     * Complete the onboarding process
     */

    public function complete(Request $request)
    {

        $tenant = app('currentTenant');

        if (!$tenant) {
            return redirect()->route('home');
        }

        // Mark onboarding as completed
        $tenant->update([







            'onboarding_completed' => true,
            'onboarding_completed_at' => now()
        ]);






        // Clear any onboarding cache
        cache()->forget("tenant_{$tenant->id}_onboarding_status");

        return redirect()->route('tenant.dashboard')->with('success', 'Welcome to Ballie! Your account setup is complete.');
    }

    private function calculateProgress($currentStep)
    {
        $steps = ['company', 'preferences', 'team', 'complete'];
        $currentIndex = array_search($currentStep, $steps);

        if ($currentIndex === false) {
            return 0;
        }

        return (($currentIndex + 1) / count($steps)) * 100;
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