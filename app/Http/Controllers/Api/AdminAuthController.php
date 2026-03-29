<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthLoginRequest;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * Issue an API token for an admin.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(AuthLoginRequest $request): JsonResponse
    {
        $email = $request->string('email')->toString();
        $password = $request->string('password')->toString();
        $deviceName = $request->string('device_name')->toString() ?: 'admin-api';

        $admin = Admin::where('email', $email)->first();

        if (! $admin || ! Hash::check($password, $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $admin->createToken($deviceName, ['admin'])->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'type' => 'admin',
            'admin' => $admin,
        ]);
    }
}
