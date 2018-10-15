<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resource;
use App\ResourceViewer;
use App\EnrolledAccount;
use App\Notification;
use Carbon\Carbon;

class ResourceController extends ClassWorxController
{
    function __construct(){
    	$this->model = new Resource();
    }

    public function create(Request $request){
      $data = $request->all();
      $data["code"] = $this->generateCode();
      $this->model = new Resource();
      $this->insertDB($data);
      if($this->response['data'] > 0 && $data['status'] == 'PUBLIC'){
        $this->insertNotification($this->retrieveCourseDetails($data['course_id']), $this->retrieveAccountDetails($data['account_id']));
      }
      return $this->response();
    }

    public function insertNotification($course, $account){
      $model = new Notification();
      $data = array(
        'creator'           => $account['id'],
        'course_id'         => $course['id'],
        'account_id'        => null,
        'title'             => 'New Resources Added',
        'description'       => $account['information']['first_name'].' '.$account['information']['last_name']. ' added new resources to '.$course['code'],
        'payload'           => 'redirect',
        'url'               => 'courses/en',
        'parameter'         => 'resources',
        'status'            => 'pushed',
        'created_at'        => Carbon::now()
      );
      $model->insert($data);
    }

    public function retrieve(Request $request){
      $data = $request->all();
      $this->model = new Resource();
      $result = $this->retrieveDB($data);
      if(sizeof($result) > 0){
      	$i = 0;
        foreach ($result as $key) {
        $this->response["data"][$i]['edit'] = false;
        $this->response["data"][$i]['privacyOption'] = false;
        $i++;
        }
      }
      else{
        //
      }
      return $this->response();
    }

    public function retrieveByCourse(Request $request){
      $data = $request->all();
      $this->model = new Resource();
      $this->retrieveDB($data);
      $result = $this->response['data'];
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $resourceViewer = ResourceViewer::where('resource_id', '=', $result[$i]['id'])->get();
          $this->response['data'][$i]['resource_total_viewers'] = sizeof($resourceViewer);
          $i++;
        }
      }
      return $this->response();
    }

    public function retrieveStudent(Request $request){
      $data = $request->all();
      $this->model = new Resource();
      $result = $this->retrieveDB($data);
      if(sizeof($result) > 0){
        $i = 0;
        $enrolledCourseResult = EnrolledAccount::where('course_id', '=', $data['condition'][0]['value'])->where('account_id', '=', $data['condition'][1]['value'])->get();
        foreach ($result as $key) {
          $this->response["data"][$i]['edit'] = false;
          $i++;
        }
        if(sizeof($enrolledCourseResult) > 0){
          $resultResources = Resource::where('course_id', '=', $enrolledCourseResult[0]['course_id'])->where('status', '=', 'PUBLIC')->get();
          $j = 0;
          foreach ($resultResources as $key) {
            array_push($this->response['data'], $resultResources[$j]);
            $this->response["data"][$i]['edit'] = false;
            $j++;
          }
        }else{
          //
        }
        return $this->response();
      }else{
        return $this->response();
      }
    }


    public function generateCode(){
      $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32);
      $codeExist = Resource::where('code', '=', $code)->get();
      if(sizeof($codeExist) > 0){
        $this->generateCode();
      }else{
        return $code;
      }
    }
}
