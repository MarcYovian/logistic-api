<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;

class CollegeStudentController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validated();

        if (!Auth::guard('student')->attempt($data)) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "username or password wrong"
                    ]
                ]
            ], 401));
        }

        /** @var \App\Models\Student $collegeStudent **/
        dd(Auth::guard('Student')->user()->setRememberToken(Str::uuid()->toString()));
    }
}
