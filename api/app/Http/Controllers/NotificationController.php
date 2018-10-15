<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use App\EnrolledAccount;
use Carbon\Carbon;
class NotificationController extends ClassWorxController
{
    function __construct(){
      $this->model = new Notification();
      $this->notRequired = array(
        'account_id',
        'course_id',
        'title',
        'description',
        'payload',
        'url',
        'status'
      );
    }

    public function create(Request $request){
      $data = $request->all();
      if($data['status'] == 'ac_viewed'){
        $insertData = array(
          'account_id'  => $data['account_id'],
          'course_id'   => null,
          'title'       => null,
          'description' => null,
          'payload'     => null,
          'url'         => null,
          'parameter'   => null,
          'status'      => $data['status'],
          'created_at'  => Carbon::now()
        );
        $model = new Notification();
        $result = $model->insert($insertData);
        return response()->json(
          array(
            'data'  => $result
          )
        );
      }else{
        $this->model = new Notification();
        $this->insertDB($data);
        return $this->response();
      }
    }


    public function retrieve(Request $request){
      $result = array(
        'data' => null,
        'notification_current' => null
      );
      $data = $request->all();
      $accountId = $data['account_id'];
      $enrolledCourses = EnrolledAccount::where('account_id', '=', $accountId)->get();
      $courseCondition = array();
      if(sizeof($enrolledCourses) > 0){
        $i = 0;
        foreach ($enrolledCourses as $key) {
          $courseCondition[$i] = $enrolledCourses[$i]['course_id'];
          $i++;
        }
        $result['data'] = Notification::where('account_id', '=', $accountId)->orWhereIn('course_id', $courseCondition)->orderBy('created_at', 'DESC')->get();
      }else{
        $result['data'] = Notification::where('account_id', '=',$accountId)->orderBy('created_at', 'DESC')->get();
      }
      $result['notification_current'] = $this->getCurrentNotification($result['data'], $accountId);
      return response()->json($result);
    }

    public function getCurrentNotification($result, $accountId){
      $counter = 0;
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          if($result[$i]['status'] == 'pushed' && $result[$i]['creator'] != $accountId){
            $counter++;
          }else{
            break;
          }
          $i++;
        }
      }else{
        //
      }
      return $counter;
    }
}
