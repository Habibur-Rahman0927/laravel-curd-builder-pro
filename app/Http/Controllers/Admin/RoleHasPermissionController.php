<?php 


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RoleHasPermission\IRoleHasPermissionService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CreateRoleHasPermissionRequest;
use App\Http\Requests\UpdateRoleHasPermissionRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Exception;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleHasPermissionController extends Controller
{

    public function __construct(private IRoleHasPermissionService $roleHasPermissionService)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
     public function index(): View
    {
        return view('admin.rolehaspermission.index')->with([]);
    }

    /**
     * Get roleHasPermission data for DataTables.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDatatables(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            return $this->roleHasPermissionService->getRoleHasPermissionData();
        }
        return response()->json([
            'success' => false,
            'message' => 'Invalid request.',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $permission_groups = User::getPermissionGroups();
        return view('admin.rolehaspermission.create')->with([
            'roles' => $roles,
            'permissions' => $permissions,
            'permission_groups' => $permission_groups,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleHasPermissionRequest $request
     * @return RedirectResponse
     */
    public function store(CreateRoleHasPermissionRequest $request): RedirectResponse
    {
        try {
            $role = Role::findOrFail($request->role_id);
            $permissions = Permission::whereIn('id', $request->input('permission', []))
                                ->pluck('id')
                                ->toArray();
            $role->syncPermissions($permissions);

            if (!empty($permissions)) {
                return redirect()->back()->with('success', __('role_has_permission_module.create_list_edit.role_has_permission') . __('standard_curd_common_label.success'));
            } else {
                return redirect()->back()->with('error', __('role_has_permission_module.response.no_permission'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('standard_curd_common_label.error'));
        }

        return redirect()->back()->with('error', __('standard_curd_common_label.error'));
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return View
     */
    public function show(string $id) // : View
    {
        // You can add logic to fetch and return data for the specific resource here.
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return View
     */
    public function edit(string $id): View
    {
        try {
            $role = Role::findOrFail($id);
            $roles = Role::all();
            $permissions = Permission::all();
            $permission_groups = User::getPermissionGroups();

            return view('admin.rolehaspermission.edit')->with([
                'data' => $role,
                'roles' => $roles,
                'permissions' => $permissions,
                'permission_groups' => $permission_groups,
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('standard_curd_common_label.error'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRoleHasPermissionRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(UpdateRoleHasPermissionRequest $request, string $id): RedirectResponse
    {
        try {

            $role = Role::findOrFail($id);
            $permissions = Permission::whereIn('id', $request->input('permission', []))
                              ->pluck('id')
                              ->toArray();
            $role->syncPermissions($permissions);

            return redirect()->back()->with('success', __('role_has_permission_module.create_list_edit.role_has_permission') . __('standard_curd_common_label.update_success'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('standard_curd_common_label.error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $data = $this->roleHasPermissionService->deleteById($id);

            if ($data) {
                return response()->json([
                    'message' => __('role_has_permission_module.create_list_edit.role_has_permission') . __('standard_curd_common_label.delete'),
                    'status_code' => ResponseAlias::HTTP_OK,
                    'data' => []
                ], ResponseAlias::HTTP_OK);
            }

            return response()->json([
                'message' => __('role_has_permission_module.create_list_edit.role_has_permission') . __('standard_curd_common_label.delete_is_not'),
                'status_code' => ResponseAlias::HTTP_BAD_REQUEST,
                'data' => []
            ], ResponseAlias::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response()->json([
                'message' => __('standard_curd_common_label.error'),
                'status_code' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'data' => []
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
