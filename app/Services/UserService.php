<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Interfaces\IUserService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

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
        abort_if($user->status == 'inactive', Response::HTTP_UNAUTHORIZED, "Inactive user.");
        abort_if($user->status == 'blocked', Response::HTTP_UNAUTHORIZED, "Blocked user.");
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
        $user = $this->repo->create($data);
        abort_unless($user, Response::HTTP_NOT_FOUND, "User not found.");
        $user->sendEmailVerificationNotification();
        return $user;
    }

    public function verifyEmail(int $id, string $hash): void
    {
        $user = $this->repo->getUserById($id);
        abort_if(!hash_equals((string) $hash, sha1($user->getEmailForVerification())), Response::HTTP_FORBIDDEN, 'Invalid or expired verification link.');
        abort_if($user->hasVerifiedEmail(), Response::HTTP_OK, 'Email already verified.');
        $user->markEmailAsVerified();
    }

    public function emailResend(string $email): void
    {
        $user = $this->repo->getUserByEmail($email);
        abort_unless($user, Response::HTTP_NOT_FOUND, "User not found.");
        abort_if($user->hasVerifiedEmail(), Response::HTTP_OK, 'Email already verified.');
        $user->sendEmailVerificationNotification();
    }

    public function sendPasswordResetLink(string $email): void
    {
        $status = Password::sendResetLink(["email" => $email]);
        abort_if($status !== Password::RESET_LINK_SENT, Response::HTTP_BAD_REQUEST, 'Unable to send reset link.');
    }

    public function resetPassword(array $data): void
    {
        $status = Password::reset($data, function ($user, $password) {
            $user->password = $password;
            $user->save();
        });
        abort_if($status !== Password::PASSWORD_RESET, Response::HTTP_BAD_REQUEST, 'Password reset failed.');
    }
}
