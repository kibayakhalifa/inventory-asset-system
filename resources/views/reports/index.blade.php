@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
    <div class="reports-container">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1><i class="fas fa-chart-line me-2"></i>Inventory Reports</h1>
                <p class="subtitle">Generate detailed reports and analytics for your inventory management</p>
            </div>
            <div class="header-actions">
                @if($hasData)
                    <form method="GET" action="{{ route('reports.export') }}" class="d-inline">
                        @foreach($request->query() as $key => $value)
                            @if($key !== 'page')
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-file-csv me-2"></i>Export CSV
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- FILTERS SECTION -->
        <div class="filter-section">
            <form id="filter-form" method="GET" action="{{ route('reports.index') }}" class="filter-form">
                <div class="form-row">
                    <!-- Report Type -->
                    <div class="form-group">
                        <label>Report Type</label>
                        <select name="report_type" id="report_type" class="form-select">
                            <option value="">Select Type</option>
                            <option value="items" {{ request('report_type') == 'items' ? 'selected' : '' }}>Items</option>
                            <option value="users" {{ request('report_type') == 'users' ? 'selected' : '' }}>Users</option>
                            <option value="transactions" {{ request('report_type') == 'transactions' ? 'selected' : '' }}>
                                Transactions</option>
                            <option value="labs" {{ request('report_type') == 'labs' ? 'selected' : '' }}>Labs</option>
                            <option value="damaged" {{ request('report_type') == 'damaged' ? 'selected' : '' }}>Damaged Items
                            </option>
                            <option value="low_stock" {{ request('report_type') == 'low_stock' ? 'selected' : '' }}>Low Stock
                            </option>
                        </select>
                    </div>

                    <!-- Lab Selector -->
                    <div class="form-group">
                        <label>Lab</label>
                        <select name="lab_id" id="lab_id" class="form-select">
                            <option value="">All Labs</option>
                            @foreach ($labs as $lab)
                                <option value="{{ $lab->id }}" {{ request('lab_id') == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->name }}</option>
                            @endforeach
                            <option value="general" {{ request('lab_id') === 'general' ? 'selected' : '' }}>General</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="form-group date-group">
                        <label>Start Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                class="date-input">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>

                    <div class="form-group date-group">
                        <label>End Date</label>
                        <div class="date-input-wrapper">
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                class="date-input">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Apply
                        </button>
                        <a href="{{ route('reports.index') }}" class="btn btn-reset">
                            <i class="fas fa-sync-alt me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- RESULTS SECTION -->
        <div class="report-results">
            @if($reportType)
                @includeIf('reports.partials.' . $reportType, [
                    'data' => $results,
                    'request' => $request
                ])
            @else
            <div class="empty-message">
                    <i class="fas fa-chart-pie fa-lg me-2"></i>
                    <span>Please select a report type and apply filters to view results</span>
                </div>
        @endif
        </div>
    </div>
@endsection

 @section('page-js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Initialize date pickers with default ranges if needed
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('end_date').max = today;

            // Update max end date when start date changes
            document.getElementById('start_date').addEventListener('change', function() {
                document.getElementById('end_date').min = this.value;
            });

            // Add animation to report results when loaded
            const reportResults = document.querySelector('.report-results');
            if (reportResults) {
                reportResults.style.opacity = 0;
                setTimeout(() => {
                    reportResults.style.transition = 'opacity 0.3s ease';
                    reportResults.style.opacity = 1;
                }, 100);
            }
        });
    </script>
@endsection