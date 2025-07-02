<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountGroup;

class AccountGroupSeeder extends Seeder
{
    public static function seedForTenant($tenantId)
    {
        // Check if account groups already exist for this tenant
        $existingGroups = AccountGroup::where('tenant_id', $tenantId)->count();
        if ($existingGroups > 0) {
            return; // Skip seeding if groups already exist
        }

        $accountGroups = [
            // Assets
            [
                'tenant_id' => $tenantId,
                'name' => 'Current Assets',
                'code' => 'CA',
                'nature' => 'assets',
                'parent_id' => null,
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Fixed Assets',
                'code' => 'FA',
                'nature' => 'assets',
                'parent_id' => null,
            ],

            // Liabilities
            [
                'tenant_id' => $tenantId,
                'name' => 'Current Liabilities',
                'code' => 'CL',
                'nature' => 'liabilities',
                'parent_id' => null,
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Long Term Liabilities',
                'code' => 'LTL',
                'nature' => 'liabilities',
                'parent_id' => null,
            ],

            // Income
            [
                'tenant_id' => $tenantId,
                'name' => 'Revenue',
                'code' => 'REV',
                'nature' => 'income',
                'parent_id' => null,
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Other Income',
                'code' => 'OI',
                'nature' => 'income',
                'parent_id' => null,
            ],

            // Expenses
            [
                'tenant_id' => $tenantId,
                'name' => 'Direct Expenses',
                'code' => 'DE',
                'nature' => 'expenses',
                'parent_id' => null,
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Indirect Expenses',
                'code' => 'IE',
                'nature' => 'expenses',
                'parent_id' => null,
            ],
        ];

        foreach ($accountGroups as $group) {
            AccountGroup::create($group);
        }

        // Create sub-groups
        $subGroups = [
            // Current Assets sub-groups
            [
                'tenant_id' => $tenantId,
                'name' => 'Cash & Bank',
                'code' => 'CB',
                'nature' => 'assets',
                'parent_id' => AccountGroup::where('tenant_id', $tenantId)->where('code', 'CA')->first()->id,
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Accounts Receivable',
                'code' => 'AR',
                'nature' => 'assets',
                'parent_id' => AccountGroup::where('tenant_id', $tenantId)->where('code', 'CA')->first()->id,
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Inventory',
                'code' => 'INV',
                'nature' => 'assets',
                'parent_id' => AccountGroup::where('tenant_id', $tenantId)->where('code', 'CA')->first()->id,
            ],

            // Fixed Assets sub-groups
            [
                'tenant_id' => $tenantId,
                'name' => 'Plant & Machinery',
                'code' => 'PM',
                'nature' => 'assets',
                'parent_id' => AccountGroup::where('tenant_id', $tenantId)->where('code', 'FA')->first()->id,
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Furniture & Fixtures',
                'code' => 'FF',
                'nature' => 'assets',
                'parent_id' => AccountGroup::where('tenant_id', $tenantId)->where('code', 'FA')->first()->id,
            ],

            // Current Liabilities sub-groups
            [
                'tenant_id' => $tenantId,
                'name' => 'Accounts Payable',
                'code' => 'AP',
                'nature' => 'liabilities',
                'parent_id' => AccountGroup::where('tenant_id', $tenantId)->where('code', 'CL')->first()->id,
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Accrued Expenses',
                'code' => 'AE',
                'nature' => 'liabilities',
                'parent_id' => AccountGroup::where('tenant_id', $tenantId)->where('code', 'CL')->first()->id,
            ],

            // Revenue sub-groups
            [
                'tenant_id' => $tenantId,
                'name' => 'Sales',
                'code' => 'SALES',
                'nature' => 'income',
                'parent_id' => AccountGroup::where('tenant_id', $tenantId)->where('code', 'REV')->first()->id,
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Service Income',
                'code' => 'SI',
                'nature' => 'income',
                'parent_id' => AccountGroup::where('tenant_id', $tenantId)->where('code', 'REV')->first()->id,
            ],

            // Direct Expenses sub-groups
            [
                'tenant_id' => $tenantId,
                'name' => 'Cost of Goods Sold',
                'code' => 'COGS',
                'nature' => 'expenses',
                'parent_id' => AccountGroup::where('tenant_id', $tenantId)->where('code', 'DE')->first()->id,
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Purchases',
                'code' => 'PUR',
                'nature' => 'expenses',
                'parent_id' => AccountGroup::where('tenant_id', $tenantId)->where('code', 'DE')->first()->id,
            ],

            // Indirect Expenses sub-groups
            [
                'tenant_id' => $tenantId,
                'name' => 'Administrative Expenses',
                'code' => 'ADMIN',
                'nature' => 'expenses',
                'parent_id' => AccountGroup::where('tenant_id', $tenantId)->where('code', 'IE')->first()->id,
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Selling Expenses',
                'code' => 'SELL',
                'nature' => 'expenses',
                'parent_id' => AccountGroup::where('tenant_id', $tenantId)->where('code', 'IE')->first()->id,
            ],
        ];

        foreach ($subGroups as $group) {
            AccountGroup::create($group);
        }
    }
}
