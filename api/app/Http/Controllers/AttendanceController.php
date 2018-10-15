<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attendance;
use App\AttendanceAccount;
use App\Course;
use App\Account;
use App\AccountProfile;
use App\AccountInformation;
use App\AccountDegree;
use App\AccountWorkExperience;
use App\EnrolledAccount;
use Carbon\Carbon;
use LaravelQRCode\Facades\QRCode;
class AttendanceController extends ClassWorxController
{
    function __construct(){
    	$this->model = new Attendance();
      $this->notRequired = array(
        'qr_code_url'
      );
    }

    public function create(Request $request){
      $request = $request->all();
      $time = Carbon::now();
      if($this->checkIfExist($request) == false){
        $request['time'] = ($request['time'] == null) ? $time->toTimeString() : $request['time'];
        $request['date'] = ($request['date'] == null) ? $time->toDateString() : $request['date'];
        $request['code'] = $this->generateCode();
        $this->model = new Attendance();
        $this->insertDB($request);
        return $this->response();
      }else{
        return $this->response();
      }
    }

    public function checkIfExist($request){
      $result = Attendance::where('course_id', '=', $request['course_id'])->whereDate('date', '=', $request['date'])->get();
      if(sizeof($result) > 0){
        return true;
      }else{
        return false;
      }
    }

    public function generateQrCode(Request $request){
      $data = $request->all();
      $path = '../storage/app/qrCodes/';
      $date = Carbon::now()->toDateString().'_';
      $time = str_replace(':', '_',Carbon::now()->toTimeString()).'.png';
      $filename = 'ATTENDANCE_'.$data['id'].'_'.$date.$time;
      $text = 'attendance/'.$data['code'];
      $qrCode = QRCode::text($text)->setSize(10)->setOutfile($path.$filename)->png();
      $data['qr_code_url'] = '/storage/qr_code/'.$filename;
      $this->model = new Attendance();
      $data = array(
        'id'  => $data['id'],
        'qr_code_url' => '/storage/qr_code/'.$filename
      );
      $this->updateDB($data);
      return $this->response();
    }
    public function generateCode(){
      $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 64);
      $codeExist = Attendance::where('code', '=', $code)->get();
      if(sizeof($codeExist) > 0){
        $this->generateCode();
      }else{
        return $code;
      }
    }


    public function retrieve(Request $request){
      $data = $request->all();
      $this->model = new Attendance();
      $this->retrieveDB($data);
      $result = $this->response['data'];

      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $courseId = $result[$i]['course_id'];
          $attendanceId = $result[$i]['id'];
          $this->response['data'][$i]['course_details'] = $this->getCourse($courseId);
          $this->response['data'][$i]['enrolled_accounts'] = $this->getEnrolledAccounts($courseId, $attendanceId);
          $this->response['data'][$i]['d_time'] = date('g:i A', strtotime($result[$i]['time']));
          $i++;
        }
        return $this->response();
      }
      return $this->response();
    }

    public function getEnrolledAccounts($courseId, $attendanceId){
      $result = EnrolledAccount::where('course_id', '=', $courseId)->where('status', '=', 1)->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $accountId = $result[$i]['account_id'];
          $result[$i]['attendance_account'] = $this->getAtttendanceAccount($attendanceId, $accountId);
          $i++;
        }
        return $result;
      }else{
        return null;
      }
    }

    public function getCourse($courseId){
      $result = Course::where('id', '=', $courseId)->get();
      return (sizeof($result) > 0) ? $result[0] : null;
    }

    public function getAtttendanceAccount($attendanceId, $accountId){
      $result = AttendanceAccount::where('attendance_id', '=', $attendanceId)->where('account_id', '=', $accountId)->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $accountId = $result[$i]['account_id'];
          $result[$i]['account_details'] = $this->getAccount($accountId);
          $i++;
        }
        return $result[0];
      }else{
        return array(
          'account_details' => $this->getAccount($accountId),
          'remarks' => null,
          'status'  => null,
          'attendance_id' => $attendanceId,
          'account_id'  => $accountId
        );
      }
    }

    public function getAccount($accountId){
      $result = Account::where('id', '=', $accountId)->get();
      if(sizeof($result) > 0){
        $profile = AccountProfile::where('account_id', '=', $accountId)->orderBy('created_at', 'DESC')->get();
        $information = AccountInformation::where('account_id', '=', $accountId)->get();
        $degree = AccountDegree::where('account_id', '=', $accountId)->orderBy('created_at','DESC')->get();
        $work = AccountWorkExperience::where('account_id', '=', $accountId)->orderBy('created_at', 'DESC')->get();
        $result[0]['profile'] = (sizeof($profile) > 0) ? $profile[0] : null;
        $result[0]['information'] = (sizeof($information) > 0) ? $information[0] : null;
        $result[0]['degree'] = (sizeof($degree) > 0) ? $degree : null;
        $result[0]['work'] = (sizeof($work) > 0) ? $work : null;
        return $result[0];
      }else{
        return null;
      }
    }
}
