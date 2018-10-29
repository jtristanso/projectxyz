<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductSold;
class ProductSoldController extends ClassWorxController
{
    function __construct(){
      $this->model = new ProductSold();
    }
}
