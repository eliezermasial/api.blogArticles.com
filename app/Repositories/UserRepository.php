<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Create a new class instance.
     */
    public function create(array $data): User
    {
        return User::create($data);
    }
}
