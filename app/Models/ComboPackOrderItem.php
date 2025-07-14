<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\ComboPack;


class ComboPackOrderItem extends Model
{
    use HasFactory;


    protected $fillable = ['combopackorder_id', 'product_id','combo_pack_id', 'quantity', 'total'];


    // protected $with = ['product'];

    protected $with = ['product', 'combo_pack'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function combo_pack()
    {
        return $this->belongsTo(ComboPack::class, 'combo_pack_id');
    }

    public function comboPackOrder()
{
    return $this->belongsTo(ComboPackOrder::class, 'combo_pack_order_id');
}


   
}
