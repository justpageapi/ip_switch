<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CartEmail extends Model
{
    use HasFactory;

    protected $table = 'cart_email';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
