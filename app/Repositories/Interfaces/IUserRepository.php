<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface IUserRepository extends IRepository
{
    public function getUserById(int $userId): ?User;
}
