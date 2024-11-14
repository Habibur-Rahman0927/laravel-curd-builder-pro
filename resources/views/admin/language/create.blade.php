@extends('layouts/layout')

@section('title', 'Create Language')

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
                <h1 class="page-title">Create Language</h1>
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

                    <form method="POST" action="{{ route('language.store') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                    value="{{ old('name') }}" required>
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
                                <input type="text" name="code"
                                    class="form-control @error('code') is-invalid @enderror" id="code"
                                    value="{{ old('code') }}" required>
                                @error('code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Language Translation Fields -->
                        @foreach ($combinedLanguageKeys as $section => $translations)
                            <h3>{{ ucfirst($section) }} Translations</h3> <!-- First level header -->
                            <div class="row">
                                @foreach ($translations as $key => $value)
                                    @if (is_array($value))
                                        <!-- Second level header -->
                                        <h4 class="mt-4">{{ ucfirst($key) }}</h4>
                                        <div class="row">
                                            @foreach ($value as $subKey => $subValue)
                                                @if (is_array($subValue))
                                                    <!-- Third level header -->
                                                    <h5 class="mt-3">{{ ucfirst($subKey) }}</h5>
                                                    <div class="row">
                                                        @foreach ($subValue as $subSubKey => $subSubValue)
                                                            <!-- Fourth level input -->
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-3">
                                                                    <label
                                                                        for="key_{{ $section . '_' . $key . '_' . $subKey . '_' . $subSubKey }}">{{ ucfirst($subSubKey) }}</label>
                                                                    <input type="text" class="form-control"
                                                                        name="translations[{{ $section }}][{{ $key }}][{{ $subKey }}][{{ $subSubKey }}]"
                                                                        id="key_{{ $section . '_' . $key . '_' . $subKey . '_' . $subSubKey }}"
                                                                        value="{{ old('translations.' . $section . '.' . $key . '.' . $subKey . '.' . $subSubKey, $subSubValue) }}">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <!-- Third level input -->
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label
                                                                for="key_{{ $section . '_' . $key . '_' . $subKey }}">{{ ucfirst($subKey) }}</label>
                                                            <input type="text" class="form-control"
                                                                name="translations[{{ $section }}][{{ $key }}][{{ $subKey }}]"
                                                                id="key_{{ $section . '_' . $key . '_' . $subKey }}"
                                                                value="{{ old('translations.' . $section . '.' . $key . '.' . $subKey, $subValue) }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <!-- Second level input -->
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="key_{{ $section . '_' . $key }}">{{ ucfirst($key) }}</label>
                                                <input type="text" class="form-control"
                                                    name="translations[{{ $section }}][{{ $key }}]"
                                                    id="key_{{ $section . '_' . $key }}"
                                                    value="{{ old('translations.' . $section . '.' . $key, $value) }}">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <hr> <!-- Divider between each section -->
                        @endforeach

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
