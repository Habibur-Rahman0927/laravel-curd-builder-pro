@extends('layouts/layout')

@section('title', 'Create User')

@section('page-style')
    @vite([])
@endsection

@section('page-script')
    @vite([])
@endsection

@section('content')
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12 page-header mb-2">
                    <div class="page-pretitle">User</div>
                    <h1 class="page-title">Create User</h1>
                </div>
            </div>
            <div class="row">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        {{-- Show Validation Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
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

                        <form class="" method="POST" action="{{ route('user.store') }}">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
        
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Select User Type</label>
                                    <select class="form-select @error('user_type') is-invalid @enderror" name="user_type">
                                        <option value="" selected>-- Select User Type --</option>
                                        <option value="1" {{ old('user_type') == 0 ? 'selected' : '' }}>Defualt</option>
                                        <option value="2" {{ old('user_type') == 1 ? 'selected' : '' }}>Super Admin</option>
                                        <option value="3" {{ old('user_type') == 2 ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @error('user_type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
        
                                <div class="col-md-6">
                                    <label class="form-label">Select User Role</label>
                                    <select class="form-select @error('role_id') is-invalid @enderror" name="role_id" required>
                                        <option value="" selected>-- Select User Role --</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
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
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" role="switch" id="statusToggle" name="is_active" value="1" {{ old('is_active') == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="statusToggle">
                                            <span class="text-success">Active</span> / <span class="text-danger">Inactive</span>
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
                                    <a href="{{ route('user.index') }}" class="btn btn-danger me-2">Cancel</a>
                                    <button type="submit" class="btn add-btn">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
