<?php

namespace App\Repository;

use App\Models\User;

interface IUserRepository
{
    public function createUser(string $name, string $email, string $password): User;

    public function getUserByEmail(string $email): ?User;
    public function getById(int $id): ?User;
}
