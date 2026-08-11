<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CrackersProduct extends Model
{
    use HasFactory;

    protected $table = 'crackers_products';

    protected $fillable = [
        'name',
        'slug',
        'category',
        'price',
        'discount_price',
        'wholesale_price',
        'wholesale_min_qty',
        'wholesale_max_qty',
        'image',
        'images',
        'stock',
        'low_stock_threshold',
        'unit',
        'description',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function orderItems()
    {
        return $this->hasMany(CrackersOrderItem::class, 'product_id');
    }

    public function inventoryLogs()
    {
        return $this->hasMany(CrackersInventoryLog::class, 'product_id')->latest();
    }
}
