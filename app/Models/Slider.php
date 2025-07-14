<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'content', 'button_text'];

    /**
     * Get the image URL with proper path
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Get the image format/extension
     */
    public function getImageFormatAttribute()
    {
        if (!$this->image) return null;
        
        $extension = pathinfo($this->image, PATHINFO_EXTENSION);
        return strtolower($extension);
    }

    /**
     * Check if the image is a modern format (WebP, AVIF)
     */
    public function isModernImageFormat()
    {
        return in_array($this->getImageFormatAttribute(), ['webp', 'avif', 'heic', 'heif']);
    }

    /**
     * Check if the image is a vector format (SVG)
     */
    public function isVectorImage()
    {
        return $this->getImageFormatAttribute() === 'svg';
    }

    /**
     * Get supported image formats
     */
    public static function getSupportedFormats()
    {
        return [
            'jpeg', 'jpg', 'png', 'gif', 'svg', 'webp', 
            'avif', 'bmp', 'tiff', 'tif', 'ico', 'heic', 'heif'
        ];
    }
}
