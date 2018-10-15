<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Announcement;
use App\AnnouncementCourse;
use Carbon\Carbon;
use App\Course;
use App\EnrolledAccount;
use App\AccountInformation;
use App\AccountProfile;
use App\Notification;
class AnnouncementController extends ClassWorxController
{
    function __construct(){
      $this->model = new Announcement();
    }

    public function create(Request $request){
      $data = $request->all();
      $accountId = $data['account_id'];
      $message = $data['message'];
      $courseId = $data['course_id'];
      $accountSemesterId = $data['account_semester_id'];

      $insertData = array(
        'account_id'  => $accountId,
        'message'     => $message
      );
      $this->model = new Announcement();
      $result = $this->insertDB($insertData);
      if($this->response['data'] > 0){
        $announcementId = $this->response['data'];
        if($courseId == null){
          $courses = Course::where('account_semester_id', '=', $accountSemesterId)->get();
          $i = 0;
          foreach ($courses as $key) {
            $insertData1 = array(
              'announcement_id' => $announcementId,
              'course_id'       => $courses[$i]['id'],
              'created_at'      => Carbon::now()
            );
            $annCourse = new AnnouncementCourse();
            $result1 = $annCourse->insert($insertData1);
            $this->insertNotification($this->retrieveCourseDetails($courses[$i]['id']), $this->retrieveAccountDetails($accountId));
            $i++;
          }
        }else{
          $insertData1 = array(
            'announcement_id' => $announcementId,
            'course_id'       => $courseId,
            'created_at'      => Carbon::now()
          );
          $annCourse = new AnnouncementCourse();
          $result1 = $annCourse->insert($insertData1);
          $this->insertNotification($this->retrieveCourseDetails($courseId), $this->retrieveAccountDetails($accountId));
        }
        return $this->response();
      }else{
        return $this->response();
      }
    }


    public function insertNotification($course, $account){
      $model = new Notification();
      $data = array(
        'creator'           => $account['id'],
        'course_id'         => $course['id'],
        'account_id'        => null,
        'title'             => 'New Announcement Added',
        'description'       => $account['information']['first_name'].' '.$account['information']['last_name']. ' added new announcements to '.$course['code'],
        'payload'           => 'redirect',
        'url'               => 'courses/en',
        'parameter'         => 'announcements',
        'status'            => 'pushed',
        'created_at'        => Carbon::now()
      );
      $model->insert($data);
    }


    public function retrieve(Request $request){
      $data = $request->all();
      $this->model = new Announcement();
      $result = $this->retrieveDB($data);
      if (sizeof($result) > 0) {
        $i = 0;
        foreach ($result as $key) {
          $coursesResult = AnnouncementCourse::where('announcement_id', '=', $result[$i]['id'])->get();
          $j = 0;
          $this->response['data'][$i]['edit'] = false;
          foreach ($coursesResult as $keyJ) {
            $courseDetails = Course::where('id', '=', $coursesResult[$j]['course_id'])->get();
            $coursesResult[$j]['course_details'] = (sizeof($courseDetails) > 0) ? $courseDetails[0] : null;
            $j++;
          }
          $this->response['data'][$i]['courses'] = (sizeof($coursesResult) > 0) ? $coursesResult : null;
          $i++;
        }
        return $this->response();
      }else{
        return $this->response();
      }
    }

    public function retrieveStudent(Request $request){
      $data = $request->all();
      $enrolledCourses = EnrolledAccount::where('account_id', '=', $data['account_id'])->where('status', '=', 1)->orderBy('created_at', 'DESC')->get();
      $response = array(
        'data' => null
      );
      if(sizeof($enrolledCourses) > 0){
        $i = 0;
        $result = [];
        foreach ($enrolledCourses as $key) {
          $announcements = Announcement::where('announcement_courses.course_id', '=', $enrolledCourses[$i]['course_id'])
                          ->leftJoin('announcement_courses', 'announcement_courses.announcement_id', '=', 'announcements.id')
                          ->orderBy('announcement_courses.created_at', 'DESC')->get();
          if(sizeof($announcements) > 0){
             $j = 0; 
             foreach ($announcements as $key) {
                $courseDetails = Course::where('id', '=', $announcements[$j]['course_id'])->get();
                $accountInfoResult = AccountInformation::where('account_id', '=', $announcements[$j]['account_id'])->get();
                $accountProfileResult = AccountProfile::where('account_id', '=', $announcements[$j]['account_id'])->get();
                $announcements[$j]['course_details'] = (sizeof($courseDetails) > 0) ? $courseDetails[0] : null;
                $announcements[$j]['account_information'] = (sizeof($accountInfoResult) > 0) ? $accountInfoResult[0] : null;
                $announcements[$j]['account_profile'] = (sizeof($accountProfileResult) > 0) ? $accountProfileResult[0] : null;
                $j++;
             }
             $response['data'][$i]['announcements'] = $announcements;
             $i++;
          }else{
            //
          }
        }
        return response()->json($response);
      }else{
        return response()->json($response);
      }
    }
}
