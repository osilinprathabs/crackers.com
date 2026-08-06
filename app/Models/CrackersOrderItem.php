<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrackersOrderItem extends Model
{
    use HasFactory;

    protected $table = 'crackers_order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'unit_price',
        'quantity',
        'total_price',
    ];

    public function order()
    {
        return $this->belongsTo(CrackersOrder::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(CrackersProduct::class, 'product_id');
    }
}
