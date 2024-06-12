<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class CollegeStudentController extends Controller
{
    public function login(Request $request)
    {
        dd($request);
        // $data = $request->validated();

        if (!Auth::guard('student')->attempt($data)) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ], 401));
        }

        /** @var \App\Models\Student $student **/
        $student = Auth::guard('student')->user();
        $token = $student->createToken('token')->plainTextToken;
        $student->setRememberToken($token);
        $student->save();

        return new StudentResource($student);
    }

    public function logout(Request $request): JsonResponse
    {
        // dd(Auth::guard('admin')->user());
        /** @var \app\Models\Student $student **/
        $student = Auth::guard('student')->user();
        $student->token = null;
        $student->save();

        return response()->json([
            'data' => true,
        ])->setStatusCode(200);
    }
}
