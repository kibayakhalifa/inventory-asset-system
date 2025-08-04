@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
    <div class="dashboard-container">
        <!-- Header Section -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1>Inventory Overview</h1>
                <p class="welcome-message">Welcome back, {{ Auth::user()->name }} <span class="welcome-emoji">👋</span></p>
            </div>
            
           
        </div>

        <!-- Summary Cards -->
        <div class="summary-grid">
            <div class="summary-card">
                <div class="card-icon bg-blue">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="card-content">
                    <span class="card-title">Total Items</span>
                    <span class="card-value">{{ $totalItems }}</span>
                    
                </div>
            </div>
            
            <div class="summary-card">
                <div class="card-icon bg-purple">
                    <i class="fas fa-flask"></i>
                </div>
                <div class="card-content">
                    <span class="card-title">Active Labs</span>
                    <span class="card-value">{{ $labCount }}</span>
                    
                </div>
            </div>
            
            <div class="summary-card">
                <div class="card-icon bg-green">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-content">
                    <span class="card-title">System Users</span>
                    <span class="card-value">{{ $systemUsers }}</span>
                    
                </div>
            </div>
            
            <div class="summary-card">
                <div class="card-icon bg-orange">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="card-content">
                    <span class="card-title">Low Stock</span>
                    <span class="card-value">{{ $lowStockCount }}</span>
                    
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="analytics-section">
            <div class="analytics-card">
                <div class="card-header">
                    <h3>Items per Lab</h3>
                    
                </div>
                <div class="chart-container">
                    <canvas id="itemsChart"></canvas>
                </div>
            </div>
            
            <div class="analytics-card">
                <div class="card-header">
                    <h3>Issued Vs Returned</h3>
                    
                </div>
                <div class="chart-container">
                    <canvas id="issuedReturnedChart"></canvas>
                </div>
            </div>
            
            <div class="analytics-card">
                <div class="card-header">
                    <h3>Low Stock Items</h3>
                    
                </div>
                <div class="chart-container">
                    <canvas id="lowStockChart"></canvas>
                </div>
            </div>
        </div>

        
    </div>
@endsection

@push('page-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Items per Lab Chart
        const itemsChart = new Chart(
            document.getElementById('itemsChart'), 
            {
                type: 'bar',
                data: {
                    labels: {!! json_encode($labNames) !!},
                    datasets: [{
                        label: 'Total Items',
                        data: {!! json_encode($itemCounts) !!},
                        backgroundColor: '#4299e1',
                        borderRadius: 4,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: { drawBorder: false }
                        },
                        x: { 
                            grid: { display: false }
                        }
                    }
                }
            }
        );

        // Issued vs Returned Chart
        const issuedReturnedChart = new Chart(
            document.getElementById('issuedReturnedChart'), 
            {
                type: 'doughnut',
                data: {
                    labels: ['Issued', 'Returned'],
                    datasets: [{
                        data: [{{ $issuedCount }}, {{ $returnedCount }}],
                        backgroundColor: ['#f6ad55', '#68d391'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 20 }
                        }
                    }
                }
            }
        );

        // Low Stock Items Chart
        const lowStockChart = new Chart(
            document.getElementById('lowStockChart'), 
            {
                type: 'bar',
                data: {
                    labels: {!! json_encode($lowStockNames) !!},
                    datasets: [{
                        label: 'Low Stock Items',
                        data: {!! json_encode($lowStockCounts) !!},
                        backgroundColor: '#fc8181',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: { drawBorder: false }
                        },
                        x: { 
                            grid: { display: false }
                        }
                    }
                }
            }
        );

        // Make charts responsive to window resize
        window.addEventListener('resize', function() {
            itemsChart.resize();
            issuedReturnedChart.resize();
            lowStockChart.resize();
        });
    });
</script>
@endpush