<?php 


namespace App\Repositories\Permission;

use App\Repositories\BaseRepository;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends BaseRepository implements IPermissionRepository
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

     /**
     * Check if a permission exists by name and group, optionally excluding a specific ID.
     *
     * @param string $name
     * @param string $groupName
     * @param string|null $excludeId
     * @return bool
     */
    public function existsByNameAndGroup(string $name, string $groupName, ?string $excludeId = null): bool
    {
        $query = Permission::where('name', $name)
            ->where('group_name', $groupName);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
