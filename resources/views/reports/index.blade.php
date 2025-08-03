@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/reports.css') }}">
@endsection

@section('content')
<div class="reports-container">
    <div class="page-header">
        <h1>Reports Center</h1>
    </div>

    <div class="filters-section">
        <form>
            <div class="filter-group">
                <label for="lab">Lab:</label>
                <select id="lab" name="lab">
                    <option>All Labs</option>
                    <option>Physics</option>
                    <option>Biology</option>
                    <option>Chemistry</option>
                    <option>Computer</option>
                    <option>General</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="type">Report Type:</label>
                <select id="type" name="type">
                    <option>Transactions</option>
                    <option>Items</option>
                    <option>Users</option>
                    <option>Logs</option>
                    <option>Damaged</option>
                </select>
            </div>

            <div class="filter-group date-range">
                <label for="from">From:</label>
                <input type="date" id="from" name="from">
                <label for="to">To:</label>
                <input type="date" id="to" name="to">
            </div>

            <div class="filter-group">
                <button type="submit">Filter</button>
            </div>
        </form>
    </div>

    <div class="export-section">
        <button class="print-btn">🖨️ Print</button>
        <button class="export-btn">⬇️ Export</button>
    </div>

    <div class="chart-section">
        <h2>Chart Preview</h2>
        <div class="chart-placeholder">[Chart goes here]</div>
    </div>

    <div class="results-section">
        <h2>Report Results</h2>
        <div class="table-placeholder">[Report table will be shown here]</div>
    </div>
</div>
@endsection
