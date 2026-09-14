<?php

namespace App\Models\Account;

use App\Models\Concerns\HasObfuscatedRouteKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Account\ChartOfAccount;

class BankAccount extends Model
{
    use HasFactory, HasObfuscatedRouteKey;

    protected $fillable = [
        'account_number',
        'account_name',
        'bank_name',
        'branch_name',
        'account_type',
        'payment_gateway',
        'opening_balance',
        'current_balance',
        'iban',
        'swift_code',
        'routing_number',
        'is_active',
        'gl_account_id',
        'creator_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'opening_balance' => 'decimal:2',
            'current_balance' => 'decimal:2',
            'is_active' => 'boolean'
        ];
    }



    public function glAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'gl_account_id');
    }

    public function gl_account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'gl_account_id');
    }

    public static function syncStoreBankAccounts()
    {
        if (class_exists('\App\Models\CrackersBankAccount')) {
            $storeBanks = \App\Models\CrackersBankAccount::all();
            foreach ($storeBanks as $sb) {
                if (empty($sb->account_number)) continue;
                static::updateOrCreate(
                    ['account_number' => $sb->account_number],
                    [
                        'account_name' => ($sb->account_holder ? $sb->account_holder . ' (' . $sb->bank_name . ')' : $sb->bank_name),
                        'bank_name' => $sb->bank_name ?: 'Bank',
                        'branch_name' => $sb->branch_name ?? '',
                        'account_type' => 'savings',
                        'opening_balance' => 0,
                        'current_balance' => 0,
                        'is_active' => $sb->is_active ?? true,
                        'creator_id' => auth()->id() ?? 1,
                        'created_by' => function_exists('creatorId') ? creatorId() : 1,
                    ]
                );
            }
        }
    }
}