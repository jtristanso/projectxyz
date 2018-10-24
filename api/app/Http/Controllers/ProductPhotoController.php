<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductPhoto;
class ProductPhotoController extends ClassworxController
{
    function __construct(){
      $this->model = new ProductPhoto();
    }
}
