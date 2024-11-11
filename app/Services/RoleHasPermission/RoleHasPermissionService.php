<?php 


namespace App\Services\RoleHasPermission;

use App\Repositories\RoleHasPermission\IRoleHasPermissionRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleHasPermissionService implements IRoleHasPermissionService
{
    /**
     * Retrieve user data for DataTables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoleHasPermissionData(): JsonResponse
    {
        try {
            $data = Role::with('permissions')->get();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    return $data->id;
                })->toJson();
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not retrieve data. Please try again later.',
            ]);
        }
    }
}
