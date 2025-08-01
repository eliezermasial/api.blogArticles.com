<?php

namespace App\Contracts\Interface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /*
        public function findByRole(string $roleName);
    */
    public function findByEmail(string $email);

    public function createUser(array $data);
}
