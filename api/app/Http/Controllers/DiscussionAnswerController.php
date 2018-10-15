<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DiscussionPost;
use App\DiscussionAnswer;
class DiscussionAnswerController extends ClassWorxController
{
    function __construct(){
      $this->model = new DiscussionAnswer();
    }
}
