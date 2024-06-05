<?php

namespace App\Http\Controllers\API;

use App\Enums\StatusBorrowing;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Resources\BorrowingColection;
use App\Http\Resources\BorrowingResource;
use App\Models\Borrowing;
use App\Models\DetailBorrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
                'admin_id' => $asset['admin_id'],
                'start_date' => $asset['start_date'],
                'end_date' => $asset['end_date'],
                'description' => $asset['description'],
                'num' => $asset['num'],
                'status' => 'PENDING',
            ]);
        }

        return response()->json([
            'message' => 'Borrowing created successfully',
            'borrowing' => $borrowing->load('detailBorrowings')
        ], 201);
        // dd("hello");

        // dd($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
