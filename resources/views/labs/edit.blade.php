@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/create-labs.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection

@section('content')
    <div class="labs-container">
        <div class="page-header">
            <div>
                <h1>Edit Lab</h1>
                <p class="subtitle">Update laboratory details</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('labs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Labs
                </a>
            </div>
        </div>

        <div class="form-container">
            <form action="{{ route('labs.update', $lab->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-section">
                    <h3 class="section-title"><i class="fas fa-info-circle"></i> Basic Information</h3>
                    
                    <div class="form-group">
                        <label for="name">Lab Name</label>
                        <select id="name" name="name" class="form-control" required>
                            <option value="">Select Lab</option>
                            @foreach(App\Models\Lab::ALLOWED_LAB_NAMES as $labName)
                                <option value="{{ $labName }}" {{ old('name', $lab->name) == $labName ? 'selected' : '' }}>
                                    {{ $labName }}
                                </option>
                            @endforeach
                        </select>
                        @error('name')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" id="description" name="description" value="{{ old('description', $lab->description) }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" value="{{ old('location', $lab->location) }}" required>
                        @error('location')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-section">
                    <h3 class="section-title"><i class="fas fa-chart-line"></i> Status</h3>
                    
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="status" value="Active" {{ old('status', $lab->status) === 'Active' ? 'checked' : '' }}>
                            <span class="radio-custom"></span>
                            <span class="radio-label">Active</span>
                        </label>
                        
                        <label class="radio-option">
                            <input type="radio" name="status" value="Maintenance" {{ old('status', $lab->status) === 'Maintenance' ? 'checked' : '' }}>
                            <span class="radio-custom"></span>
                            <span class="radio-label">Maintenance</span>
                        </label>
                        
                        <label class="radio-option">
                            <input type="radio" name="status" value="Closed" {{ old('status', $lab->status) === 'Closed' ? 'checked' : '' }}>
                            <span class="radio-custom"></span>
                            <span class="radio-label">Closed</span>
                        </label>
                    </div>
                    @error('status')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Lab
                    </button>
                    <a href="{{ route('labs.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection