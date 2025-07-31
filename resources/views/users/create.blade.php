@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/create-users.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="users-container">
    <div class="page-header">
        <div>
            <h1>Create New User</h1>
            <p class="subtitle">Add a new user account to the system</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="alert-title">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                There were some issues with your submission
            </div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-user-circle mr-1"></i>
                    Basic Information
                </h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="John Doe">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="user@example.com">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-lock mr-1"></i>
                    Account Security
                </h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="Minimum 8 characters">
                        <div class="input-hint">Must be at least 8 characters</div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Re-enter your password">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-user-shield mr-1"></i>
                    User Permissions
                </h3>
                
                <div class="form-group">
                    <label>System Roles</label>
                    <div class="radio-group">
                        @foreach($roles as $role)
                            <label class="radio-option">
                                <input type="radio" name="roles[]" value="{{ $role }}" {{ in_array($role, old('roles', [])) ? 'checked' : '' }}>
                                <span class="radio-label">{{ $role }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-user-check mr-1"></i>
                    Account Status
                </h3>
                
                <div class="form-group">
                    <label for="status">User Status</label>
                    <select id="status" name="status" required>
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus mr-1"></i>
                    Create User
                </button>
                
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times mr-1"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
