<?php

namespace App\Http\Controllers\API;

use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AdminResource;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\AdminRegisterRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminController extends Controller
{
    public function register(AdminRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (Admin::where('username', $data['username'])->count() == 1) {
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => [
                        "username already registered"
                    ]
                ]
            ], 400));
        }

        $user = new Admin($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        return (new AdminResource($user))->response()->setStatusCode(201);
    }

    public function login(AdminLoginRequest $request): AdminResource
    {
        $data = $request->validated();

        if (!Auth::guard('admin')->attempt($data)) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ], 401));
        }

        /** @var \App\Models\Admin $admin **/
        $admin = Auth::guard('admin')->user();
        $admin->token = Str::uuid()->toString();
        $admin->save();

        return new AdminResource($admin);
    }

    public function show(Request $request): AdminResource
    {
        $admin = Auth::guard('admin')->user();

        return new AdminResource($admin);
    }

    public function logout(Request $request): JsonResponse
    {
        // dd(Auth::guard('admin')->user());
        /** @var \app\Models\Admin $admin **/
        $admin = Auth::guard('admin')->user();
        $admin->token = null;
        $admin->save();

        return response()->json([
            'data' => true,
        ])->setStatusCode(200);
    }
}
