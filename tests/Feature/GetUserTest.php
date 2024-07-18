<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetUserTest extends TestCase
{
    use RefreshDatabase;

    public function testValidUserResponse()
    {
        $expectedResponse = [
            'message',
            'user' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at'
            ]
        ];

        Sanctum::actingAs($this->makeUser()->create());
        $this->getJson(route('user'))
            ->assertSuccessful()
            ->assertJsonStructure($expectedResponse);
    }
}
