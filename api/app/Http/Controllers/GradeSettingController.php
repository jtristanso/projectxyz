<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GradeSetting;

class GradeSettingController extends ClassWorxController
{
    function __construct(){
      $this->model = new GradeSetting();
      $this->notRequired = array(
        "account_semester_id",
        "course_id"
      );
    }
    public function update(Request $request){
      $data = $request->all();
      if(isset($data['account_semester_id']) === true){
        if($this->checkIfExistSemesterId($data['account_semester_id']) == true){
          $updateData = array();
          $updateData['quizzes_rate'] = $data["data"]['quizzes_rate'];
          $updateData['exams_rate'] = $data["data"]['exams_rate'];
          $updateData['attendance_rate'] = $data["data"]['attendance_rate'];
          $updateData['projects_rate'] = $data["data"]['projects_rate'];
          $updateData['passing_percentage_quizzes'] = $data["data"]['passing_percentage_quizzes'];
          $updateData['passing_percentage_exams'] = $data['data']['passing_percentage_exams'];
          $updateData['id'] = $data['data']['id'];
          $this->model = new GradeSetting();
          $this->updateDB($updateData);
        }else{
          $updateData = $data["data"];
          $updateData['account_semester_id'] = $data['account_semester_id'];
          $this->model = new GradeSetting();
          $this->insertDB($updateData);
        }
      }else{
        if($this->checkIfExistCourseId($data['course_id']) == true){
          $updateData = array();
          $updateData['quizzes_rate'] = $data["data"]['quizzes_rate'];
          $updateData['exams_rate'] = $data["data"]['exams_rate'];
          $updateData['attendance_rate'] = $data["data"]['attendance_rate'];
          $updateData['projects_rate'] = $data["data"]['projects_rate'];
          $updateData['passing_percentage_quizzes'] = $data["data"]['passing_percentage_quizzes'];
          $updateData['passing_percentage_exams'] = $data['data']['passing_percentage_exams'];
          $updateData['id'] = $data['data']['id'];
          $this->model = new GradeSetting();
          $this->updateDB($updateData);
        }else{
          $updateData = $data["data"];
          $updateData['course_id'] = $data['course_id'];
          $this->model = new GradeSetting();
          $this->insertDB($updateData);
        }
      } 
      return $this->response();
    }

    public function updateByCourse(Request $request){
      $data = $request->all();
      $this->model = new GradeSetting();
      $this->updateDB($data);
      return $this->response();
    }
    
    public function checkIfExistSemesterId($semesterId){
      $result = GradeSetting::where('account_semester_id', '=', $semesterId)->get();
      if(sizeof($result) > 0)
        return true;
        return false;
    }
    public function checkIfExistCourseId($courseId){
      $result = GradeSetting::where('course_id', '=', $courseId)->get();
      if(sizeof($result) > 0)
        return true;
        return false;
    }
}
