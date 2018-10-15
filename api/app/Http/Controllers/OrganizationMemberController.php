<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrganizationMember;
use App\AccountDegree;
use App\School;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class OrganizationMemberController extends ClassWorxController
{
    function __construct(){
      $this->model = new OrganizationMember();
    }

    public function retrieve(Request $request){
    	$data = $request->all();
      $result = DB::table('organization_members as T1')
                  ->leftJoin('account_informations as T2', 'T2.account_id', '=', 'T1.account_id')
                  ->leftJoin('account_profiles as T3', 'T3.account_id', '=', 'T1.account_id')
                  ->where('T1.organization_id', '=', $data['organization_id'])
                  ->where('T1.deleted_at', '=', null)
                  ->orderBy('T2.last_name', 'ASC')
                  ->get(['T1.id as organization_member_id', 'T1.account_id as account_id_params', 'T1.*', 'T2.*', 'T3.*']);
      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $result[$i]->degree = $this->getAccountDegrees($key->account_id_params);
          $i++;
        }
        return response()->json(array(
	      	'data' => $result,
	      	'timestamps' => Carbon::now()
	      ));
      }else{
      	return response()->json(array(
	      	'data' => null,
	      	'timestamps' => Carbon::now()
	      ));
      } 
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
}
