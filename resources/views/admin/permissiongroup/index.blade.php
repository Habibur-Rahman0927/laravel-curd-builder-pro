@extends('layouts/layout')

@section('title', __('permission_group_module.create_list_edit.list_page_title'))

@section('page-style')
    @vite([])
@endsection

@section('page-script')
    @vite(['resources/assets/js/permissiongroup.js'])
@endsection

@section('content')
    <div class="content">
        <div id="routeData" data-url="{{ route('permissiongroup-list') }}"></div>
        <div class="row">
            <div class="col-md-12 page-header mb-2">
                <div class="page-pretitle">{{ __('permission_group_module.create_list_edit.permission_group') }}</div>
                <h1 class="page-title">{{ __('permission_group_module.create_list_edit.list_permission_group_list') }}</h1>
            </div>
        </div>
        <div class="row">
            <div class="card shadow">
                <div class="card-header">
                    <div class="btn-group-wrapper">
                        <div class="export-dropdown">
                            <button type="button" class="btn btn-primary dropdown-toggle export-btn"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('standard_curd_common_label.export')}}
                            </button>
                            <ul class="dropdown-menu">
                                <li><button type="button" class="btn btn-secondary mb-1" id="csvExport">{{ __('standard_curd_common_label.csv') }}</button></li>
                                <li><button type="button" class="btn btn-secondary mb-1" id="excelExport">{{ __('standard_curd_common_label.excel') }}</button>
                                </li>
                                <li><button type="button" class="btn btn-secondary mb-1" id="printExport">{{ __('standard_curd_common_label.print') }}</button>
                                </li>
                            </ul>
                        </div>
                        <a href="{{ route('permissiongroup.create') }}" class="btn btn-success add-btn">{{ __('standard_curd_common_label.add_new') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered yajra-datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('standard_curd_common_label.id') }}</th>
                                    <th>{{ __('permission_group_module.field_label.name') }}</th>
                                    <th>{{ __('standard_curd_common_label.action') }}</th>
                                </tr>
                                <tr>
                                    <th><input type="text" placeholder="{{ __('standard_curd_common_label.search') }} {{ __('standard_curd_common_label.id') }}" class="column-search form-control" />
                                    </th>
                                    <th><input type="text" placeholder="{{ __('standard_curd_common_label.search') }} {{ __('permission_group_module.field_label.name') }}"
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
