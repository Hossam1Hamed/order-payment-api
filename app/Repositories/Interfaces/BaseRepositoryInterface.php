<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    /**
     * Get all records.
     */
    public function all(): Collection;

    /**
     * Find a record by its primary key.
     */
    public function find(int $id): ?Model;

    /**
     * Find a record by a specific field and value.
     */
    public function findBy(string $field, mixed $value): ?Model;

    /**
     * Create a new record.
     */
    public function create(array $data): Model;

    /**
     * Update an existing record by its primary key.
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a record by its primary key.
     */
    public function delete(int $id): bool;
}
