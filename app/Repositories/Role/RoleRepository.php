<?php 


namespace App\Repositories\Role;

use Spatie\Permission\Models\Role;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository implements IRoleRepository
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }
}
