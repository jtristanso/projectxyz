<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MessengerGroup;
class MessengerGroupController extends ClassWorxController
{
    function __construct(){
      $this->model = new MessengerGroup();
    }
}
