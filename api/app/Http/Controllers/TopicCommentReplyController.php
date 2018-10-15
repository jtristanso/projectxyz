<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TopicCommentReply;
class TopicCommentReplyController extends ClassWorxController
{
    function __construct(){
    	$this->model = new TopicCommentReply();
    }
}
