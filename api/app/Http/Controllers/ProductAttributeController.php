<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductAttribute;
class ProductAttributeController extends ClassWorxController
{
    function __construct(){
      $this->model = new ProductAttribute();
    }
}
