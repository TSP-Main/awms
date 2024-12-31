<?php

namespace App\Http\Controllers\Api;

use App\Classes\StatusEnum;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends BaseController
{
    public function register(RegisterUserRequest $request)
    {
        try {
            DB::beginTransaction();
            if ($request->role === StatusEnum::ADMIN) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone_number_1' => $request->phone_number_1,
                    'phone_number_2' => $request->phone_number_2,
                    'address' => $request->address
                ]);
                $user->assignRole(StatusEnum::ADMIN);
                DB::commit();
                return $this->sendResponse($user, 'User register successfully');
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number_1' => $request->phone_number_1,
                'phone_number_2' => $request->phone_number_2,
                'address' => $request->address
            ]);
            $user->assignRole(StatusEnum::DEALER);
            DB::commit();
            return $this->sendResponse($user, 'User register successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating employee user: ' . $e->getMessage());
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            // Check if user exists and verify password
            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->sendError('Invalid credentials');
            }

            // Create a new token
            $token = $user->createToken(User::AUTH_TOKEN)->accessToken;

            return $this->sendResponse([
                'access_token' => $token,
                'user' => $user->name,
                'email' => $user->email,
            ], 'Successfully logged in');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function logout()
    {
        if (Auth::user()) {
            $user = Auth::user()->token();
            $user->revoke();

            return $this->sendResponse([], 'User logged out successfully.');
        }
        return $this->sendError(['error' => ['Invalid operation.']]);
    }

}
