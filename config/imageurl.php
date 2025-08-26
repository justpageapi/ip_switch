<?php

return [
    /*'category_img_url' => 'http://localhost/pos/uploads/category/',
    'product_img_url' => 'http://localhost/pos/uploads/',*/

    /*'category_img_url' => 'http://pos.delivermyvape.co.uk/uploads/category/',
    'product_img_url' => 'http://pos.delivermyvape.co.uk/uploads/',*/

    'category_img_url' => env('CDN_URL') . '/uploads/category/',
    'brand_img_url' => env('CDN_URL') . '/uploads/category/',
    'product_img_url' => env('CDN_URL') . '/uploads/',
    'banner_img_url' => env('CDN_URL') . '/uploads/',

    //with cdn
    /*'category_img_url' => 'https://delivermyvape.b-cdn.net/uploads/category/',
    'brand_img_url' => 'https://delivermyvape.b-cdn.net/uploads/category/',
    'product_img_url' => 'https://delivermyvape.b-cdn.net/uploads/',*/
];
