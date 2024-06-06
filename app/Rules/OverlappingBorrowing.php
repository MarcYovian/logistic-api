<?php

namespace App\Rules;

use App\Models\DetailBorrowing;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OverlappingBorrowing implements ValidationRule
{
    protected $startDate;
    protected $endDate;
    protected $assetId;

    public function __construct($startDate, $endDate, $assetId)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->assetId = $assetId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }

    public function passes($attribute, $value)
    {
        // Check if there are any borrowings overlapping with the given dates
        return !DetailBorrowing::where('asset_id', $this->assetId)
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->startDate, $this->endDate])
                    ->orWhereBetween('end_date', [$this->startDate, $this->endDate]);
            })
            ->exists();
    }

    public function message()
    {
        return 'The asset is already borrowed within the specified date range.';
    }
}
