<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LoginLogger;
class LoginLoggerController extends ClassWorxController
{
    function __construct(){
      $this->model = new LoginLogger();
    }
}
