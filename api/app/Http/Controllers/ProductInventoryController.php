<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductInventory;
class ProductInventoryController extends ClassWorxController
{
    function __construct(){
      $this->model = new ProductInventory();
    }
}
