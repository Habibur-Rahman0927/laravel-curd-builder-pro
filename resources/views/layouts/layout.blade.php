@extends('layouts/mainLayoutMaster')
@section('layoutContent')
<div class="wrapper">
    @include('layouts/sections/sidebar/sidebar')
    <div class="active" id="body">
        @include('layouts/sections/navbar/navbar')
        @yield('content')

    </div>

</div>
@endsection
