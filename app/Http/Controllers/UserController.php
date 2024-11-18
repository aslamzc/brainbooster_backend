<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\Interfaces\IUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserController extends Controller
{
    private IUserService $service;

    public function __construct(IUserService $userService)
    {
        $this->service = $userService;
    }

    public function login(LoginRequest $request)
    {
        try {
            ['email' => $email, 'password' => $password] = $request->all();
            $user = $this->service->authenticate($email, $password);
            $response['accessToken'] = $this->service->createAccessToken($user);
            $response['user'] = $user;
            $response['message'] = "Success";
            return response($response);
        } catch (Throwable $e) {
            Log::info(__method__, ['message' => $e->getMessage()]);
            return response(["error" => $e->getMessage()], (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
    }

    public function getUser()
    {
        try {
            $response['user'] = $this->service->getAuthUser();
            $response['message'] = "Success";
            return response($response);
        } catch (Throwable $e) {
            Log::info(__method__, ['message' => $e->getMessage()]);
            return response(["error" => $e->getMessage()], (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $response['user'] = $this->service->register($request->all());
            $response['message'] = "Success";
            return response($response);
        } catch (Throwable $e) {
            Log::info(__method__, ['message' => $e->getMessage()]);
            return response(["error" => $e->getMessage()], (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
    }

    public function emailVerify($id, $hash)
    {
        try {
            $this->service->verifyEmail($id, $hash);
            $response['message'] = "Email verified successfully.";
            return response($response);
        } catch (Throwable $e) {
            Log::info(__method__, ['message' => $e->getMessage()]);
            return response(["error" => $e->getMessage()], (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
    }

    public function emailResend(Request $request)
    {
        try {
            $this->service->emailResend($request->email);
            $response['message'] = "Verification email resent.";
            return response($response);
        } catch (Throwable $e) {
            Log::info(__method__, ['message' => $e->getMessage()]);
            return response(["error" => $e->getMessage()], (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
    }

    public function passwordResetLink(ForgotPasswordRequest $request)
    {
        try {
            $this->service->sendPasswordResetLink($request->email);
            $response['message'] = "Password reset link sent.";
            return response($response);
        } catch (Throwable $e) {
            Log::info(__method__, ['message' => $e->getMessage()]);
            return response(["error" => $e->getMessage()], (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $this->service->resetPassword($request->only('email', 'password', 'password_confirmation', 'token'));
            $response['message'] = "Password reset successfully.";
            return response($response);
        } catch (Throwable $e) {
            Log::info(__method__, ['message' => $e->getMessage()]);
            return response(["error" => $e->getMessage()], (method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500);
        }
    }
}
