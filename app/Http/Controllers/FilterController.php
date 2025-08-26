<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Rules\matchpassword;
use App\Models\ProjectData;
use App\Models\Variant;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\CartData;
use App\Models\Models;
use BackendHelper;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HomePageController;


class FilterController extends Controller
{

  public function index($name){
      if($name != null){
        $brand = Category::where(array('name'=>$name,'type'=>'brand'))->first();
        if($brand != null){
            $models = Models::where('brand_id',$brand->id)->get();
            return view('frontend.filter_page',array('models'=>$models,'name'=>$name));
        }else{
            return redirect()->route('dashboard');
        }
      }
      else{
          return redirect()->route('dashboard');
      }
    //return view('frontend.filter_page',compact('name','type'));
  }

  public function get_filter_data(Request $request){
    $brand_id = $request->brand_id;
    $dt_html = '';
    $dt_category = '';
    //for brand list
    if(isset($brand_id)){

      $models = Models::with('brand')->active()->where('brand_id',$brand_id)->orderBy('ordering','ASC')->get()->take(10);
      $brand = Brand::find($brand_id);
        ob_start(); ?>
        <li class="list-group-item fw-bold">
            <a class="pointer-class text-normal d-block text-dark"><?php echo $brand->name ?><i class="float-end" onclick="filter_button();">BACK</i></a>
        </li>
      <?php if($models->count() > 0){ ?>

        <?php foreach($models as $model){ ?> 
          <!-- <li class="list-group-item"><a href="#" onclick="filter_modelname('model',<?php echo $model->id ?>);" class="text-normal d-block"><?php echo $model->name ?></a></li> -->
          <li class="list-group-item"><a href="<?php echo route('Device',['name'=>$model->name]); ?>" class="text-normal d-block"><?php echo $model->name ?></a></li>
        <?php } ?>

        <li class="list-group-item  fw-bold text-center"><a href="<?php echo route('Filter',['name'=>$brand->name]) ?>"  class="text-normal d-block">View All Device</a></li> 

        <?php 
      }
       $dt_html .= ob_get_contents();
        ob_end_clean();
    }
    else{      
      $brands = Category::getAllBrand();
      if($brands->count() > 0){
        ob_start();  ?>
        <?php foreach($brands as $brand){ ?>
          <li class="list-group-item" ><a  class="pointer-class text-normal d-block" onclick="brand_name(<?php echo $brand->id ?>)"><?php echo $brand->name ?><i class="float-end bi bi-arrow-right"></i></a></li>
        <?php }
        $dt_html .= ob_get_contents();
        ob_end_clean();            
      }

      /* $category = Category::active()->get();
      if($category->count() > 0){
        ob_start();  ?>
        <?php foreach($category as $category){ ?>
          <li class="list-group-item" ><a class="pointer-class text-normal d-block" onclick="filter_categoryname('<?php echo $category->name ?>');"><?php echo $category->name ?><i class="float-end bi bi-arrow-right"></i></a></li>
        <?php }
        $dt_category .= ob_get_contents();
        ob_end_clean();            
      } */
    }

    $data['html'] = $dt_html; 
    $data['category_html'] = $dt_category;
    $out['type']      = 'success';
    $out['data']      = $data;
    echo json_encode($out);
    die;
  }

  public function destroy_filter_session(){
    if(Session::has('ModelId')){
      Session::forget('ModelId');
      Session::save();
    }
    $out['type']      = 'success';
    echo json_encode($out);
    die;
  }
}


  /*function load_brand_data(Request $request){
    $out['type']      = 'error';
    $product_id = $request->product_id;

    $data = [];
    $data['status'] = true;
    if(isset($product_id)){
      $products = Session::get('all_products');
      $products = $products[0]; 

      $product_key = array_search($product_id, array_column($products, 'product_id'));
      $dt_html = '';
      if($product_key>=0){
        $current_pdr = $products[$product_key];
        
        ob_start();
        foreach($current_pdr['brands'] as $b){
          $hdr_id = 'brand_id_' . $b['brand_id'] .$product_id;
          $brand_name = $b['brand_name'];
          
          ?>
          <div class="card-header brand_header" data-bs-toggle="collapse" data-bs-target="#<?php echo $hdr_id;?>" role="button" aria-expanded="true" data-placement="bottom" title="View Details" data-brand_id="<?php echo $b['brand_id'];?>" data-product_id="<?php echo $product_id;?>" data-brand_isclicked="0">
            <div class="row">                                                     
              <div class="col align-self-center">                
                <h3 class="mb-0">Compatible Brand : <?php echo $brand_name;?></h3>
              </div>                            
              <div class="col-auto align-self-center padding-bottom">                      
                <i id="down" class="fa fa-chevron-down" aria-hidden="true"></i>
              </div>                                      
            </div>
          </div>
          <div class="collapse" id="<?php echo $hdr_id;?>" ></div> 
          <?php
        }
        $dt_html .= ob_get_contents();
        ob_end_clean();
      }
      $data['html'] = $dt_html;
    }

    $out['type']      = 'success';
    $out['data']      = $data;
    echo json_encode($out);
    die;
  }*/
