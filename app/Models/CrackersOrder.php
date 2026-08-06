<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account\Customer;

class CrackersOrder extends Model
{
    use HasFactory;

    protected $table = 'crackers_orders';

    protected $fillable = [
        'order_number',
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'delivery_address',
        'city',
        'pincode',
        'subtotal',
        'gst_rate',
        'gst_amount',
        'discount',
        'grand_total',
        'payment_method',
        'payment_proof',
        'payment_status',
        'status',
        'order_type_pricing',
        'notes',
    ];

    public function getGstAmountAttribute($value)
    {
        $val = floatval($value);
        if ($val <= 0 && $this->grand_total > 0 && $this->subtotal > 0 && $this->grand_total > $this->subtotal) {
            return round($this->grand_total - $this->subtotal, 2);
        }
        return $val;
    }

    public function getGstRateAttribute($value)
    {
        $val = floatval($value);
        if ($val <= 0 && $this->subtotal > 0 && $this->grand_total > $this->subtotal) {
            $diff = $this->grand_total - $this->subtotal;
            return round(($diff / $this->subtotal) * 100, 2);
        }
        return $val ?: 18;
    }

    public function items()
    {
        return $this->hasMany(CrackersOrderItem::class, 'order_id');
    }

    public function getIsPosAttribute()
    {
        return str_starts_with($this->order_number, 'CRK-POS-') || $this->city === 'In-Store';
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public static function generateOrderNumber()
    {
        $latest = static::latest('id')->first();
        $next = $latest ? $latest->id + 1 : 1;
        return 'CRK-' . date('Ymd') . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
