@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Edit Voucher</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('accounting.vouchers.index') }}">Vouchers</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('accounting.vouchers.show', $voucher) }}">{{ $voucher->voucher_no }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="btn-group">
            <a href="{{ route('accounting.vouchers.show', $voucher) }}" class="btn btn-info">
                <i class="bi bi-eye"></i> View Details
            </a>
            <a href="{{ route('accounting.vouchers.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Please fix the following issues:
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('accounting.vouchers.update', $voucher) }}" method="POST" id="voucherForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-8">
                <!-- Voucher Details -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-file-text"></i> Voucher Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="voucher_no" class="form-label">Voucher No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('voucher_no') is-invalid @enderror" 
                                    id="voucher_no" name="voucher_no" value="{{ old('voucher_no', $voucher->voucher_no) }}" required>
                                @error('voucher_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="voucher_type" class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('voucher_type') is-invalid @enderror" id="voucher_type" name="voucher_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Journal" {{ old('voucher_type', $voucher->voucher_type) == 'Journal' ? 'selected' : '' }}>Journal Entry</option>
                                    <option value="Payment" {{ old('voucher_type', $voucher->voucher_type) == 'Payment' ? 'selected' : '' }}>Payment Voucher</option>
                                    <option value="Receipt" {{ old('voucher_type', $voucher->voucher_type) == 'Receipt' ? 'selected' : '' }}>Receipt Voucher</option>
                                    <option value="Contra" {{ old('voucher_type', $voucher->voucher_type) == 'Contra' ? 'selected' : '' }}>Contra Entry</option>
                                </select>
                                @error('voucher_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                    id="date" name="date" value="{{ old('date', $voucher->date->format('Y-m-d')) }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="reference" class="form-label">Reference</label>
                                <input type="text" class="form-control @error('reference') is-invalid @enderror" 
                                    id="reference" name="reference" value="{{ old('reference', $voucher->reference) }}" maxlength="100">
                                @error('reference')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" name="description" rows="2">{{ old('description', $voucher->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Debit Entries -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-arrow-up-circle"></i> Debit Entries</h5>
                        <button type="button" class="btn btn-sm btn-light" onclick="addDebitEntry()">
                            <i class="bi bi-plus"></i> Add Entry
                        </button>
                    </div>
                    <div class="card-body" id="debitEntriesContainer">
                        @php
                            $debitEntries = $voucher->voucherDetails->where('debit', '>', 0);
                        @endphp
                        @foreach($debitEntries as $index => $entry)
                            <div class="debit-entry mb-3 p-3 border rounded">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label class="form-label">Account <span class="text-danger">*</span></label>
                                        <select class="form-select" name="debit_entries[{{ $index }}][account_id]" required>
                                            <option value="">Select Account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}" {{ $entry->account_id == $account->id ? 'selected' : '' }}>
                                                    {{ $account->account_code }} - {{ $account->account_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control debit-amount" name="debit_entries[{{ $index }}][amount]" 
                                            value="{{ $entry->debit }}" step="0.01" min="0.01" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Description</label>
                                        <input type="text" class="form-control" name="debit_entries[{{ $index }}][description]" 
                                            value="{{ $entry->description }}">
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeEntry(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <strong>Total Debit: ৳<span id="totalDebit">0.00</span></strong>
                    </div>
                </div>

                <!-- Credit Entries -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-arrow-down-circle"></i> Credit Entries</h5>
                        <button type="button" class="btn btn-sm btn-light" onclick="addCreditEntry()">
                            <i class="bi bi-plus"></i> Add Entry
                        </button>
                    </div>
                    <div class="card-body" id="creditEntriesContainer">
                        @php
                            $creditEntries = $voucher->voucherDetails->where('credit', '>', 0);
                        @endphp
                        @foreach($creditEntries as $index => $entry)
                            <div class="credit-entry mb-3 p-3 border rounded">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label class="form-label">Account <span class="text-danger">*</span></label>
                                        <select class="form-select" name="credit_entries[{{ $index }}][account_id]" required>
                                            <option value="">Select Account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}" {{ $entry->account_id == $account->id ? 'selected' : '' }}>
                                                    {{ $account->account_code }} - {{ $account->account_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control credit-amount" name="credit_entries[{{ $index }}][amount]" 
                                            value="{{ $entry->credit }}" step="0.01" min="0.01" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Description</label>
                                        <input type="text" class="form-control" name="credit_entries[{{ $index }}][description]" 
                                            value="{{ $entry->description }}">
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeEntry(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <strong>Total Credit: ৳<span id="totalCredit">0.00</span></strong>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Summary -->
                <div class="card shadow-sm mb-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-calculator"></i> Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>Total Debit:</strong>
                                <span class="text-danger">৳<span id="summaryDebit">0.00</span></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>Total Credit:</strong>
                                <span class="text-success">৳<span id="summaryCredit">0.00</span></span>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>Difference:</strong>
                                <span id="difference" class="fw-bold">৳0.00</span>
                            </div>
                        </div>
                        <div id="balanceStatus" class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Add entries to check balance
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                <i class="bi bi-check-circle"></i> Update Voucher
                            </button>
                            <a href="{{ route('accounting.vouchers.show', $voucher) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let debitIndex = {{ $debitEntries->count() }};
let creditIndex = {{ $creditEntries->count() }};

function addDebitEntry() {
    const container = document.getElementById('debitEntriesContainer');
    const entry = document.createElement('div');
    entry.className = 'debit-entry mb-3 p-3 border rounded';
    entry.innerHTML = `
        <div class="row">
            <div class="col-md-5">
                <label class="form-label">Account <span class="text-danger">*</span></label>
                <select class="form-select" name="debit_entries[${debitIndex}][account_id]" required>
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->account_code }} - {{ $account->account_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control debit-amount" name="debit_entries[${debitIndex}][amount]" 
                    step="0.01" min="0.01" required onchange="calculateTotals()">
            </div>
            <div class="col-md-3">
                <label class="form-label">Description</label>
                <input type="text" class="form-control" name="debit_entries[${debitIndex}][description]">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeEntry(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(entry);
    debitIndex++;
}

function addCreditEntry() {
    const container = document.getElementById('creditEntriesContainer');
    const entry = document.createElement('div');
    entry.className = 'credit-entry mb-3 p-3 border rounded';
    entry.innerHTML = `
        <div class="row">
            <div class="col-md-5">
                <label class="form-label">Account <span class="text-danger">*</span></label>
                <select class="form-select" name="credit_entries[${creditIndex}][account_id]" required>
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->account_code }} - {{ $account->account_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control credit-amount" name="credit_entries[${creditIndex}][amount]" 
                    step="0.01" min="0.01" required onchange="calculateTotals()">
            </div>
            <div class="col-md-3">
                <label class="form-label">Description</label>
                <input type="text" class="form-control" name="credit_entries[${creditIndex}][description]">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeEntry(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(entry);
    creditIndex++;
}

function removeEntry(button) {
    button.closest('.debit-entry, .credit-entry').remove();
    calculateTotals();
}

function calculateTotals() {
    let totalDebit = 0;
    let totalCredit = 0;
    
    document.querySelectorAll('.debit-amount').forEach(input => {
        totalDebit += parseFloat(input.value) || 0;
    });
    
    document.querySelectorAll('.credit-amount').forEach(input => {
        totalCredit += parseFloat(input.value) || 0;
    });
    
    document.getElementById('totalDebit').textContent = totalDebit.toFixed(2);
    document.getElementById('totalCredit').textContent = totalCredit.toFixed(2);
    document.getElementById('summaryDebit').textContent = totalDebit.toFixed(2);
    document.getElementById('summaryCredit').textContent = totalCredit.toFixed(2);
    
    const difference = Math.abs(totalDebit - totalCredit);
    document.getElementById('difference').textContent = '৳' + difference.toFixed(2);
    
    const statusDiv = document.getElementById('balanceStatus');
    const submitBtn = document.getElementById('submitBtn');
    
    if (totalDebit === 0 && totalCredit === 0) {
        statusDiv.className = 'alert alert-info';
        statusDiv.innerHTML = '<i class="bi bi-info-circle"></i> Add entries to check balance';
        submitBtn.disabled = true;
    } else if (difference < 0.01) {
        statusDiv.className = 'alert alert-success';
        statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Voucher is balanced!';
        submitBtn.disabled = false;
    } else {
        statusDiv.className = 'alert alert-danger';
        statusDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Voucher is not balanced!';
        submitBtn.disabled = true;
    }
}

// Attach event listeners to existing inputs
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.debit-amount, .credit-amount').forEach(input => {
        input.addEventListener('change', calculateTotals);
        input.addEventListener('input', calculateTotals);
    });
    
    // Initial calculation
    calculateTotals();
});
</script>
@endsection
