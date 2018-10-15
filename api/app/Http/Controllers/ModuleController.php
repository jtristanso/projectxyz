<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Module;
class ModuleController extends ClassWorxController
{
    function __construct(){
      $this->model = new Module();
    }
}
