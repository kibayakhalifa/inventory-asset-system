@extends('layouts.app')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/labs.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

@endsection

@section('content')
<div class="labs-container">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>Lab Management</h1>
            <p class="subtitle">Manage all laboratory locations and their inventory</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-import">
                <i class="fas fa-file-import"></i> Import
            </button>
            <button class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Lab
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form class="filter-form">
            <div class="form-group">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search labs...">
            </div>
            <div class="form-group">
                <select>
                    <option>All Departments</option>
                    <option>Chemistry</option>
                    <option>Biology</option>
                    <option>Physics</option>
                    <option>Computer Science</option>
                </select>
            </div>
            <div class="form-group">
                <select>
                    <option>All Statuses</option>
                    <option>Active</option>
                    <option>Maintenance</option>
                    <option>Closed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-filter">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>
    </div>

    <!-- Labs Table -->
    <div class="table-container">
        <table class="labs-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Lab Name</th>
                    <th>Department</th>
                    <th>Location</th>
                    <th>Items</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <div class="lab-name">
                            <i class="fas fa-flask lab-icon chemistry"></i>
                            Chemistry Lab A
                        </div>
                    </td>
                    <td>Chemistry</td>
                    <td>Building 1, Room 101</td>
                    <td>142</td>
                    <td><span class="badge badge-active">Active</span></td>
                    <td class="actions">
                        <button class="btn-action btn-view"><i class="fas fa-eye"></i></button>
                        <button class="btn-action btn-edit"><i class="fas fa-edit"></i></button>
                        <button class="btn-action btn-delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>
                        <div class="lab-name">
                            <i class="fas fa-microscope lab-icon biology"></i>
                            Biology Research Lab
                        </div>
                    </td>
                    <td>Biology</td>
                    <td>Building 2, Room 205</td>
                    <td>87</td>
                    <td><span class="badge badge-active">Active</span></td>
                    <td class="actions">
                        <button class="btn-action btn-view"><i class="fas fa-eye"></i></button>
                        <button class="btn-action btn-edit"><i class="fas fa-edit"></i></button>
                        <button class="btn-action btn-delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>
                        <div class="lab-name">
                            <i class="fas fa-atom lab-icon physics"></i>
                            Physics Lab B
                        </div>
                    </td>
                    <td>Physics</td>
                    <td>Building 1, Room 112</td>
                    <td>65</td>
                    <td><span class="badge badge-maintenance">Maintenance</span></td>
                    <td class="actions">
                        <button class="btn-action btn-view"><i class="fas fa-eye"></i></button>
                        <button class="btn-action btn-edit"><i class="fas fa-edit"></i></button>
                        <button class="btn-action btn-delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>
                        <div class="lab-name">
                            <i class="fas fa-laptop-code lab-icon computer"></i>
                            Computer Lab 3
                        </div>
                    </td>
                    <td>Computer Science</td>
                    <td>Building 3, Room 301</td>
                    <td>42</td>
                    <td><span class="badge badge-closed">Closed</span></td>
                    <td class="actions">
                        <button class="btn-action btn-view"><i class="fas fa-eye"></i></button>
                        <button class="btn-action btn-edit"><i class="fas fa-edit"></i></button>
                        <button class="btn-action btn-delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <button disabled><i class="fas fa-chevron-left"></i></button>
        <button class="active">1</button>
        <button>2</button>
        <button><i class="fas fa-chevron-right"></i></button>
    </div>
</div>
@endsection