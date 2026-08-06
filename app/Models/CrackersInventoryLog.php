<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrackersInventoryLog extends Model
{
    use HasFactory;

    protected $table = 'crackers_inventory_logs';

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'old_stock',
        'new_stock',
        'notes',
        'created_by',
    ];

    public function product()
    {
        return $this->belongsTo(CrackersProduct::class, 'product_id');
    }
}
