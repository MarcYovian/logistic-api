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
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AssetCollection
    {
        $page = $request->input("page", 1);
        $size = $request->input("size", 10);

        $assets = Asset::query()->orderByDesc('updated_at')->paginate(perPage: $size, page: $page);

        return new AssetCollection($assets);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AssetCreateRequest $request): JsonResponse
    {
        // dd(Auth::guard('admin')->user());
        $data = $request->validated();
        $image = $request->file('image');
        if ($image) {
            $originalImage = $image->getClientOriginalName();
            $encryptedImage = $image->hashName();

            $image->store('public/assets-image');
            $url = Storage::url('public/assets-image/' . $encryptedImage);
            $urlImage = url($url);
        }
        $admin = Auth::guard('admin')->user();
        // dd($admin);
        $asset = new Asset($data);
        $asset->admin_id = $admin->id;
        if ($image) {
            $asset->original_image = $originalImage;
            $asset->encrypted_image = $encryptedImage;
            $asset->url_image = $urlImage;
        }

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
        $asset = Asset::where('id', $id)->first();

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
        $data = $request->validated();
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

        $image = $request->file('image');
        if ($image) {
            if ($asset->encrypted_image) {
                Storage::delete('public/assets-image/' . $asset->encrypted_image);
            }

            $originalImage = $image->getClientOriginalName();
            $encryptedImage = $image->hashName();
            $image->store('public/assets-image');
            $url = Storage::url('public/assets-image/' . $encryptedImage);
            $urlImage = url($url);

            $asset->original_image = $originalImage;
            $asset->encrypted_image = $encryptedImage;
            $asset->url_image = $urlImage;
        }

        $asset->fill($data);
        $asset->save();

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

    public function allAsset()
    {
        $assets = Asset::query()->orderByDesc('updated_at')->get();
        return new AssetCollection($assets);
    }
}
