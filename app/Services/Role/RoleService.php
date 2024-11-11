<?php 


namespace App\Services\Role;

use App\Repositories\Role\IRoleRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class RoleService extends BaseService implements IRoleService
{
    public function __construct(private IRoleRepository $roleRepository)
    {
        parent::__construct($roleRepository);
    }

    /**
     * Retrieve user data for DataTables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoleData(): JsonResponse
    {
        try {
            $data = $this->roleRepository->findAll([]);
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
