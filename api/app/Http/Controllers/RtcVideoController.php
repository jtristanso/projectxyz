<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RtcVideo;
class RtcVideoController extends ClassWorxController
{
    function __construct(){
      $this->model = new RtcVideo();
    }
}
