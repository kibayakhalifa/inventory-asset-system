@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/transactions.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection

@section('content')
    <div class="transactions-container">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1>Transaction History</h1>
                <p class="subtitle">View issued and returned inventory records</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Transaction
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
            <form method="GET" action="{{ route('transactions.index') }}" class="filter-form">
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" name="item" placeholder="Item name" value="{{ request('item') }}">
                    </div>

                    <div class="form-group">
                        <input type="text" name="student" placeholder="Student name" value="{{ request('student') }}">
                    </div>

                    <div class="form-group">
                        <input type="text" name="staff" placeholder="Staff name" value="{{ request('staff') }}">
                    </div>

                    <div class="form-group">
                        <select name="department">
                            <option value="">All Departments</option>
                            <option value="general" {{ request('department') === 'general' ? 'selected' : '' }}>General
                            </option>
                            @foreach($labs as $lab)
                                <option value="{{ $lab->id }}" {{ request('department') == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <select name="action">
                            <option value="">All Types</option>
                            <option value="issue" {{ request('action') == 'issue' ? 'selected' : '' }}>Issue</option>
                            <option value="return" {{ request('action') == 'return' ? 'selected' : '' }}>Return</option>
                        </select>
                    </div>

                    <div class="form-group date-group">
                        <label>From Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="date-input">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>

                    <div class="form-group date-group">
                        <label>To Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="date-input">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-filter">
                            <i class="fas fa-filter"></i> Apply
                        </button>
                        @if(request()->anyFilled(['item', 'student', 'staff', 'department', 'action', 'from_date', 'to_date']))
                            <a href="{{ route('transactions.index') }}" class="btn btn-reset">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="table-wrapper">
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Student</th>
                        <th>Handled By</th>
                        <th>Department</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Condition</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td class="serial">
                                {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}
                            </td>
                            <td class="item-name">{{ $transaction->item->name ?? 'N/A' }}</td>
                            <td class="student-name">{{ $transaction->student->name ?? 'N/A' }}</td>
                            <td class="staff-name">{{ $transaction->user->name ?? 'N/A' }}</td>
                            <td class="department">{{ $transaction->lab?->name ?? 'General' }}</td>
                            <td class="action-type">
                                <span class="badge {{ $transaction->action === 'issue' ? 'badge-issue' : 'badge-return' }}">
                                    {{ ucfirst($transaction->action) }}
                                </span>
                            </td>
                            <td class="quantity">{{ $transaction->quantity }}</td>
                            <td class="condition">
                                @if($transaction->condition)
                                    <span class="badge badge-condition condition-{{ $transaction->condition }}">
                                        {{ ucfirst($transaction->condition) }}
                                    </span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="date">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                            <td class="actions">
                                <a href="{{ route('transactions.show', $transaction) }}" class="btn-action btn-view"
                                    title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('transactions.edit', $transaction) }}" class="btn-action btn-edit"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST"
                                    class="d-inline delete-form">
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
                            <td colspan="9" class="empty-message">
                                <i class="fas fa-database"></i> No transactions found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="pagination-wrapper">
                {{ $transactions->appends(request()->query())->links() }}
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
                        text: 'This transaction will be permanently deleted and inventory adjusted!',
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