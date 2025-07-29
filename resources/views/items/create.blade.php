@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/create-items.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection

@section('content')
<div class="items-container">
    <!-- Page Header -->
    <div class="items-header">
        <div>
            <h2>Add New Item</h2>
        </div>
    </div>

    @if ($errors->any())
        <div class="items-alert-danger">
            <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="items-form-container">
        <form action="{{ route('items.store') }}" method="POST">
            @csrf

            <div class="items-form-group">
                <label for="name">Item Name *</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="items-form-group">
                <label for="type">Type *</label>
                <select name="type" id="type" required>
                    <option value="">-- Select Type --</option>
                    <option value="equipment">Equipment</option>
                    <option value="uniform">Uniform</option>
                    <option value="stationery">Stationery</option>
                </select>
            </div>

            <div class="items-form-group">
                <label for="lab_id">Assigned To</label>
                <select name="lab_id" id="lab_id">
                    <option value="">General</option>
                    @foreach ($labs as $lab)
                        <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="items-form-group">
                <label for="quantity_to_add">Quantity to Add *</label>
                <input type="number" name="quantity_to_add" id="quantity_to_add" min="1" required>
            </div>

            <div class="items-form-group">
                <label for="reorder_threshold">Reorder Threshold</label>
                <input type="number" name="reorder_threshold" id="reorder_threshold" min="0">
            </div>

            <div class="items-form-check">
                <input type="checkbox" name="issued_once" id="issued_once" value="1">
                <label for="issued_once">Issue Once</label>
            </div>

            <button type="submit" class="items-btn items-btn-primary">
                <i class="fas fa-save"></i> Add Item
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('type');
        const issuedCheckbox = document.getElementById('issued_once');

        function handleTypeChange() {
            if (typeSelect.value === 'uniform') {
                issuedCheckbox.checked = true;
                issuedCheckbox.disabled = true;
            } else {
                issuedCheckbox.checked = false;
                issuedCheckbox.disabled = false;
            }
        }

        typeSelect.addEventListener('change', handleTypeChange);
        handleTypeChange(); 
    });
</script>
@endsection