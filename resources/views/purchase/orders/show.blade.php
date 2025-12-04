@extends('layouts.app')

@section('title', 'Purchase Order Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-cart-check me-2"></i>Purchase Order Details
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('purchase.orders.index') }}">Purchase Orders</a></li>
                    <li class="breadcrumb-item active">{{ $order->po_no }}</li>
                </ol>
            </nav>
        </div>
        <div>
            @if($order->status == 'Draft')
                <a href="{{ route('purchase.orders.edit', $order) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-2"></i>Edit
                </a>
            @endif
            <a href="{{ route('purchase.orders.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
            <button onclick="window.print()" class="btn btn-info">
                <i class="bi bi-printer me-2"></i>Print
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- PO Header -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>{{ $order->po_no }}
                        </h5>
                        @if($order->status == 'Draft')
                            <span class="badge bg-secondary">Draft</span>
                        @elseif($order->status == 'Approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($order->status == 'Pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($order->status == 'Completed')
                            <span class="badge bg-info">Completed</span>
                        @else
                            <span class="badge bg-danger">{{ $order->status }}</span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-building me-2"></i>Vendor Information
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Vendor:</td>
                                    <td>
                                        <strong>{{ $order->vendor->vendor_name }}</strong>
                                        @if($order->vendor->company_name)
                                            <br><small class="text-muted">{{ $order->vendor->company_name }}</small>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Contact:</td>
                                    <td>{{ $order->vendor->contact_person ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Phone:</td>
                                    <td>
                                        @if($order->vendor->phone)
                                            <i class="bi bi-telephone me-1"></i>{{ $order->vendor->phone }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email:</td>
                                    <td>
                                        @if($order->vendor->email)
                                            <a href="mailto:{{ $order->vendor->email }}">{{ $order->vendor->email }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-calendar me-2"></i>Order Information
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">PO Date:</td>
                                    <td><strong>{{ $order->date->format('d M Y') }}</strong></td>
                                </tr>
                                @if($order->delivery_date)
                                    <tr>
                                        <td class="text-muted">Delivery Date:</td>
                                        <td>{{ $order->delivery_date->format('d M Y') }}</td>
                                    </tr>
                                @endif
                                @if($order->purchaseRequisition)
                                    <tr>
                                        <td class="text-muted">PR Reference:</td>
                                        <td>
                                            <a href="{{ route('purchase.requisitions.show', $order->purchaseRequisition) }}">
                                                {{ $order->purchaseRequisition->pr_number }}
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="text-muted">Created By:</td>
                                    <td>{{ $order->creator->name ?? 'N/A' }}</td>
                                </tr>
                                @if($order->approved_by)
                                    <tr>
                                        <td class="text-muted">Approved By:</td>
                                        <td>
                                            {{ $order->approver->name ?? 'N/A' }}
                                            <br><small class="text-muted">{{ $order->approved_at?->format('d M Y, h:i A') }}</small>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>

                        @if($order->payment_terms)
                            <div class="col-12">
                                <hr>
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-credit-card me-2"></i>Payment Terms
                                </h6>
                                <div class="p-3 bg-light rounded">
                                    {{ $order->payment_terms }}
                                </div>
                            </div>
                        @endif

                        @if($order->remarks)
                            <div class="col-12">
                                <hr>
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-chat-left-text me-2"></i>Remarks
                                </h6>
                                <div class="p-3 bg-light rounded">
                                    {{ $order->remarks }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-box-seam me-2"></i>Order Items
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Item</th>
                                    <th width="10%">Quantity</th>
                                    <th width="12%" class="text-end">Unit Price</th>
                                    <th width="10%" class="text-end">Tax %</th>
                                    <th width="12%" class="text-end">Discount</th>
                                    <th width="15%" class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $subtotal = 0; $totalTax = 0; $totalDiscount = 0; @endphp
                                @foreach($order->details as $index => $detail)
                                    @php
                                        $itemSubtotal = $detail->quantity * $detail->unit_price;
                                        $itemTax = $itemSubtotal * ($detail->tax_rate / 100);
                                        $subtotal += $itemSubtotal;
                                        $totalTax += $itemTax;
                                        $totalDiscount += $detail->discount;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $detail->item->name }}</strong>
                                            <br><small class="text-muted">
                                                Code: {{ $detail->item->code }}
                                                @if($detail->item->category)
                                                    | {{ $detail->item->category->name }}
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            {{ number_format($detail->quantity, 2) }}
                                            @if($detail->item->unit)
                                                <small class="text-muted">{{ $detail->item->unit->name }}</small>
                                            @endif
                                        </td>
                                        <td class="text-end">৳{{ number_format($detail->unit_price, 2) }}</td>
                                        <td class="text-end">{{ number_format($detail->tax_rate, 2) }}%</td>
                                        <td class="text-end">৳{{ number_format($detail->discount, 2) }}</td>
                                        <td class="text-end"><strong>৳{{ number_format($detail->total, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="6" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end"><strong>৳{{ number_format($subtotal, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end"><strong>Total Tax:</strong></td>
                                    <td class="text-end"><strong>৳{{ number_format($totalTax, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end"><strong>Total Discount:</strong></td>
                                    <td class="text-end text-danger"><strong>-৳{{ number_format($totalDiscount, 2) }}</strong></td>
                                </tr>
                                <tr class="table-primary">
                                    <td colspan="6" class="text-end"><strong>Grand Total:</strong></td>
                                    <td class="text-end"><h5 class="mb-0">৳{{ number_format($order->total_amount, 2) }}</h5></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        @if($order->status == 'Draft')
                            <a href="{{ route('purchase.orders.edit', $order) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-2"></i>Edit Order
                            </a>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                                <i class="bi bi-check-circle me-2"></i>Approve Order
                            </button>
                            <form action="{{ route('purchase.orders.destroy', $order) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this purchase order?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-trash me-2"></i>Delete Order
                                </button>
                            </form>
                        @endif
                        
                        @if($order->status == 'Approved')
                            <a href="{{ route('purchase.grn.create') }}?po_id={{ $order->id }}" class="btn btn-success">
                                <i class="bi bi-box-arrow-in-down me-2"></i>Create GRN
                            </a>
                        @endif
                        
                        <button onclick="window.print()" class="btn btn-info">
                            <i class="bi bi-printer me-2"></i>Print PO
                        </button>
                        
                        <a href="{{ route('purchase.vendors.show', $order->vendor) }}" class="btn btn-outline-primary">
                            <i class="bi bi-building me-2"></i>View Vendor
                        </a>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Order Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-muted small">Total Items</div>
                        <div class="h4 text-primary">{{ $order->details->count() }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Total Quantity</div>
                        <div class="h4 text-info">{{ number_format($order->details->sum('quantity'), 2) }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Order Value</div>
                        <div class="h4 text-success">৳{{ number_format($order->total_amount, 2) }}</div>
                    </div>
                    <div class="mb-0">
                        <div class="text-muted small">Status</div>
                        <div class="mt-2">
                            @if($order->status == 'Draft')
                                <span class="badge bg-secondary fs-6">Draft</span>
                            @elseif($order->status == 'Approved')
                                <span class="badge bg-success fs-6">Approved</span>
                            @elseif($order->status == 'Pending')
                                <span class="badge bg-warning fs-6">Pending</span>
                            @else
                                <span class="badge bg-info fs-6">{{ $order->status }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body">
                    <small class="text-muted d-block mb-2">
                        <i class="bi bi-calendar-plus me-1"></i>
                        Created: {{ $order->created_at->format('d M Y, h:i A') }}
                    </small>
                    <small class="text-muted d-block">
                        <i class="bi bi-calendar-check me-1"></i>
                        Updated: {{ $order->updated_at->format('d M Y, h:i A') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('purchase.orders.approve', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-check-circle me-2"></i>Approve Purchase Order
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve this purchase order?</p>
                    <p class="mb-0"><strong>PO Number:</strong> {{ $order->po_no }}</p>
                    <p class="mb-0"><strong>Vendor:</strong> {{ $order->vendor->vendor_name }}</p>
                    <p><strong>Total Amount:</strong> ৳{{ number_format($order->total_amount, 2) }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i>Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

@media print {
    .btn, .breadcrumb, nav, .card-header .badge {
        display: none !important;
    }
}
</style>
@endsection
