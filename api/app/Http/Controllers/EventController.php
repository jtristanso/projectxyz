<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
use App\EventFeaturedImage;
use Carbon\Carbon;
class EventController extends ClassWorxController
{
    function __construct(){
    	$this->model = new Event();
    	$this->notRequired = array('more_info', 'end_time', 'max_attendees');
    }

    public function create(Request $request){
    	$data = $request->all();

    	$this->insertDB($data);
    	return $this->response();
    }

    public function retrieve(Request $request){
    	$data = $request->all();
    	$this->retrieveDB($data);

    	$result = $this->response['data'];

    	if(sizeof($result) > 0){
    		$i = 0;
    		foreach ($result as $key) {
    			$this->response['data'][$i]['featured'] = $this->getFeaturedImage($result[$i]['id']);
    			$i++;
    		}
    	}

    	return $this->response();
    }

    public function getFeaturedImage($eventId){
    	$result = EventFeaturedImage::where('event_id', '=', $eventId)->orderBy('created_at', 'DESC')->get();
    	return (sizeof($result) > 0) ? $result[0] : null;
    }
}
