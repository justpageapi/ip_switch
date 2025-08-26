<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInfo extends Model
{
    use HasFactory;

    protected $table = 'product_info';
    protected $primaryKey = 'id';


    function product_group()
    {
        return $this->belongsTo(ProductGroup::class, 'product_group_id', 'id');
    }
    function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public static function getAllTags()
    {
        $tagList = ProductInfo::pluck('tags')->toArray();
        $tagList = array_filter($tagList);
        // dd($tagList);

        $newTagList = [];
        foreach ($tagList as $key => $tag) {

            $newTagList[] = array_merge(
                explode(',', $tag),
            );
        }

        $newTagList = array_merge(...$newTagList);
        $newTagList = array_unique($newTagList);
        $newTagList = array_values($newTagList);
        return $newTagList;
    }
}
