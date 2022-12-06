<?php

namespace App\Repositories;

use App\Http\Resources\Auth\AuthResource;
use App\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements AuthRepositoryInterface
{
    use ApiResponser;

    public function register($request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        return $this->successResponse($user, 'User created successfully', Response::HTTP_CREATED);
    }

    public function login($request)
    {
        $credentials = $request->only(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return $this->errorResponse('Invalid Credentials', Response::HTTP_UNAUTHORIZED);
        }
        $user = new AuthResource(auth()->user());
        return $this->successResponse($user, 'Logged In successfully', Response::HTTP_OK);
    }

    public function logout()
    {
        try {
            $user = Auth::user()->token();
            $user->revoke();
            return $this->successResponse('Logged out successfully', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function me()
    {
    }
}
