@extends('tenant.layouts.app')

@section('title', $ledgerAccount->name . ' - Ledger Account')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ $ledgerAccount->name }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('tenant.dashboard', $tenant) }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tenant.accounting.index', $tenant) }}">Accounting</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tenant.accounting.ledger-accounts.index', $tenant) }}">Ledger Accounts</a></li>
                    <li class="breadcrumb-item active">{{ $ledgerAccount->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <a href="{{ route('tenant.accounting.ledger-accounts.edit', [$tenant, $ledgerAccount]) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('tenant.accounting.vouchers.create', [$tenant, 'account_id' => $ledgerAccount->id]) }}">
                    <i class="fas fa-plus"></i> New Transaction
                </a></li>
                <li><a class="dropdown-item" href="#" onclick="printLedger()">
                    <i class="fas fa-print"></i> Print Ledger
                </a></li>
                <li><a class="dropdown-item" href="{{ route('tenant.accounting.ledger-accounts.export-ledger', [$tenant, $ledgerAccount]) }}">
                    <i class="fas fa-download"></i> Export Ledger
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="#" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i> Delete Account
                </a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <!-- Account Information -->
        <div class="col-lg-8">
            <!-- Account Details Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Account Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Account Code:</td>
                                    <td><code>{{ $ledgerAccount->code }}</code></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Account Name:</td>
                                    <td>{{ $ledgerAccount->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Account Type:</td>
                                    <td>
                                        <span class="badge bg-{{ $ledgerAccount->account_type === 'asset' ? 'success' : ($ledgerAccount->account_type === 'liability' ? 'danger' : ($ledgerAccount->account_type === 'equity' ? 'warning' : ($ledgerAccount->account_type === 'income' ? 'info' : 'secondary'))) }}">
                                            {{ ucfirst($ledgerAccount->account_type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Account Group:</td>
                                    <td>{{ $ledgerAccount->accountGroup->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Balance Type:</td>
                                    <td>{{ $ledgerAccount->balance_type === 'dr' ? 'Debit (Dr)' : 'Credit (Cr)' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Parent Account:</td>
                                    <td>
                                        @if($ledgerAccount->parent)
                                            <a href="{{ route('tenant.accounting.ledger-accounts.show', [$tenant, $ledgerAccount->parent]) }}">
                                                {{ $ledgerAccount->parent->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">None (Main Account)</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status:</td>
                                    <td>
                                        @if($ledgerAccount->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Created:</td>
                                    <td>{{ $ledgerAccount->created_at->format('M d, Y g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Last Updated:</td>
                                    <td>{{ $ledgerAccount->updated_at->format('M d, Y g:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($ledgerAccount->description)
                        <div class="mt-3">
                            <h6 class="fw-bold">Description:</h6>
                            <p class="text-muted">{{ $ledgerAccount->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Transactions</h6>
                    <a href="{{ route('tenant.accounting.vouchers.index', [$tenant, 'account_id' => $ledgerAccount->id]) }}"
                       class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recentTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Voucher</th>
                                        <th>Description</th>
                                        <th class="text-end">Debit</th>
                                        <th class="text-end">Credit</th>
                                        <th class="text-end">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $runningBalance = $ledgerAccount->opening_balance; @endphp
                                    @foreach($recentTransactions as $transaction)
                                        @php
                                            $runningBalance += ($transaction->debit_amount - $transaction->credit_amount);
                                        @endphp
                                        <tr>
                                            <td>{{ $transaction->voucher->voucher_date->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('tenant.accounting.vouchers.show', [$tenant, $transaction->voucher]) }}">
                                                    {{ $transaction->voucher->voucher_number }}
                                                </a>
                                            </td>
                                            <td>{{ Str::limit($transaction->description, 50) }}</td>
                                            <td class="text-end">
                                                @if($transaction->debit_amount > 0)
                                                    ₦{{ number_format($transaction->debit_amount, 2) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($transaction->credit_amount > 0)
                                                    ₦{{ number_format($transaction->credit_amount, 2) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-end {{ $runningBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                                ₦{{ number_format(abs($runningBalance), 2) }}
                                                <small>{{ $runningBalance >= 0 ? 'Dr' : 'Cr' }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No transactions found for this account.</p>
                            <a href="{{ route('tenant.accounting.vouchers.create', [$tenant, 'account_id' => $ledgerAccount->id]) }}"
                               class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add First Transaction
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sub Accounts -->
            @if($ledgerAccount->children->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Sub Accounts</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th class="text-end">Balance</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ledgerAccount->children as $child)
                                        <tr>
                                            <td><code>{{ $child->code }}</code></td>
                                            <td>{{ $child->name }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($child->account_type) }}</span>
                                            </td>
                                            <td class="text-end">
                                                @php $childBalance = $child->getCurrentBalance(); @endphp
                                                <span class="{{ $childBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                                    ₦{{ number_format(abs($childBalance), 2) }}
                                                    <small>{{ $childBalance >= 0 ? 'Dr' : 'Cr' }}</small>
                                                </span>
                                            </td>
                                            <td>
                                                @if($child->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('tenant.accounting.ledger-accounts.show', [$tenant, $child]) }}"
                                                       class="btn btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('tenant.accounting.ledger-accounts.edit', [$tenant, $child]) }}"
                                                       class="btn btn-outline-secondary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Balance Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Balance Summary</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="row">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-info">₦{{ number_format($ledgerAccount->opening_balance, 2) }}</h4>
                                    <small class="text-muted">Opening Balance</small>
                                </div>
                            </div>
                            <div class="col-6">
                                @php $currentBalance = $ledgerAccount->getCurrentBalance(); @endphp
                                <h4 class="{{ $currentBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                    ₦{{ number_format(abs($currentBalance), 2) }}
                                    <small>{{ $currentBalance >= 0 ? 'Dr' : 'Cr' }}</small>
                                </h4>
                                <small class="text-muted">Current Balance</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-success">₦{{ number_format($totalDebits, 2) }}</h6>
                            <small class="text-muted">Total Debits</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-danger">₦{{ number_format($totalCredits, 2) }}</h6>
                            <small class="text-muted">Total Credits</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            @if($ledgerAccount->address || $ledgerAccount->phone || $ledgerAccount->email)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Contact Information</h6>
                    </div>
                    <div class="card-body">
                        @if($ledgerAccount->address)
                            <div class="mb-3">
                                <strong>Address:</strong><br>
                                <span class="text-muted">{{ $ledgerAccount->address }}</span>
                            </div>
                        @endif

                        @if($ledgerAccount->phone)
                            <div class="mb-3">
                                <strong>Phone:</strong><br>
                                <a href="tel:{{ $ledgerAccount->phone }}">{{ $ledgerAccount->phone }}</a>
                            </div>
                        @endif

                        @if($ledgerAccount->email)
                            <div class="mb-3">
                                <strong>Email:</strong><br>
                                <a href="mailto:{{ $ledgerAccount->email }}">{{ $ledgerAccount->email }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('tenant.accounting.vouchers.create', [$tenant, 'account_id' => $ledgerAccount->id]) }}"
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> New Transaction
                        </a>
                        <a href="{{ route('tenant.accounting.ledger-accounts.create', [$tenant, 'parent_id' => $ledgerAccount->id]) }}"
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-sitemap"></i> Add Sub Account
                        </a>
                        <a href="{{ route('tenant.accounting.reports.ledger', [$tenant, 'account_id' => $ledgerAccount->id]) }}"
                           class="btn btn-outline-info btn-sm">
                            <i class="fas fa-chart-line"></i> View Ledger Report
                        </a>
                        <a href="{{ route('tenant.accounting.reports.trial-balance', $tenant) }}"
                           class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-balance-scale"></i> Trial Balance
                        </a>
                    </div>
                </div>
            </div>

            <!-- Account Statistics -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h6>{{ $transactionCount }}</h6>
                            <small class="text-muted">Total Transactions</small>
                        </div>
                        <div class="col-6">
                            <h6>{{ $ledgerAccount->children->count() }}</h6>
                            <small class="text-muted">Sub Accounts</small>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <small class="text-muted">
                            Last Transaction:
                            @if($lastTransaction)
                                {{ $lastTransaction->created_at->diffForHumans() }}
                            @else
                                Never
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the account <strong>"{{ $ledgerAccount->name }}"</strong>?</p>
                @if($ledgerAccount->children->count() > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        This account has {{ $ledgerAccount->children->count() }} sub-account(s). Deleting this account will also delete all sub-accounts.
                    </div>
                @endif
                @if($transactionCount > 0)
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        This account has {{ $transactionCount }} transaction(s). Deleting this account will affect your financial records.
                    </div>
                @endif
                <p class="text-danger"><strong>This action cannot be undone.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('tenant.accounting.ledger-accounts.destroy', [$tenant, $ledgerAccount]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Account</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function printLedger() {
    window.open('{{ route("tenant.accounting.ledger-accounts.print-ledger", [$tenant, $ledgerAccount]) }}', '_blank');
}

// Auto-refresh balance every 30 seconds
setInterval(function() {
    fetch('{{ route("tenant.accounting.ledger-accounts.balance", [$tenant, $ledgerAccount]) }}')
        .then(response => response.json())
        .then(data => {
            // Update balance display
            const balanceElements = document.querySelectorAll('.current-balance');
            balanceElements.forEach(element => {
                element.textContent = '₦' + data.balance.toLocaleString('en-US', {minimumFractionDigits: 2});
                element.className = data.balance >= 0 ? 'text-success' : 'text-danger';
            });
        })
        .catch(error => console.log('Balance refresh failed:', error));
}, 30000);
</script>
@endpush
