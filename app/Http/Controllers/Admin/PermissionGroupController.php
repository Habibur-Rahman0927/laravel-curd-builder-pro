<?php 


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PermissionGroup\IPermissionGroupService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CreatePermissionGroupRequest;
use App\Http\Requests\UpdatePermissionGroupRequest;
use Illuminate\Http\JsonResponse;
use Exception;

class PermissionGroupController extends Controller
{

    public function __construct(private IPermissionGroupService $permissionGroupService)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
     public function index(): View
    {
        return view('admin.permissiongroup.index')->with([]);
    }

    /**
     * Get permissionGroup data for DataTables.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDatatables(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            return $this->permissionGroupService->getPermissionGroupData();
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
        return view('admin.permissiongroup.create')->with([]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PermissionGroupRequest $request
     * @return RedirectResponse
     */
    public function store(CreatePermissionGroupRequest $request): RedirectResponse
    {
        try {
            $response = $this->permissionGroupService->create($request->all());

            if ($response) {
                return redirect()->back()->with('success', __('permission_group_module.create_list_edit.permission_group') . __('standard_curd_common_label.success'));
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
            $response = $this->permissionGroupService->findById($id);

            return view('admin.permissiongroup.edit')->with([
                'data' => $response,
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('standard_curd_common_label.error'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePermissionGroupRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(UpdatePermissionGroupRequest $request, string $id): RedirectResponse
    {
        try {
            $data = $request->except(['_token', '_method']);
            $this->permissionGroupService->update(['id' => $id], $data);

            return redirect()->back()->with('success', __('permission_group_module.create_list_edit.permission_group') . __('standard_curd_common_label.update_success'));
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
            $data = $this->permissionGroupService->deleteById($id);

            if ($data) {
                return response()->json([
                    'message' => __('permission_group_module.create_list_edit.permission_group') . __('standard_curd_common_label.delete'),
                    'status_code' => ResponseAlias::HTTP_OK,
                    'data' => []
                ], ResponseAlias::HTTP_OK);
            }

            return response()->json([
                'message' => __('permission_group_module.create_list_edit.permission_group') . __('standard_curd_common_label.delete_is_not'),
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
