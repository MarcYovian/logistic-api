<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin;
use App\Models\CollegeStudent;
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

        $admin = Admin::where('token', $token)->first();
        $collegeStudent = CollegeStudent::where('remember_token', $token)->first();
        if (!$admin && !$collegeStudent) {
            $authenticate = false;
        } else {
            if ($admin) {
                Auth::guard('admin')->login($admin);
                // dd(Auth::guard('admin')->user());
            } else {
                Auth::guard('collegeStudent')->login($collegeStudent);
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
