@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection

@section('content')
    <div class="users-container">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1>Users Management</h1>
                <p class="subtitle">Manage system users and permissions</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New User
                </a>
            </div>
        </div>

        <!-- Flash Message -->
        @if(session('success'))
            <div class="alert alert-success" id="success-alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('users.index') }}" class="filter-form">
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" name="search" placeholder="Search by name" value="{{ request('search') }}">
                    </div>

                    <div class="form-group">
                        <select name="role">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <select name="status">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-filter">
                            <i class="fas fa-filter"></i> Apply
                        </button>
                        @if(request()->anyFilled(['search', 'role', 'status', 'from_date', 'to_date']))
                            <a href="{{ route('users.index') }}" class="btn btn-reset">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="serial">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>
                            <td class="name">{{ $user->name }}</td>
                            <td class="email">{{ $user->email }}</td>
                            <td>
                                @forelse($user->getRoleNames() as $role)
                                    <span class="badge bg-secondary">{{ $role }}</span>
                                @empty
                                    <span class="text-muted">No role</span>
                                @endforelse
                            </td>
                            <td>
                                <span class="badge badge-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="actions">
                                <a href="{{ route('users.show', $user) }}" class="btn-action btn-view" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $user) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action btn-delete show-confirm" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-message">
                                <i class="fas fa-database"></i> No users found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="pagination-wrapper">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add placeholder functionality for date inputs
            const dateInputs = document.querySelectorAll('.date-input');

            dateInputs.forEach(input => {
                if (!input.value) {
                    input.setAttribute('placeholder', input.name === 'from_date' ? 'Start date' : 'End date');
                }
                input.addEventListener('change', function () {
                    if (this.value) {
                        this.removeAttribute('placeholder');
                    } else {
                        this.setAttribute('placeholder', this.name === 'from_date' ? 'Start date' : 'End date');
                    }
                });
            });

            // Auto-dismiss success alert
            const alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 3000);
            }
        });
    </script>
@endsection

@push('page-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.show-confirm');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This user account will be permanently deleted!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.closest('form').submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush