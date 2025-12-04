@extends('layouts.app')

@section('title', 'Category Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-tag me-2"></i>Category Details
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item active">{{ $category->category_name }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('inventory.categories.edit', $category) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('inventory.categories.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Category Information -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Category Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-hash me-2"></i>Basic Details
                            </h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Category Code:</td>
                                    <td><strong>{{ $category->category_code }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Category Name:</td>
                                    <td><strong>{{ $category->category_name }}</strong></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Parent Category:</td>
                                    <td>
                                        @if($category->parent)
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-arrow-return-right me-1"></i>{{ $category->parent->category_name }}
                                            </span>
                                        @else
                                            <span class="text-muted">Top Level Category</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="bi bi-graph-up me-2"></i>Statistics
                            </h6>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 border rounded text-center">
                                        <div class="text-muted small">Total Items</div>
                                        <div class="h4 mb-0 mt-1 text-primary">{{ $category->items->count() }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 border rounded text-center">
                                        <div class="text-muted small">Sub-Categories</div>
                                        <div class="h4 mb-0 mt-1 text-info">{{ $category->children->count() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($category->description)
                            <div class="col-12">
                                <hr>
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-chat-left-text me-2"></i>Description
                                </h6>
                                <div class="p-3 bg-light rounded">
                                    {{ $category->description }}
                                </div>
                            </div>
                        @endif

                        <!-- Sub-Categories -->
                        @if($category->children->count() > 0)
                            <div class="col-12">
                                <hr>
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-diagram-3 me-2"></i>Sub-Categories ({{ $category->children->count() }})
                                </h6>
                                <div class="row g-2">
                                    @foreach($category->children as $child)
                                        <div class="col-md-4">
                                            <div class="card border">
                                                <div class="card-body p-3">
                                                    <h6 class="mb-1">
                                                        <a href="{{ route('inventory.categories.show', $child) }}" class="text-decoration-none">
                                                            {{ $child->category_name }}
                                                        </a>
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i class="bi bi-tag me-1"></i>{{ $child->category_code }}
                                                    </small>
                                                    <div class="mt-2">
                                                        @if($child->is_active)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-danger">Inactive</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Associated Items -->
                        @if($category->items->count() > 0)
                            <div class="col-12">
                                <hr>
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-box-seam me-2"></i>Items in This Category ({{ $category->items->count() }})
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($category->items->take(10) as $item)
                                                <tr>
                                                    <td><strong>{{ $item->code }}</strong></td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>
                                                        @if($item->is_active)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('inventory.items.show', $item) }}" class="btn btn-sm btn-info">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if($category->items->count() > 10)
                                        <div class="text-center py-2">
                                            <small class="text-muted">Showing 10 of {{ $category->items->count() }} items</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Timestamps -->
                        <div class="col-12">
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-plus me-1"></i>Created: {{ $category->created_at->format('d M Y, h:i A') }}
                                    </small>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-check me-1"></i>Updated: {{ $category->updated_at->format('d M Y, h:i A') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('inventory.categories.edit', $category) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Edit Category
                        </a>
                        <a href="{{ route('inventory.items.create') }}?category_id={{ $category->id }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i>Add Item to Category
                        </a>
                        <form action="{{ route('inventory.categories.destroy', $category) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this category?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-danger w-100" 
                                    {{ ($category->items->count() > 0 || $category->children->count() > 0) ? 'disabled' : '' }}>
                                <i class="bi bi-trash me-2"></i>Delete Category
                            </button>
                        </form>
                        @if($category->items->count() > 0 || $category->children->count() > 0)
                            <small class="text-danger text-center">
                                <i class="bi bi-exclamation-triangle me-1"></i>Cannot delete with items/sub-categories
                            </small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Category Hierarchy -->
            @if($category->parent)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-diagram-3 me-2"></i>Hierarchy
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex flex-column gap-2">
                            <div>
                                <small class="text-muted">Parent Category:</small>
                                <div class="mt-1">
                                    <a href="{{ route('inventory.categories.show', $category->parent) }}" class="text-decoration-none">
                                        <i class="bi bi-arrow-up-circle me-1"></i>{{ $category->parent->category_name }}
                                    </a>
                                </div>
                            </div>
                            <div>
                                <small class="text-muted">Current Category:</small>
                                <div class="mt-1">
                                    <strong><i class="bi bi-tag me-1"></i>{{ $category->category_name }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection
