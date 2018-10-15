<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AttendanceAccount;
use App\Notification;
use App\Attendance;
use App\Course;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class AttendanceAccountController extends ClassWorxController
{
    function __construct(){
      $this->model = new AttendanceAccount();
      $this->notRequired = array(
        'remarks'
      );
    }

    public function create(Request $request){
      $request = $request->all();
      if($this->checkIfExist($request) == false){
        $this->model = new AttendanceAccount();
        $this->insertDB($request);
        $attendance = Attendance::where('id', '=', $request['attendance_id'])->get();
        $account = $this->retrieveAccountDetails($request['account_id']);
        if(sizeof($attendance) > 0){
          $course = $this->retrieveCourseDetails($attendance[0]['course_id']);
          $this->insertNotification($course, $account, $request, $attendance[0]);
        }else{
          //
        }
        return $this->response();
      }else{
        return $this->response();
      }
    }

    public function createByMobile(Request $request){
      $this->model = new AttendanceAccount();
      $this->insertDB($request->all());
      $response = array();
      
      if($this->response['data'] > 0){
        $response[] = array(
          "data" => $this->response['data']
        );
      }
      return response()->json(
        array(
          "data"  => ($this->response['data'] > 0) ? $response : [],
          "error" => ($this->response['data'] > 0) ? "" : "You are not allowed for this event.",
          "timestamps" => Carbon::now()
        )
      );
    }

    public function insertNotification($course, $account, $data, $attendance){
      $model = new Notification();
      $data = array(
        'creator'           => $course['account_id'],
        'course_id'         => null,
        'account_id'        => $account['id'],
        'title'             => 'New Attendance Added',
        'description'       => 'You were mark as '.$data['status'].' in '.$course['code'],
        'payload'           => 'redirect',
        'url'               => 'courses/en',
        'parameter'         => 'attendances',
        'status'            => 'pushed',
        'created_at'        => Carbon::now()
      );
      $model->insert($data);
    }

    public function checkIfExist($request){
      $result = AttendanceAccount::where('attendance_id', '=', $request['attendance_id'])->where('account_id', '=', $request['account_id'])->get();
      if(sizeof($result) > 0){
        return true;
      }else{
        return false;
      }
    }

    public function retrieveByCodeMobile(Request $request){
      $data = $request->all();
      $result = Attendance::where('code', '=', $data['code'])->get();
      $response = array();
      if(sizeof($result) > 0){
        $attendanceResult = AttendanceAccount::where('account_id', '=', $data['account_id'])->where('attendance_id', '=', $result[0]['id'])->get();
        $courseResult = $this->retrieveCourseDetails($result[0]['course_id']);
       
        $courseSchedule = null;
        $course = null;
        $courseCode = null;
        if($courseResult !== null){
          $timeStart = date('g:i A', strtotime($courseResult['time_start']));
          $timeEnd = date('g:i A', strtotime($courseResult['time_end']));
          $schedule = $timeStart." - ".$timeEnd." ".$courseResult["days"];
          $courseSchedule = $schedule;
          $course = $courseResult["description"];
          $courseCode = $courseResult["code"];
        }else{
          $courseSchedule = "";
          $course = "";
          $courseCode = "";
        }
        
        $status = (sizeof($attendanceResult) > 0) ? $attendanceResult[0]["status"] : "";
        $response[] = array(
          "id"    => $result[0]['id'],
          "code"  => $result[0]["code"],
          "date"  => Carbon::createFromFormat('Y-m-d', $result[0]["date"])->copy()->tz('Asia/Manila')->format('F j, Y'),
          "time"  => date('g:i A', strtotime($result[0]['time'])),
          "course"  => $course,
          "course_code" => $courseCode,
          "course_schedule"  => $courseSchedule,
          "status"=> $status
        );
      }
      return response()->json(
        array(
          "data"  => (sizeof($result) > 0) ? $response : [],
          "error" => (sizeof($result) > 0) ? "" : "You are not allowed for this event.",
          "timestamps" => Carbon::now()
        )
      );
    }
}
