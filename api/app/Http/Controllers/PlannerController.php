<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Planner;
use App\PlannerOther;
use App\Course;
use Carbon\Carbon;
class PlannerController extends ClassWorxController
{
    function __construct(){
    	$this->model = new Planner();
        $this->notRequired = array(
            'course_id', 'deadline_date', 'deadline_time', 'planner_other_id'
        );
    }

    public function create(Request $request){
        $data = $request->all();
        $model = new Planner();
        $model->account_id = $data['account_id'];
        $model->course_id = $data['course_id'];
        $model->planner_other_id = $data['planner_other_id'];
        $model->category = $data['category'];
        $model->title = $data['title'];
        $model->deadline_date = $data['deadline_date'];
        $model->deadline_time = $data['deadline_time'];
        $model->created_at = Carbon::now();
        $model->save();

        if(isset($model->id)){
            return response()->json(array(
                'data' => $model->id,
                'timetamps' => Carbon::now()
            ));
        }else{
            return response()->json(array(
                'data' => null,
                'timetamps' => Carbon::now()
            ));
        }
    }

    public function retrieveByTeacher(Request $request){
    	$data = $request->all();
    	$accountSemesterId = $data['account_semester_id'];
    	$accountId = $data['account_id'];
    	$courses = Course::where('account_semester_id', '=', $accountSemesterId)->get();

    	if(sizeof($courses) > 0){
    		$i = 0;
    		foreach ($courses as $key) {
    			$courses[$i]['notes'] = $this->getPlans($courses[$i]['id'], $accountId);
    			$courses[$i]['add_flag'] = false;
                $courses[$i]['new_plan'] = null;
    			$i++;
    		}
    		return response()->json(array('data' => $courses));
    	}else{
    		return response()->json(array('data' => null));
    	}
    }

    public function getPlans($courseId, $accountId){
        $result = Planner::where('course_id', '=', $courseId)->where('category', '=', 'course')->where('account_id', '=', $accountId)->get();
        if(sizeof($result) > 0){
            $i = 0;
            foreach ($result as $key) {
                $result[$i]['edit_flag'] = false;
                $i++;
            }
            return $result;
        }else{
            return null;
        }
    }

    public function retrieveByStudent(Request $request){
    	$data = $request->all();
    }
}
