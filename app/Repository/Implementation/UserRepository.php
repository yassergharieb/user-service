<?php

namespace App\Repository\Implementation;

use App\Models\User;
use App\Repository\IUserRepository;
use Illuminate\Support\Facades\Hash;

class UserRepository implements IUserRepository
{
    public function createUser(string $name, string $email, string $password): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
    }

    public function getUserByEmail($email): ?User
    {
        return User::getUserByEmail($email);
    }

    public function getById(int $id): ?User
    {
        return User::find($id);
    }
}
