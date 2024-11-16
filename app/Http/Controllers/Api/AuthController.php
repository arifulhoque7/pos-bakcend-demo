<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Traits\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LogingUserRequest;
use App\Http\Requests\Api\LoginUserRequest;
use App\Http\Requests\Api\RegisterUserRequest;
use App\Services\V1\UserService;

/**
 * Class AuthController
 * Handles authentication-related HTTP requests, delegating business logic to UserService.
 */
class AuthController extends Controller
{
    use ApiResponses;

    /**
     * @var UserService The service handling user authentication processes
     */
    protected $userService;

    /**
     * AuthController constructor.
     * Injects UserService to manage authentication-related business logic.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Register a new user.
     * Validates the request, registers the user using UserService, and returns the user data with a token.
     *
     * @param RegisterUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterUserRequest $request)
    {
        $result = $this->userService->register($request->validated());
        return $this->success('User created successfully', $result, 201);
    }

    /**
     * Authenticate a user.
     * Validates the request, attempts to log in using UserService, and returns a token on success.
     *
     * @param LogingUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LogingUserRequest $request)
    {
        $result = $this->userService->login($request->validated());
        if (!$result) {
            return $this->error('Invalid credentials', 401);
        }
        return $this->ok('Authenticated', $result);
    }

    /**
     * Log out the current user.
     * Uses UserService to handle the logout process and returns a success response.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->userService->logout($request->user());
        return $this->ok('Logged out successfully');
    }
}
