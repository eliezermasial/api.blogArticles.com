<?php

namespace App\Contracts\Interface;

interface BaseRepositoryInterface
{
    public function get();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
