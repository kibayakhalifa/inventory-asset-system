@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/create-transactions.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
    <div class="transactions-container">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1>Create New Transaction</h1>
                <p class="subtitle">Record item issuance or return</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Transactions
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> Please fix the following errors:
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-container">
            <form action="{{ route('transactions.store') }}" method="POST">
                @csrf

                <!-- Transaction Type -->
                <div class="form-section">
                    <h3><i class="fas fa-exchange-alt"></i> Transaction Type</h3>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="action" value="issue" checked>
                            <span class="radio-custom"></span>
                            <span class="radio-label">Issue Item</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="action" value="return">
                            <span class="radio-custom"></span>
                            <span class="radio-label">Return Item</span>
                        </label>
                    </div>
                </div>

                <!-- Item Selection -->
                <div class="form-section">
                    <h3><i class="fas fa-cube"></i> Item Details</h3>
                    <div class="form-group">
                        <label for="item_id">Select Item *</label>
                        <select id="item_id" name="item_id" required>
                            <option value="">-- Select Item --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}
                                    data-available="{{ $item->quantity_available }}"
                                    class="{{ $item->quantity_available <= 0 ? 'unavailable-item' : '' }}">
                                    {{ $item->name }}
                                    ({{ $item->quantity_available }} available)
                                    {{ $item->quantity_available <= 0 ? ' - OUT OF STOCK' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity *</label>
                        <input type="number" id="quantity" name="quantity" min="1" value="{{ old('quantity') }}" required>
                        <div id="quantity-error" class="error-message" style="display: none;">
                            <i class="fas fa-exclamation-triangle"></i> Not enough items in stock
                        </div>
                    </div>
                </div>

                <!-- Student Details -->
                <div class="form-section">
                    <h3><i class="fas fa-user-graduate"></i> Student Details</h3>
                    <div class="form-group">
                        <label for="student_id">Select Student *</label>
                        <select id="student_id" name="student_id" required>
                            <option value="">-- Select Student --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->id_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Department -->
                <div class="form-section">
                    <h3><i class="fas fa-building"></i> Department</h3>
                    <div class="form-group">
                        <label for="lab_id">Select Department</label>
                        <select id="lab_id" name="lab_id">
                            <option value="">General (Default)</option>
                            @foreach($labs as $lab)
                                <option value="{{ $lab->id }}" {{ old('lab_id') == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Hidden Fields -->
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                <input type="hidden" name="date" value="{{ now()->format('Y-m-d H:i:s') }}">

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <i class="fas fa-save"></i> Save Transaction
                    </button>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    @section('page-js')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const itemSelect = document.getElementById('item_id');
                const quantityInput = document.getElementById('quantity');
                const quantityError = document.getElementById('quantity-error');
                const submitBtn = document.getElementById('submit-btn');
                const actionRadios = document.querySelectorAll('input[name="action"]');
                const labSelect = document.getElementById('lab_id');

                function validateQuantity() {
                    const selectedItem = itemSelect.options[itemSelect.selectedIndex];
                    const available = parseInt(selectedItem.getAttribute('data-available'));
                    const quantity = parseInt(quantityInput.value);
                    const isIssue = document.querySelector('input[name="action"]:checked').value === 'issue';

                    if (isIssue && selectedItem.value && quantity > available) {
                        quantityError.style.display = 'block';
                        submitBtn.disabled = true;
                        return false;
                    } else {
                        quantityError.style.display = 'none';
                        submitBtn.disabled = false;
                        return true;
                    }
                }

                // Remove required attribute from lab_id if needed
                labSelect.required = false;

                itemSelect.addEventListener('change', validateQuantity);
                quantityInput.addEventListener('input', validateQuantity);
                actionRadios.forEach(radio => {
                    radio.addEventListener('change', validateQuantity);
                });

                // Initial validation
                validateQuantity();
            });
        </script>
    @endsection
@endsection