<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PlannerOther;
use App\Planner;
class PlannerOtherController extends ClassWorxController
{
    function __construct(){
      $this->model = new PlannerOther();
    }

    public function retrieve(Request $request){
      $data = $request->all();
      $accountId = $data['account_id'];
      $category = PlannerOther::where('account_id', '=', $accountId)->orderBy('created_at', 'DESC')->get();

      if(sizeof($category) > 0){
        $i = 0;
        foreach ($category as $key) {
          $category[$i]['notes'] = $this->getPlans($category[$i]['id'], $accountId);
          $category[$i]['add_flag'] = false;
          $category[$i]['new_plan'] = null;
          $i++;
        }
        return response()->json(array('data' => $category));
      }else{
        return response()->json(array('data' => null));
      }
    }

    public function getPlans($plannerOtherId, $accountId){
      $result = Planner::where('planner_other_id', '=', $plannerOtherId)->where('category', '=', 'others')->where('account_id', '=', $accountId)->get();
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

}
