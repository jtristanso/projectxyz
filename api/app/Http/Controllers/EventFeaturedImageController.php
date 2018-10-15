<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventFeaturedImage;
use Carbon\Carbon;
class EventFeaturedImageController extends ClassWorxController
{
    function __construct(){
    	$this->model = new EventFeaturedImage();
    }

    public function create(Request $request){
    	$data = $request->all();
	  	if(isset($data['file_url'])){
	  		$date = Carbon::now()->toDateString();
	  		$time = str_replace(':', '_',Carbon::now()->toTimeString());
	  		$ext = $request->file('file')->extension();
	  		$filename = $data['event_id'].'_'.$date.'_'.$time.'.'.$ext;
	  		$result = $request->file('file')->storeAs('eventFeaturedImages', $filename);
	  		$this->model = new EventFeaturedImage();
	  		$insertData = array(
	  			'event_id'	=> $data['event_id'],
	  			'url'	=> '/storage/event_feautured_image/'.$filename
	  		);
	  		$this->insertDB($insertData);
	  		return $this->response();
	  	}else{
	  		return response()->json(array('data' => null));
	  	}
    }
}
