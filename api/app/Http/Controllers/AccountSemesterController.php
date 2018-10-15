<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountSemester;
use App\Account;
use App\GradeSetting;
use App\Semester;
class AccountSemesterController extends ClassWorxController
{
    function __construct(){
    	$this->model = new AccountSemester();
    }

    public function create(Request $request){
      $data = $request->all();
      if($this->checkIfExist($data) == false){
        $data['code'] = $this->generateCode();      
        $this->model = new AccountSemester();
        $this->insertDB($data);
        if($this->response['data'] > 0){
          Account::where('id', '=', $data['account_id'])->update(array('active_semester' => $this->response['data']));
        }
        return $this->response();
      }else{
        return response()->json(array('data' => null, 'message' => 'The semester you have selected was already created.'));
      }
    }

    public function retrieve(Request $request){
      $this->rawRequest = $request;
      $this->retrieveDB($request->all());
      $result =  $this->response['data'];

      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $this->response["data"][$i]['error_message'] = null;
          $this->response['data'][$i]['semester_details'] = $this->getSemesters($result[$i]['semester_id']);
          if(intval($result[$i]['grade_setting']) == 0){
            $gradeSetting = GradeSetting::where('account_semester_id', '=', $result[$i]['id'])->get();
            if(sizeof($gradeSetting) > 0){
              $this->response["data"][$i]['grade_flag'] = false;
              $this->response["data"][$i]["grade_settings_content"] = $gradeSetting;
            }else{
              $this->response["data"][$i]['grade_flag'] = true;
              $this->response["data"][$i]["grade_settings_content"] = array(
                array(
                  "quizzes_rate" => 0,
                  "exams_rate" => 0,
                  "attendance_rate" => 0,
                  "projects_rate" => 0,
                  "passing_percentage_quizzes" => 0,
                  "passing_percentage_exams" => 0
                )
              );
            }
          }else{
            $this->response["data"][$i]["grade_settings_content"] = null;
          }
          $i++;
        }
      }else{
        //
      }
      return $this->response();
    }

    public function getSemesters($semesterId){
      $result = Semester::where('id', '=', $semesterId)->get();
      return (sizeof($result) > 0) ? $result[0] : null;
    }

    public function generateCode(){
      $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32);
      $codeExist = AccountSemester::where('code', '=', $code)->get();
      if(sizeof($codeExist) > 0){
        $this->generateCode();
      }else{
        return $code;
      }
    }


    public function checkIfExist($request){
      $result = AccountSemester::where('account_id', '=', $request['account_id'])->where('semester_id', '=', $request['semester_id'])->get();
      return (sizeof($result) > 0) ? true : false;
    }


}
