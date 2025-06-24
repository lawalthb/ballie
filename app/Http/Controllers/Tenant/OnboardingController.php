<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Helpers\TenantHelper;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function index()
    {
        $tenant = TenantHelper::getCurrentTenant();

        return view('tenant.onboarding.index', compact('tenant'));
    }

    public function step(Request $request, $step)
    {
        $tenant = TenantHelper::getCurrentTenant();

        $validSteps = ['company', 'preferences', 'users', 'complete'];

        if (!in_array($step, $validSteps)) {
            abort(404);
        }

        return view("tenant.onboarding.steps.{$step}", compact('tenant', 'step'));
    }

    public function saveStep(Request $request, $step)
    {
        $tenant = TenantHelper::getCurrentTenant();

        switch ($step) {
            case 'company':
                $validated = $request->validate([
                    'business_registration_number' => 'nullable|string',
                    'tax_identification_number' => 'nullable|string',
                    'address' => 'required|string',
                    'city' => 'required|string',
                    'state' => 'required|string',
                    'postal_code' => 'nullable|string',
                    'website' => 'nullable|url',
                    'logo' => 'nullable|image|max:2048',
                ]);

                if ($request->hasFile('logo')) {
                    $logoPath = $request->file('logo')->store('tenant-logos', 'public');
                    $validated['logo'] = $logoPath;
                }

                $tenant->update($validated);
                break;

            case 'preferences':
                $validated = $request->validate([
                    'currency' => 'required|string|size:3',
                    'timezone' => 'required|string',
                    'date_format' => 'required|string',
                    'fiscal_year_start' => 'required|date_format:m-d',
                    'invoice_prefix' => 'nullable|string|max:10',
                    'quote_prefix' => 'nullable|string|max:10',
                ]);

                $tenant->update([
                    'settings' => array_merge($tenant->settings ?? [], $validated)
                ]);
                break;

            case 'users':
                $validated = $request->validate([
                    'invite_users' => 'boolean',
                    'user_emails' => 'nullable|array',
                    'user_emails.*' => 'email',
                    'user_roles' => 'nullable|array',
                    'user_roles.*' => 'in:admin,manager,accountant,sales,employee',
                ]);

                // Store user invitations for later processing
                if ($validated['invite_users'] && !empty($validated['user_emails'])) {
                    $invitations = [];
                    foreach ($validated['user_emails'] as $index => $email) {
                        if ($email) {
                            $invitations[] = [
                                'email' => $email,
                                'role' => $validated['user_roles'][$index] ?? 'employee',
                            ];
                        }
                    }

                    $tenant->update([
                        'pending_invitations' => $invitations
                    ]);
                }
                break;
        }

        return response()->json(['success' => true]);
    }

    public function complete(Request $request)
    {
        $tenant = TenantHelper::getCurrentTenant();

        // Mark onboarding as completed
        $tenant->update([
            'onboarding_completed' => true,
            'onboarding_completed_at' => now(),
        ]);

        // Process any pending user invitations
        $this->processPendingInvitations($tenant);

        return redirect()
            ->route('tenant.dashboard', ['tenant' => $tenant->slug])
            ->with('success', 'Welcome to Ballie! Your account setup is complete.');
    }

    private function processPendingInvitations($tenant)
    {
        if (!empty($tenant->pending_invitations)) {
            // TODO: Send invitation emails to users
            // This would typically involve creating invitation tokens
            // and sending emails with registration links
        }
    }
}
