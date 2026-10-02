<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrackersBankAccount extends Model
{
    use HasFactory;

    protected $table = 'crackers_bank_accounts';

    protected $fillable = [
        'bank_name',
        'account_holder',
        'account_number',
        'ifsc_code',
        'branch_name',
        'upi_id',
        'qr_code',
        'is_primary',
        'is_active',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get primary bank account for Quotations & Store Wire Transfers
     */
    public static function getPrimaryAccount()
    {
        return static::where('is_primary', true)->where('is_active', true)->first() 
            ?: static::where('is_active', true)->first()
            ?: static::first();
    }
}
