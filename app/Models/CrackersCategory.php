<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrackersCategory extends Model
{
    use HasFactory;

    protected $table = 'crackers_categories';

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
