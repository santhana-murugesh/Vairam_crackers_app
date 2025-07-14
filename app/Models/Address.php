<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Dispatch;

class Address extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class, );
    }

    public function dispatch()
    {
        return $this->hasMany(Dispatch::class);
    }
    public function ComboPackOrder()
    {
        return $this->hasMany(ComboPackOrder::class);
    }
}
