<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\UserFactoriesTrait;

abstract class TestCase extends BaseTestCase
{
    use UserFactoriesTrait;
}
