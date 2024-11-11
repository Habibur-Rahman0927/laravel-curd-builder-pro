<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements IBaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new resource.
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * Find a resource by its ID.
     *
     * @param int|string $id
     * @param array $columns
     * @return Model|null
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById(int|string $id, array $columns = ['*']): ?Model
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * Find a resource by its conditions.
     *
     * @param array $conditions
     * @param array $columns
     * @return Model|null
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findFirstByConditions(array $conditions, array $columns = ['*']): ?Model
    {
        return $this->model->where($conditions)->select($columns)->first();
    }

    /**
     * Get all resources with optional filters.
     *
     * @param array $conditions
     * @param array $columns
     * @return Collection|array
     */
    public function findAll(array $conditions = [], array $columns = ['*']): Collection|array
    {
        return $this->model->where($conditions)->get($columns);
    }

    /**
     * Get resources with pagination based on filters.
     *
     * @param int $limit
     * @param array $conditions
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function findAllWithPagination(array $conditions = [], array $columns = ['*'], int $limit): LengthAwarePaginator
    {
        return $this->model->where($conditions)->paginate($limit);
    }

    /**
     * Get a limited set of resources with optional filters.
     *
     * @param int $limit
     * @param array $conditions
     * @param array $columns
     * @return Collection
     */
    public function findByLimit(int $limit, array $conditions = [], array $columns = ['*']): Collection
    {
        return $this->model->where($conditions)->limit($limit)->get($columns);
    }

    /**
     * Update a resource by its ID.
     *
     * @param array $conditions
     * @param array $attributes
     * @return int
     */
    public function update(array $conditions, array $attributes): int
    {
        return $this->model->where($conditions)->update($attributes);
    }

    /**
     * Update or create a resource.
     *
     * @param array $conditions
     * @param array $attributes
     * @return Model
     */
    public function updateOrCreate(array $conditions, array $attributes): Model
    {
        return $this->model->updateOrCreate($conditions, $attributes);
    }

    /**
     * Delete a resource by its ID.
     *
     * @param int|string $id
     * @return int
     */
    public function deleteById(int|string $id): int
    {
        return $this->model->destroy($id);
    }
    
    /**
     * Delete multiple resources based on conditions.
     *
     * @param array $conditions
     * @return int The number of deleted records.
     */
    public function deleteAll(array $conditions): int
    {
        return $this->model->where($conditions)->delete();
    }
}
