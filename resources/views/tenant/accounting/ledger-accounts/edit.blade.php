@extends('tenant.layouts.app')

@section('title', 'Edit Ledger Account')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Ledger Account</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('tenant.dashboard', $tenant) }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tenant.accounting.index', $tenant) }}">Accounting</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tenant.accounting.ledger-accounts.index', $tenant) }}">Ledger Accounts</a></li>
                    <li class="breadcrumb-item active">Edit {{ $ledgerAccount->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <a href="{{ route('tenant.accounting.ledger-accounts.show', [$tenant, $ledgerAccount]) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('tenant.accounting.ledger-accounts.index', $tenant) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <form action="{{ route('tenant.accounting.ledger-accounts.update', [$tenant, $ledgerAccount]) }}" method="POST" id="accountForm">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Account Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Account Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                           id="code" name="code" value="{{ old('code', $ledgerAccount->code) }}" required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Unique identifier for the account</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Account Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $ledgerAccount->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="account_type" class="form-label">Account Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('account_type') is-invalid @enderror"
                                            id="account_type" name="account_type" required>
                                        <option value="">Select Account Type</option>
                                        <option value="asset" {{ old('account_type', $ledgerAccount->account_type) === 'asset' ? 'selected' : '' }}>Asset</option>
                                        <option value="liability" {{ old('account_type', $ledgerAccount->account_type) === 'liability' ? 'selected' : '' }}>Liability</option>
                                        <option value="equity" {{ old('account_type', $ledgerAccount->account_type) === 'equity' ? 'selected' : '' }}>Equity</option>
                                        <option value="income" {{ old('account_type', $ledgerAccount->account_type) === 'income' ? 'selected' : '' }}>Income</option>
                                        <option value="expense" {{ old('account_type', $ledgerAccount->account_type) === 'expense' ? 'selected' : '' }}>Expense</option>
                                    </select>
                                    @error('account_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="account_group_id" class="form-label">Account Group <span class="text-danger">*</span></label>
                                    <select class="form-select @error('account_group_id') is-invalid @enderror"
                                            id="account_group_id" name="account_group_id" required>
                                        <option value="">Select Account Group</option>
                                        @foreach($accountGroups as $group)
                                            <option value="{{ $group->id }}"
                                                    data-nature="{{ $group->nature }}"
                                                    {{ old('account_group_id', $ledgerAccount->account_group_id) == $group->id ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('account_group_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="parent_id" class="form-label">Parent Account</label>
                                    <select class="form-select @error('parent_id') is-invalid @enderror"
                                            id="parent_id" name="parent_id">
                                        <option value="">No Parent (Main Account)</option>
                                        @foreach($parentAccounts as $parent)
                                            @if($parent->id !== $ledgerAccount->id)
                                                <option value="{{ $parent->id }}" {{ old('parent_id', $ledgerAccount->parent_id) == $parent->id ? 'selected' : '' }}>
                                                    {{ $parent->code }} - {{ $parent->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional: Select a parent account to create a sub-account</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="balance_type" class="form-label">Balance Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('balance_type') is-invalid @enderror"
                                            id="balance_type" name="balance_type" required>
                                        <option value="dr" {{ old('balance_type', $ledgerAccount->balance_type) === 'dr' ? 'selected' : '' }}>Debit (Dr)</option>
                                        <option value="cr" {{ old('balance_type', $ledgerAccount->balance_type) === 'cr' ? 'selected' : '' }}>Credit (Cr)</option>
                                    </select>
                                    @error('balance_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="opening_balance" class="form-label">Opening Balance</label>
                            <div class="input-group">
                                <span class="input-group-text">₦</span>
                                <input type="number" class="form-control @error('opening_balance') is-invalid @enderror"
                                       id="opening_balance" name="opening_balance"
                                       value="{{ old('opening_balance', $ledgerAccount->opening_balance) }}"
                                       step="0.01">
                                @error('opening_balance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                Current Balance: ₦{{ number_format($ledgerAccount->current_balance, 2) }}
                                {{ $ledgerAccount->current_balance >= 0 ? 'Dr' : 'Cr' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3">{{ old('description', $ledgerAccount->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Account Summary -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Account Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h5 class="text-success">₦{{ number_format($ledgerAccount->opening_balance, 2) }}</h5>
                                    <small class="text-muted">Opening Balance</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h5 class="{{ $ledgerAccount->current_balance >= 0 ? 'text-success' : 'text-danger' }}">
                                    ₦{{ number_format(abs($ledgerAccount->current_balance), 2) }}
                                    <small>{{ $ledgerAccount->current_balance >= 0 ? 'Dr' : 'Cr' }}</small>
                                </h5>
                                <small class="text-muted">Current Balance</small>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <small class="text-muted">
                                Created: {{ $ledgerAccount->created_at->format('M d, Y') }}<br>
                                Last Updated: {{ $ledgerAccount->updated_at->format('M d, Y') }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Contact Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3">{{ old('address', $ledgerAccount->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone', $ledgerAccount->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $ledgerAccount->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   value="1" {{ old('is_active', $ledgerAccount->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                            <div class="form-text">Uncheck to deactivate this account</div>
                        </div>

                        @if($ledgerAccount->hasChildren())
                            <div class="alert alert-warning mt-3">
                                <small><i class="fas fa-exclamation-triangle"></i> This account has sub-accounts. Deactivating it may affect related accounts.</small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Account
                            </button>
                            <a href="{{ route('tenant.accounting.ledger-accounts.show', [$tenant, $ledgerAccount]) }}"
                               class="btn btn-info">
                                <i class="fas fa-eye"></i> View Account
                            </a>
                            <a href="{{ route('tenant.accounting.ledger-accounts.index', $tenant) }}"
                               class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const accountTypeSelect = document.getElementById('account_type');
    const accountGroupSelect = document.getElementById('account_group_id');
    const balanceTypeSelect = document.getElementById('balance_type');

    // Auto-suggest balance type based on account type
    accountTypeSelect.addEventListener('change', function() {
        const accountType = this.value;
        let suggestedBalanceType = 'dr';

        switch(accountType) {
            case 'asset':
            case 'expense':
                suggestedBalanceType = 'dr';
                break;
            case 'liability':
            case 'equity':
            case 'income':
                suggestedBalanceType = 'cr';
                break;
        }

        balanceTypeSelect.value = suggestedBalanceType;
        filterAccountGroups(accountType);
    });

    // Filter account groups based on account type
    function filterAccountGroups(accountType) {
        const options = accountGroupSelect.querySelectorAll('option');

        options.forEach(option => {
            if (option.value === '') return;

            const nature = option.dataset.nature;
            let shouldShow = false;

            switch(accountType) {
                case 'asset':
                    shouldShow = nature === 'assets';
                    break;
                case 'liability':
                    shouldShow = nature === 'liabilities';
                    break;
                case 'equity':
                    shouldShow = nature === 'equity';
                    break;
                case 'income':
                    shouldShow = nature === 'income';
                    break;
                case 'expense':
                    shouldShow = nature === 'expenses';
                    break;
                default:
                    shouldShow = true;
            }

            option.style.display = shouldShow ? 'block' : 'none';
        });
    }

    // Initialize filter on page load
    if (accountTypeSelect.value) {
        filterAccountGroups(accountTypeSelect.value);
    }

    // Form validation
    document.getElementById('accountForm').addEventListener('submit', function(e) {
        let isValid = true;
        const requiredFields = ['code', 'name', 'account_type', 'account_group_id', 'balance_type'];

        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });

    // Remove validation errors on input
    document.querySelectorAll('.form-control, .form-select').forEach(field => {
        field.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
});
</script>
@endpush
