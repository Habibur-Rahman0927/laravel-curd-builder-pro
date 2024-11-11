<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BaseService implements IBaseService
{
    private object $modelRepository;

    public function __construct($modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }

    /**
     * Create a new resource.
     *
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes): mixed
    {
        return $this->modelRepository->create($attributes);
    }

    /**
     * Find a resource by its ID.
     *
     * @param int|string $id
     * @param string[] $columns
     * @return mixed
     */
    public function findById(int|string $id, $columns = ['*']): mixed
    {
        return $this->modelRepository->findById($id, $columns);
    }

    /**
     * Find a resource by its conditions.
     *
     * @param array $conditions
     * @param string[] $columns
     * @return mixed
     */
    public function findFirstByConditions(array $conditions, array $columns = ['*']): mixed
    {
        return $this->modelRepository->findFirstByConditions($conditions, $columns);
    }

    /**
     * Get all resources with optional filters.
     *
     * @param array $conditions
     * @param string[] $columns
     * @return Collection|array
     */
    public function findAll(array $conditions = [], array $columns = ['*']): Collection|array
    {
        return $this->modelRepository->findAll($conditions, $columns);
    }

    /**
     * Get resources with pagination based on filters.
     *
     * @param int $limit
     * @param array $conditions
     * @param string[] $columns
     * @return mixed
     */
    public function findAllWithPagination(array $conditions = [], array $columns = ['*'], int $limit): mixed
    {
        return $this->modelRepository->findAllWithPagination($conditions, $columns, $limit);
    }

    /**
     * Get a limited set of resources with optional filters.
     *
     * @param int $limit
     * @param array $conditions
     * @param string[] $columns
     * @return mixed
     */
    public function findByLimit(int $limit, array $conditions = [], array $columns = ['*']): mixed
    {
        return $this->modelRepository->findByLimit($limit, $conditions, $columns);
    }

    /**
     * Update a resource by its ID.
     *
     * @param array $conditions
     * @param array $attributes
     * @return mixed
     */
    public function update(array $conditions, array $attributes): mixed
    {
        return $this->modelRepository->update($conditions, $attributes);
    }

    /**
     * Update or create a resource.
     *
     * @param array $conditions
     * @param array $attributes
     * @return mixed
     */
    public function updateOrCreate(array $conditions, array $attributes): mixed
    {
        return $this->modelRepository->updateOrCreate($conditions, $attributes);
    }

    /**
     * Delete a resource by its ID.
     *
     * @param int|string $id
     * @return mixed
     */
    public function deleteById(int|string $id): mixed
    {
        return $this->modelRepository->deleteById($id);
    }

    /**
     * Delete multiple resources based on conditions.
     *
     * @param array $conditions
     * @return int The number of deleted records.
     */
    public function deleteAll(array $conditions): int
    {
        return $this->modelRepository->deleteAll($conditions);
    }

    /**
     * Get a list of resources for DataTables.
     *
     * @param Request $request
     * @return JsonResponse|array
     */
    public function dataTableList(Request $request): JsonResponse|array
    {
        return []; // Implement DataTables logic here.
    }
}
