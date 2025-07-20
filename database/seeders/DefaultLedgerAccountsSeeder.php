<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LedgerAccount;
use App\Models\AccountGroup;

class DefaultLedgerAccountsSeeder extends Seeder
{
    public static function seedForTenant($tenantId)
    {
        // Check if ledger accounts already exist for this tenant
        $existingAccounts = LedgerAccount::where('tenant_id', $tenantId)->count();
        if ($existingAccounts > 0) {
            return; // Skip seeding if accounts already exist
        }

        // Get account groups for this tenant
        $accountGroups = AccountGroup::where('tenant_id', $tenantId)->get()->keyBy('name');

        $defaultAccounts = [
            // CURRENT ASSETS
            [
                'name' => 'Cash in Hand',
                'code' => 'CASH-001',
                'account_group_id' => $accountGroups->get('Current Assets')?->id,
                'account_type' => 'asset',
                'description' => 'Physical cash available in the business',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Bank Account - Current',
                'code' => 'BANK-001',
                'account_group_id' => $accountGroups->get('Current Assets')?->id,
                'account_type' => 'asset',
                'description' => 'Primary bank current account',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Petty Cash',
                'code' => 'PETTY-001',
                'account_group_id' => $accountGroups->get('Current Assets')?->id,
                'account_type' => 'asset',
                'description' => 'Small cash fund for minor expenses',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Accounts Receivable',
                'code' => 'AR-001',
                'account_group_id' => $accountGroups->get('Current Assets')?->id,
                'account_type' => 'asset',
                'description' => 'Money owed by customers',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Sales Ledger',
                'code' => 'SALES-LED-001',
                'account_group_id' => $accountGroups->get('Current Assets')?->id,
                'account_type' => 'asset',
                'description' => 'Customer receivables ledger',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Stock in Hand',
                'code' => 'STOCK-001',
                'account_group_id' => $accountGroups->get('Current Assets')?->id,
                'account_type' => 'asset',
                'description' => 'Inventory/Stock on hand',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'VAT Input',
                'code' => 'VAT-IN-001',
                'account_group_id' => $accountGroups->get('Current Assets')?->id,
                'account_type' => 'asset',
                'description' => 'VAT paid to suppliers',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],

            // CURRENT LIABILITIES
            [
                'name' => 'Accounts Payable',
                'code' => 'AP-001',
                'account_group_id' => $accountGroups->get('Current Liabilities')?->id,
                'account_type' => 'liability',
                'description' => 'Money owed to suppliers',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Purchase Ledger',
                'code' => 'PURCH-LED-001',
                'account_group_id' => $accountGroups->get('Current Liabilities')?->id,
                'account_type' => 'liability',
                'description' => 'Supplier payables ledger',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'VAT Output',
                'code' => 'VAT-OUT-001',
                'account_group_id' => $accountGroups->get('Current Liabilities')?->id,
                'account_type' => 'liability',
                'description' => 'VAT collected from customers',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'PAYE Tax Payable',
                'code' => 'PAYE-001',
                'account_group_id' => $accountGroups->get('Current Liabilities')?->id,
                'account_type' => 'liability',
                'description' => 'Pay As You Earn tax payable',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Withholding Tax Payable',
                'code' => 'WHT-001',
                'account_group_id' => $accountGroups->get('Current Liabilities')?->id,
                'account_type' => 'liability',
                'description' => 'Withholding tax payable to FIRS',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],

            // DIRECT INCOME
            [
                'name' => 'Sales Revenue',
                'code' => 'SALES-001',
                'account_group_id' => $accountGroups->get('Direct Income')?->id,
                'account_type' => 'income',
                'description' => 'Revenue from sales of goods',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Service Income',
                'code' => 'SERV-001',
                'account_group_id' => $accountGroups->get('Direct Income')?->id,
                'account_type' => 'income',
                'description' => 'Income from services provided',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Sales Returns',
                'code' => 'SALES-RET-001',
                'account_group_id' => $accountGroups->get('Direct Income')?->id,
                'account_type' => 'income',
                'description' => 'Returns and allowances on sales',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],

            // INDIRECT INCOME
            [
                'name' => 'Interest Income',
                'code' => 'INT-INC-001',
                'account_group_id' => $accountGroups->get('Indirect Income')?->id,
                'account_type' => 'income',
                'description' => 'Interest earned on bank deposits',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Discount Received',
                'code' => 'DISC-REC-001',
                'account_group_id' => $accountGroups->get('Indirect Income')?->id,
                'account_type' => 'income',
                'description' => 'Discounts received from suppliers',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Other Income',
                'code' => 'OTHER-INC-001',
                'account_group_id' => $accountGroups->get('Indirect Income')?->id,
                'account_type' => 'income',
                'description' => 'Miscellaneous income',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],

            // DIRECT EXPENSES
            [
                'name' => 'Purchases',
                'code' => 'PURCH-001',
                'account_group_id' => $accountGroups->get('Direct Expenses')?->id,
                'account_type' => 'expense',
                'description' => 'Purchases of goods for resale',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Cost of Goods Sold',
                'code' => 'COGS-001',
                'account_group_id' => $accountGroups->get('Direct Expenses')?->id,
                'account_type' => 'expense',
                'description' => 'Direct cost of goods sold',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Purchase Returns',
                'code' => 'PURCH-RET-001',
                'account_group_id' => $accountGroups->get('Direct Expenses')?->id,
                'account_type' => 'expense',
                'description' => 'Returns to suppliers',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],

            // INDIRECT EXPENSES
            [
                'name' => 'Office Rent',
                'code' => 'RENT-001',
                'account_group_id' => $accountGroups->get('Indirect Expenses')?->id,
                'account_type' => 'expense',
                'description' => 'Monthly office rent expense',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Salaries & Wages',
                'code' => 'SAL-001',
                'account_group_id' => $accountGroups->get('Indirect Expenses')?->id,
                'account_type' => 'expense',
                'description' => 'Employee salaries and wages',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Electricity Expense',
                'code' => 'ELEC-001',
                'account_group_id' => $accountGroups->get('Indirect Expenses')?->id,
                'account_type' => 'expense',
                'description' => 'Electricity and power expenses',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Telephone & Internet',
                'code' => 'TEL-001',
                'account_group_id' => $accountGroups->get('Indirect Expenses')?->id,
                'account_type' => 'expense',
                'description' => 'Communication expenses',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Bank Charges',
                'code' => 'BANK-CHG-001',
                'account_group_id' => $accountGroups->get('Indirect Expenses')?->id,
                'account_type' => 'expense',
                'description' => 'Bank fees and charges',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Office Supplies',
                'code' => 'SUPP-001',
                'account_group_id' => $accountGroups->get('Indirect Expenses')?->id,
                'account_type' => 'expense',
                'description' => 'Office supplies and stationery',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Transportation',
                'code' => 'TRANS-001',
                'account_group_id' => $accountGroups->get('Indirect Expenses')?->id,
                'account_type' => 'expense',
                'description' => 'Transportation and travel expenses',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Professional Fees',
                'code' => 'PROF-001',
                'account_group_id' => $accountGroups->get('Indirect Expenses')?->id,
                'account_type' => 'expense',
                'description' => 'Legal, accounting, and professional fees',
                'opening_balance' => 0,
                'current_balance' => 0,
                             'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Marketing & Advertising',
                'code' => 'MARK-001',
                'account_group_id' => $accountGroups->get('Indirect Expenses')?->id,
                'account_type' => 'expense',
                'description' => 'Marketing and advertising expenses',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Insurance',
                'code' => 'INS-001',
                'account_group_id' => $accountGroups->get('Indirect Expenses')?->id,
                'account_type' => 'expense',
                'description' => 'Insurance premiums',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Depreciation Expense',
                'code' => 'DEPR-001',
                'account_group_id' => $accountGroups->get('Indirect Expenses')?->id,
                'account_type' => 'expense',
                'description' => 'Depreciation on fixed assets',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Interest Expense',
                'code' => 'INT-EXP-001',
                'account_group_id' => $accountGroups->get('Indirect Expenses')?->id,
                'account_type' => 'expense',
                'description' => 'Interest paid on loans',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Miscellaneous Expense',
                'code' => 'MISC-001',
                'account_group_id' => $accountGroups->get('Indirect Expenses')?->id,
                'account_type' => 'expense',
                'description' => 'Other miscellaneous expenses',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],

            // CAPITAL ACCOUNTS (if you have Capital Account group)
            [
                'name' => 'Owner\'s Capital',
                'code' => 'CAPITAL-001',
                'account_group_id' => $accountGroups->get('Capital Account')?->id ?? $accountGroups->get('Current Liabilities')?->id,
                'account_type' => 'equity',
                'description' => 'Owner\'s capital investment',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Owner\'s Drawings',
                'code' => 'DRAW-001',
                'account_group_id' => $accountGroups->get('Capital Account')?->id ?? $accountGroups->get('Current Assets')?->id,
                'account_type' => 'equity',
                'description' => 'Owner\'s withdrawals',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Retained Earnings',
                'code' => 'RETAIN-001',
                'account_group_id' => $accountGroups->get('Capital Account')?->id ?? $accountGroups->get('Current Liabilities')?->id,
                'account_type' => 'equity',
                'description' => 'Accumulated retained earnings',
                'opening_balance' => 0,
                'current_balance' => 0,
                'is_system_account' => true,
                'is_active' => true,
            ],
        ];

        // Create all default ledger accounts
        foreach ($defaultAccounts as $accountData) {
            // Skip if account group doesn't exist
            if (!$accountData['account_group_id']) {
                continue;
            }

            $accountData['tenant_id'] = $tenantId;
            $accountData['created_at'] = now();
            $accountData['updated_at'] = now();

            LedgerAccount::create($accountData);
        }
    }

    public function run()
    {
        // This method can be used for standalone seeding if needed
        $tenantId = $this->command->option('tenant-id');

        if ($tenantId) {
            self::seedForTenant($tenantId);
            $this->command->info("Default ledger accounts seeded for tenant ID: {$tenantId}");
        } else {
            $this->command->error('Please provide --tenant-id option');
        }
    }
}
