<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use App\Contracts\Interface\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function findByEmail(string $email): User|null
    {
        return $this->model->where("email", $email)->first();
    }

    public function getOrCreateRole(string $roleName)
    {
        return Role::firstOrCreate(['name' => $roleName ?? 'user']);
    }
}
