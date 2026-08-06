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
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
