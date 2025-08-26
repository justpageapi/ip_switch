<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PostCodeForShop extends Model
{
  use HasFactory;

  protected $table = 'post_code_for_shop';

  protected $hidden = [
    'created_at',
    'updated_at',
  ];
}
