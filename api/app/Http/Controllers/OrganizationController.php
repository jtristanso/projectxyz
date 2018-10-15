<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organization;
use App\OrganizationLogo;
use App\OrganizationMember;
use App\School;
use App\Event;
use App\EventFeaturedImage;
use App\EventTicket;
use App\Account;
use App\AccountDegree;
use App\AccountInformation;
use App\AccountProfile;
use App\AccountSchool;
use App\AccountSemester;
use App\AccountWorkExperience;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class OrganizationController extends ClassWorxController
{
    function __construct(){
      $this->model = new Organization();
      $this->notRequired = array(
        'about', 'vision', 'mission', 'address'
      );
    }

    public function create(Request $request){
      $data = $request->all();
      $this->insertDB($data);
      if($this->response['data'] > 0){
        $_orgMember = new OrganizationMember();
        $_orgMember->account_id = $data['account_id'];
        $_orgMember->organization_id = $this->response['data'];
        $_orgMember->type = 'ADMIN';
        $_orgMember->status = 'APPROVED';
        $_orgMember->created_at = Carbon::now();
        $_orgMember->save();
      }
      return $this->response();
    }

    public function retrieve(Request $request){
      $data = $request->all();
      $condition = array(
        'condition' => $data['condition']
      );
      $this->retrieveDB($condition);

      $result = $this->response['data'];
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $this->response['data'][$i]['school'] = $this->getSchool($result[$i]['school_id']);
          $this->response['data'][$i]['logo'] = $this->getLogo($result[$i]['id']);
          $this->response['data'][$i]['members'] = $this->getTotalMembers($result[$i]['id']);
          $this->response['data'][$i]['join_status'] = $this->getJoinStatus($result[$i]['id'], $data['account_id']);
          $i++;
        }
      }
      return $this->response();
    }

    public function retrieveMyAffiliates(Request $request){
      $data = $request->all();

      $result = DB::table('organization_members')
                ->leftJoin('organizations as T2', 'T2.id', '=', 'organization_members.organization_id')
                ->where('organization_members.account_id', '=', $data['account_id'])
                ->where('organization_members.status', '=', 'APPROVED')
                ->orderBy('T2.name', 'ASC')
                ->get(['organization_members.*', 'T2.*', 'organization_members.type as member_type']);
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $result[$i]->school = $this->getSchool($key->school_id);
          $result[$i]->logo = $this->getLogo($key->organization_id);
          $result[$i]->events = $this->getEvents($key->organization_id, $data['account_id']);
          $result[$i]->members = $this->getMembers($key->organization_id);
          $i++;
        }
      }
      return response()->json(array('data' => $result, 'timestamps' => Carbon::now()));
    }

    public function getAccountDetails($accountId){
      $result = Account::where('id', '=', $accountId)->get();
      if(sizeof($result) > 0){
        $profile = AccountProfile::where('account_id', '=', $accountId)->orderBy('created_at', 'DESC')->get();
        $information = AccountInformation::where('account_id', '=', $accountId)->get();
        $degree = AccountDegree::where('account_id', '=', $accountId)->orderBy('created_at','DESC')->get();
        $work = AccountWorkExperience::where('account_id', '=', $accountId)->orderBy('created_at', 'DESC')->get();
        $result[0]['profile'] = (sizeof($profile) > 0) ? $profile[0] : null;
        $result[0]['information'] = (sizeof($information) > 0) ? $information[0] : null;
        $result[0]['degree'] = (sizeof($degree) > 0) ? $degree : null;
        $result[0]['work'] = (sizeof($work) > 0) ? $work : null;
        return $result[0];
      }else{
        return null;
      }
    }

    public function getSchool($schoolId){
      $result = School::where('id', '=', $schoolId)->get();
      return (sizeof($result) > 0) ? $result[0] : null;
    }

    public function getLogo($organizationId){
      $result = OrganizationLogo::where('organization_id', '=', $organizationId)->orderBy('created_at', 'DESC')->get();
      return (sizeof($result) > 0) ? $result[0] : null;
    }

    public function getMembers($organizationId){
      $result = DB::table('organization_members as T1')
                  ->leftJoin('account_informations as T2', 'T2.account_id', '=', 'T1.account_id')
                  ->leftJoin('account_profiles as T3', 'T3.account_id', '=', 'T1.account_id')
                  ->where('T1.organization_id', '=', $organizationId)
                  ->where('T1.deleted_at', '=', null)
                  ->orderBy('T2.last_name', 'ASC')
                  ->get(['T1.id as organization_member_id', 'T1.account_id as account_id_params', 'T1.*', 'T2.*', 'T3.*']);
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $result[$i]->degree = $this->getAccountDegrees($key->account_id_params);
          $i++;
        }
      }
      return (sizeof($result) > 0) ? $result : null;
    }

    public function getAccountDegrees($accountId){
      $result = AccountDegree::where('account_id', '=', $accountId)->orderBy('year_started', 'DESC')->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $school = School::where('id', '=', $result[$i]['school_id'])->get();
          $result[$i]['school'] = (sizeof($school) > 0) ? $school[0] : null; 
          $i++;
        }
        return $result[0];
      }else{
        return null;
      }
    }

    public function getTotalMembers($organizationId){
      $result = OrganizationMember::where('organization_id', '=', $organizationId)->where('status', '=', 'APPROVED')->count();
      return $result;
    }

    public function getJoinStatus($organizationId, $accountId){
      $result = OrganizationMember::where('organization_id', '=', $organizationId)->where('account_id', '=', $accountId)->get();
      return (sizeof($result) > 0) ? $result[0]['status'] : null;
    }

    public function getEvents($organizationId, $accountId){
      $result = Event::where('organization_id', '=', $organizationId)->orderBy('date', 'DESC')->get();
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $eventDateTime = $result[$i]['date'].' '.$result[$i]['end_time'];
          $eventDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $eventDateTime);
          $currentDate = Carbon::now();
          $diff = $currentDate->diffInSeconds($eventDateTime);
          $result[$i]['date_flag'] = ($diff < 0) ? false : true;
          $result[$i]['featured'] = $this->getEventFeaturedImages($result[$i]['id']);
          $result[$i]['view_date'] = Carbon::createFromFormat('Y-m-d', $result[$i]['date'])->copy()->tz('Asia/Manila')->format('M d');
          $result[$i]['view_start_time'] = date('g:i A', strtotime($result[$i]['start_time']));
          $result[$i]['view_end_time'] = date('g:i A', strtotime($result[$i]['end_time']));
          $result[$i]['my_ticket'] = $this->getEventTicket($accountId, $result[$i]['id']);
          $result[$i]['total_tickets'] = intval($result[$i]['max_attendees']) - $this->getTotalTickets($result[$i]['id']);
          $i++;
        }
      }
      return (sizeof($result) > 0) ? $result : null;
    }

    public function getTotalTickets($eventId){
      return EventTicket::where('event_id', '=', $eventId)->count();
    }

    public function getEventTicket($accountId, $eventId){
      $result = EventTicket::where('account_id', '=', $accountId)->where('event_id', '=', $eventId)->get();
      return (sizeof($result) > 0) ? $result[0] : null;
    }

    public function getEventFeaturedImages($eventId){
      $result = EventFeaturedImage::where('event_id', '=', $eventId)->orderBy('created_at', 'DESC')->get();
      return (sizeof($result) > 0) ? $result[0] : null;
    }

    public function updateActiveOrg(Request $request){
      $data = $request->all();
      $this->model = new Account();
      $this->updateDB($data);
      return $this->response();
    }
}
