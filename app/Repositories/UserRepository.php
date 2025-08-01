<?php

namespace App\Repositories;

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
    
    public function createUser(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): User|null
    {
        return User::where("email", $email)->first();
    }
}
