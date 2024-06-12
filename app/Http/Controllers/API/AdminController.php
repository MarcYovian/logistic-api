<?php

namespace App\Http\Controllers\API;

use App\Enums\AdminType;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AdminResource;
use App\Http\Requests\UpdateIsActiveAdminRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        $page = $request->input("page", 1);
        $size = $request->input("size", 10);
        $loggedInAdminId = Auth::guard('admin')->user()->id;
        $admins = Admin::query()->where('id', '!=', $loggedInAdminId)->orderByDesc('updated_at')->paginate(perPage: $size, page: $page);

        return AdminResource::collection($admins);
    }

    public function update(Request $request, $id)
    {
        $data = Admin::findOrFail($id);

        if (isset($data)) {
            $data->type = $request['type'];
            $data->save();
        }

        return response()->json([
            'data' => true,
        ])->setStatusCode(200);
    }

    public function updateIsActive(UpdateIsActiveAdminRequest $request, $id)
    {
        $data = $request->validated();
        // Mendapatkan admin yang sedang login
        $loggedInAdmin = Auth::guard('admin')->user();

        // Memastikan hanya superuser yang dapat melakukan perubahan
        if ($loggedInAdmin->type !== AdminType::SUPERUSER->value) {
            return response()->json([
                'errors' => [
                    'message' => ['You do not have permission to perform this action.']
                ]
            ], Response::HTTP_FORBIDDEN);
        }
        // Mendapatkan admin yang akan diperbarui
        $admin = Admin::findOrFail($id);
        $admin->is_active = $data['is_active'];
        $admin->save();

        return response()->json([
            'message' => 'Admin status updated successfully.',
            'admin' => $admin
        ]);
    }
    public function show(Request $request): AdminResource
    {
        $admin = Auth::guard('admin')->user();

        return new AdminResource($admin);
    }

    public function destroy($id)
    {
        $current = Auth::guard('admin')->user();

        if ($current->type != AdminType::SUPERUSER->value) {
            throw new HttpResponseException(
                response()->json([
                    'errors' => [
                        "message" => [
                            "forbidden"
                        ]
                    ]
                ])->setStatusCode(403)
            );
        }

        $admin = Admin::findOrFail($id);
        if (!$admin) {
            throw new HttpResponseException(
                response()->json([
                    'errors' => [
                        "message" => [
                            "not found"
                        ]
                    ]
                ])->setStatusCode(404)
            );
        }
        // dd($admin);

        $admin->delete();
        return response()->json([
            'data' => true
        ])->setStatusCode(200);
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
