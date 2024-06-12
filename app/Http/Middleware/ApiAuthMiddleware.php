<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin;
use App\Models\CollegeStudent;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $authenticate = true;
        if (!$token) {
            $authenticate = false;
        }

        $admin = Admin::where('token', $token)->where('is_active', true)->first();
        $student = Student::where('token', $token)->first();
        // dd($student);
        // dd($admin);
        if (!$admin && !$student) {

            $authenticate = false;
        } else {
            if ($admin) {
                Auth::guard('admin')->login($admin);
            } else {
                Auth::guard('student')->login($student);
            }
        }
        if ($authenticate) {
            return $next($request);
        } else {
            return response()->json([
                "errors" => [
                    "message" => [
                        "unauthorized"
                    ]
                ]
            ])->setStatusCode(401);
        }
    }
}
