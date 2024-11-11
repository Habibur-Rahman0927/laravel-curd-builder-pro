<?php 


namespace App\Http\Controllers\Admin;

use App\Enums\PermissionEnum;
use App\Enums\PermissionGroupEnum;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Services\Permission\IPermissionService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Services\PermissionGroup\IPermissionGroupService;
use Illuminate\Http\JsonResponse;
use Exception;

class PermissionController extends Controller
{

    public function __construct(
                                private IPermissionService $permissionService,
                                private readonly IPermissionGroupService $permissionGroupService
    )
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
     public function index(): View
    {
        return view('admin.permission.index')->with([]);
    }

    /**
     * Get permission data for DataTables.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDatatables(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            return $this->permissionService->getPermissionData();
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
        $permissions = Helpers::getCustomNameValueFromEnum(PermissionEnum::class);
        $permissionGroups = $this->permissionGroupService->findAll();
        return view('admin.permission.create')->with([
            'permissions' => $permissions,
            'permissionGroups' => $permissionGroups,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PermissionRequest $request
     * @return RedirectResponse
     */
    public function store(CreatePermissionRequest $request): RedirectResponse
    {
        try {
            $permissions = $request->input('permissions', []);

            if (empty($permissions) || !is_array($permissions)) {
                return redirect()->back()->with('error', 'No permissions were provided.');
            }

            $result = $this->permissionService->createPermissions($permissions, $request->group_name);

            if ($result['success']) {
                return redirect()->back()->with('success', $result['message']);
            } else {
                return redirect()->back()->with('error', $result['message']);
            }

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.'. $e->getMessage());
        }
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
            $permissions = Helpers::getCustomNameValueFromEnum(PermissionEnum::class);
            $permissionGroups = $this->permissionGroupService->findAll();
            $response = $this->permissionService->findById($id);
            return view('admin.permission.edit')->with([
                'data' => $response,
                'permissions' => $permissions,
                'permissionGroups' => $permissionGroups,
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving the resource.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePermissionRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(UpdatePermissionRequest $request, string $id): RedirectResponse
    {
        try {
            $data = $request->only(['name', 'group_name']);
            $result = $this->permissionService->updatePermission($id, $data);
    
            if ($result['success']) {
                return redirect()->back()->with('success', $result['message']);
            } else {
                return redirect()->back()->with('error', $result['message']);
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
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
            $data = $this->permissionService->deleteById($id);

            if ($data) {
                return response()->json([
                    'message' => 'Permission deleted successfully',
                    'status_code' => ResponseAlias::HTTP_OK,
                    'data' => []
                ], ResponseAlias::HTTP_OK);
            }

            return response()->json([
                'message' => 'Permission is not deleted successfully',
                'status_code' => ResponseAlias::HTTP_BAD_REQUEST,
                'data' => []
            ], ResponseAlias::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while trying to delete.',
                'status_code' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                'data' => []
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
