<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];


    protected $quared = [];



    protected $fillable = ['category', 'images', 'sort'];

    // public function product(){
    //     return $this->hasMany(Product::class)->where('out_of_stock', 0);
    // }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'category_id')->orderBy('sort', 'ASC');
    }

}
