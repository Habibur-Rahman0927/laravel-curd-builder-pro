@extends('layouts/layout')

@section('title',  __('dashboard_label.page_title') )

@section('page-style')
    @vite([])
@endsection

@section('page-script')
    @vite([])
@endsection

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12 page-header">
                <div class="page-pretitle">{{ __('dashboard_label.overview') }}</div>
                <h2 class="page-title">{{ __('dashboard_label.dashboard') }}</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card shadow">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="icon-big text-center">
                                    <i class="teal fas fa-user-group"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="detail">
                                    <p class="detail-subtitle">{{ __('dashboard_label.users') }}</p>
                                    <span class="number">{{$userCount}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card shadow">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="icon-big text-center">
                                    <i class="olive fas fa-lock"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="detail">
                                    <p class="detail-subtitle">{{ __('dashboard_label.roles') }}</p>
                                    <span class="number">{{$roleCount}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card shadow">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="icon-big text-center">
                                    <i class="violet fas fa-key"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="detail">
                                    <p class="detail-subtitle">{{ __('dashboard_label.permissions') }}</p>
                                    <span class="number">{{ $permissionCount }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="icon-big text-center">
                                    <i class="orange fas fa-folder"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="detail">
                                    <p class="detail-subtitle">{{ __('dashboard_label.permission_groups') }}</p>
                                    <span class="number">{{ $permissionGroupCount }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
