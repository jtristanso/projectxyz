<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_Spreadsheet;
use Google_Service_Sheets_Request;
use Google_Service_Sheets_BatchUpdateSpreadsheetRequest;
use Google_Service_Sheets_ValueRange;
use Google_Service_Sheets_BatchUpdateValuesRequest;
use App\Report;
use Carbon\Carbon;

class GoogleSheetController extends ReportController
{

    protected $client_id     = '501790273381-90ii644bc32va996b12uh5u9jpm1uli7.apps.googleusercontent.com';
    protected $client_secret = 'B7NOVHTYeOTcbkXpHNEImnmB';
    protected $client;

    protected $response = array(
      'data'      => null,
      'redirect'  => null,
      'timestamp' => null
    );

    protected $redirectUrl = null;



    function __construct(){
      $credentials = storage_path().'/credentials/credentials.json';
      $this->client = new Google_Client();
      $this->client->setApplicationName('ClassWorx Generated Reports');
      $this->client->setScopes(Google_Service_Sheets::SPREADSHEETS);
      $this->client->setAuthConfig($credentials);
      $this->client->setAccessType("offline");
    }

    public function response(){
      $this->response['timestamp'] = Carbon::now();
      return response()->json($this->response);
    }

    public function getAuthUrl(Request $request){
      $data = $request->all();
      $this->client->setRedirectUri($data['config']['GOOGLE_REDIRECT_URI']);
      $this->response['redirect'] = $this->client->createAuthUrl();
      $_report = new Report();
      $_report->account_id = $data['account_id'];
      $_report->course_id = $data['course_id'];
      $_report->description = $data['description'];
      $_report->client_ip = (isset($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP'] : 'empty';
      $_report->remote_address = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'empty';
      $_report->address = (isset($_SERVER['HTTP_X_FORWARDE‌​D_FOR'])) ? $_SERVER['HTTP_X_FORWARDE‌​D_FOR'] : 'empty';
      $_report->status = 'created';
      $_report->token = null;
      $_report->spreadsheet = null;
      $_report->spreadsheet_title = null;
      $_report->created_at = Carbon::now();
      $_report->save();
      return $this->response();
    }

    public function setAccessToken($code, $data){
      if($code != null){
        $this->client->authenticate($code);
        $access_token = $this->client->getAccessToken();
        $this->client->setAccessToken($access_token['access_token']);
        if($this->client->isAccessTokenExpired()){
          $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
        }
        Report::where('id', '=', $data['id'])->update(array('token' => $access_token['access_token']));
      }else{
        echo 'Emty code';
      }
    }

    public function generate(Request $request){
      $url =  $request->fullUrl();
      $code = $this->extractCode($url);
      $this->getConfigByExecute($url);
      $code = str_replace('%', '/', $code);
      // echo $code;
      $client_ip = (isset($_SERVER['HTTP_CLIENT_IP'])) ? $_SERVER['HTTP_CLIENT_IP'] : 'empty';
      $remote_address = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'empty';
      $address = (isset($_SERVER['HTTP_X_FORWARDE‌​D_FOR'])) ? $_SERVER['HTTP_X_FORWARDE‌​D_FOR'] : 'empty';
      
      $reportResult = Report::where('status', '=', 'created')
        ->where('client_ip', '=', $client_ip)
        ->where('remote_address', '=', $remote_address)
        ->where('address', '=', $address)
        ->where('status', '=', 'created')
        ->orderBy('created_at', 'DESC')
        ->get();
      
      if(sizeof($reportResult) > 0){
        $this->setAccessToken($code, $reportResult[0]);
        switch ($reportResult[0]['description']) {
          case 'attendance':
            $this->generateAttendance($code, $reportResult[0]);
            break;
          
          default:
            # code...
            break;
        }
      }
    }

    public function extractCode($url){
      $parameter = $this->extract($url);
      $code = explode('=', $parameter[1]);
      return $code[1];
    }

    public function getConfigByExecute($url){
      $array = explode('/', $url);
      if($array[2] == 'classworx.co' || $array[2] == 'www.classworx.co'){
        $this->redirectUrl = 'http://classworx.co/#/reports/ft';
      }else{
        $this->redirectUrl = 'http://localhost:8080/#/reports/ft';
      }
    }

    public function extract($url){
      $array = explode('/', $url);
      $parameter = null;
      $i = 0;
      foreach ($array as $key) {
        if(strpos($array[$i], '?')){
          $parameter = explode('?', $array[$i]);
        }
        $i++;
      }
      return $parameter;
    }

    public function generateAttendance($code, $data){
      $service = new Google_Service_Sheets($this->client);
      $requestBody = new Google_Service_Sheets_Spreadsheet();
      $spreadsheet = $service->spreadsheets->create($requestBody);


      $course = $this->retrieveCourseDetails($data['course_id']);

      $date = Carbon::now();
      $title = $course['code'].' - '.$date->format('F j, Y');
      $updateTitle = [
        // Change the spreadsheet's title.
        new Google_Service_Sheets_Request([
            'updateSpreadsheetProperties' => [
                'properties' => [
                    'title' => $title
                ],
                'fields' => 'title'
            ]
        ])
      ];

      $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
          'requests' => $updateTitle
      ]);

      $response = $service->spreadsheets->batchUpdate($spreadsheet['spreadsheetId'], $batchUpdateRequest);

      $body =  new Google_Service_Sheets_BatchUpdateValuesRequest([
        'valueInputOption' => 'USER_ENTERED',
        'data' => $this->attendance($course['id'])
      ]);

      $result = $service->spreadsheets_values->batchUpdate($spreadsheet['spreadsheetId'], $body);

      $exist = Report::where('account_id', '=', $request['account_id'])
              ->where('status', '=', 'created')
              ->orderBy('created_at', 'DESC')
              ->get();

      if(sizeof($exist) > 0){
        $exist = Report::where('id', '=', $exist[0]['id'])
            ->update(array('spreadsheet' => $spreadsheet['spreadsheetId'], 'spreadsheet_title' => $title, 'status' => 'finished'));
      }

      return redirect($this->redirectUrl);
    }

    public function sample(Request $request){
      $data = $request->all();
      $code = $data['code'];
      $accountId = $data['account_id'];
      $this->setAccessToken($code, $accountId);
    }
}
