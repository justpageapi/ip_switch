<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  use HasFactory;
  protected $appends = ['imagepath', 'brandimagepath'];
  protected $table = 'category';

  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  protected $primaryKey = 'id';

  public function scopeActive($query)
  {
    $query->where('is_active', 1);
  }

  public static function getAllCategory($is_active = 1, $id = null)
  {
    // $cat = Category::where(array('type' => 'category', 'is_active' => $is_active, 'is_on_web' => $is_active));


    $cat = Category::where('type', 'category')->where('is_active', $is_active)->where('is_on_web', $is_active)
      ->orderByRaw('CASE WHEN brand_sequence IS NULL OR brand_sequence = "" THEN 1 ELSE 0 END, brand_sequence ASC');

    if ($id == null) {
      $cat = $cat->get();
    } else {
      $cat = $cat->find($id);
    }
    return $cat;
  }

  public static function getAllBrand($is_active = 1, $id = null)
  {
    // $cat = Category::where(array('type' => 'brand', 'is_active' => $is_active));

    $cat = Category::where('type', 'brand')->where('is_active', $is_active)
      ->orderByRaw('CASE WHEN brand_sequence IS NULL OR brand_sequence = "" THEN 1 ELSE 0 END, brand_sequence ASC');

    if ($id == null) {
      $cat = $cat->get();
    } else {
      $cat = $cat->find($id);
    }
    return $cat;
  }

  public function getimagepathAttribute()
  {
    if ($this->image != null) {
      return config('imageurl.category_img_url') . $this->image;
    } else {
      return config('imageurl.category_img_url') . 'p.png';
    }
  }

  public function getbrandimagepathAttribute()
  {
    if ($this->image != null) {
      return config('imageurl.brand_img_url') . $this->image;
    } else {
      return config('imageurl.brand_img_url') . 'brand_default.jpg';
    }
  }

  public function product()
  {
    return $this->hasMany(Product::class, 'category_id', 'id');
  }
  /*public static function getProductByModel($model_id){
      //$uniq_pdr_id = Variant::whereIn('model_id', array($model_id))->groupBy('product_id')->pluck('product_id');
      $uniq_pdr_id = Product::with('variantmodel','notnullqty')->get()->toArray();
      dd($uniq_pdr_id);

    }*/

  /*public function getImageAttribute($image)
    {
        if($image!=null)
        {
            return env('imageurl').'categoryimage/'.$image;
        }
    }*/
}
