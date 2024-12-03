<?php 


namespace App\Repositories\User;

use App\Repositories\IBaseRepository;
use Illuminate\Database\Eloquent\Collection;

interface IUserRepository extends IBaseRepository
{
    public function getUserWithRole(array $conditions = [], array $columns = ['*']): Collection|array;
}
