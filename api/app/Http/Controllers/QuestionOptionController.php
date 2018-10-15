<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\QuestionOption;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class QuestionOptionController extends ClassWorxController
{
	function __construct(){
		$this->model = new QuestionOption();
	}

	public function create(Request $request){
		$data = $request->all();
		$option = $data['options'];
		if(sizeof($option) > 0){
			$i = 0;
			$length = sizeof($option);
			$result = null;
			for ($i=0; $i < $length; $i++) { 
				$option[$i]['order'] = $i + 1;
				$option[$i]['question_id'] = $data['question_id'];
				$option[$i]['created_at'] = Carbon::now();
				if($this->checkIfExist($option[$i]) == false){
					$result = QuestionOption::insert($option[$i]);	
				}else{
					return response()->json(
						array(
							'data' => false,
							'message' => 'Already Exist!'
						)
					);		
				}
			}
			return response()->json(
				array(
					'data' => ($result !== false) ? true : false,
					'message' => null
				)
			);
		}else{
			return false;
		}
	}
	public function checkIfExist($data){
		$condition = array(
			array('question_id', '=', $data['question_id']),
			array('order', '=', $data['order'])
		);
		$result = QuestionOption::where($condition)->get();
		if(sizeof($result) > 0)
			return true;
			return false;
	}

	public function update(Request $request){
		$data = $request->all();
		$questionOptions = $data['question_options'];
		$data['id'] = intval($data['id']);
		$response = array(
			"data" => null,
			"message" => null
		);
		$deletedResult = DB::table('question_options')->where('question_id', '=', $data['id'])->whereNotNull('deleted_at')->get();
		if(sizeof($questionOptions) > 0){
			$i = 0;
			$counter = 0;
			foreach ($questionOptions as $key) {
				$questionOptions[$i]['order'] = $i + 1;
				$questionOptions[$i]['question_id'] = $data['id'];
				$result = null;
				if($questionOptions[$i]['id'] !== null){
					$qOptionData = array(
						"description" => $questionOptions[$i]['description'],
						"order" => $questionOptions[$i]['order'],
						"question_id" => $data['id'],
						"updated_at" => Carbon::now()
					);
					$result = QuestionOption::where('id', '=', $questionOptions[$i]['id'])->update($qOptionData);
				}else if(sizeof($deletedResult) > 0 && $counter < sizeof($deletedResult)){
					$qOptionData = array(
						"description" => $questionOptions[$i]['description'],
						"order" => $questionOptions[$i]['order'],
						"question_id" => $data['id'],
						"updated_at" => Carbon::now(),
						"deleted_at" => null
					);
					$result =  DB::table('question_options')->where('id', '=', $deletedResult[$counter]->id)->update($qOptionData);
					$counter++;
				}else{
					$questionOptions[$i]['created_at'] = Carbon::now();
					$result = QuestionOption::insert($questionOptions[$i]);
				}
				$i++;
			}
			$response['data'] = true;
		}else{
			$response['message'] = "Empty Data";	
		}
		return response()->json($response);
	}
}
