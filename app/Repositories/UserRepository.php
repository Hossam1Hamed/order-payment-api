<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Find a user by email address.
     */
    public function findByEmail(string $email): ?User
    {
        /** @var User|null */
        return $this->model->where('email', $email)->first();
    }

    /**
     * Create a new user with a hashed password.
     */
    public function createUser(array $data): User
    {
        /** @var User */
        return $this->model->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
