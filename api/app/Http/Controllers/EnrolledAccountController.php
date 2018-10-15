<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EnrolledAccount;
use App\Course;
use App\GradeSetting;
use App\Answer;
use App\Attendance;
use App\AttendanceAccount;
use App\Account;
use App\AccountInformation;
use App\AccountProfile;
use App\AccountDegree;
use App\AccountWorkExperience;
use App\Test;
use App\Question;
use App\Announcement;
use App\Topic;
use App\TopicComment;
use App\TopicCommentReply;
use App\TopicCommentStar;
use App\Resource;
use App\ResourceViewer;
use App\Notification;
use App\School;
use Carbon\Carbon;
class EnrolledAccountController extends ClassWorxController
{

    private $testStatus = false;
    function __construct(){
      $this->model = new EnrolledAccount();
      $this->notRequired = array(
        "declined_date",
        "confirmed_date"
      );
    }

    public function create(Request $request){
      $data = $request->all();

      $result = Course::where('enrolment_code', '=', $data['enrolment_code'])->get();
      if(sizeof($result) > 0){
        $exist =  $this->checkIfExistEnrolledAccount($data['account_id'], $result[0]['id']);
        if($exist == false){
          $insertData = array(
            'account_id'      => $data['account_id'],
            'course_id'       => $result[0]['id'],
            'status'          => 0
          );
          $account = $this->getAccount($data['account_id']);
          $this->insertNotificationByRequest($result[0], $account);
          $this->model = new EnrolledAccount();
          $this->insertDB($insertData);
          return $this->response();
        }else{
          return response()->json(array('message' => "You're already enrolled this Course!", 'error' => true, "data" => null));
        }
      }else{
        return response()->json(array('message' => "Enrolment Code not Found!", 'error' => true, "data" => null));
      }
    }

    public function update(Request $request){
      $data = $request->all();
      $status = '';
      if(intval($data['status']) == 1){
        $data['confirmed_date'] = Carbon::now();
        $status = 'approved';
      }else if(intval($data['status']) == 2){
        $data['declined_date'] = Carbon::now();
        $status = 'declined';
      }
      else{
        //
      }
      $this->model = new EnrolledAccount();
      $this->updateDB($data);
      if($this->response['data'] == true){
        $enrolled = EnrolledAccount::where('id', '=', $data['id'])->get();
        $course = Course::where('id', '=', $enrolled[0]['course_id'])->get();
        $account = $this->getAccount($enrolled[0]['account_id']);
        $this->insertNotificationByUpdate($course[0], $account, $status);
      }else{
        //
      }
      return $this->response();
    }
    
    public function insertNotificationByRequest($course, $account){
      $model = new Notification();
      $data = array(
        'creator'           => $account['id'],
        'account_id'        => $course['account_id'],
        'course_id'         => null,
        'title'             => 'Enrolment Request',
        'description'       => $account['information']['first_name'].' '.$account['information']['last_name']. ' sent request to enrol to your course at '.$course['code'],
        'payload'           => 'redirect',
        'url'               => 'courses',
        'parameter'         => null,
        'status'            => 'pushed',
        'created_at'        => Carbon::now()
      );
      $model->insert($data);
    }

    public function insertNotificationByUpdate($course, $account, $status){
      $model = new Notification();
      $data = array(
        'creator'           => $course['account_id'],
        'account_id'        => $account['id'],
        'course_id'         => null,
        'title'             => 'Enrolment Request was '.$status,
        'description'       => 'Your request to '.$course['code'].' was '.$status,
        'payload'           => 'redirect',
        'url'               => 'courses/en',
        'parameter'         => null,
        'status'            => 'pushed',
        'created_at'        => Carbon::now()
      );
      $model->insert($data);
    }



    public function retrieve(Request $request){
      $data = $request->all();
      $this->model = new EnrolledAccount();
      $result = $this->retrieveDB($data);
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $accountId = $data['condition'][0]['value'];
          $courseId = $result[$i]['course_id'];
          $this->response['data'][$i]['topic'] = $this->getTopics($courseId, $accountId);
          $this->response['data'][$i]['announcement'] = $this->getAnnouncements($courseId);
          $this->response['data'][$i]['test'] = $this->getTests($courseId, $accountId);
          $this->response['data'][$i]['status_description'] = $this->statusDesciption($courseId);
          $this->response['data'][$i]['resource'] = $this->getResources($courseId);
          $this->response['data'][$i]['attendance'] = $this->getAttendance($courseId, $accountId);
          $courseResult = $this->getCourse($courseId);
          if(sizeof($courseResult) > 0){
            $gradeSettingResult = GradeSetting::where('course_id', '=', $courseResult[0]->id)->get();
            $gradeSettingResultSemester = GradeSetting::where('account_semester_id', '=', $courseResult[0]->account_semester_id)->get();
            $this->response['data'][$i]['course_details'] = $courseResult[0];
            $this->response['data'][$i]['course_details']['time_start'] = date('g:i A', strtotime($this->response['data'][$i]['course_details']['time_start']));
            $this->response['data'][$i]['course_details']['time_end'] = date('g:i A', strtotime($this->response['data'][$i]['course_details']['time_end']));
            $this->response['data'][$i]['grade_settings'] = (sizeof($gradeSettingResult) > 0) ? $gradeSettingResult : $gradeSettingResultSemester;
            $this->response['data'][$i]['teacher_account'] = $this->getAccount($courseResult[0]['account_id']);
          }else{
            $this->response['data'][$i]['course_details'] = null;
            $this->response['data'][$i]['grade_settings'] = null;
            $this->response['data'][$i]['teacher_account_information'] = null;
            $this->response['data'][$i]['teacher_account_profile'] = null;
            $this->response['data'][$i]['teacher_account_degree'] = null;
          }
          $i++;
        }
        return $this->response();
      }else{
        return response()->json(array("data" => null));
      }
    }
    public function statusDesciption($status){
      switch (intval($status)) {
        case 0:
          return "PENDING";
        case 1:
          return null;
        case 2:
          return "DECLINED";
      }
    }

    public function checkIfExistEnrolledAccount($accountId, $courseId){
      $result = EnrolledAccount::where('account_id', '=', $accountId)->where('course_id', '=', $courseId)->get();
      if(sizeof($result) > 0){
        return true;
      }else{
        return false;
      }
    }

    public function getCourse($courseId){
      $result = Course::where('id', '=', $courseId)->get();
      return (sizeof($result) > 0) ? $result : null; 
    }



    public function getAnnouncements($courseId){
      $result = Announcement::where('announcement_courses.course_id', '=', $courseId)->leftJoin('announcement_courses', 'announcement_courses.announcement_id', '=', 'announcements.id')
            ->orderBy('announcement_courses.created_at', 'DESC')->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $result[$i]['account'] = $this->getAccount($result[$i]['account_id']);
          $i++;
        }
        return $result;
      }else{
        return null;
      }
    }

    public function getTests($courseId, $accountId){
      $result = Test::where('id', '=', $courseId)->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $questions = Question::where('test_id', '=', $result[$i]['id'])->get();
          $result[$i]['total_questions'] = sizeof($questions);
          $result[$i]['total_scores'] = $this->getScores($result[$i]['id'], $accountId);
          $result[$i]['take_flag'] = ($this->testStatus === false) ? false : true;
          $i++;
        }
        return $result;
      }else{
        return null;
      }
    }

    public function getScores($testId, $accountId){
      $questions = Question::where('test_id', '=', $testId)->get();
      if(sizeof($questions) > 0){
        $i = 0;
        $score = 0;
        foreach ($questions as $key) {
          $answer = Answer::where('question_id', '=', $questions[$i]['id'])->where('account_id', '=', $accountId)->get();
          if(sizeof($answer) > 0){
            $this->testStatus = true;
            if(intval($answer[0]['answer_status']) == 1){
              $score++;
            }
          }
          $i++;
        }
        return $score;
      }
      return 0;
    }

    public function getTopics($courseId, $accountId){
      $result = Topic::where('course_id', '=', $courseId)->orderBy('created_at', 'DESC')->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $result[$i]['account'] = $this->getAccount($result[$i]['account_id']);
          $result[$i]['stars'] = $this->getTotalStars($result[$i]['id']);
          $result[$i]['new_comment_flag'] = false;
          $result[$i]['star_flag'] = $this->getStarFlag($result[$i]['id'], $accountId);
          $result[$i]['edit_flag'] = false;
          $comments = TopicComment::where('topic_id', '=', $result[$i]['id'])->orderBy('created_at', 'ASC')->get();
          if(sizeof($comments) > 0){
            $j = 0;
            foreach ($comments as $keyComment) {
              $comments[$j]['account'] = $this->getAccount($comments[$j]['account_id']);
              $comments[$j]['new_reply_flag'] = false;
              $replies = TopicCommentReply::where('topic_comment_id', '=', $comments[$j]['id'])->orderBy('created_at', 'ASC')->get();
              if(sizeof($replies) > 0){
                $k = 0;
                foreach ($replies as $key) {
                  $replies[$k]['account'] = $this->getAccount($replies[$k]['account_id']);
                  $k++;
                }
                $comments[$j]['replies'] = $replies;
              }else{
                $comments[$j]['replies'] = null;
              }
              $j++;
            }
            $result[$i]['comments'] = $comments;
          }else{
            $result[$i]['comments'] = null;
          }
          $i++;
        }
        return $result;
      }else{
        return null;
      }
    }

    public function getTotalStars($topicId){
      $result = TopicCommentStar::where('topic_id', '=', $topicId)->get();
      return sizeof($result);
    }
    public function getStarFlag($topicId, $accountId){
      $result = TopicCommentStar::where('topic_id', '=', $topicId)->where('account_id', '=', $accountId)->get();
      return (sizeof($result) > 0) ? true : false;
    }
    public function getAccount($accountId){
      $result = Account::where('id', '=', $accountId)->get();
      if(sizeof($result) > 0){
        $profile = AccountProfile::where('account_id', '=', $accountId)->orderBy('created_at', 'DESC')->get();
        $information = AccountInformation::where('account_id', '=', $accountId)->get();
        $work = AccountWorkExperience::where('account_id', '=', $accountId)->orderBy('created_at', 'DESC')->get();
        $result[0]['profile'] = (sizeof($profile) > 0) ? $profile[0] : null;
        $result[0]['information'] = (sizeof($information) > 0) ? $information[0] : null;
        $result[0]['degree'] = $this->getAccountDegrees($accountId);
        $result[0]['work'] = (sizeof($work) > 0) ? $work : null;
        return $result[0];
      }else{
        return null;
      }
    }

    public function getAccountDegrees($accountId){
      $result = AccountDegree::where('account_id', '=', $accountId)->orderBy('year_started', 'DESC')->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $result[$i]['edit_flag'] = false;
          $school = School::where('id', '=', $result[$i]['school_id'])->get();
          $result[$i]['school'] = (sizeof($school) > 0) ? $school[0] : null; 
        }
        return $result;
      }else{
        return null;
      }
    }

    public function getResources($courseId){
      $result = Resource::where('course_id', '=', $courseId)->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $resourceViewer = ResourceViewer::where('resource_id', '=', $result[$i]['id'])->get();
          $result[$i]['resource_total_viewers'] = sizeof($resourceViewer);
          $i++;
        }
        return $result;
      }else{
        return null;
      }
    }

    public function getAttendance($courseId, $accountId){
      $result = Attendance::where('course_id', '=', $courseId)->orderBy('created_at', 'DESC')->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $attendance = AttendanceAccount::where('attendance_id', '=', $result[$i]['id'])->where('account_id', '=', $accountId)->get();
          if(sizeof($attendance) > 0){
            $result[$i]['details'] = $attendance[0];
          }else{
            $result[$i]['details'] = null;
          }
          $i++;
        }
        return $result;
      }else{
        return null;
      }
    }
}
