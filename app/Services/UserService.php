<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Interfaces\IUserService;
use Illuminate\Http\Response;

class UserService extends BaseService implements IUserService
{
    private IUserRepository $repo;

    public function __construct(IUserRepository $userRepository)
    {
        $this->repo = $userRepository;
    }

    public function authenticate(string $email, string $password): User
    {
        abort_if(!auth()->attempt(["email" => $email, "password" => $password]), Response::HTTP_UNAUTHORIZED, "email or password incorrect.");
        $user = auth()->user();
        abort_if(!$user->hasVerifiedEmail(), Response::HTTP_UNAUTHORIZED, "email address is not verified.");
        return $user;
    }

    public function createAccessToken(User $user, string $tokenName = 'web-token', array $scopes = []): string
    {
        return $user->createToken($tokenName, $scopes)->plainTextToken;
    }

    public function getAuthUser(): User
    {
        $user =  auth()->user();
        abort_unless($user, Response::HTTP_NOT_FOUND, "User not found.");
        return $user;
    }

    public function register(array $data): User
    {
        $user = $this->repo->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
        abort_unless($user, Response::HTTP_NOT_FOUND, "User not found.");
        return $user;
    }
}
