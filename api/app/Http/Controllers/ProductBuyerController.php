<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductBuyer;
class ProductBuyerController extends ClassWorxController
{
    function __construct(){
      $this->model = new ProductBuyer();
    }
}
