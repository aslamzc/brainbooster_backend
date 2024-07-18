<?php

namespace Tests\Traits;

use App\Models\User;
use Database\Factories\UserFactory;

trait UserFactoriesTrait
{
    protected function makeUser(int $times = null): UserFactory
    {
        return User::factory()->count($times);
    }
}
