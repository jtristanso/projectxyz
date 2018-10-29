<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\ProductPhoto;
class ProductController extends ClassWorxController
{
    function __construct(){
      $this->model = new Product();
      $this->notRequired = array('description');
    }

    public function retrieve(Request $request){
      $data = $request->all();

      $this->model = new Product();
      $this->retrieveDB($data);
      $products = $this->response['data'];

      if(sizeof($products) > 0){
        $i = 0;
        foreach ($products as $key) {
          $this->response['data'][$i]['photos'] = $this->getProductPhoto($products[$i]['id']);
          $this->response['data'][$i]['account'] = $this->retrieveAccountDetails($products[$i]['account_id']);
          $i++;
        }
      }
      return $this->response();
    }

    public function getProductPhoto($productId){
      $result = ProductPhoto::where('product_id', '=', $productId)->orderBy('status', 'ASC')->get();
      return (sizeof($result) > 0) ? $result : null;
    }
}
