<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\AccountDegree;
use App\AccountInformation;
use App\AccountProfile;
use App\AccountSchool;
use App\AccountSemester;
use App\AccountWorkExperience;
use App\Announcement;
use App\AnnouncementCourse;
use App\Answer;
use App\Attendance;
use App\AttendanceAccount;
use App\Calendar;
use App\Course;
use App\EnrolledAccount;
use App\GradeSetting;
use App\Notification;
use App\NotificationSetting;
use App\Question;
use App\QuestionOption;
use App\Resource;
use App\ResourceViewer;
use App\School;
use App\Semester;
use App\Test;
use App\Topic;
use App\TopicComment;
use App\TopicCommentReply;
use App\TopicCommentStar;
use Google_Service_Sheets_ValueRange;
use Google_Service_Sheets_BatchUpdateValuesRequest;
use Carbon\Carbon;

class ReportController extends ClassWorxController{

	protected $columns = [
		'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
		'M', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y',
		'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI'];


	public function sheetHeaderCourseDetails($courseId){
		$course = $this->retrieveCourseDetails($courseId);
		

		$values = [
			[$course['code'], $course['description'].' - ('.$course['units'].') Unit(s)']
		];

		$data = new Google_Service_Sheets_ValueRange([
      'values'  => $values,
      'majorDimension'  => 'ROWS',
      'range'   => 'A1:B1'
    ]); 

    return $data;
	}

	public function setHeaderCourseTime($courseId){
		$course = $this->retrieveCourseDetails($courseId);
		$values = [
			[$course['time_start'].' - '.$course['time_end'].' '.$course['days']]
		];

    $data = new Google_Service_Sheets_ValueRange([
      'values'  => $values,
      'majorDimension'  => 'ROWS',
      'range'   => 'A2'
    ]);

    return $data;
	}


	public function attendance($courseId){

		$enrolees = $this->retrieveEnrolees($courseId);
		$attendances = $this->retrieveAttendance($courseId);
		$response = [];
		$response[] = $this->sheetHeaderCourseDetails($courseId);
		$response[] = $this->setHeaderCourseTime($courseId);
		if(sizeof($enrolees) > 0 && sizeof($attendances) > 0){
			$counter = 0;

			// ID Number, name, course, 
			$array = ['ID Number', 'Name', 'Course'];
			$h = 3;
			foreach ($attendances as $key) {
				$array[$h] = Carbon::createFromFormat('Y-m-d', $key->date)->copy()->tz('Asia/Manila')->format('D F j,Y').' @'.date('g:i A', strtotime($key->time));
				$h++;
				$counter++;
			}
			if($h > 3){
				$array[$h] = 'Summary(T - P - L - A)';
			}
			$values = [
				$array
			];
			$range = ($counter > 0) ? 'A4:'.$this->columns[$counter + 3].'4' : 'A4:C4';
			$response[] = new Google_Service_Sheets_ValueRange([
	      'values'  => $values,
	      'majorDimension'  => 'ROWS',
	      'range'   => $range
    	]);

			$i = 0;
			$row = 5;
			foreach ($enrolees as $keyEnrolees) {
				$j = 3;
				$acccount = $this->retrieveAccountDetails($enrolees[$i]['account_details']['id']);
				$array = [
					$acccount['degree'][0]['school_student_code'], 
					$acccount['information']['last_name'].', '.$acccount['information']['first_name'],
					$acccount['degree'][0]['course']
				];

				$late = 0;
				$absent = 0;
				$present = 0;
				$counter = 0;
				foreach ($attendances as $key) {
					$attendanceResult = AttendanceAccount::where('attendance_id', '=', $key->id)->where('account_id', '=', $enrolees[$i]['account_details']['id'])->get();
					if(sizeof($attendanceResult) > 0){
						$array[$j] = $attendanceResult[0]['status'];
						if($attendanceResult[0]['status'] == 'LATE'){
							$late++;
						}else if($attendanceResult[0]['status'] == 'ABSENT'){
							$absent++;
						}else if($attendanceResult[0]['status'] == 'PRESENT'){
							$present++;
						}
					}else{
						$array[$j] = '-';
					}
					$j++;
					$counter++;
				}
				$range = ($counter > 0) ? 'A'.$row.':'.$this->columns[$counter + 3].$row : 'A'.$row.':C'.$row;
				$total = sizeof($attendances);
				$array[$j] = $total.' - '.$present.' - '.$late.' - '.$absent;
				$values = [
					$array
				];
				$response[] = new Google_Service_Sheets_ValueRange([
		      'values'  => $values,
		      'majorDimension'  => 'ROWS',
		      'range'   => $range
	    	]);
				$i++;
				$row++;
			}

			return $response;
		}else{
			return $response;
		}
	}

	public function exams(){
		return null;
	}

	public function grades(){
		return null;
	}
}
