<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentLoginRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function login(StudentLoginRequest $request)
    {
        $data = $request->validated();
        if (!Auth()->guard('student')->attempt($data)) {
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
    public function logout()
    {
        /** @var \app\Models\Student $student **/
        $student = Auth::guard('student')->user();
        // dd($student);
        $student->token = null;
        $student->save();

        return response()->json([
            'data' => true,
        ])->setStatusCode(200);
    }

    public function current()
    {
        $student = Auth::guard('student')->user();

        return new StudentResource($student);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();
        return StudentResource::collection($students);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::find($id);
        if (!$student) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "student not foud"
                    ]
                ]
            ], 404));
        }

        return new StudentResource($student);
    }
}
