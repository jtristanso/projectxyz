<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;
class RatingController extends ClassWorxController
{
    function __construct(){
      $this->model = new Rating();
    }
}
