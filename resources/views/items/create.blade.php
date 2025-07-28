@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/create-items.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <h2>Add New Item</h2>

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

    <form action="{{ route('items.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Item Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="type">Type</label>
            <select name="type" id="type" class="form-control">
                <option value="">-- Select Type --</option>
                <option value="equipment">Equipment</option>
                <option value="uniform">Uniform</option>
                <option value="stationery">Stationery</option>
            </select>
        </div>

        <div class="form-group">
            <label for="lab_id">Assigned To</label>
            <select name="lab_id" id="lab_id" class="form-control">
                <option value="">General</option>
                @foreach ($labs as $lab)
                    <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="quantity_to_add">Quantity to Add</label>
            <input type="number" name="quantity_to_add" class="form-control" min="1" required>
        </div>

        <div class="form-group">
            <label for="reorder_threshold">Reorder Threshold</label>
            <input type="number" name="reorder_threshold" class="form-control" min="0">
        </div>

        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" name="issued_once" id="issued_once" value="1">
            <label class="form-check-label" for="issued_once">Issue Once</label>
        </div>

        <button type="submit" class="btn btn-primary">Add Item</button>
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
                issuedCheckbox.checked = false;
                issuedCheckbox.disabled = false;
            }
        }

        typeSelect.addEventListener('change', handleTypeChange);
        handleTypeChange(); 
    });
</script>
@endsection
