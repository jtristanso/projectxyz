<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\QuestionOption;
use Carbon\Carbon;
class QuestionController extends ClassWorxController
{
    function __construct(){
    	$this->model = new Question();
      $this->notRequired = array(
        'answer'
      );
    }

    public function create(Request $request){
      $data = $request->all();
      $data['order'] = $this->getLatestOrder($data['test_id']);
      if($data['type'] == 'multiple_answers' || $data['type'] == 'multiple_choice'){
        $data['answer'] = '';
      }else{
        //
      }
      $this->model = new Question();
      $this->insertDB($data);
      return $this->response();
    }

    public function getLatestOrder($testId){
      $result = Question::where('test_id', '=', $testId)->orderBy('order', 'DESC')->limit(1)->get();
      if(sizeof($result) > 0)
        return $result[0]->order + 1;
        return 1;
    }

    public function retrieve(Request $request){
      $data = $request->all();
      $response = array(
        "data" => null,
        "message" => null
      );
      $result = null;
      if(isset($data['condition'])){
        $result = Question::where($data['condition'][0]['column'], '=', $data['condition'][0]['value'])->orderBy('order', 'ASC')->get();
        if(sizeof($result) > 0){
          $i = 0;
          foreach ($result as $key) {
              $result[$i]['edit'] = false;
              $result[$i]['error_message'] = null;
              $qOResult = QuestionOption::where('question_id', '=', $key->id)->orderBy('order', 'ASC')->get();
              if(sizeof($qOResult) > 0){
                $result[$i]['question_options'] = $qOResult;
              }else{
                $result[$i]['question_options'] = [];
              }
              $i++;
          }
          $response['data'] = $result;
        }else{
          $response['message'] = 'Empty';
        }
        return response()->json($response);
      }else{
        $this->retreveDB($data);
        return $this->response();
      }
    }

    public function retrieveOnTake(Request $request){
      $data = $request->all();
      $response = array(
        "data" => null,
        "message" => null
      );
      $result = null;
      if(isset($data['condition'])){
        // $result = Question::where($data['condition'][0]['column'], '=', $data['condition'][0]['value'])->inRandomOrder()->get();
        $result = Question::where($data['condition'][0]['column'], '=', $data['condition'][0]['value'])->orderBy('order', 'ASC')->get();
        if(sizeof($result) > 0){
          $i = 0;
          foreach ($result as $key) {
              $result[$i]['answer'] = '';
              // $qOResult = QuestionOption::where('question_id', '=', $key->id)->inRandomOrder()->get();
              $qOResult = QuestionOption::where('question_id', '=', $key->id)->orderBy('order', 'ASC')->get();
              if(sizeof($qOResult) > 0){
                $result[$i]['question_options'] = $qOResult;
              }else{
                $result[$i]['question_options'] = [];
              }
              $i++;
          }
          $response['data'] = $result;
        }else{
          $response['message'] = 'Empty';
        }
        return response()->json($response);
      }else{
        $this->retreveDB($data);
        return $this->response();
      }
    }

    public function delete(Request $request){
      $data = $request->all();
      $id = $data['value'];
      $column = $data['column'];
      $questionId = $data['id'];
      $response = array(
        "data"  => null,
        "message" => null
      );
      $deleteData = array(
        "deleted_at" => Carbon::now()
      );
      Question::where('id', '=', $questionId)->update($deleteData);
      $result = Question::where($column, '=', $id)->orderBy('order', 'ASC')->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $updateData = array(
            "order" => $i + 1,
            "updated_at" => Carbon::now()
          );
          Question::where('id', '=', $key->id)->update($updateData);
          $i++;
        }
        $response["data"] = true;
      }else{
        $response["message"] = "Error";
      }
      return response()->json($response);
    }

    public function update(Request $request){
      $data = $request->all();
      $response = array(
        "data" => null,
        "message" => null
      );
      if(isset($data['data']) == true){
        // Update Questions table
        // Update Question Options Table
        $questionData = array(
          "question" => $data['data']['question'],
          "answer"   => $data['data']['answer'],
          "order"    => $data['data']['order'],
          "points"    => $data['data']['points'],
          "updated_at" => Carbon::now()
        );
        $result = Question::where('id', '=', $data['data']['id'])->update($questionData);
        $response["data"] = true;
      }else{
        $response["message"] = "data is not found";
      }
      return response()->json($response);
    }
}
