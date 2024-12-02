<?php 


namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements IUserRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all User resources with optional filters.
     *
     * @param array $conditions
     * @param array $columns
     * @return Collection|array
     */
    public function getUserWithRole(array $conditions = [], array $columns = ['*']): Collection|array
    {
        return $this->model
                    ->with(['role'])
                    ->where($conditions)->get($columns);
    }
}
