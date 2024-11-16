<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\V1\UserService;
use App\Http\Traits\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Api\V1\CreateUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;

/**
 * Class UserController
 * Handles HTTP requests related to user operations and delegates business logic to the UserService.
 */
class UserController extends Controller
{
    use ApiResponses;

    /**
     * @var UserService The service handling user data processes
     */
    protected $userService;

    /**
     * UserController constructor.
     * Injects UserService to manage user-related business logic.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of all users.
     * Retrieves all users from the database using UserService and returns them as a resource collection.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $users = $this->userService->findAll();
        return UserResource::collection($users);
    }

    /**
     * Display the specified user.
     * Retrieves a single user by ID using UserService and returns it as a user resource.
     *
     * @param int $id User ID
     * @return UserResource
     */
    public function show(int $id)
    {
        try {
            $user = $this->userService->findOne($id);
            return $this->success("User found", new UserResource($user),  200);
        } catch (\Exception $e) {
            return $this->error("User not found", 404);
        }
    }

    /**
     * Store a newly created user in storage.
     * Validates the request, creates a user using UserService, and returns the user as a resource.
     *
     * @param CreateUserRequest $request
     * @return UserResource
     */
    public function store(CreateUserRequest $request)
    {
        try {
            $data = $request->validated();
            $user = $this->userService->create($data);
            return $this->success("User created successfully", new UserResource($user),   201);
        } catch (ValidationException $e) {
            return $this->error($e->errors(), 422);
        }
    }

    /**
     * Update the specified user in storage.
     * Validates the request, updates the user using UserService, and returns the updated user as a resource.
     *
     * @param UpdateUserRequest $request
     * @param int $id User ID
     * @return UserResource
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        try {
            $data = $request->validated();
            $user = $this->userService->update($id, $data);
            return $this->success("User updated successfully", new UserResource($user),  200);
        } catch (\Exception $e) {
            return $this->error("User not found", 404);
        }
    }

    /**
     * Remove the specified user from storage.
     * Deletes a user by ID using UserService and returns a success response if successful.
     *
     * @param int $id User ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if ($this->userService->delete($id)) {
            return $this->ok("User deleted successfully");
        }
        return $this->error("User not found", 404);
    }
}
