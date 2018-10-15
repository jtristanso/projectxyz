<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TopicComment;
class TopicCommentController extends ClassWorxController
{
    function __construct(){
    	$this->model = new TopicComment();
    }
}
