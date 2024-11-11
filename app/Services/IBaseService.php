<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface IBaseService
{
    /**
     * Create a new resource.
     *
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes): mixed;

    /**
     * Find a resource by its ID.
     *
     * @param int|string $id
     * @param string[] $columns
     * @return mixed
     */
    public function findById(int|string $id, array $columns = ['*']): mixed;

    /**
     * Find a resource by its conditions.
     *
     * @param array $conditions
     * @param string[] $columns
     * @return mixed
     */
    public function findFirstByConditions(array $conditions, array $columns = ['*']): mixed;

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
     * @return mixed
     */
    public function findAllWithPagination(array $conditions = [], array $columns = ['*'], int $limit): mixed;

    /**
     * Get a limited set of resources with optional filters.
     *
     * @param int $limit
     * @param array $conditions
     * @param string[] $columns
     * @return mixed
     */
    public function findByLimit(int $limit, array $conditions = [], array $columns = ['*']): mixed;

    /**
     * Update a resource by its ID.
     *
     * @param array $conditions
     * @param array $attributes
     * @return mixed
     */
    public function update(array $conditions, array $attributes): mixed;

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
     * @return mixed
     */
    public function deleteById(int|string $id): mixed;

    /**
     * Delete multiple resources based on conditions.
     *
     * @param array $conditions
     * @return int The number of deleted records.
     */
    public function deleteAll(array $conditions): int;

    /**
     * Get a list of resources for DataTables.
     *
     * @param Request $request
     * @return JsonResponse|array
     */
    public function dataTableList(Request $request): JsonResponse|array;
}
