<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface IBaseRepository
{
    /**
     * Create a new resource.
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * Find a resource by its ID.
     *
     * @param int|string $id
     * @param string[] $columns
     * @return Model
     */
    public function findById(int|string $id, array $columns = ['*']): ?Model;

    /**
     * Find a resource by its conditions.
     *
     * @param array $conditions
     * @param array $columns
     * @return Model|null
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findFirstByConditions(array $conditions, array $columns = ['*']): ?Model;

    /**
     * Get all resources with optional filters.
     *
     * @param array $conditions
     * @param string[] $columns
     * @return Collection|array
     */
    public function findAll(array $conditions = [], array $columns = ['*']): Collection|array;

    /**
     * Get resources with pagination based on filters.
     *
     * @param int $limit
     * @param array $conditions
     * @param string[] $columns
     * @return LengthAwarePaginator
     */
    public function findAllWithPagination(array $conditions = [], array $columns = ['*'],  int $limit): LengthAwarePaginator;

    /**
     * Get a limited set of resources with optional filters.
     *
     * @param int $limit
     * @param array $conditions
     * @param string[] $columns
     * @return Collection
     */
    public function findByLimit(int $limit, array $conditions = [], array $columns = ['*']): Collection;

    /**
     * Update a resource by its ID.
     *
     * @param array $conditions
     * @param array $attributes
     * @return int
     */
    public function update(array $conditions, array $attributes): int;

    /**
     * Update or create a resource.
     *
     * @param array $conditions
     * @param array $attributes
     * @return mixed
     */
    public function updateOrCreate(array $conditions, array $attributes): mixed;

    /**
     * Delete a resource by its ID.
     *
     * @param int|string $id
     * @return int
     */
    public function deleteById(int|string $id): int;
    
    /**
     * Delete multiple resources based on conditions.
     *
     * @param array $conditions
     * @return int The number of deleted records.
     */
    public function deleteAll(array $conditions): int;
}
