<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VoucherType;
use App\Models\Tenant;

class VoucherTypeSeeder extends Seeder
{
    public function run()
    {
        // This should be run for each tenant after they complete onboarding
        // For now, we'll create a method to seed for a specific tenant
    }

    public static function seedForTenant($tenantId)
    {
        $voucherTypes = [
            [
                'tenant_id' => $tenantId,
                'name' => 'Journal Voucher',
                'code' => 'JOURNAL',
                'abbreviation' => 'JV',
                'description' => 'For general journal entries and adjustments',
                'prefix' => 'JV-',
                'has_reference' => false,
                'affects_inventory' => false,
                'affects_cashbank' => false,
                'is_system_defined' => true,
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Payment Voucher',
                'code' => 'PAYMENT',
                'abbreviation' => 'PV',
                'description' => 'For recording payments made',
                'prefix' => 'PV-',
                'has_reference' => true,
                'affects_inventory' => false,
                'affects_cashbank' => true,
                'is_system_defined' => true,
                'default_accounts' => json_encode([
                    'credit_side' => ['cash', 'bank'], // These will be account group codes
                ])
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Receipt Voucher',
                'code' => 'RECEIPT',
                'abbreviation' => 'RV',
                'description' => 'For recording receipts received',
                'prefix' => 'RV-',
                'has_reference' => true,
                'affects_inventory' => false,
                'affects_cashbank' => true,
                'is_system_defined' => true,
                'default_accounts' => json_encode([
                    'debit_side' => ['cash', 'bank'],
                ])
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Contra Voucher',
                'code' => 'CONTRA',
                'abbreviation' => 'CV',
                'description' => 'For transfers between cash and bank accounts',
                'prefix' => 'CV-',
                'has_reference' => true,
                'affects_inventory' => false,
                'affects_cashbank' => true,
                'is_system_defined' => true,
                'default_accounts' => json_encode([
                    'both_sides' => ['cash', 'bank'],
                ])
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Sales Voucher',
                'code' => 'SALES',
                'abbreviation' => 'SV',
                'description' => 'For recording sales transactions',
                'prefix' => 'SV-',
                'has_reference' => true,
                'affects_inventory' => true,
                'affects_cashbank' => false,
                'is_system_defined' => true,
                'default_accounts' => json_encode([
                    'debit_side' => ['debtors', 'cash', 'bank'],
                    'credit_side' => ['sales'],
                ])
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Purchase Voucher',
                'code' => 'PURCHASE',
                'abbreviation' => 'PU',
                'description' => 'For recording purchase transactions',
                'prefix' => 'PU-',
                'has_reference' => true,
                'affects_inventory' => true,
                'affects_cashbank' => false,
                'is_system_defined' => true,
                'default_accounts' => json_encode([
                    'debit_side' => ['purchases'],
                    'credit_side' => ['creditors', 'cash', 'bank'],
                ])
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Credit Note',
                'code' => 'CREDIT_NOTE',
                'abbreviation' => 'CN',
                'description' => 'For sales returns and allowances',
                'prefix' => 'CN-',
                'has_reference' => true,
                'affects_inventory' => true,
                'affects_cashbank' => false,
                'is_system_defined' => true,
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'Debit Note',
                'code' => 'DEBIT_NOTE',
                'abbreviation' => 'DN',
                'description' => 'For purchase returns and claims',
                'prefix' => 'DN-',
                'has_reference' => true,
                'affects_inventory' => true,
                'affects_cashbank' => false,
                'is_system_defined' => true,
            ],
        ];

        foreach ($voucherTypes as $voucherType) {
            VoucherType::create($voucherType);
        }
    }
}