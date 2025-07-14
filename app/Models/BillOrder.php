<?php

namespace App\Models;

use App\Models\BillItem;
use App\Models\Address;
use App\Models\Customer;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function billitems()
    {
        return $this->hasMany(BillItem::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
    
}
