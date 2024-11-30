@extends('layouts/layout')

@section('title',  __('profile.page_title') )

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
                <h2 class="page-title">{{ __('profile.profile') }}</h2>
                <br>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
               <div class="card">
                  <div class="card-body pt-3">
                     @if(session('success'))
                         <div class="alert alert-success alert-dismissible fade show" role="alert">
                             {{ session('success') }}
                             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                         </div>
                     @endif
                     @if($errors->any())
                         <div class="alert alert-danger alert-dismissible fade show" role="alert">
                             <ul>
                                 @foreach($errors->all() as $error)
                                     <li>{{ $error }}</li>
                                 @endforeach
                             </ul>
                             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                         </div>
                     @endif

                     <ul class="nav nav-tabs nav-tabs-bordered">
                        <li class="nav-item"> <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-edit">{{ __('profile.edit_profile') }}</button></li>
                     </ul>
                     <div class="tab-content pt-2">
                        <div class="tab-pane fade show active profile-edit pt-3" id="profile-edit">
                           <form action="{{ route('profile.update') }}" method="POST">
                             @csrf
                             @method('patch')
                              <div class="row mb-3">
                                 <label for="name" class="col-md-4 col-lg-3 col-form-label">{{ __('profile.name') }}</label>
                                 <div class="col-md-8 col-lg-9"> 
                                     <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', auth()->user()->name)}}" required>
                                     @error('name')
                                         <div class="invalid-feedback">
                                             {{ $message }}
                                         </div>
                                     @enderror
                                 </div>
                              </div>
                              <div class="row mb-3">
                                 <label for="email" class="col-md-4 col-lg-3 col-form-label">{{ __('profile.email') }}</label>
                                 <div class="col-md-8 col-lg-9"> 
                                     <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email', auth()->user()->email) }}" required>
                                     @error('email')
                                         <div class="invalid-feedback">
                                             {{ $message }}
                                         </div>
                                     @enderror
                                 </div>
                              </div>
                              <div class="text-center"> <button type="submit" class="btn add-btn">{{ __('profile.save_changes') }}</button></div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
               <div class="card">
                  <div class="card-body pt-3">
                     @if(session('password_success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('password_success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if($errors->getBag('passwordUpdate')->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul>
                                @foreach($errors->getBag('passwordUpdate')->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                     <ul class="nav nav-tabs nav-tabs-bordered">
                        <li class="nav-item"> <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-change-password">{{ __('profile.change_password') }}</button></li>
                     </ul>
                     <div class="tab-content pt-2">
                        <div class="tab-pane fade show active pt-3" id="profile-change-password">
                           <form action="{{ route('password.update') }}" method="POST">
                             @csrf
                             @method('put')
                              <div class="row mb-3">
                                 <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">{{ __('profile.current_password') }}</label>
                                 <div class="col-md-8 col-lg-9"> 
                                     <input name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" id="currentPassword" required>
                                     @error('current_password')
                                         <div class="invalid-feedback">
                                             {{ $message }}
                                         </div>
                                     @enderror
                                 </div>
                              </div>
                              <div class="row mb-3">
                                 <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">{{ __('profile.new_password') }}</label>
                                 <div class="col-md-8 col-lg-9"> 
                                     <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="newPassword" required>
                                     @error('password')
                                         <div class="invalid-feedback">
                                             {{ $message }}
                                         </div>
                                     @enderror
                                 </div>
                              </div>
                              <div class="row mb-3">
                                 <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">{{ __('profile.reenter_new_password') }}</label>
                                 <div class="col-md-8 col-lg-9"> 
                                     <input name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="renewPassword" required>
                                     @error('password_confirmation')
                                         <div class="invalid-feedback">
                                             {{ $message }}
                                         </div>
                                     @enderror
                                 </div>
                              </div>
                              <div class="text-center"> <button type="submit" class="btn add-btn">{{ __('profile.change_password') }}</button></div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
    </div>

@endsection
