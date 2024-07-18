<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;

class UserRepository extends BaseRepository implements IUserRepository
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUserById(int $userId): ?User
    {
        return $this->user->find($userId);
    }
}
