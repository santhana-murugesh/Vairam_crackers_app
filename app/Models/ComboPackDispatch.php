<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboPackDispatch extends Model
{
    use HasFactory;

    protected $table = 'combopack_dispatches'; // Explicitly set the table name

    protected $fillable = [
        'combo_pack_order_id',
        'LR_number',
        'transport',
        'book_date',
        'delivery_date',
    ];

    public function combopackorder()
    {
        return $this->belongsTo(ComboPackOrder::class, 'combo_pack_order_id');
    }
}
