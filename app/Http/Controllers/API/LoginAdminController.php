<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Resources\AdminResource;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAdminController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AdminLoginRequest $request)
    {
        $data = $request->validated();
        if (!Auth()->guard('admin')->attempt($data)) {
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
        $token = $admin->createToken('token')->plainTextToken;
        $admin->setRememberToken($token);
        $admin->save();

        return new AdminResource($admin);
    }
}
