<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationInfo extends Model
{
    use HasFactory;

    protected $table = 'application_infos';

    protected $fillable = [
        'platform',
        'version_name',
        'version_code',
        'app_name',
        'package_name',
        'release_notes',
        'force_update',
    ];
}
