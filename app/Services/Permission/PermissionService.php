<?php 


namespace App\Services\Permission;

use App\Repositories\Permission\IPermissionRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionService extends BaseService implements IPermissionService
{
    public function __construct(private IPermissionRepository $permissionRepository)
    {
        parent::__construct($permissionRepository);
    }

    /**
     * Retrieve user data for DataTables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissionData(): JsonResponse
    {
        try {
            $data = $this->permissionRepository->findAll([]);
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

    /**
     * Create multiple permissions.
     *
     * @param array $permissions
     * @param string $groupName
     * @return array
     */
    public function createPermissions(array $permissions, string $groupName): array
    {
        $result = [
            'success' => true,
            'message' => 'Permissions added successfully.',
        ];

        DB::beginTransaction();

        try {
            foreach ($permissions as $value) {
                $exists = $this->permissionRepository->existsByNameAndGroup($value, $groupName);

                if ($exists) {
                    throw new Exception("Permission '{$value}' already exists.");
                }

                $data = [
                    'name' => $value,
                    'group_name' => $groupName,
                ];

                Permission::create($data);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            $result = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }

        return $result;
    }

     /**
     * Update a permission.
     *
     * @param string $id
     * @param array $data
     * @return array
     */
    public function updatePermission(string $id, array $data): array
    {
        $result = [
            'success' => true,
            'message' => 'Permission updated successfully.',
        ];

        DB::beginTransaction();

        try {
            $exists = $this->permissionRepository->existsByNameAndGroup($data['name'], $data['group_name'], $id);

            if ($exists) {
                throw new Exception("Permission '{$data['name']}' already exists in this group.");
            }

            $this->update(['id' => $id], $data);

            DB::commit();

        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                DB::rollBack();
                $result = [
                    'success' => false,
                    'message' => 'A permission with this name and guard already exists.',
                ];
            } else {
                DB::rollBack();
                $result = [
                    'success' => false,
                    'message' => 'Database error occurred.',
                ];
            }
        } catch (Exception $e) {
            DB::rollBack();

            $result = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }

        return $result;
    }
}
