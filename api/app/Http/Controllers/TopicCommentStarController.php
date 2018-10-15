<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TopicCommentStar;
use Carbon\Carbon;
class TopicCommentStarController extends ClassWorxController
{
    function __construct(){
      $this->model = new TopicCommentStar();
    }

    public function create(Request $request){
    	$data = $request->all();

    	$result = TopicCommentStar::where('account_id', '=', $data['account_id'])->where('topic_id', '=', $data['topic_id'])->get();

    	if(sizeof($result) > 0){
    		$deleteData = array(
    			'id' 				 => $result[0]['id']
    		);
    		$this->model = new TopicCommentStar();
    		$this->deleteDB($deleteData);
    		return $this->response();
    	}else{
    		$this->model = new TopicCommentStar();
    		$this->insertDB($data);
    		return $this->response();
    	}
    }
}
