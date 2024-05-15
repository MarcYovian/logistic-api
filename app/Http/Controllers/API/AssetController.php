<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\AssetCollection;
use App\Models\Asset;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AssetCreateRequest;
use App\Http\Requests\AssetUpdateRequest;
use App\Http\Resources\AssetResource;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AssetCollection
    {
        $page = $request->input("page", 1);
        $size = $request->input("size", 10);

        $assets = Asset::query()->paginate(perPage: $size, page: $page);

        return new AssetCollection($assets);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AssetCreateRequest $request): JsonResponse
    {
        // dd($request);
        $data = $request->validated();
        $admin = Auth::guard('admin')->user();

        $asset = new Asset($data);
        $asset->admin_id = $admin->id;
        $asset->save();

        return (new AssetResource($asset))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): AssetResource
    {
        // dd($id);
        $admin = Auth::guard('admin')->user();
        // dd($admin);
        $asset = Asset::where('id', $id)->where('admin_id', $admin->id)->first();

        if (!$asset) {
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

        return new AssetResource($asset);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AssetUpdateRequest $request, int $id): AssetResource
    {
        $admin = Auth::guard('admin')->user();

        $asset = Asset::where('id', $id)->where('admin_id', $admin->id)->first();

        if (!$asset) {
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

        $data = $request->validated();
        $asset->fill($data);
        $asset->save();
        // dd($asset);

        return new AssetResource($asset);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $admin = Auth::guard('admin')->user();

        $asset = Asset::where('id', $id)->where('admin_id', $admin->id)->first();
        if (!$asset) {
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

        $asset->delete();
        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }
}
