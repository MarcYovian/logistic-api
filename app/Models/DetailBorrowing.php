<?php

namespace App\Models;

use App\Enums\StatusBorrowing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBorrowing extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'detail_borrowings';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'borrowing_id',
        'asset_id',
        'admin_id',
        'start_date',
        'end_date',
        'description',
        'num',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => StatusBorrowing::class,
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function asset()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function admin()
    {
        return $this->belongsTo(Borrowing::class);
    }
}
