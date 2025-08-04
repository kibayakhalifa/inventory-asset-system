@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Test Report Fetch Route</h2>
    <form method="POST" action="{{ route('reports.fetch') }}">
        @csrf

        <div class="mb-3">
            <label for="report_type" class="form-label">Report Type</label>
            <select class="form-select" name="report_type" required>
                <option value="items">Items</option>
                <option value="users">Users</option>
                <option value="transactions">Transactions</option>
                <option value="labs">Labs</option>
                <option value="damaged">Damaged Items</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="from" class="form-label">From Date</label>
            <input type="date" name="from" class="form-control">
        </div>

        <div class="mb-3">
            <label for="to" class="form-label">To Date</label>
            <input type="date" name="to" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
