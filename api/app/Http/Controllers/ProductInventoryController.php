<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductInventory;
class ProductInventoryController extends ClassworxController
{
    function __construct(){
      $this->model = new ProductInventory();
    }
}
