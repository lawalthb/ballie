<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test tenant
        $tenant = Tenant::create([
            'name' => 'Yardley Beard Company',
            'slug' => 'yardley-beard',
            'domain' => 'yardley-beard.ballie.test',
            'database' => 'tenant_yardley_beard',
            'status' => 'active',
            'business_type' => 'Limited Liability Company',
            'industry' => 'Technology',
            'country' => 'Nigeria',
            'state' => 'Lagos',
            'city' => 'Lagos',
            'settings' => [
                'currency' => 'NGN',
                'timezone' => 'Africa/Lagos',
                'date_format' => 'd/m/Y',
                'time_format' => '12',
            ],
            'onboarding_progress' => [],
            'onboarding_completed_at' => null,
        ]);

        // Create a test user for this tenant
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@yardley-beard.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Attach user to tenant
        $user->tenants()->attach($tenant->id, [
            'role' => User::ROLE_OWNER,
            'is_active' => true,
            'joined_at' => now(),
        ]);

        $this->command->info('Test tenant created: ' . $tenant->name);
        $this->command->info('Test user created: ' . $user->email . ' (password: password)');
    }
}