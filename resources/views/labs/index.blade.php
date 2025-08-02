@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/labs.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection

@section('content')
    <div class="labs-container">
        <div class="page-header">
            <div>
                <h1>Lab Management</h1>
                <p class="subtitle">Manage all laboratory locations and their inventory</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('labs.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Lab
                </a>
            </div>
        </div>

        <div class="table-container">
            <table class="labs-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lab Name</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Items</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($labs as $lab)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="lab-name">
                                    <i class="fas fa-flask lab-icon"></i> {{ $lab->name }}
                                </div>
                            </td>
                            <td>{{ $lab->description }}</td>
                            <td>{{ $lab->location }}</td>
                            <td>{{ $lab->items_count }}</td>
                            <td>
                                @php
                                    $statusClass = match ($lab->status) {
                                        'Active' => 'badge-active',
                                        'Maintenance' => 'badge-maintenance',
                                        'Closed' => 'badge-closed',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $lab->status }}</span>
                            </td>
                            <td class="actions">
                                <a href="{{ route('labs.show', $lab->id) }}" class="btn-action btn-view">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('labs.edit', $lab->id) }}" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('labs.destroy', $lab->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete show-confirm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
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
                        text: 'This lab will be permanently deleted!',
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