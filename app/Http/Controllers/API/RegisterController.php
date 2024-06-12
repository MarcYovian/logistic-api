<?php

namespace App\Http\Controllers\API;

use App\Enums\AdminType;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AdminRegisterRequest $request): JsonResponse
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
}
