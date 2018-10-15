<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Topic;
use App\Notification;
use Carbon\Carbon;
class TopicController extends ClassWorxController
{
    function __construct(){
    	$this->model = new Topic();
    }

    public function create(Request $request){
    	$data = $request->all();
    	$data['status'] = 'OPEN';
    	$this->model = new Topic();
    	$this->insertDB($data);
        if($this->response['data'] > 0){
            $course = $this->retrieveCourseDetails($data['course_id']);
            $account = $this->retrieveAccountDetails($data['account_id']);
            $this->insertNotification($course, $account);
            if($data['account_id'] != $course['account_id']){
                $this->insertNotificationTeacher($course, $account);
            }
        }else{
            //
        }
    	return $this->response();
    }

    public function insertNotification($course, $account){
      $model = new Notification();
      $data = array(
        'creator'           => $account['id'],
        'course_id'         => $course['id'],
        'account_id'        => null,
        'title'             => 'New Topic Added',
        'description'       => $account['information']['first_name'].' '.$account['information']['last_name']. ' added new topic for discussion to '.$course['code'],
        'payload'           => 'redirect',
        'url'               => 'courses/en',
        'parameter'         => 'topics',
        'status'            => 'pushed',
        'created_at'        => Carbon::now()
      );
      $model->insert($data);
    }

    public function insertNotificationTeacher($course, $account){
      $model = new Notification();
      $data = array(
        'creator'           => $account['id'],
        'account_id'        => $course['account_id'],
        'course_id'         => null,
        'title'             => 'New Topic Added',
        'description'       => $account['information']['first_name'].' '.$account['information']['last_name']. ' added new topic for discussion to '.$course['code'],
        'payload'           => 'redirect',
        'url'               => 'courses',
        'parameter'         => 'topics',
        'status'            => 'pushed',
        'created_at'        => Carbon::now()
      );
      $model->insert($data);
    }

}
