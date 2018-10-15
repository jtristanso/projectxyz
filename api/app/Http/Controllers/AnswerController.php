<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Answer;
use App\Question;
class AnswerController extends ClassWorxController
{
    function __construct(){
      $this->model = new Answer();
      $this->notRequired = array(
        'answer'
      );
    }

    public function create(Request $request){
      $request = $request->all();
      $i = 0;
      $data = $request['data'];
      foreach ($data as $key) {
         $questionResult = Question::where('id', '=', $data[$i]['id'])->get();
         $answer = (sizeof($questionResult) > 0) ? $questionResult[0]['answer'] : null;
         $this->model = new Answer();
         $insertData = array(
          'question_id'   => $data[$i]['id'],
          'answer'        => $data[$i]['answer'],
          'account_id'    => $data[$i]['account_id'],
          'answer_status' => $this->checkAnswer($data[$i]['type'], $data[$i]['answer'], $answer)
         );
         if($this->checkIfExist($data[$i]['account_id'], $data[$i]['id']) == false){
            $this->model = new Answer();
            $this->insertDB($insertData);
         }else{
          //
         }
         $i++;
      }
      return $this->response();
    }

    public function checkIfExist($accountId, $questionId){
      $result = Answer::where('account_id', '=', $accountId)->
                        where('question_id', '=', $questionId)->get();
      return (sizeof($result) > 0) ? true : false;
    }
    public function checkAnswer($type, $answer, $questionAnswer){
      if($type == 'multiple_choice'){
        return (intval($answer) == intval($questionAnswer)) ? 1 : 0;
      }else if($type == 'multiple_answers'){
        $arrayAnswers = explode(',', $answer);
        $counter = 0;
        foreach ($arrayAnswers as $key) {
          $tempAnswer = ','.$key.',';
          if(strpos($questionAnswer, $tempAnswer)){
            $counter++;
          }
        }
        return $counter;
      }else if($type == 'short_answer'){
        return ($answer == $questionAnswer) ? 1 : 0;
      }
    }
}
