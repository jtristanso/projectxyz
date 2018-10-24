<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductPrice;
class ProductPriceController extends ClassworxController
{
    function __construct(){
      $this->model = new ProductPrice();
    }
}
