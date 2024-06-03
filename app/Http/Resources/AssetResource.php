<?php

namespace App\Http\Resources;

use App\Models\BorrowingDate;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Exceptions\HttpResponseException;

class AssetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'original_image' => $this->original_image,
            'encrypted_image' => $this->encrypted_image,
            'url_image' => $this->url_image,
            'borrowing_dates' => $this->whenNotNull($this->borrowingDates->setVisible(['start_date', 'end_date'])),
            'admin' => $this->admin
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}
