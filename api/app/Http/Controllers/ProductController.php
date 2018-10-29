<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
class ProductController extends ClassWorxController
{
    function __construct(){
      $this->model = new Product();
      $this->notRequired = array('description');
    }
}
