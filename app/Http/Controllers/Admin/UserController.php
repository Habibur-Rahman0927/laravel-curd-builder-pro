<?php 


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\User\IUserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function __construct(private IUserService $userService)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
     public function index(): View
    {
        return view('admin.user.index')->with([]);
    }

    /**
     * Get user data for DataTables.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDatatables(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            return $this->userService->getUserData();
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
        return view('admin.user.create')->with([
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return RedirectResponse
     */
    public function store(CreateUserRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $response = $this->userService->create($request->all());

            if (Role::where('id', $request->role_id)->exists()) {
                $role = Role::find($request->role_id);
                $response->assignRole($role->name);
            } else {
                return redirect()->back()->with('error', 'Role does not exist.');
            }

            DB::commit();
            return redirect()->back()->with('success', 'User added successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong. Please try again. ' . $e->getMessage());
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
            $response = $this->userService->findById($id);
            $roles = Role::all();
            return view('admin.user.edit')->with([
                'data' => $response,
                'roles' => $roles
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving the resource.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $request, string $id): RedirectResponse
    {
        try {
            $data = $request->except(['_token', '_method']);

            if (!empty($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }

            $this->userService->update(['id' => $id], $data);

            $user = $this->userService->findById($id);
            if (Role::where('id', $request->role_id)->exists()) {
                $user->roles()->detach();
                $role = Role::find($request->role_id);
                $user->assignRole($role->name);
            } else {
                return redirect()->back()->with('error', 'Role does not exist.');
            }

            return redirect()->back()->with('success', 'User updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while updating.');
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
            $data = $this->userService->deleteById($id);

            if ($data) {
                return response()->json([
                    'message' => 'User deleted successfully',
                    'status_code' => ResponseAlias::HTTP_OK,
                    'data' => []
                ], ResponseAlias::HTTP_OK);
            }

            return response()->json([
                'message' => 'User is not deleted successfully',
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
