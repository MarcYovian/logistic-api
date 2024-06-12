<?php

namespace App\Http\Controllers\API;

use App\Enums\StatusBorrowing;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveRequest;
use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Resources\BorrowingColection;
use App\Http\Resources\BorrowingResource;
use App\Models\Borrowing;
use App\Models\DetailBorrowing;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrowing = Borrowing::query()->orderByDesc('created_at')->get();
        // dd($borrowing);
        return BorrowingResource::collection($borrowing);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBorrowingRequest $request)
    {
        // dd($request->all());
        $data = $request->validated();

        $borrowing = Borrowing::create([
            'student_id' => $request->student_id,
            'ukm_name' => $request->ukm_name,
            'event_name' => $request->event_name,
            'num_of_participants' => $request->num_of_participants,
            'event_date' => $request->event_date,
        ]);

        foreach ($request->assets as $asset) {
            DetailBorrowing::create([
                'borrowing_id' => $borrowing->id,
                'asset_id' => $asset['asset_id'],
                'admin_id' => $asset['admin_id'] ?? null,
                'start_date' => $asset['start_date'],
                'end_date' => $asset['end_date'],
                'description' => $asset['description'],
                'num' => $asset['num'],
                'status' => 'PENDING',
            ]);
        }

        return (new BorrowingResource($borrowing))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $borrowing = Borrowing::find($id);

        if (!$borrowing) {
            throw new HttpResponseException(
                response()->json([
                    'errors' => [
                        'message' => [
                            'not found'
                        ],
                    ]
                ])
            );
        }

        return new BorrowingResource($borrowing);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    }

    public function approve(ApproveRequest $request, $id)
    {
        $validated = $request->validated();
        // dd($validated);
        $borrowing = Borrowing::findOrFail($id);

        foreach ($validated['details'] as $detail) {
            $detailBorrowing = DetailBorrowing::findOrFail($detail['id']);
            $detailBorrowing->update([
                'status' => $detail['status'],
            ]);
        }

        $allApproved = $borrowing->detailBorrowings()->where('status', '!=', 'APPROVED')->count() === 0;

        $adminId = Auth::guard('admin')->user()->id;
        if ($allApproved) {
            $borrowing->update([
                'status' => 'APPROVED',
                'approved_by' => $adminId,
                'approved_at' => now(),
            ]);
        } else {
            // Jika ada yang ditolak, ubah status peminjaman menjadi REJECTED
            if ($borrowing->detailBorrowings()->where('status', 'REJECTED')->count() > 0) {
                $borrowing->update([
                    'status' => 'REJECTED',
                    'approved_by' => $adminId,
                    'approved_at' => now(),
                ]);
            }
        }

        return response()->json([
            'message' => 'Borrowing details updated successfully',
            'borrowing' => new BorrowingResource($borrowing),
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
