@extends('layouts.app')

@section('page-css')
    <link href="{{ asset('css/create-users.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="users-container">
    <div class="page-header">
        <div>
            <h1>User Details</h1>
            <p class="subtitle">View user account information</p>
        </div>
        <div>
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                <i class="fas fa-edit mr-1"></i> Edit User
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="form-container">
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-user-circle mr-1"></i>
                Basic Information
            </h3>
            
            <div class="form-grid">
                <div class="display-group">
                    <span class="display-label">Full Name</span>
                    <span class="display-value">{{ $user->name }}</span>
                </div>

                <div class="display-group">
                    <span class="display-label">Email Address</span>
                    <span class="display-value">{{ $user->email }}</span>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-user-shield mr-1"></i>
                User Permissions
            </h3>
            
            <div class="display-group">
                <span class="display-label">System Roles</span>
                <div class="display-value">
                    @foreach($user->roles as $role)
                        <span class="badge badge-primary">{{ $role->name }}</span>
                    @endforeach
                    @if($user->roles->isEmpty())
                        <span class="text-muted">No roles assigned</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-user-check mr-1"></i>
                Account Status
            </h3>
            
            <div class="display-group">
                <span class="display-label">Status</span>
                <span class="display-value">
                    @if($user->status)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">Inactive</span>
                    @endif
                </span>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-calendar-alt mr-1"></i>
                Account Timestamps
            </h3>
            
            <div class="form-grid">
                <div class="display-group">
                    <span class="display-label">Created At</span>
                    <span class="display-value">{{ $user->created_at->format('M d, Y H:i') }}</span>
                </div>

                <div class="display-group">
                    <span class="display-label">Last Updated</span>
                    <span class="display-value">{{ $user->updated_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <form id="deleteForm" action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="button" id="deleteButton" class="btn btn-danger">
                    <i class="fas fa-trash mr-1"></i> Delete User
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('page-js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButton = document.getElementById('deleteButton');
    
    deleteButton.addEventListener('click', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the user account!",
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