<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_proceeded' => 'boolean',
    ];
}
