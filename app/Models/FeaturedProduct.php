<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeaturedProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'title',
        'description',
        'highlight_text',
        'featured_description',
        'icon',
        'image',
        'button_text',
        'button_url',
        'sort_order',
        'is_active',
        'background_color',
        'text_color',
        'show_price',
        'show_discount',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_price' => 'boolean',
        'show_discount' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    // Accessor to get display title (either custom title or product name)
    public function getDisplayTitleAttribute()
    {
        return $this->title ?: ($this->product ? $this->product->name : 'Featured Product');
    }

    // Accessor to get display description
    public function getDisplayDescriptionAttribute()
    {
        if ($this->featured_description) {
            return $this->featured_description;
        }
        
        if ($this->description) {
            return $this->description;
        }
        
        return $this->product ? $this->product->description : '';
    }

    // Accessor to get display image
    public function getDisplayImageAttribute()
    {
        if ($this->image) {
            return $this->image;
        }
        
        return $this->product ? $this->product->image : null;
    }
}
