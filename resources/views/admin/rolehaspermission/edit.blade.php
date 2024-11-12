@extends('layouts/layout')

@section('title', 'Edit Role Has Permission')

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
                <div class="page-pretitle">Role Has Permission</div>
                <h1 class="page-title">Edit Role Has Permission</h1>
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

                    <form class="" method="POST" action="{{ route('rolehaspermission.update', $data->id) }}">
                        @csrf
                        @method('PUT') <!-- Use PUT method for updating -->
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Role Name</h6>
                            </div>
                            <div class="form-group col-sm-9 text-secondary">
                                <input type="text" name="name" class="form-control" placeholder="Enter Role name"
                                    value="{{ $data->name }}" />
                            </div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="" id="permission_all">
                            <label for="permission_all" class="form-check-label">Permission All</label>
                        </div>
                        <hr>
                        @foreach ($permission_groups as $group => $permissions)
                            <div class="row">
                                @php
                                    // Determine if all permissions in the group are checked
                                    $groupChecked = collect($permissions)->every(function ($permission) use ($data) {
                                        return $data->hasPermissionTo($permission->name);
                                    });
                                @endphp
                                <div class="col-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input group-checkbox" type="checkbox" name=""
                                            id="group_{{ $group }}" {{ $groupChecked ? 'checked' : '' }}>
                                        <label for="group_{{ $group }}"
                                            class="form-check-label">{{ \App\Helpers\Helpers::convertToNormalText($group) }}</label>
                                    </div>
                                </div>
                                <div class="col-9">
                                    @foreach ($permissions as $permission)
                                        <div class="form-check form-switch">
                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                value="{{ $permission->id }}" name="permission[]"
                                                id="permission_{{ $permission->id }}"
                                                {{ $data->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                            <label for="permission_{{ $permission->id }}"
                                                class="form-check-label">{{ \App\Helpers\Helpers::convertToNormalText($permission->name) }}</label>
                                        </div>
                                    @endforeach
                                    <br>
                                </div>
                            </div>
                        @endforeach

                        <div class="row">
                            <div class="col-md-12 text-end">
                                <a href="{{ route('rolehaspermission.index') }}" class="btn btn-danger me-2">Back</a>
                                <button type="submit" class="btn add-btn">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            function updatePermissionAllCheckbox() {
                var allGroupsChecked = $(".group-checkbox").length === $(".group-checkbox:checked").length;
                var allPermissionsChecked = $(".permission-checkbox").length === $(".permission-checkbox:checked")
                    .length;
                var isChecked = allGroupsChecked && allPermissionsChecked;
                $("#permission_all").prop("checked", isChecked);
            }

            $("#permission_all").on('click', function() {
                var isChecked = $(this).prop("checked");
                $(".permission-checkbox").prop("checked", isChecked);

                $(".group-checkbox").prop("checked", isChecked);
            });

            $(".group-checkbox").on('click', function() {
                var isChecked = $(this).prop("checked");
                var permissionCheckboxes = $(this).closest('.row').find(".permission-checkbox");
                permissionCheckboxes.prop("checked", isChecked);

                var allPermissionsChecked = permissionCheckboxes.length === permissionCheckboxes.filter(
                    ':checked').length;
                $(this).prop("checked", allPermissionsChecked);

                updatePermissionAllCheckbox();
            });

            $(".permission-checkbox").on('click', function() {
                var groupCheckbox = $(this).closest('.row').find(".group-checkbox");
                var allPermissionsChecked = groupCheckbox.length === groupCheckbox.filter(':checked')
                .length;
                groupCheckbox.prop("checked", allPermissionsChecked);
                updatePermissionAllCheckbox();
            });
            updatePermissionAllCheckbox();
        });
    </script>
@endpush
