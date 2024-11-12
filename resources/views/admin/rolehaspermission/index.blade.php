@extends('layouts/layout')

@section('title', 'List Role Has Permission')

@section('page-style')
    @vite([])
@endsection

@section('page-script')
    @vite(['resources/assets/js/rolehaspermission.js'])
@endsection

@section('content')
    <div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg custom-modal-dialog">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="permissionModalLabel">Permission List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Scrollable table will be populated here -->
                    <div id="permissionTableContainer">
                        <!-- Table content will be injected here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div id="routeData" data-url="{{ route('rolehaspermission-list') }}"></div>
        <div class="row">
            <div class="col-md-12 page-header mb-2">
                <div class="page-pretitle">Role Has Permission</div>
                <h1 class="page-title">Role Has Permission List</h1>
            </div>
        </div>
        <div class="row">
            <div class="card shadow">
                <div class="card-header">
                    <div class="btn-group-wrapper">
                        <div class="export-dropdown">
                            <button type="button" class="btn btn-primary dropdown-toggle export-btn"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Export
                            </button>
                            <ul class="dropdown-menu">
                                <li><button type="button" class="btn btn-secondary mb-1" id="csvExport">CSV</button></li>
                                <li><button type="button" class="btn btn-secondary mb-1" id="excelExport">Excel</button>
                                </li>
                                <li><button type="button" class="btn btn-secondary mb-1" id="printExport">Print</button>
                                </li>
                            </ul>
                        </div>
                        <a href="{{ route('rolehaspermission.create') }}" class="btn btn-success add-btn">Add New</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Role Name</th>
                                    <th>Permissions</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <th><input type="text" placeholder="Search ID" class="column-search form-control" />
                                    </th>
                                    <th><input type="text" placeholder="Search Role Name"
                                            class="column-search form-control" /></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
