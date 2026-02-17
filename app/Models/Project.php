<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'image',
        'name',
        'title',
        'description',
        'status',
        'end_at',
        'basic_package_text',
    ];

    protected $casts = [
        'end_at' => 'datetime',
    ];
}
