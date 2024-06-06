<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowingResource extends JsonResource
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
            'ukm_name' => $this->ukm_name,
            'event_name' => $this->event_name,
            'num_of_participants' => $this->num_of_participants,
            'event_date' => $this->event_date,
            'student' => $this->Student,
            'detail_borrowings' => $this->DetailBorrowings
        ];
    }
}
