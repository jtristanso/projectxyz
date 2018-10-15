<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventComment;
use App\EventCommentReply;
class EventCommentController extends ClassWorxController
{
    function __construct(){
    	$this->model = new EventComment();
    }

    public function retrieve(Request $request){
    	$data = $request->all();
    	$this->retrieveDB($data);

    	$result = $this->response['data'];
    	if(sizeof($result) > 0){
    		$i = 0;

    		foreach ($result as $key) {
    			$this->response['data'][$i]['account'] = $this->retrieveAccountDetails($result[$i]['account_id']);
    			$this->response['data'][$i]['comment_replies'] = $this->getReplies($result[$i]['id']);
    			$this->response['data'][$i]['new_reply_flag'] = false;
    			$i++;
    		}
    	}
    	return $this->response();
    }

    public function getReplies($commentId){
    	$result = EventCommentReply::where('event_comment_id', '=', $commentId)->orderBy('created_at', 'ASC')->get();
    	if(sizeof($result) > 0){
    		$i = 0;
    		foreach ($result as $key) {
    			$result[$i]['account'] = $this->retrieveAccountDetails($result[$i]['account_id']);
    			$i++;
    		}
    		return $result;
    	}else{
    		return null;
    	}
    }
}
