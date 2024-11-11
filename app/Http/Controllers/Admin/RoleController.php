<?php 


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Role\IRoleService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\JsonResponse;
use Exception;

class RoleController extends Controller
{

    public function __construct(private IRoleService $roleService)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
     public function index(): View
    {
        return view('admin.role.index')->with([]);
    }

    /**
     * Get role data for DataTables.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDatatables(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            return $this->roleService->getRoleData();
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
        return view('admin.role.create')->with([]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleRequest $request
     * @return RedirectResponse
     */
    public function store(CreateRoleRequest $request): RedirectResponse
    {
        try {
            $response = $this->roleService->create($request->all());

            if ($response) {
                return redirect()->back()->with('success', 'Role added successfully.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
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
            $response = $this->roleService->findById($id);

            return view('admin.role.edit')->with([
                'data' => $response,
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving the resource.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRoleRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(UpdateRoleRequest $request, string $id): RedirectResponse
    {
        try {
            $data = $request->except(['_token', '_method']);
            $this->roleService->update(['id' => $id], $data);

            return redirect()->back()->with('success', 'Role updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while updating.' . $e->getMessage());
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
            $data = $this->roleService->deleteById($id);

            if ($data) {
                return response()->json([
                    'message' => 'Role deleted successfully',
                    'status_code' => ResponseAlias::HTTP_OK,
                    'data' => []
                ], ResponseAlias::HTTP_OK);
            }

            return response()->json([
                'message' => 'Role is not deleted successfully',
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
