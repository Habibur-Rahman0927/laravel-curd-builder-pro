@extends('layouts/layout')

@section('title', 'Edit {{ name }}')

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
            <div class="page-pretitle">{{ name }}</div>
            <h1 class="page-title">Edit {{ name }}</h1>
        </div>
    </div>
    <div class="row">
        <div class="card shadow w-100">
            <div class="card-body">
                <h5 class="card-title"></h5>

                {{-- Show Validation Errors --}}
                {{-- @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif --}}
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

                <form class="" method="POST" action="{{ route('{{ name }}.update', $data->id) }}">
                    @csrf
                    @method('PUT') <!-- Use PUT method for updating -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $data->name) }}">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input @error('status') is-invalid @enderror" type="checkbox" role="switch" id="statusToggle" name="status" value="1" {{ $data->status ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusToggle">
                                    <span class="text-success">Active</span> / <span class="text-danger">Inactive</span>
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
                            <a href="{{ route('{{ name }}.index') }}" class="btn btn-secondary me-2">Back</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
