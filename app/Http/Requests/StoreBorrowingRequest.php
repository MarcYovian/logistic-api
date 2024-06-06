<?php

namespace App\Http\Requests;

use App\Models\DetailBorrowing;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


use Illuminate\Foundation\Http\FormRequest;

class StoreBorrowingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ($this->user('admin') != null) || $this->user('student') != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'ukm_name' => 'required|string|max:100',
            'event_name' => 'required|string|max:200',
            'num_of_participants' => 'nullable|integer',
            'event_date' => 'nullable|date',
            'assets' => 'required|array',
            'assets.*.admin_id' => 'nullable|exists:admins,id',
            'assets.*.start_date' => 'required|date',
            'assets.*.end_date' => 'required|date|after_or_equal:assets.*.start_date',
            'assets.*.asset_id' => [
                'required',
                'exists:assets,id',
            ],
            'assets.*.description' => 'nullable|string',
            'assets.*.num' => 'required|integer|min:1',

            // Aturan validasi kustom untuk memeriksa tumpang tindih peminjaman asset
            'assets.*' => function ($attribute, $value, $fail) {
                $assetId = $value['asset_id'];
                $startDate = $value['start_date'];
                $endDate = $value['end_date'];

                // Periksa apakah ada peminjaman untuk asset yang sama dalam rentang waktu yang diberikan
                $existingBorrowings = DetailBorrowing::where('asset_id', $assetId)
                    ->where(function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('start_date', [$startDate, $endDate])
                            ->orWhereBetween('end_date', [$startDate, $endDate])
                            ->orWhere(function ($query) use ($startDate, $endDate) {
                                $query->where('start_date', '<', $startDate)
                                    ->where('end_date', '>', $endDate);
                            });
                    })
                    ->exists();

                if ($existingBorrowings) {
                    $fail('The asset is already borrowed within the specified time range.');
                }
            },
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}
