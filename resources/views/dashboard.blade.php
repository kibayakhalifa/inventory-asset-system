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
            
            <div class="header-actions">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search inventory...">
                </div>
                <button class="notification-btn">
                    <i class="far fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
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
                    <span class="card-trend positive">
                        <i class="fas fa-arrow-up"></i> 5.2%
                    </span>
                </div>
            </div>
            
            <div class="summary-card">
                <div class="card-icon bg-purple">
                    <i class="fas fa-flask"></i>
                </div>
                <div class="card-content">
                    <span class="card-title">Active Labs</span>
                    <span class="card-value">{{ $labCount }}</span>
                    <span class="card-trend neutral">
                        <i class="fas fa-equals"></i> No change
                    </span>
                </div>
            </div>
            
            <div class="summary-card">
                <div class="card-icon bg-green">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-content">
                    <span class="card-title">System Users</span>
                    <span class="card-value">{{ $systemUsers }}</span>
                    <span class="card-trend positive">
                        <i class="fas fa-arrow-up"></i> 2 new
                    </span>
                </div>
            </div>
            
            <div class="summary-card">
                <div class="card-icon bg-orange">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="card-content">
                    <span class="card-title">Low Stock</span>
                    <span class="card-value">{{ $lowStockCount }}</span>
                    <span class="card-trend negative">
                        <i class="fas fa-arrow-down"></i> Needs attention
                    </span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="analytics-section">
            <div class="analytics-card">
                <div class="card-header">
                    <h3>Items per Lab</h3>
                    <select class="chart-filter">
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option>Last 90 days</option>
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="itemsChart"></canvas>
                </div>
            </div>
            
            <div class="analytics-card">
                <div class="card-header">
                    <h3>Issued Vs Returned</h3>
                    <select class="chart-filter">
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option>Last 90 days</option>
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="issuedReturnedChart"></canvas>
                </div>
            </div>
            
            <div class="analytics-card">
                <div class="card-header">
                    <h3>Low Stock Items</h3>
                    <select class="chart-filter">
                        <option>All Labs</option>
                        @foreach($labNames as $lab)
                        <option>{{ $lab }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="lowStockChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Alerts -->
        <div class="activity-section">
            <div class="activity-card">
                <div class="card-header">
                    <h3>Recent Activity</h3>
                    <a href="#" class="view-all">View All <i class="fas fa-chevron-right"></i></a>
                </div>
                <ul class="activity-list">
                    <!-- Static activity items -->
                    <li class="activity-item">
                        <div class="activity-icon bg-blue">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="activity-details">
                            <p>Added 10 lab coats to Chemistry Lab</p>
                            <span class="activity-time">Today, 09:42 AM</span>
                        </div>
                        <button class="activity-action">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon bg-green">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                        <div class="activity-details">
                            <p>Borrowed 1 laptop by John Doe</p>
                            <span class="activity-time">Yesterday, 03:15 PM</span>
                        </div>
                        <button class="activity-action">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon bg-purple">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                        <div class="activity-details">
                            <p>Returned microscope from Physics Lab</p>
                            <span class="activity-time">Yesterday, 11:30 AM</span>
                        </div>
                        <button class="activity-action">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="alerts-card">
                <div class="card-header">
                    <h3>System Alerts</h3>
                    <span class="badge bg-red">3 New</span>
                </div>
                <ul class="alerts-list">
                    <!-- Static alert items -->
                    <li class="alert-item warning">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="alert-details">
                            <p>3 items overdue for return</p>
                            <span class="alert-time">2 hours ago</span>
                        </div>
                    </li>
                    <li class="alert-item critical">
                        <div class="alert-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="alert-details">
                            <p>Microscope #B-205 needs maintenance</p>
                            <span class="alert-time">5 hours ago</span>
                        </div>
                    </li>
                    <li class="alert-item info">
                        <div class="alert-icon">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <div class="alert-details">
                            <p>New software update available</p>
                            <span class="alert-time">1 day ago</span>
                        </div>
                    </li>
                </ul>
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