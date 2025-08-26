<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGroup extends Model
{

    protected $table = 'product_group';
    protected $primaryKey = 'id';
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    use HasFactory;

    public function product_group_items()
    {
        return $this->hasMany(ProductInfo::class, 'product_group_id', 'id');
    }
}
