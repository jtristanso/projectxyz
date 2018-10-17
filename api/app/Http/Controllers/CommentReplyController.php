<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CommentReply;
class CommentReplyController extends ClassWorxController
{
    function __construct(){
    	$this->model = new CommentReply();
    }
}
