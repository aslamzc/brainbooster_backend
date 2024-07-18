<?php

namespace App\Services\Interfaces;

use App\Models\User;

interface IUserService extends IService
{
    public function authenticate(string $email, string $password): User;
    public function createAccessToken(User $user, string $tokenName = '', array $scopes = []): string;
    public function getAuthUser(): User;
}
