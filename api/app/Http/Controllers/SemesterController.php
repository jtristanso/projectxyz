<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Semester;
class SemesterController extends ClassWorxController
{
    function __construct(){
      $this->model = new Semester();
    }
}
