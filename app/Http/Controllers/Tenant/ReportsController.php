<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\LedgerAccount;
use App\Models\AccountGroup;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function trialBalance(Request $request)
    {
        $asOfDate = $request->as_of_date ? Carbon::parse($request->as_of_date) : Carbon::now();

        $ledgerAccounts = LedgerAccount::where('tenant_id', tenant()->id)
            ->with('accountGroup')
            ->active()
            ->get();

        $trialBalanceData = [];
        $totalDebits = 0;
        $totalCredits = 0;

        foreach ($ledgerAccounts as $account) {
            $balance = $account->getCurrentBalance($asOfDate);

            if (abs($balance) > 0.01) { // Only show accounts with balance
                $balanceInfo = $account->getFormattedBalance($asOfDate);

                $debitAmount = $balanceInfo['type'] === 'dr' ? $balanceInfo['amount'] : 0;
                $creditAmount = $balanceInfo['type'] === 'cr' ? $balanceInfo['amount'] : 0;

                $trialBalanceData[] = [
                    'account' => $account,
                    'debit_amount' => $debitAmount,
                    'credit_amount' => $creditAmount,
                ];

                $totalDebits += $debitAmount;
                $totalCredits += $creditAmount;
            }
        }

        return view('tenant.reports.trial-balance', compact(
            'trialBalanceData',
            'totalDebits',
            'totalCredits',
            'asOfDate'
        ));
    }

    public function dayBook(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::now();

        $vouchers = \App\Models\Voucher::where('tenant_id', tenant()->id)
            ->where('voucher_date', $date)
            ->where('status', 'posted')
            ->with(['voucherType', 'entries.ledgerAccount'])
            ->orderBy('voucher_number')
            ->get();

        return view('tenant.reports.day-book', compact('vouchers', 'date'));
    }

    public function profitLoss(Request $request)
    {
        $fromDate = $request->from_date ? Carbon::parse($request->from_date) : Carbon::now()->startOfYear();
        $toDate = $request->to_date ? Carbon::parse($request->to_date) : Carbon::now();

        // Get Income accounts
        $incomeGroups = AccountGroup::where('tenant_id', tenant()->id)
            ->where('nature', 'income')
            ->with(['ledgerAccounts' => function($query) {
                $query->active();
            }])
            ->get();

        // Get Expense accounts
        $expenseGroups = AccountGroup::where('tenant_id', tenant()->id)
            ->where('nature', 'expenses')
            ->with(['ledgerAccounts' => function($query) {
                $query->active();
            }])
            ->get();

        $incomeData = [];
        $expenseData = [];
        $totalIncome = 0;
        $totalExpenses = 0;

        // Process income accounts
        foreach ($incomeGroups as $group) {
            $groupTotal = 0;
            $accounts = [];

            foreach ($group->ledgerAccounts as $account) {
                $balance = abs($account->getCurrentBalance($toDate));
                if ($balance > 0.01) {
                    $accounts[] = [
                        'account' => $account,
                        'amount' => $balance,
                    ];
                    $groupTotal += $balance;
                }
            }

            if ($groupTotal > 0) {
                $incomeData[] = [
                    'group' => $group,
                    'accounts' => $accounts,
                    'total' => $groupTotal,
                ];
                $totalIncome += $groupTotal;
            }
        }

        // Process expense accounts
        foreach ($expenseGroups as $group) {
            $groupTotal = 0;
            $accounts = [];

            foreach ($group->ledgerAccounts as $account) {
                $balance = abs($account->getCurrentBalance($toDate));
                if ($balance > 0.01) {
                    $accounts[] = [
                        'account' => $account,
                        'amount' => $balance,
                    ];
                    $groupTotal += $balance;
                }
            }

            if ($groupTotal > 0) {
                $expenseData[] = [
                    'group' => $group,
                    'accounts' => $accounts,
                    'total' => $groupTotal,
                ];
                $totalExpenses += $groupTotal;
            }
        }

        $netProfit = $totalIncome - $totalExpenses;

        return view('tenant.reports.profit-loss', compact(
            'incomeData',
            'expenseData',
            'totalIncome',
            'totalExpenses',
            'netProfit',
            'fromDate',
            'toDate'
        ));
    }

    public function balanceSheet(Request $request)
    {
        $asOfDate = $request->as_of_date ? Carbon::parse($request->as_of_date) : Carbon::now();

        // Get Assets
        $assetGroups = AccountGroup::where('tenant_id', tenant()->id)
            ->where('nature', 'assets')
            ->with(['ledgerAccounts' => function($query) {
                $query->active();
            }])
            ->get();

        // Get Liabilities
        $liabilityGroups = AccountGroup::where('tenant_id', tenant()->id)
            ->where('nature', 'liabilities')
            ->with(['ledgerAccounts' => function($query) {
                $query->active();
            }])
            ->get();

        $assetData = [];
        $liabilityData = [];
        $totalAssets = 0;
        $totalLiabilities = 0;

        // Process assets
        foreach ($assetGroups as $group) {
            $groupTotal = 0;
            $accounts = [];

            foreach ($group->ledgerAccounts as $account) {
                $balance = abs($account->getCurrentBalance($asOfDate));
                if ($balance > 0.01) {
                    $accounts[] = [
                        'account' => $account,
                        'amount' => $balance,
                    ];
                    $groupTotal += $balance;
                }
            }

            if ($groupTotal > 0) {
                $assetData[] = [
                    'group' => $group,
                    'accounts' => $accounts,
                    'total' => $groupTotal,
                ];
                $totalAssets += $groupTotal;
            }
        }

        // Process liabilities
        foreach ($liabilityGroups as $group) {
            $groupTotal = 0;
            $accounts = [];

            foreach ($group->ledgerAccounts as $account) {
                $balance = abs($account->getCurrentBalance($asOfDate));
                if ($balance > 0.01) {
                    $accounts[] = [
                        'account' => $account,
                        'amount' => $balance,
                    ];
                    $groupTotal += $balance;
                }
            }

            if ($groupTotal > 0) {
                $liabilityData[] = [
                    'group' => $group,
                    'accounts' => $accounts,
                    'total' => $groupTotal,
                ];
                $totalLiabilities += $groupTotal;
            }
        }

        // Calculate net profit from P&L
        $netProfit = $this->calculateNetProfit($asOfDate);
        $totalLiabilities += $netProfit; // Add net profit to liabilities side

        return view('tenant.reports.balance-sheet', compact(
            'assetData',
            'liabilityData',
            'totalAssets',
            'totalLiabilities',
            'netProfit',
            'asOfDate'
        ));
    }

    private function calculateNetProfit($asOfDate)
    {
        $incomeAccounts = LedgerAccount::where('tenant_id', tenant()->id)
            ->whereHas('accountGroup', function($query) {
                $query->where('nature', 'income');
            })
            ->active()
            ->get();

        $expenseAccounts = LedgerAccount::where('tenant_id', tenant()->id)
            ->whereHas('accountGroup', function($query) {
                $query->where('nature', 'expenses');
            })
            ->active()
            ->get();

        $totalIncome = $incomeAccounts->sum(function($account) use ($asOfDate) {
            return abs($account->getCurrentBalance($asOfDate));
        });

        $totalExpenses = $expenseAccounts->sum(function($account) use ($asOfDate) {
            return abs($account->getCurrentBalance($asOfDate));
        });

        return $totalIncome - $totalExpenses;
    }
}
