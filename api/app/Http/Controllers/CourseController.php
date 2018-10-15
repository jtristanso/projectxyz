<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attendance;
use App\AttendanceAccount;
use App\Course;
use App\EnrolledAccount;
use App\GradeSetting;
use App\Account;
use App\AccountInformation;
use App\AccountProfile;
use App\AccountDegree;
use App\AccountWorkExperience;
use App\AccountSemester;
use App\Resource;
use App\ResourceViewer;
use App\Topic;
use App\TopicComment;
use App\TopicCommentReply;
use App\TopicCommentStar;
use App\Test;
use App\Announcement;
use App\AnnouncementCourse;
use Carbon\Carbon;
use App\Report;
use App\School;
class CourseController extends ClassWorxController
{
    function __construct(){
      $this->model = new Course();
    }

    public function create(Request $request){
      $data = $request->all();
      $data["enrolment_code"] = $this->generateCode();
      $this->model = new Course();
      $this->insertDB($data);
      if($this->response['data'] > 0){
        // create topic
        // create announcements
        $courseId = $this->response['data'];
        $_topic = new Topic();
        $_topic->account_id = $data['account_id'];
        $_topic->course_id = $courseId;
        $_topic->text = 'Welcome to ClassWorx Class Discussion, where students can exchange and share their knowledge. Just click the button new topic button to add a new discussion. You can also navigate other exciting features!';
        $_topic->status = 'OPEN';
        $_topic->created_at = Carbon::now();
        $_topic->save();

        $courseId = $this->response['data'];
        $_announcement = new Announcement();
        $_announcement->account_id = $data['account_id'];
        $_announcement->message = 'Welcome to ClassWorx Class Announcement. You can post here announcements to your classes. Click the button NEW ANNOUNCEMENT button to create a post. Cheers!';
        $_announcement->created_at = Carbon::now();
        $_announcement->save();

        $_announcementCourse = new AnnouncementCourse();
        $_announcementCourse->announcement_id = $_announcement->id;
        $_announcementCourse->course_id = $courseId;
        $_announcementCourse->created_at = Carbon::now();
        $_announcementCourse->save();
      }
      return $this->response();
    }

    public function retrieve(Request $request){
      $data = $request->all();
      $result = $this->retrieveDB($data);
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $accountId = $data['condition'][0]['value'];
          $courseId = $result[$i]['id'];
          $accountSemesterId = $result[$i]['account_semester_id'];
          $this->response['data'][$i]['topic'] = $this->getTopics($courseId, $accountId);
          $this->response['data'][$i]['announcement'] = $this->getAnnouncements($courseId);
          $this->response['data'][$i]['test'] = $this->getTests($courseId);
          $this->response['data'][$i]['resource'] = $this->getResources($courseId);
          $this->response['data'][$i]['enrollees'] = $this->getEnrollees($courseId);
          $this->response['data'][$i]['attendance'] = $this->getAttendance($courseId, $accountId);
          $this->response['data'][$i]['d_time_start'] = date('g:i A', strtotime($this->response['data'][$i]['time_start']));
          $this->response['data'][$i]['d_time_end'] = date('g:i A', strtotime($this->response['data'][$i]['time_end']));
          $this->response['data'][$i]['grade_settings'] = $this->getGradeSettings($courseId, $accountSemesterId);
          $this->response['data'][$i]['course_details_edit_flag'] = false;
          $this->response['data'][$i]['editable'] = true;
          $this->response['data'][$i]['reports'] = $this->getReports($courseId, $accountId);
          $i++;
        }
        return $this->response();
      }else{
        return $this->response();
      }
    }  

    public function retrieveByAttendance(Request $request){
      $request = $request->all();
      $this->model = new Course();
      $this->retrieveDB($request);
      if(sizeof($this->response['data']) > 0){
        $courseId = $this->response['data'][0]['id'];
        $enrollees = $this->getEnrollees($courseId);
        $attendance = null;
        if(sizeof($enrollees) > 0){
          if($request['date'] !== null && $request['date'] !== ''){
            $attendance = Attendance::where('course_id', '=', $courseId)->whereDate('date', '=', $request['date'])->get();
          }else{
            $date = Carbon::now();
            $attendance = Attendance::where('course_id', '=', $courseId)->whereDate('date', '=', $date->toDateString())->get();
          }
          $i = 0;
          foreach ($enrollees as $key) {
            $accountId = $enrollees[$i]['account_id'];
            if(sizeof($attendance) > 0){
              $attId = $attendance[0]['id'];
              $attendanceAccount = AttendanceAccount::where('account_id', '=', $accountId)->where('attendance_id', '=', $attId)->get();
              $enrollees[$i]['attendance_account'] = (sizeof($attendanceAccount) > 0)? $attendanceAccount[0] : null;
              $enrollees[$i]['remarks'] = (sizeof($attendanceAccount) > 0)? $attendanceAccount[0]['remarks'] : null;
              $enrollees[$i]['status'] = (sizeof($attendanceAccount) > 0)? $attendanceAccount[0]['status'] : null;
            }else{
              $enrollees[$i]['remarks'] = null;
              $enrollees[$i]['status'] = null;
              $enrollees[$i]['attendance_account'] = null;
            }
            $enrollees[$i]['attendance'] = (sizeof($attendance) > 0) ? $attendance[0] : null;
            $i++;         
          }
        }else{
          $enrollees = null;
        }
        $this->response['data'][0]['enrollees'] = $enrollees;
        $this->response['data'][0]['attendance'] = (sizeof($attendance) > 0) ? $attendance[0] : null;
        $this->response['data'][0]['d_time_start'] = date('g:i A', strtotime($this->response['data'][0]['time_start']));
        $this->response['data'][0]['d_time_end'] = date('g:i A', strtotime($this->response['data'][0]['time_end']));
        return $this->response();
      }else{
        return $this->response();
      }
    }

    public function generateCode(){
      $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 12);
      $codeExist = Course::where('enrolment_code', '=', $code)->get();
      if(sizeof($codeExist) > 0){
        $this->generateCode();
      }else{
        return $code;
      }
    }

    public function accounts(Request $request){
      $data = $request->all();
      $result = EnrolledAccount::where('course_id', '=', $data['course_id'])->where('status', '=', 1)->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $result[$i]['edit_status_flag'] = false;
          $accountResult = Account::where('id', '=', $result[$i]['account_id'])->get();
          $accountInfoResult = AccountInformation::where('account_id', '=', $result[$i]['account_id'])->get();
          $accountProfileResult = AccountProfile::where('account_id', '=', $result[$i]['account_id'])->get();
          $accountDegreeResult = AccountDegree::where('account_id', '=', $result[$i]['account_id'])->get();
          $result[$i]['account'] = (sizeof($accountResult) > 0) ? $accountResult[0] : null;
          $result[$i]['account_information'] = (sizeof($accountInfoResult) > 0) ? $accountInfoResult[0] : null;
          $result[$i]['account_profile'] = (sizeof($accountProfileResult) > 0) ? $accountProfileResult[0] : null;
          $result[$i]['account_degree'] = (sizeof($accountDegreeResult) > 0) ? $accountDegreeResult[0] : null;
          $i++;
        }
        return response()->json(array('data' => $result));
      }else{
        return response()->json(array('data' => null));
      }
    }

    public function getGradeSettings($courseId, $accountSemesterId){
      $accountSemester = AccountSemester::where('id', '=', $accountSemesterId)->get();
      $settings = (sizeof($accountSemester) > 0) ? $accountSemester[0]['grade_setting'] : null;
      $result = [];
      if($settings == 1 || $settings == '1'){
        $result = GradeSetting::where('course_id', '=', $courseId)->get();
        if(sizeof($result) > 0){
          $result[0]['editable'] = true;
          $result[0]['passing_edit_flag'] = false;
          $result[0]['percentage_edit_flag'] = false;
        }else{
          $result[0] = array(
            'editable'  => true,
            'passing_edit_flag' => false,
            'percentage_edit_flag'  => false,
            'passing_percentage_quizzes' => 0,
            'passing_percentage_exams' => 0,
            'exams_rate' => 0,
            'quizzes_rate' => 0,
            'attendance_rate' => 0,
            'projects_rate' => 0,
            'id' => null,
            'course_id' => $courseId,
            'account_semester_id' => $accountSemesterId
          );
        }
      }else{
        $result = GradeSetting::where('account_semester_id', '=', $accountSemesterId)->get();
        if(sizeof($result) > 0){
          $result[0]['editable'] = false;
        }else{
          $result[0] = array(
            'editable'  => false,
            'passing_edit_flag' => false,
            'percentage_edit_flag'  => false,
            'passing_percentage_quizzes' => 0,
            'passing_percentage_exams' => 0,
            'exams_rate' => 0,
            'quizzes_rate' => 0,
            'attendance_rate' => 0,
            'projects_rate' => 0,
            'id' => null,
            'course_id' => $courseId,
            'account_semester_id' => $accountSemesterId
          );
        }
      }
      return $result;
    }

    public function getAnnouncements($courseId){
      $result = Announcement::where('announcement_courses.course_id', '=', $courseId)->leftJoin('announcement_courses', 'announcement_courses.announcement_id', '=', 'announcements.id')
            ->orderBy('announcement_courses.created_at', 'DESC')->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $result[$i]['account'] = $this->getAccount($result[$i]['account_id']);
          $result[$i]['edit_flag'] = false;
          $i++;
        }
        return $result;
      }else{
        return null;
      }
    }

    public function getTests($courseId){
      $result = Test::where('course_id', '=', $courseId)->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $result[$i]['d_available_time'] = date('g:i A', strtotime($result[$i]['available_time']));
          $i++;
        }
        return $result;
      }else{
        return null;
      }
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

    public function getEnrollees($courseId){
      $result = EnrolledAccount::where('course_id', '=', $courseId)->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $result[$i]['account_details'] = $this->getAccount($result[$i]['account_id']);
          $i++;
        }
        return $result;
      }else{
        return null;
      }
    }

    public function getAccount($accountId){
      $result = Account::where('id', '=', $accountId)->get();
      if(sizeof($result) > 0){
        $profile = AccountProfile::where('account_id', '=', $accountId)->orderBy('created_at', 'DESC')->get();
        $information = AccountInformation::where('account_id', '=', $accountId)->get();
        $work = AccountWorkExperience::where('account_id', '=', $accountId)->orderBy('created_at', 'DESC')->get();
        $result[0]['profile'] = (sizeof($profile) > 0) ? $profile[0] : null;
        $result[0]['information'] = (sizeof($information) > 0) ? $information[0] : null;
        $result[0]['degree'] = $this->getDegree($accountId);
        $result[0]['work'] = (sizeof($work) > 0) ? $work : null;
        return $result[0];
      }else{
        return null;
      }
    }

    public function getDegree($accountId){
        $result = AccountDegree::where('account_id', '=', $accountId)->orderBy('created_at','DESC')->get();
        if(sizeof($result) > 0){
          $school = School::where('id', '=', $result[0]['school_id'])->get();
          $result[0]['school'] = (sizeof($school) > 0) ? $school[0] : null;
        }else{
          return null;
        }
        return $result;
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
          $present = AttendanceAccount::where('attendance_id', '=', $result[$i]['id'])->where('status', '=', 'PRESENT')->get();
          $absent = AttendanceAccount::where('attendance_id', '=', $result[$i]['id'])->where('status', '=', 'ABSENT')->get();
          $late = AttendanceAccount::where('attendance_id', '=', $result[$i]['id'])->where('status', '=', 'LATE')->get();
          $all = AttendanceAccount::where('attendance_id', '=', $result[$i]['id'])->get();
          $result[$i]['present'] = (sizeof($all) > 0) ? round((sizeof($present) / sizeof($all)) * 100, 2)  : 0;
          $result[$i]['absent'] = (sizeof($all) > 0) ? round((sizeof($absent) / sizeof($all)) * 100, 2)  : 0;
          $result[$i]['late'] = (sizeof($all) > 0) ? round((sizeof($late) / sizeof($all)) * 100, 2) : 0;
          $result[$i]['total'] = sizeof($all);
          $result[$i]['d_time'] = date('g:i A', strtotime($result[$i]['time']));
          $result[$i]['view_date_split'] = Carbon::createFromFormat('Y-m-d', $key->date)->copy()->tz('Asia/Manila')->format('D,F,j,Y');

          $result[$i]['view_date'] = Carbon::createFromFormat('Y-m-d', $key->date)->copy()->tz('Asia/Manila')->format('D F j, Y');          $i++;
        }
        return $result;
      }else{
        return null;
      }
    }

    public function getReports($courseId, $accountId){
      $result = Report::where('course_id', '=', $courseId)->where('account_id', '=', $accountId)->orderBy('created_at', 'DESC')->get();
      return (sizeof($result) > 0) ? $result : null;
    }

    public function updateActiveCourse(Request $request){
      $data = $request->all();
      $this->model = new Account();
      $this->updateDB($data);
      return $this->response();
    }
}
