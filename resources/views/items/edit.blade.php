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
            <h2>Edit Item</h2>
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
        <form action="{{ route('items.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div class="items-form-group">
                <label for="name">Item Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $item->name) }}" required>
            </div>

            {{-- Type --}}
            <div class="items-form-group">
                <label for="type">Type *</label>
                <select name="type" id="type" required>
                    <option value="">-- Select Type --</option>
                    <option value="equipment" {{ $item->type === 'equipment' ? 'selected' : '' }}>Equipment</option>
                    <option value="uniform" {{ $item->type === 'uniform' ? 'selected' : '' }}>Uniform</option>
                    <option value="stationery" {{ $item->type === 'stationery' ? 'selected' : '' }}>Stationery</option>
                </select>
            </div>

            {{-- Assigned To --}}
            <div class="items-form-group">
                <label for="lab_id">Assigned To</label>
                <select name="lab_id" id="lab_id">
                    <option value="">General</option>
                    @foreach ($labs as $lab)
                        <option value="{{ $lab->id }}" {{ $item->lab_id == $lab->id ? 'selected' : '' }}>
                            {{ $lab->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Total Quantity --}}
            <div class="items-form-group">
                <label for="quantity_total">Total Quantity *</label>
                <input type="number" name="quantity_total" id="quantity_total" min="0" 
                       value="{{ old('quantity_total', $item->quantity_total) }}" required>
            </div>

            {{-- Available Quantity --}}
            <div class="items-form-group">
                <label for="quantity_available">Available Quantity *</label>
                <input type="number" name="quantity_available" id="quantity_available" min="0" 
                       max="{{ old('quantity_total', $item->quantity_total) }}" 
                       value="{{ old('quantity_available', $item->quantity_available) }}" required>
            </div>

            {{-- Reorder Threshold --}}
            <div class="items-form-group">
                <label for="reorder_threshold">Reorder Threshold</label>
                <input type="number" name="reorder_threshold" id="reorder_threshold" min="0" 
                       value="{{ old('reorder_threshold', $item->reorder_threshold) }}">
            </div>

            {{-- Issue Once --}}
            <div class="items-form-check">
                <input type="checkbox" name="issued_once" id="issued_once" value="1"
                    {{ $item->issued_once ? 'checked' : '' }}>
                <label for="issued_once">Issue Once</label>
            </div>

            <button type="submit" class="items-btn items-btn-primary">
                <i class="fas fa-save"></i> Update Item
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('type');
        const issuedCheckbox = document.getElementById('issued_once');
        const totalQtyInput = document.getElementById('quantity_total');
        const availableQtyInput = document.getElementById('quantity_available');

        function handleTypeChange() {
            if (typeSelect.value === 'uniform') {
                issuedCheckbox.checked = true;
                issuedCheckbox.disabled = true;
            } else {
                issuedCheckbox.disabled = false;
            }
        }

        // Update max available quantity when total changes
        totalQtyInput.addEventListener('change', function() {
            availableQtyInput.max = this.value;
            if (parseInt(availableQtyInput.value) > parseInt(this.value)) {
                availableQtyInput.value = this.value;
            }
        });

        typeSelect.addEventListener('change', handleTypeChange);
        handleTypeChange();
    });
</script>
@endsection