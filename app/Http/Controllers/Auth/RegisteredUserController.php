<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Plan;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $plans = Plan::where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('monthly_price')
                    ->get();

        return view('auth.register', compact('plans'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'business_name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', 'string', 'in:retail,service,restaurant,manufacturing,wholesale,other'],
            'phone' => ['nullable', 'string', 'max:20'],
            'plan' => ['nullable', 'string', 'exists:plans,slug'],
            'terms' => ['required', 'accepted'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'business_name' => $request->business_name,
            'business_type' => $request->business_type,
            'phone' => $request->phone,
            'onboarding_completed' => false,
            'onboarding_step' => 'business_info',
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect to onboarding instead of dashboard
        return redirect()->route('onboarding.index');
    }
}
