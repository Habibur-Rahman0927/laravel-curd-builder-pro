@extends('layouts/layout')

@section('title', __('{{ name }}_module.create_list_edit.create_page_title'))

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
                <div class="page-pretitle">{{ __('{{ name }}_module.create_list_edit.{{ name }}') }}</div>
                <h1 class="page-title">{{ __('{{ name }}_module.create_list_edit.create_page_title') }}</h1>
            </div>
        </div>
        <div class="row">
                <div class="card shadow w-100">
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
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form class="" method="POST" action="{{ route('{{ name }}.store') }}">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="name" class="form-label">{{ __('{{ name }}_module.field_label.name') }}</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}">
                                    @error('name')
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
                                        <input class="form-check-input @error('status') is-invalid @enderror" type="checkbox" role="switch" id="statusToggle" name="status" value="1" {{ old('status') == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="statusToggle">
                                            <span class="text-success">{{ __('standard_curd_common_label.active') }}</span> / <span class="text-danger">{{ __('standard_curd_common_label.inactive') }}</span>
                                        </label>
                                    </div>
                                    @error('status')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            

                            <div class="row">
                                <div class="col-md-12 text-end"> 
                                    <a href="{{ route('{{ name }}.index') }}" class="btn btn-secondary me-2">{{ __('standard_curd_common_label.cancel') }}</a>
                                    <button type="submit" class="btn btn-primary">{{ __('standard_curd_common_label.submit') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    </div>

@endsection
