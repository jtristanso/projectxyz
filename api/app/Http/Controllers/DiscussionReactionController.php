<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DiscussionReaction;
use Carbon\Carbon;
class DiscussionReactionController extends ClassWorxController
{
    function __construct(){
    	$this->model = new DiscussionReaction();
    }

    public function create(Request $request){
    	$data = $request->all();

    	$resultNotDeleted = DiscussionReaction::where('account_id', '=', $data['account_id'])->where('discussion_post_id', '=', $data['discussion_post_id'])->whereNull('deleted_at')->get();

    	$resultDeleted = DiscussionReaction::where('account_id', '=', $data['account_id'])->where('discussion_post_id', '=', $data['discussion_post_id'])->whereNotNull('deleted_at')->get();
    	if(sizeof($resultNotDeleted) > 0){
    		DiscussionReaction::where('id', '=', $resultNotDeleted[0]['id'])->update(array('deleted_at' => Carbon::now()));
    		return response()->json(array('data' => true));
    	}else if(sizeof($resultDeleted) > 0){
    		DiscussionReaction::where('id', '=', $resultDeleted[0]['id'])->update(array('deleted_at' => null));
    		return response()->json(array('data' => true));
    	}else{
    		$this->model = new DiscussionReaction();
    		$this->insertDB($data);
    		return $this->response();
    	}
    }
}
