
@extends('layouts/layout')

@section('title', 'Edit Language')

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
                <div class="page-pretitle">Language</div>
                <h1 class="page-title">Edit Language</h1>
            </div>
        </div>
        <div class="row">
            <div class="card shadow w-100">
                <div class="card-body">
                    <h5 class="card-title"></h5>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session("success"))
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

                    <form method="POST" action="{{ route('language.update', $data->id) }}">
                        @csrf
                        @method('PUT')
						<div class="row mb-3">
						    <div class="col-md-12">
						        <label for="name" class="form-label">Name</label>
						        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $data->name) }}" required>
						        @error('name')
						            <div class="invalid-feedback">
						                {{ $message }}
						            </div>
						        @enderror
						    </div>
						</div>
						<div class="row mb-3">
						    <div class="col-md-12">
						        <label for="code" class="form-label">Code</label>
						        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" id="code" value="{{ old('code', $data->code) }}" required>
						        @error('code')
						            <div class="invalid-feedback">
						                {{ $message }}
						            </div>
						        @enderror
						    </div>
						</div>

                        <div class="row">
                            <div class="col-md-12 text-end"> 
                                <a href="{{ route('language.index') }}" class="btn btn-danger me-2">Back</a>
                                <button type="submit" class="btn add-btn">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
            