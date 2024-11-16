<?php

namespace App\Services\V1;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Get all users
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function findAll()
    {
        return User::orderBy('id', 'desc')->paginate(10);
    }

    /**
     * Get a user by id
     *
     * @param int $id
     * @return User
     */
    public function findOne(int $id)
    {
        return User::findOrFail($id);
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     */
    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password'] ?? '12345678');
        return User::create($data);
    }

    /**
     * Update a user
     *
     * @param int $id
     * @param array $data
     * @return User
     */
    public function update(int $id, array $data)
    {
        $user = $this->findOne($id);
        $user->update($data);
        return $user;
    }

    /**
     * Delete a user
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        return User::destroy($id) > 0;
    }

    /**
     * Register a new user
     *
     * @param array $data
     * @return User
     */
    public function register(array $data)
    {
        return $this->create($data);
    }

    /**
     * Authenticate a user
     *
     * @param array $data
     * @return string|null
     */
    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return null;
        }
        $user->token = $user->createToken('auth_token')->plainTextToken;
        return $user;
    }

    /**
     * Log out a user
     *
     * @param User $user
     */
    public function logout(User $user)
    {
        $user->tokens()->delete();
    }
}
