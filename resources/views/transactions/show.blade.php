@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/create-transactions.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection



@section('content')
<div class="transactions-container">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>Transaction Details</h1>
            <p class="subtitle">View transaction information</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Transactions
            </a>
        </div>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="form-container">
        <!-- Display Mode (Non-Editable) -->
        <div class="form-section">
            <h3><i class="fas fa-exchange-alt"></i> Transaction Type</h3>
            <div class="display-group">
                <span class="display-label">Action:</span>
                <span class="display-value badge {{ $transaction->action === 'issue' ? 'badge-primary' : 'badge-success' }}">
                    {{ ucfirst($transaction->action) }}
                </span>
            </div>
        </div>

        <!-- Item Details -->
        <div class="form-section">
            <h3><i class="fas fa-cube"></i> Item Details</h3>
            <div class="display-group">
                <span class="display-label">Item:</span>
                <span class="display-value">
                    {{ $transaction->item->name }} (ID: {{ $transaction->item_id }})
                </span>
            </div>
            <div class="display-group">
                <span class="display-label">Quantity:</span>
                <span class="display-value">{{ $transaction->quantity }}</span>
            </div>
            <div class="display-group">
                <span class="display-label">Current Stock:</span>
                <span class="display-value {{ $transaction->item->quantity_available <= 0 ? 'text-danger' : '' }}">
                    {{ $transaction->item->quantity_available }}
                    @if($transaction->item->quantity_available <= 0)
                        <i class="fas fa-exclamation-triangle ml-1"></i>
                    @endif
                </span>
            </div>
        </div>

        <!-- Student Details -->
        <div class="form-section">
            <h3><i class="fas fa-user-graduate"></i> Student Details</h3>
            <div class="display-group">
                <span class="display-label">Student:</span>
                <span class="display-value">
                    {{ $transaction->student->name }} ({{ $transaction->student->id_number }})
                </span>
            </div>
        </div>

        <!-- Department -->
        <div class="form-section">
            <h3><i class="fas fa-building"></i> Department</h3>
            <div class="display-group">
                <span class="display-label">Assigned To:</span>
                <span class="display-value">
                    {{ $transaction->lab_id ? $transaction->lab->name : 'General' }}
                </span>
            </div>
        </div>

        <!-- Transaction Metadata -->
        <div class="form-section">
            <h3><i class="fas fa-info-circle"></i> Transaction Info</h3>
            <div class="display-group">
                <span class="display-label">Processed By:</span>
                <span class="display-value">
                    {{ $transaction->user->name }} ({{ $transaction->user->email }})
                </span>
            </div>
            <div class="display-group">
                <span class="display-label">Transaction Date:</span>
                <span class="display-value">
                    {{ $transaction->created_at->format('M d, Y h:i A') }}
                </span>
            </div>
            <div class="display-group">
                <span class="display-label">Last Updated:</span>
                <span class="display-value">
                    {{ $transaction->updated_at->format('M d, Y h:i A') }}
                </span>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Transaction
            </a>
            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="d-inline" id="deleteForm">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger" id="deleteButton">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>

@push('page-js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButton = document.getElementById('deleteButton');
    
    deleteButton.addEventListener('click', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the transaction and adjust inventory!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        });
    });
});
</script>
@endpush
@endsection