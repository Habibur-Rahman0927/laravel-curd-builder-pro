<?php 


namespace App\Services\Permission;
use App\Services\IBaseService;
use Illuminate\Http\Request;

interface IPermissionService extends IBaseService
{
    public function getPermissionData();

    public function createPermissions(array $permissions, string $groupName): array;

    public function updatePermission(string $id, array $data): array;
}
