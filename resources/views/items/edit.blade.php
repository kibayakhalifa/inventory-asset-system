@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/create-items.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <h2>Edit Item</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following errors:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('items.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div class="form-group">
            <label for="name">Item Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
        </div>

        {{-- Type --}}
        <div class="form-group">
            <label for="type">Type</label>
            <select name="type" id="type" class="form-control">
                <option value="">-- Select Type --</option>
                <option value="equipment" {{ $item->type === 'equipment' ? 'selected' : '' }}>Equipment</option>
                <option value="uniform" {{ $item->type === 'uniform' ? 'selected' : '' }}>Uniform</option>
                <option value="stationery" {{ $item->type === 'stationery' ? 'selected' : '' }}>Stationery</option>
            </select>
        </div>

        {{-- Assigned To --}}
        <div class="form-group">
            <label for="lab_id">Assigned To</label>
            <select name="lab_id" id="lab_id" class="form-control">
                <option value="">General</option>
                @foreach ($labs as $lab)
                    <option value="{{ $lab->id }}" {{ $item->lab_id == $lab->id ? 'selected' : '' }}>
                        {{ $lab->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Total Quantity --}}
        <div class="form-group">
            <label for="quantity_total">Total Quantity</label>
            <input type="number" name="quantity_total" class="form-control" min="0" value="{{ old('quantity_total', $item->quantity_total) }}" required>
        </div>

        {{-- Available Quantity --}}
        <div class="form-group">
            <label for="quantity_available">Available Quantity</label>
            <input type="number" name="quantity_available" class="form-control" min="0" max="{{ old('quantity_total', $item->quantity_total) }}" value="{{ old('quantity_available', $item->quantity_available) }}" required>
        </div>

        {{-- Reorder Threshold --}}
        <div class="form-group">
            <label for="reorder_threshold">Reorder Threshold</label>
            <input type="number" name="reorder_threshold" class="form-control" min="0" value="{{ old('reorder_threshold', $item->reorder_threshold) }}">
        </div>

        {{-- Issue Once --}}
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" name="issued_once" id="issued_once" value="1"
                {{ $item->issued_once ? 'checked' : '' }}>
            <label class="form-check-label" for="issued_once">Issue Once</label>
        </div>

        <button type="submit" class="btn btn-primary">Update Item</button>
    </form>
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
                issuedCheckbox.disabled = false;
            }
        }

        typeSelect.addEventListener('change', handleTypeChange);
        handleTypeChange(); 
    });
</script>
@endsection
