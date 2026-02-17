<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'image',
        'name',
        'description',
        'stars',
    ];
}
