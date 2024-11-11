<?php 


namespace App\Services\PermissionGroup;

use App\Repositories\PermissionGroup\IPermissionGroupRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class PermissionGroupService extends BaseService implements IPermissionGroupService
{
    public function __construct(private IPermissionGroupRepository $permissiongroupRepository)
    {
        parent::__construct($permissiongroupRepository);
    }

    /**
     * Retrieve user data for DataTables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissionGroupData(): JsonResponse
    {
        try {
            $data = $this->permissiongroupRepository->findAll([]);
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
