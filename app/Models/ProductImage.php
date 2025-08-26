<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class ProductImage extends Model {

  use HasFactory;
  protected $table = 'product_image';
  protected $primaryKey = 'id';

  protected $hidden = [
    'created_at',
    'updated_at',
  ];

 }
 ?>