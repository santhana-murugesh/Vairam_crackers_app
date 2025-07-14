<?php

namespace App\Models;

use App\Models\BillOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Address;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['name','mobile_number','whatsapp_number'];

    public function address(){
        return $this->belongsTo(Address::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function billOrders(){
        return $this->hasMany(BillOrder::class);
    }
    public function ComboPackOrder(){
        return $this->hasMany(ComboPackOrder::class);
    }

}
