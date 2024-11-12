@extends('layouts/layout')

@section('title', 'List Permission')

@section('page-style')
    @vite([])
@endsection

@section('page-script')
    @vite(['resources/assets/js/permission.js'])
@endsection

@section('content')
    <div class="content">
        <div id="routeData" data-url="{{ route('permission-list') }}"></div>
        <div class="row">
            <div class="col-md-12 page-header mb-2">
                <div class="page-pretitle">Permission</div>
                <h1 class="page-title">Permission List</h1>
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
                        <a href="{{ route('permission.create') }}" class="btn btn-success add-btn">Add New</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Permission Group Name</th>
                                    <th>Permission Name</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <th><input type="text" placeholder="Search ID" class="column-search form-control" />
                                    </th>
                                    <th><input type="text" placeholder="Search Group Name"
                                            class="column-search form-control" /></th>
                                    <th><input type="text" placeholder="Search Permission Name"
                                            class="column-search form-control" /></th>
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
