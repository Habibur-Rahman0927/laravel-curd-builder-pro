@extends('layouts/layout')

@section('title', __('permission_group_module.create_list_edit.edit_page_title'))

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
                <div class="page-pretitle">{{ __('permission_group_module.create_list_edit.permission_group') }}</div>
                <h1 class="page-title">{{ __('permission_group_module.create_list_edit.edit_page_title') }}</h1>
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

                    <form class="" method="POST" action="{{ route('permissiongroup.update', $data->id) }}">
                        @csrf
                        @method('PUT') <!-- Use PUT method for updating -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label">{{ __('permission_group_module.field_label.name') }}</label>
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

                        <div class="row">
                            <div class="col-md-12 text-end">
                                <a href="{{ route('permissiongroup.index') }}" class="btn btn-danger me-2">{{ __('standard_curd_common_label.back') }}</a>
                                <button type="submit" class="btn add-btn">{{ __('standard_curd_common_label.update') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
