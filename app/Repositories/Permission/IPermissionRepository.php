<?php 


namespace App\Repositories\Permission;

use App\Repositories\IBaseRepository;

interface IPermissionRepository extends IBaseRepository
{
    /**
     * Check if a permission exists by name and group, optionally excluding a specific ID.
     *
     * @param string $name
     * @param string $groupName
     * @param string|null $excludeId
     * @return bool
     */
    public function existsByNameAndGroup(string $name, string $groupName, ?string $excludeId = null): bool;
}
