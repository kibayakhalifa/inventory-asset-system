@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

@endsection

@section('content')
    <div class="dashboard-container">
        {{-- dashboard header --}}
        <div class="dashboard-header">
            <div class="header-text">
                <h1>Inventory Dashboard</h1>
                <p class="message">Welcome back, {{ Auth::user()->name }}!</p>
            </div>

            {{-- search bar --}}
            <div class="search-container">
                <input type="text" class="dashboard-search" placeholder="Search Items, Labs, Students or Users... ">
                <svg class="search-icon" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        {{-- ksi cards --}}
        <div class="summary-cards">
            {{-- total itms --}}
            <div class="dashboard-card total-items">
                <h3><i class="fa-solid fa-file fa-flip" style="color: #667eea;"></i> Total Items</h3>
                <p class="card-value">1200</p>
                <p class="card-text">Across the school</p>
            </div>
            {{-- Active Labs --}}
            <div class="dashboard-card active-labs">
                <h3><i class="fas fa-vial fa-beat-fade" style="color:#4299e1;"></i> Total Labs</h3>

                <p class="card-value">4</p>
                <p class="card-text">With Inventory</p>
            </div>
            {{-- system users --}}
            <div class="dashboard-card system-users">
                <h3><i class="fa-solid fa-users fa-bounce" style="color:#9f7aea;"></i> System Users</h3>
                <p class="card-value">15</p>
                <p class="card-text">Active accounts</p>
            </div>
            <!-- Low Stock -->
            <div class="dashboard-card low-stock">
                <h3><i class="fa-solid fa-bell fa-shake" style="color:#ed8936;"></i> Low Stock</h3>
                <p class="card-value">5</p>
                <p class="card-text">Items need restocking</p>
            </div>

            <!-- Transactions -->
            <div class="dashboard-card transactions">
                <h3><i class="fa-solid fa-arrow-rotate-right fa-spin" style="color:#38b2ac;"></i> Transactions</h3>
                <p class="card-value">42</p>
                <p class="card-text">This week</p>
            </div>

            <!-- Overdue -->
            <div class="dashboard-card overdue">
                <h3><i class="fa-solid fa-circle-exclamation fa-fade" style="color:#f56565;"></i> Overdue Returns</h3>
                <p class="card-value">3</p>
                <p class="card-text">Items pending</p>
            </div>

        </div>

        {{-- charts section --}}
        <div class="charts-section">
            <!--items by category -->
            <div class="chart-container">
                <div class="chart-header">
                    <h2>Items by Category</h2>
                    <select class="chart-filter">
                        <option value="">This Month</option>
                        <option value="">Last Month</option>
                        <option value="">This Year</option>
                    </select>
                </div>
                <div class="chart-placeholder">
                    {{-- chart would be rendered here --}}
                    <div class="chart-mockup"> </div>
                </div>
            </div>
            {{-- lab usage --}}
            <div class="chart-container">
                <div class="chart-header">
                    <h2>Lab Usage</h2>
                    <select class="chart-filter">
                        <option value="">This Month</option>
                        <option value="">Last Month</option>
                        <option value="">This Year</option>
                    </select>
                </div>
                <div class="chart-placeholder">
                    {{-- chart would be displayed here --}}
                    <div class="chart-mockup"></div>
                </div>
            </div>
        </div>
        <!-- Recent Activity & Alerts -->
        <div class="activity-section">
            <!-- Recent Activity -->
            <div class="activity-container">
                <div class="section-header">
                    <h2>Recent Activity</h2>
                    <a href="#" class="view-all">View All</a>
                </div>
                <ul class="activity-list">
                    <li>
                        <div class="activity-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <div class="activity-details">
                            <p>Added 10 lab coats to Chemistry Lab</p>
                            <span class="activity-time">Today, 09:42 AM</span>
                        </div>
                    </li>
                    <li>
                        <div class="activity-icon checkout">
                            <svg viewBox="0 0 24 24">
                                <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div class="activity-details">
                            <p>Borrowed 1 laptop by John Doe</p>
                            <span class="activity-time">Yesterday, 03:15 PM</span>
                        </div>
                    </li>
                    <li>
                        <div class="activity-icon return">
                            <svg viewBox="0 0 24 24">
                                <path d="M15 19l-7-7 7-7" />
                            </svg>
                        </div>
                        <div class="activity-details">
                            <p>Returned microscope from Physics Lab</p>
                            <span class="activity-time">Yesterday, 11:30 AM</span>
                        </div>
                    </li>
                    <li>
                        <div class="activity-icon update">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div class="activity-details">
                            <p>Updated inventory counts for Biology Lab</p>
                            <span class="activity-time">Monday, 2:45 PM</span>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- System Alerts -->
            <div class="alerts-container">
                <div class="section-header">
                    <h2>System Alerts</h2>
                </div>
                <ul class="alerts-list">
                    <li class="alert-warning">
                        <div class="alert-icon">
                            <i class="fa-solid fa-triangle-exclamation fa-flip" style="color: #ed8936;"></i>
                        </div>
                        <div class="alert-details">
                            <p>3 items are overdue for return</p>
                            <span class="alert-time">2 hours ago</span>
                        </div>
                    </li>
                    <li class="alert-critical">
                        <div class="alert-icon">
                            <i class="fa-solid fa-screwdriver-wrench fa-fade" style="color: #f56565;"></i>

                        </div>
                        <div class="alert-details">
                            <p>Microscope #B-205 needs maintenance</p>
                            <span class="alert-time">5 hours ago</span>
                        </div>
                    </li>
                    <li class="alert-info">
                        <div class="alert-icon">
                            <i class="fa-solid fa-arrows-rotate fa-spin" style="color: #4299e1;"></i>

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