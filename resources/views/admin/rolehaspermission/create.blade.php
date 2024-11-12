@extends('layouts/layout')

@section('title', 'Create Role Has Permission')

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
                <h1 class="page-title">Create Role Has Permission</h1>
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

                    <form class="" method="POST" action="{{ route('rolehaspermission.store') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Role Name</h6>
                            </div>
                            <div class="form-group col-sm-9 text-secondary">
                                <select class="form-select" name="role_id" id="">
                                    <option value=""> -- Select Role -- </option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="" id="permission_all">
                            <label for="permission_all" class="form-check-label">Permission All</label>
                        </div>
                        <hr>
                        @foreach ($permission_groups as $group => $permissions)
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input group-checkbox" type="checkbox" name=""
                                            id="group_{{ $group }}">
                                        <label for="group_{{ $group }}"
                                            class="form-check-label">{{ \App\Helpers\Helpers::convertToNormalText($group) }}</label>
                                    </div>
                                </div>
                                <div class="col-9">
                                    @foreach ($permissions as $permission)
                                        <div class="form-check form-switch">
                                            <input class="form-check-input permission-checkbox" type="checkbox"
                                                value="{{ $permission->id }}" name="permission[]"
                                                id="permission_{{ $permission->id }}">
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
                                <a href="{{ route('rolehaspermission.index') }}" class="btn btn-danger me-2">Cancel</a>
                                <button type="submit" class="btn add-btn">Submit</button>
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
            // When the "Permission All" checkbox is clicked
            $("#permission_all").on('click', function() {
                var isChecked = $(this).prop("checked");
                // Find all the permission checkboxes
                $(".permission-checkbox").prop("checked", isChecked);

                // Find all the group checkboxes
                $(".group-checkbox").prop("checked", isChecked);
            });

            // When a group checkbox is clicked
            $(".group-checkbox").on('click', function() {
                var isChecked = $(this).prop("checked");
                // Find all the permission checkboxes inside the same parent div
                var permissionCheckboxes = $(this).closest('.row').find(".permission-checkbox");
                permissionCheckboxes.prop("checked", isChecked);

                // Check if any permission checkbox in the group is unchecked
                var isAnyUnchecked = permissionCheckboxes.filter(':not(:checked)').length > 0;
                // Update the "Permission All" checkbox state based on the permission checkboxes
                $("#permission_all").prop("checked", !isAnyUnchecked);

                // Check if all group checkboxes are checked, then check the "Permission All" checkbox
                var allGroupsChecked = $(".group-checkbox").length === $(".group-checkbox:checked").length;
                $("#permission_all").prop("checked", allGroupsChecked);
            });

            // When a permission checkbox is clicked
            $(".permission-checkbox").on('click', function() {
                var groupCheckbox = $(this).closest('.row').find(".group-checkbox");
                // Check if any permission checkbox in the group is unchecked
                var isAnyUnchecked = $(this).closest('.row').find(".permission-checkbox").filter(
                    ':not(:checked)').length > 0;
                // Update the group checkbox state based on the permission checkboxes
                groupCheckbox.prop("checked", !isAnyUnchecked);

                // Check if all permission checkboxes are checked, then check the group checkbox
                var permissionCheckboxes = $(this).closest('.row').find(".permission-checkbox");
                var allChecked = permissionCheckboxes.length === permissionCheckboxes.filter(':checked')
                    .length;
                groupCheckbox.prop("checked", allChecked);

                // Check if all group checkboxes are checked, then check the "Permission All" checkbox
                var allGroupsChecked = $(".group-checkbox").length === $(".group-checkbox:checked").length;
                $("#permission_all").prop("checked", allGroupsChecked);
            });
        });
    </script>
@endpush
