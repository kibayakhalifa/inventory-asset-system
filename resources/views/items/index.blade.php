@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection

@section('content')
    <div class="items-container">
        
        <div class="page-header">
            <div>
                <h1>Inventory Items</h1>
                <p class="subtitle">Manage all inventory items across labs</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('items.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Item
                </a>
            </div>
        </div>

        <!-- Flash Message -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('items.index') }}" class="filter-form">
                <div class="form-group search-group">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Search items..." value="{{ request('search') }}">
                </div>

                <div class="form-group">
                    <select class="filter-select" name="type">
                        <option value="">All Types</option>
                        <option value="equipment" {{ request('type') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                        <option value="uniform" {{ request('type') == 'uniform' ? 'selected' : '' }}>Uniform</option>
                        <option value="stationery" {{ request('type') == 'stationery' ? 'selected' : '' }}>Stationery</option>
                    </select>
                </div>

                <div class="form-group">
                    <select class="filter-select" name="lab">
                        <option value="">All Labs</option>
                        <option value="general" {{ request('lab') === 'general' ? 'selected' : '' }}>General</option>
                        @foreach($labs->unique('id') as $lab)
                            <option value="{{ $lab->id }}" {{ request('lab') == $lab->id ? 'selected' : '' }}>
                                {{ $lab->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-filter">
                    <i class="fas fa-filter"></i> Apply
                </button>

                @if(request()->has('search') || request()->has('type') || request()->has('lab'))
                    <a href="{{ route('items.index') }}" class="btn btn-reset">
                        <i class="fas fa-times"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Items Table -->
        <div class="table-container">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Assigned To</th>
                        <th>Total</th>
                        <th>Available</th>
                        <th>Reorder At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                        <tr>
                            <td>{{ ($items->currentPage() - 1) * $items->perPage() + $index + 1 }}</td>
                    
                            <td class="item-name">{{ $item->name }}</td>
                            <td class="item-type">{{ ucfirst($item->type) }}</td>
                            <td class="lab-name">{{ $item->lab?->name ?? 'General' }}</td>
                            <td class="quantity">{{ $item->quantity_total }}</td>
                            <td class="quantity">
                                <span class="{{ $item->quantity_available <= $item->reorder_threshold ? 'text-warning' : '' }}">
                                    {{ $item->quantity_available }}
                                </span>
                            </td>
                            <td class="threshold">{{ $item->reorder_threshold }}</td>
                            <td class="actions">
                                <a href="{{ route('items.edit', $item) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Delete"
                                        onclick="return confirm('Are you sure you want to delete this item?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-message">
                                <i class="fas fa-box-open"></i> No items found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <!-- Pagination -->
        @if($items->hasPages())
            <div class="pagination">
                {{ $items->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const flash = document.querySelector('.alert');
        if (flash) {
            setTimeout(() => {
                flash.style.transition = 'opacity 0.5s ease-out';
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 500); 
            }, 4000); 
        }
    });
</script>

@endsection