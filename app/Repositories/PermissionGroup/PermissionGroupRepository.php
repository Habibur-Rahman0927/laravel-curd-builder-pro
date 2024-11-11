<?php 


namespace App\Repositories\PermissionGroup;

use App\Models\PermissionGroup;
use App\Repositories\BaseRepository;

class PermissionGroupRepository extends BaseRepository implements IPermissionGroupRepository
{
    public function __construct(PermissionGroup $model)
    {
        parent::__construct($model);
    }
}
