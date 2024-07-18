<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testValidLogin()
    {
        $user = $this->makeUser()->create();

        $payload = [
            'email' => $user->email,
            'password' => 'password',
        ];
        $expectedResponse = [
            'accessToken',
            'user' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at'
            ],
            'message',
        ];

        $this->postJson(route('login'), $payload)
            ->assertSuccessful()
            ->assertJsonStructure($expectedResponse);
    }

    public function testInvalidLogin()
    {
        $user = $this->makeUser()->create();
        $payload = [
            'email' => $user->email,
            'password' => '123',
        ];
        $expectedResponse = [
            'error'
        ];

        $this->postJson(route('login'), $payload)
            ->assertUnauthorized()
            ->assertJsonStructure($expectedResponse)
            ->assertSeeText('email or password incorrect.');
    }

    public function testLoginValidation()
    {
        $errors = [
            'email',
            'password'
        ];

        $this->postJson(route('login'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors($errors);
    }

    public function testLoginEmailUnverified()
    {
        $user = $this->makeUser()->unverified()->create();

        $payload = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $expectedResponse = [
            'error'
        ];

        $this->postJson(route('login'), $payload)
            ->assertUnauthorized()
            ->assertJsonStructure($expectedResponse)
            ->assertSeeText('email address is not verified.');
    }
}
