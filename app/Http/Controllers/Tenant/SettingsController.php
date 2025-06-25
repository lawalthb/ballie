<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('tenant.settings.index');
    }

    public function company()
    {
        return view('tenant.settings.company');
    }

    public function users()
    {
        return view('tenant.settings.users');
    }

    public function roles()
    {
        return view('tenant.settings.roles');
    }

    public function integrations()
    {
        return view('tenant.settings.integrations');
    }

    public function billing()
    {
        return view('tenant.settings.billing');
    }

    public function security()
    {
        return view('tenant.settings.security');
    }
}
