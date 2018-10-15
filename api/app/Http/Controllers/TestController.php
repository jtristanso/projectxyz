<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Test;
use App\Question;
use App\Course;
use App\Notification;
use Carbon\Carbon;
class TestController extends ClassWorxController
{
    function __construct(){
      $this->model = new Test();
    }

    public function create(Request $request){
      $data = $request->all();
      $data['code'] = $this->generateCode();      
      $this->model = new Test();
      $this->insertDB($data);
      if($this->response['data'] > 0){
        $course = $this->retrieveCourseDetails($data['course_id']);
        if(sizeof($course) > 0){
          $account = $this->retrieveAccountDetails($course[0]['account_id']);
          $this->insertNotification($course, $account);
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
        'title'             => 'New Test Added',
        'description'       => $account['information']['first_name'].' '.$account['information']['last_name']. ' added new test to '.$course['code'],
        'payload'           => 'redirect',
        'url'               => 'courses',
        'parameter'         => 'tests',
        'status'            => 'pushed',
        'created_at'        => Carbon::now()
      );
      $model->insert($data);
    }
    
    public function retrieve(Request $request){
      $response = array(
        "data" => null,
        "message" => null
      );
      $data = $request->all();
      $this->model = new Test();
      $result = $this->retrieveDB($data);

      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $questionResult = Question::where('test_id', '=', $key->id)->get();
          $couseDetails = Course::where('id', '=', $key->course_id)->get();
          $result[$i]['total_score'] = 0;
          $result[$i]['status_flag'] = 0;
          $result[$i]['total_questions'] = sizeof($questionResult);
          $result[$i]['course_details'] = (sizeof($couseDetails) > 0) ? $couseDetails[0] : null;
          $i++;
        }
        $response['data'] = $result;
      }else{
        $response['message'] = "Empty";
      }
      return response()->json($response);
    }

    public function retrieveByCourse(Request $request){
      $data = $request->all();
      $this->model =  new Test();
      $this->retrieveDB($data);
      $result = $this->response['data'];
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $this->response['data'][$i]['d_available_time'] = date('g:i A', strtotime($result[$i]['available_time']));
          $i++;
        }
      }
      return $this->response();
    }

    public function generateCode(){
      $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32);
      $codeExist = Test::where('code', '=', $code)->get();
      if(sizeof($codeExist) > 0){
        $this->generateCode();
      }else{
        return $code;
      }
    }
}
