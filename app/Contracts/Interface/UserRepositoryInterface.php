<?php

namespace App\Contracts\Interface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail(string $email);
    public function getOrCreateRole(string $roleName);
}
