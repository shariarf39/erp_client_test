@extends('layouts.app')

@section('title', 'Create Voucher')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Create Journal Voucher</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Accounting</li>
                    <li class="breadcrumb-item"><a href="{{ route('accounting.vouchers.index') }}">Vouchers</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('accounting.vouchers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <form action="{{ route('accounting.vouchers.store') }}" method="POST" id="voucherForm">
        @csrf
        
        <div class="row">
            <div class="col-md-8">
                <!-- Header Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-receipt"></i> Voucher Header Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="voucher_no" class="form-label">Voucher Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('voucher_no') is-invalid @enderror" 
                                    id="voucher_no" name="voucher_no" value="{{ old('voucher_no', $voucherNo) }}" readonly>
                                @error('voucher_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="voucher_date" class="form-label">Voucher Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('voucher_date') is-invalid @enderror" 
                                    id="voucher_date" name="voucher_date" value="{{ old('voucher_date', date('Y-m-d')) }}" required>
                                @error('voucher_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="voucher_type" class="form-label">Voucher Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('voucher_type') is-invalid @enderror" 
                                    id="voucher_type" name="voucher_type" required>
                                    <option value="Journal" selected>Journal Voucher (JV)</option>
                                    <option value="Payment">Payment Voucher (PV)</option>
                                    <option value="Receipt">Receipt Voucher (RV)</option>
                                    <option value="Contra">Contra Voucher (CV)</option>
                                </select>
                                @error('voucher_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="narration" class="form-label">Narration</label>
                                <textarea class="form-control @error('narration') is-invalid @enderror" 
                                    id="narration" name="narration" rows="2" placeholder="Enter voucher description...">{{ old('narration') }}</textarea>
                                @error('narration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Voucher Entries -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-list-alt"></i> Voucher Entries</h5>
                            <div>
                                <button type="button" class="btn btn-sm btn-success me-2" onclick="addDebitRow()">
                                    <i class="fas fa-plus"></i> Add Debit
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" onclick="addCreditRow()">
                                    <i class="fas fa-plus"></i> Add Credit
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <!-- Debit Entries -->
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="table-success">
                                    <tr>
                                        <th width="50%" colspan="2">Debit Entries</th>
                                        <th width="30%">Amount</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="debitTableBody">
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            Click "Add Debit" to add debit entries
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Credit Entries -->
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered mb-0">
                                <thead class="table-danger">
                                    <tr>
                                        <th width="50%" colspan="2">Credit Entries</th>
                                        <th width="30%">Amount</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="creditTableBody">
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            Click "Add Credit" to add credit entries
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Panel -->
            <div class="col-md-4">
                <!-- Balance Check -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="mb-0"><i class="fas fa-balance-scale"></i> Voucher Balance</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-success"><strong>Total Debit:</strong></span>
                            <h5 class="text-success mb-0" id="totalDebitDisplay">৳0.00</h5>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-danger"><strong>Total Credit:</strong></span>
                            <h5 class="text-danger mb-0" id="totalCreditDisplay">৳0.00</h5>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><strong>Difference:</strong></span>
                            <h5 class="mb-0" id="differenceDisplay">৳0.00</h5>
                        </div>
                        <div class="alert mt-3 mb-0" id="balanceAlert" style="display: none;">
                            <i class="fas fa-info-circle"></i> <span id="balanceMessage"></span>
                        </div>
                        
                        <input type="hidden" name="total_debit" id="total_debit" value="0">
                        <input type="hidden" name="total_credit" id="total_credit" value="0">
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Quick Guide</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-check text-success"></i> 
                                <small>Debit and Credit must be equal</small>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success"></i> 
                                <small>Add multiple debit/credit entries</small>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success"></i> 
                                <small>Select only ledger accounts</small>
                            </li>
                            <li>
                                <i class="fas fa-check text-success"></i> 
                                <small>Add narration for clarity</small>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-info w-100 mb-2">
                            <i class="fas fa-save"></i> Create Voucher
                        </button>
                        <a href="{{ route('accounting.vouchers.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let debitRowCount = 0;
let creditRowCount = 0;
const accounts = @json($accounts);

function addDebitRow() {
    debitRowCount++;
    const tbody = document.getElementById('debitTableBody');
    
    // Remove "no entries" message if present
    if (tbody.children.length === 1 && tbody.children[0].cells.length === 1) {
        tbody.innerHTML = '';
    }
    
    const row = tbody.insertRow();
    row.innerHTML = `
        <td colspan="2">
            <select class="form-select form-select-sm" name="debits[${debitRowCount}][account_id]" required>
                <option value="">Select Account</option>
                ${accounts.map(account => `
                    <option value="${account.id}">
                        ${account.account_code} - ${account.account_name}
                    </option>
                `).join('')}
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm debit-amount" 
                name="debits[${debitRowCount}][amount]" 
                value="0" min="0" step="0.01" required onchange="calculateBalance()">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this, 'debit')">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
}

function addCreditRow() {
    creditRowCount++;
    const tbody = document.getElementById('creditTableBody');
    
    // Remove "no entries" message if present
    if (tbody.children.length === 1 && tbody.children[0].cells.length === 1) {
        tbody.innerHTML = '';
    }
    
    const row = tbody.insertRow();
    row.innerHTML = `
        <td colspan="2">
            <select class="form-select form-select-sm" name="credits[${creditRowCount}][account_id]" required>
                <option value="">Select Account</option>
                ${accounts.map(account => `
                    <option value="${account.id}">
                        ${account.account_code} - ${account.account_name}
                    </option>
                `).join('')}
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm credit-amount" 
                name="credits[${creditRowCount}][amount]" 
                value="0" min="0" step="0.01" required onchange="calculateBalance()">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this, 'credit')">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
}

function removeRow(button, type) {
    button.closest('tr').remove();
    
    const tbody = document.getElementById(type === 'debit' ? 'debitTableBody' : 'creditTableBody');
    if (tbody.children.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center text-muted py-3">
                    Click "Add ${type === 'debit' ? 'Debit' : 'Credit'}" to add ${type} entries
                </td>
            </tr>
        `;
    }
    
    calculateBalance();
}

function calculateBalance() {
    let totalDebit = 0;
    let totalCredit = 0;
    
    // Calculate total debit
    document.querySelectorAll('.debit-amount').forEach(input => {
        totalDebit += parseFloat(input.value) || 0;
    });
    
    // Calculate total credit
    document.querySelectorAll('.credit-amount').forEach(input => {
        totalCredit += parseFloat(input.value) || 0;
    });
    
    const difference = Math.abs(totalDebit - totalCredit);
    
    // Update display
    document.getElementById('totalDebitDisplay').textContent = '৳' + totalDebit.toFixed(2);
    document.getElementById('totalCreditDisplay').textContent = '৳' + totalCredit.toFixed(2);
    document.getElementById('differenceDisplay').textContent = '৳' + difference.toFixed(2);
    
    // Update hidden fields
    document.getElementById('total_debit').value = totalDebit.toFixed(2);
    document.getElementById('total_credit').value = totalCredit.toFixed(2);
    
    // Show balance status
    const balanceAlert = document.getElementById('balanceAlert');
    const balanceMessage = document.getElementById('balanceMessage');
    const differenceDisplay = document.getElementById('differenceDisplay');
    
    if (totalDebit === 0 && totalCredit === 0) {
        balanceAlert.style.display = 'none';
        differenceDisplay.className = 'mb-0';
    } else if (difference === 0) {
        balanceAlert.style.display = 'block';
        balanceAlert.className = 'alert alert-success mt-3 mb-0';
        balanceMessage.textContent = 'Voucher is balanced!';
        differenceDisplay.className = 'mb-0 text-success';
    } else {
        balanceAlert.style.display = 'block';
        balanceAlert.className = 'alert alert-danger mt-3 mb-0';
        balanceMessage.textContent = 'Voucher is not balanced. Difference: ৳' + difference.toFixed(2);
        differenceDisplay.className = 'mb-0 text-danger';
    }
}

// Form validation
document.getElementById('voucherForm').addEventListener('submit', function(e) {
    const debitTbody = document.getElementById('debitTableBody');
    const creditTbody = document.getElementById('creditTableBody');
    
    // Check if entries exist
    if (debitTbody.children.length === 0 || (debitTbody.children.length === 1 && debitTbody.children[0].cells.length === 1)) {
        e.preventDefault();
        alert('Please add at least one debit entry');
        return false;
    }
    
    if (creditTbody.children.length === 0 || (creditTbody.children.length === 1 && creditTbody.children[0].cells.length === 1)) {
        e.preventDefault();
        alert('Please add at least one credit entry');
        return false;
    }
    
    // Check if balanced
    const totalDebit = parseFloat(document.getElementById('total_debit').value) || 0;
    const totalCredit = parseFloat(document.getElementById('total_credit').value) || 0;
    
    if (Math.abs(totalDebit - totalCredit) > 0.01) {
        e.preventDefault();
        alert('Voucher must be balanced. Debit and Credit amounts must be equal.');
        return false;
    }
});
</script>
@endsection
