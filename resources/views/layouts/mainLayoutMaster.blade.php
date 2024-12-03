<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title') | Laravel 11 Admin Panel CRUD Builder and API Builder</title>
    <meta name="robots" content="index, follow">
    <meta content="Laravel-11 CRUD Builder is a powerful admin panel and API generator with role-based access control, multi-language support, and automatic code generation." name="description">
    <meta content="Laravel CRUD Builder, Admin Panel Generator, API Builder, Role-Based Access Control, Permission Management, Multi-Language Support, Automatic Code Generation, Swagger Documentation, REST API, Laravel CMS, Laravel 11 Admin Panel" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('assets/img/logo.png') }}" type="image/x-icon">
    @include('layouts/sections/styles')
</head>

<body>


    @yield('layoutContent')



    @include('layouts/sections/scripts')
</body>

</html>
