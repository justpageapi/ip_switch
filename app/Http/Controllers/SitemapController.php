<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seo;

class SitemapController extends Controller
{
  //    
  public function index() {
    $data = Seo::getData();
      return response()->view('sitemap', [
          'datas' => $data
    ])->header('Content-Type', 'text/xml');
  }
}
