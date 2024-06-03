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
