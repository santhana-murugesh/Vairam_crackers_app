<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'price',
        'quantity',
        'total',
    ];


    public function billingProduct()
    {
        return $this->belongsTo(BillingProduct::class, 'product_id'); // Make sure 'product_id' is the correct foreign key
    }
}

