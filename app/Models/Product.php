<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_category_id', 'name', 'slug', 'description',
        'price', 'weight_label', 'stock', 'is_active', 'image_url'
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function category()
    {
        return $this->subCategory?->category;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 5.0, 1);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        if ($this->image_url) {
            return asset('storage/' . $this->image_url);
        }
        return 'https://placehold.co/400x400/e8f5e9/2D5016?text=Beras';
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp '.number_format($this->price, 0, ',', '.');
    }

    public function hasOrders(): bool
    {
        return $this->orderItems()->exists();
    }
}
