<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Order;

class Dispatch extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }

    public function items()
    {
        return $this->order->items();
    }

    public function customer()
    {
        return $this->order->customer();
    }

    public function address()
    {
        return $this->order->address();
    }
}
