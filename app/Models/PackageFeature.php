<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageFeature extends Model
{
    protected $fillable = [
        'package_id',
        'text',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
