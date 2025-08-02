@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/create-labs.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection

@section('content')
    <div class="labs-container">
        <div class="page-header">
            <div>
                <h1>Lab Details</h1>
                <p class="subtitle">View laboratory information</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('labs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Labs
                </a>
            </div>
        </div>

        <div class="form-container">
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-info-circle"></i> Basic Information</h3>
                
                <div class="display-group">
                    <span class="display-label">Lab Name</span>
                    <span class="display-value">{{ $lab->name }}</span>
                </div>
                
                <div class="display-group">
                    <span class="display-label">Description</span>
                    <span class="display-value">{{ $lab->description ?? 'N/A' }}</span>
                </div>
                
                <div class="display-group">
                    <span class="display-label">Location</span>
                    <span class="display-value">{{ $lab->location }}</span>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-chart-line"></i> Status</h3>
                
                <div class="display-group">
                    <span class="display-label">Current Status</span>
                    @php
                        $statusClass = match ($lab->status) {
                            'Active' => 'badge-active',
                            'Maintenance' => 'badge-maintenance',
                            'Closed' => 'badge-closed',
                        };
                    @endphp
                    <span class="badge {{ $statusClass }}">{{ $lab->status }}</span>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="section-title"><i class="fas fa-flask"></i> Inventory</h3>
                
                <div class="display-group">
                    <span class="display-label">Total Items</span>
                    <span class="display-value">{{ $lab->items_count }}</span>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="{{ route('labs.edit', $lab->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Lab
                </a>
                <form action="{{ route('labs.destroy', $lab->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger show-confirm">
                        <i class="fas fa-trash"></i> Delete Lab
                    </button>
                </form>
            </div>
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