@extends('layouts/layout')

@section('title', __('user_module.create_list_edit.edit_page_title'))

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
                <div class="page-pretitle">{{ __('user_module.create_list_edit.user') }}</div>
                <h1 class="page-title">{{ __('user_module.create_list_edit.edit_page_title') }}</h1>
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

                    <form class="" method="POST" action="{{ route('user.update', $data->id) }}">
                        @csrf
                        @method('PUT') <!-- Use PUT method for updating -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label">{{ __('user_module.field_label.name') }}</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                    value="{{ old('name', $data->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="email" class="form-label">{{ __('user_module.field_label.email') }}</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                    value="{{ old('email', $data->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="password" class="form-label">{{ __('user_module.field_label.password') }} {{ __('user_module.field_label.password_option') }}</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('user_module.field_label.select_user_type') }}</label>
                                <select class="form-select @error('user_type') is-invalid @enderror" name="user_type">
                                    <option value="" selected> -- {{ __('user_module.field_label.select_user_type') }} -- </option>
                                    <option value="1" {{ $data->user_type == 1 ? 'selected' : '' }}>{{ __('user_module.field_label.select_user_type_options.defualt') }}</option>
                                    <option value="2" {{ $data->user_type == 2 ? 'selected' : '' }}>{{ __('user_module.field_label.select_user_type_options.super_admin') }}
                                    </option>
                                    <option value="3" {{ $data->user_type == 3 ? 'selected' : '' }}>{{ __('user_module.field_label.select_user_type_options.admin') }}</option>
                                </select>
                                @error('user_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('user_module.field_label.select_user_role') }}</label>
                                <select class="form-select @error('role_id') is-invalid @enderror" name="role_id">
                                    <option value="" selected> -- {{ __('user_module.field_label.select_user_role') }} -- </option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ $data->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">{{ __('standard_curd_common_label.status') }}</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox"
                                        role="switch" id="statusToggle" name="is_active" value="1"
                                        {{ $data->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusToggle">
                                        <span class="text-success">{{ __('standard_curd_common_label.active') }}</span> / <span class="text-danger">{{ __('standard_curd_common_label.inactive') }}</span>
                                    </label>
                                </div>
                                @error('is_active')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-end">
                                <a href="{{ route('user.index') }}" class="btn btn-danger me-2">{{ __('standard_curd_common_label.back') }}</a>
                                <button type="submit" class="btn add-btn">{{ __('standard_curd_common_label.update') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
