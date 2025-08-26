<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SiteProduct extends Model
{
  use HasFactory;
  protected $table = 'site_product';

  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  protected $primaryKey = 'id';
  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id', 'id');
  }
}
