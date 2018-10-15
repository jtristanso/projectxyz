<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventTicket;
use App\Event;
use App\EventFeaturedImage;
use Carbon\Carbon;
use LaravelQRCode\Facades\QRCode;
use Illuminate\Support\Facades\DB;
class EventTicketController extends ClassWorxController
{
    function __construct(){
      $this->model = new EventTicket();
      $this->notRequired = array(
        'attendance_status', 'attendance_remarks'
      );
    }

    public function create(Request $request){
      $data = $request->all();
      $path = '../storage/app/qrCodes/';
      $date = Carbon::now()->toDateString().'_';
      $time = str_replace(':', '_',Carbon::now()->toTimeString()).'.png';
      $filename = 'EVENT_'.$data['organization_id'].'_'.$date.$time;
      $data['code'] = $this->generateCode();
      $data['attendance_remarks'] = null;
      $data['attendance_status'] = null;
      $text = 'event/'.$data['code'];
      $qrCode = QRCode::text($text)->setSize(10)->setOutfile($path.$filename)->png();
      $data['qr_code_url'] = '/storage/qr_code/'.$filename;
      $this->insertDB($data);
      return $this->response();
    }

    public function generateCode(){
      $code = 'E-'.substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 254);
      $codeExist = EventTicket::where('code', '=', $code)->get();
      if(sizeof($codeExist) > 0){
        $this->generateCode();
      }else{
        return $code;
      }
    }

    public function retrieveByCodeMobile(Request $request){
      $data = $request->all();
      $result = DB::table('event_tickets as T1')
            ->leftJoin('events as T2', 'T2.id', '=', 'T1.event_id')
            ->where('T1.account_id', '=', $data['account_id'])
            ->where('T1.deleted_at', '=', null)
            ->where('T1.code', '=', $data['code'])
            ->get(['T1.*', 'T2.title', 'T2.venue']);
      return response()->json(
        array(
          "data"  => (sizeof($result) > 0) ? $result : [],
          "error" => (sizeof($result) > 0) ? "" : "You are not allowed for this event.",
          "timestamps" => Carbon::now()
        )
      );
    }

    public function retrieveByCode(Request $request){
      $data = $request->all();
     
      $this->model = new EventTicket();
      $this->retrieveDB($data);
      $result = $this->response['data'];

      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $this->response['data'][$i]['event'] = $this->getEvent($result[$i]['event_id']);
          $i++;
        }
      }
      return $this->response();
    }

    public function retrieveMyTickets(Request $request){
      $data = $request->all();
      $this->model = new EventTicket();
      $this->retrieveDB($data);
      $result = $this->response['data'];

      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $this->response['data'][$i]['event'] = $this->getEvent($result[$i]['event_id']);
          $i++;
        }
      }
      return $this->response();
    }

    public function retrieveByAttendees(Request $request){
      $data = $request->all();
      $this->model = new EventTicket();
      $this->retrieveDB($data);
      $result = $this->response['data'];

      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $this->response['data'][$i]['account'] = $this->retrieveAccountDetails($result[$i]['account_id']);
          $i++;
        }
      }
      return $this->response();
    }

    public function getEvent($eventId){
      $result = Event::where('id', '=', $eventId)->Where('deleted_at', '=', null)->orWhere('deleted_at', '!=', null)->get();
      if(sizeof($result) > 0){
        $result[0]['view_date'] = Carbon::createFromFormat('Y-m-d', $result[0]['date'])->copy()->tz('Asia/Manila')->format('M d, Y');
        $result[0]['view_date_without_year'] = Carbon::createFromFormat('Y-m-d', $result[0]['date'])->copy()->tz('Asia/Manila')->format('M d');
        $result[0]['view_start_time'] = date('g:i A', strtotime($result[0]['start_time']));
        $result[0]['view_end_time'] = date('g:i A', strtotime($result[0]['end_time']));
        $result[0]['featured'] = $this->getEventFeaturedImages($eventId);
      }
      return (sizeof($result) > 0) ? $result[0] : null;
    }

    public function getEventFeaturedImages($eventId){
      $result = EventFeaturedImage::where('event_id', '=', $eventId)->orderBy('created_at', 'DESC')->get();
      return (sizeof($result) > 0) ? $result[0] : null;
    }

    public function updateByMobile(Request $request){
      $data = $request->all();
      $this->model = new EventTicket();
      $this->updateDB($data);
      $result = $this->response['data'];

      return response()->json(array(
        "data" => ($result == true) ? [array("data" => $result)] : [],
        "error" => ($result == true) ? "" : "Unable to Update",
        "timestamps" => Carbon::now()
      ));
    }
}
