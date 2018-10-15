<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Calendar;
use Carbon\Carbon;
class CalendarController extends ClassWorxController
{
    function __construct(){
    	$this->model = new Calendar();
    }

    protected $response = array(

    );
    public function retrieve(Request $request){
      $data = $request->all();
      $start = null;
      $plusMonth = $data['plus_month'];
      $start = null;
      if($plusMonth != null && $plusMonth != ''){
        $start = Carbon::now()->startOfMonth()->addMonth($plusMonth);
      }else{
        $start = Carbon::now()->startOfMonth()->subMonth($data['less_month']);
      }
      
      // get the day of the first day
      // get the day of the last day
      $startDay = $start->format('D');
      $currentDate = null;
      $startRemDays = $this->getStartRemainingDays($startDay);
      $counter = 0;
      for ($i=0; $i < 5; $i++) {
        $arrayOfDates = array();
        for ($j=0; $j < 7; $j++) { 
          $date = ($plusMonth != null) ? Carbon::now()->startOfMonth()->addMonth($plusMonth)->subDay($startRemDays)->addDay($counter): Carbon::now()->startOfMonth()->subMonth($data['less_month'])->subDay($startRemDays)->addDay($counter);
          $arrayOfDates[$j] = array(
            'date' => $date->format('d'),
            'day'  => $date->format('D'),
            'year' => $date->format('Y'),
            'month' => $date->format('m'),
            'month_text' => $date->format('F'),
            'complete_date' => $date->format('Y-m-d'),
            'current_date' => (Carbon::now()->format('Y-m-d') == $date->format('Y-m-d')) ? true : false,
            'selected' => false
          );
          $counter++;
          if($arrayOfDates[$j]['current_date'] == true){
            $currentDate = $arrayOfDates[$j];
          }
        }
        $this->response[$i]['dates']  = $arrayOfDates;
      }
      return response()->json(array(
        'data' => $this->response,
        'current' => $currentDate
      ));
    }

    public function retrieveByWeek(Request $request){
      $data = $request->all();
      $start = null;
      $plusWeek = $data['plus_week'];
      $start = null;
      if($plusWeek != null && $plusWeek != ''){
        $start = Carbon::now()->startOfMonth()->addWeek($plusWeek);
      }else{
        $start = Carbon::now()->startOfMonth()->subWeek($data['less_week']);
      }
      $startDay = $start->format('D');
      $currentDate = null;
      $startRemDays = $this->getStartRemainingDays($startDay);
      $counter = 0;
      $arrayOfDates = array();
      for ($j=0; $j < 7; $j++) { 
        $date = ($plusWeek != null) ? Carbon::now()->addWeek($plusWeek)->subDay($startRemDays)->addDay($counter): Carbon::now()->addWeek($data['less_week'])->subDay($startRemDays)->addDay($counter);
        $arrayOfDates[$j] = array(
          'date' => $date->format('d'),
          'day'  => $date->format('D'),
          'year' => $date->format('Y'),
          'month' => $date->format('m'),
          'month_text' => $date->format('F'),
          'complete_date' => $date->format('Y-m-d'),
          'current_date' => (Carbon::now()->format('Y-m-d') == $date->format('Y-m-d')) ? true : false,
          'selected' => false
        );
        $counter++;
        if($arrayOfDates[$j]['current_date'] == true){
          $currentDate = $arrayOfDates[$j];
        }
      }
      return response()->json(array(
        'data' => $arrayOfDates
      ));
    }

    public function getStartRemainingDays($startDay){
      $days = array(
        'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'
      );
      $counter = 0;
      for ($counter = 0; $counter < 7; $counter++) { 
        if($days[$counter] == $startDay){
          break;
        }
      }
      return $counter;
    }
}
