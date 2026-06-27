<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find a user by email address.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Create a new user with a hashed password.
     */
    public function createUser(array $data): User;
}
