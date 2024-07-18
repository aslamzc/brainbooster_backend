<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\Interfaces\IUserService;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Tests\TestCase;
use Throwable;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;
    private IUserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = app(IUserService::class);
    }

    public function testAuthenticate(): void
    {
        $user = $this->makeUser()->create();
        $resultUser = $this->userService->authenticate($user->email, 'password');
        $this->assertEquals($resultUser->toArray(), $user->toArray());
    }

    public function testAuthenticateFaild(): void
    {
        try {
            $user = $this->makeUser()->create();
            $this->userService->authenticate($user->email, '123');
        } catch (Throwable $e) {
            $this->assertEquals(Response::HTTP_UNAUTHORIZED, $e->getStatusCode());
            $this->assertEquals('email or password incorrect.', $e->getMessage());
        }
    }

    public function testAuthenticateUnverified(): void
    {
        try {
            $user = $this->makeUser()->unverified()->create();
            $this->userService->authenticate($user->email, 'password');
        } catch (Throwable $e) {
            $this->assertEquals(Response::HTTP_UNAUTHORIZED, $e->getStatusCode());
            $this->assertEquals('email address is not verified.', $e->getMessage());
        }
    }

    public function testCreateAccessToken(): void
    {
        $user = $this->makeUser()->create();
        $token = $this->userService->createAccessToken($user);
        $this->assertNotEmpty($token);
    }

    public function testGetAuthUser(): void
    {
        $user = Sanctum::actingAs($this->makeUser()->create());
        $resultUser = $this->userService->getAuthUser();
        $this->assertEquals($resultUser,  $user);
    }
}
