@extends('layouts/layout')

@section('title', __('permission_module.create_list_edit.edit_page_title'))

@section('page-style')
    @vite([])
@endsection

@section('page-script')
    @vite([])
@endsection

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12 page-header mb-2">
                <div class="page-pretitle">{{ __('permission_module.create_list_edit.permission') }}</div>
                <h1 class="page-title">{{ __('permission_module.create_list_edit.edit_page_title') }}</h1>
            </div>
        </div>
        <div class="row">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title"></h5>

                    {{-- Show Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form class="" method="POST" action="{{ route('permission.update', $data->id) }}">
                        @csrf
                        @method('PUT') <!-- Use PUT method for updating -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">{{ __('permission_module.field_label.permission_group_name') }}</label>
                                <select class="form-select @error('group_name') is-invalid @enderror" name="group_name">
                                    <option value="" selected> -- {{ __('permission_module.field_label.select_permission_group') }} -- </option>
                                    @foreach ($permissionGroups as $index => $value)
                                        <option value="{{ $value->name }}"
                                            {{ $data->group_name == $value->name ? 'selected' : '' }}>
                                            {{ ucfirst($value->name) }}</option>
                                    @endforeach
                                </select>
                                @error('group_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">{{ __('permission_module.field_label.permission_name') }}</label>
                                <select class="form-select @error('name') is-invalid @enderror" name="name">
                                    <option value="" selected> -- {{ __('permission_module.field_label.select_permission') }} -- </option>
                                    @foreach ($permissions as $value => $text)
                                        <option value="{{ $value }}" {{ $data->name == $value ? 'selected' : '' }}>
                                            {{ $text }}</option>
                                    @endforeach
                                </select>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-end">
                                <a href="{{ route('permission.index') }}" class="btn btn-danger me-2">{{ __('standard_curd_common_label.back') }}</a>
                                <button type="submit" class="btn add-btn">{{ __('standard_curd_common_label.update') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
