<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Permission\IPermissionService;
use App\Services\PermissionGroup\IPermissionGroupService;
use App\Services\Role\IRoleService;
use App\Services\User\IUserService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
            private readonly IUserService $userService,
            private readonly IRoleService $roleService,
            private readonly IPermissionService $permissionService,
            private readonly IPermissionGroupService $permissionGroupService,
    )
    {

    }
    public function index(Request $request)
    {
        $userCount = $this->userService->findAll()->count();
        $roleCount = $this->roleService->findAll()->count();
        $permissionCount = $this->permissionService->findAll()->count();
        $permissionGroupCount = $this->permissionGroupService->findAll()->count();
        return view('admin.dashboard.index')->with([
            'userCount' => $userCount,
            'roleCount' => $roleCount,
            'permissionCount' => $permissionCount,
            'permissionGroupCount' => $permissionGroupCount,
        ]);
    }
}
