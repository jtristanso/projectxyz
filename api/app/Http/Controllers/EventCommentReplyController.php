<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventCommentReply;
class EventCommentReplyController extends ClassWorxController
{
    function __construct(){
    	$this->model = new EventCommentReply();
    }
}
