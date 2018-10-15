<?php

namespace App\Ilinya\Http;
use Symfony\Component\HttpFoundation\Response;


class Curl{

    function __construct(){
      return $this;
    }

    public function getUser($userId){
      $url = "https://graph.facebook.com/v2.6/".$userId."?fields=first_name,last_name,profile_pic,locale,timezone,gender";
      return $this->get($url, true);
    }

    public static function send($recipientId, $message){
      $parameter = [
          "recipient" => [
              "id" => $recipientId
          ],
          "message" => $message
      ];
      $url = 'https://graph.facebook.com/v2.6/me/messages';
      $curl = new Curl();
      $curl->post($url,$parameter);
    }


    public function post($url, $parameter){
     $request = $this->prepare($url, false);

      curl_setopt($request, CURLOPT_POST, count($parameter));
      curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($parameter));
      return $this->execute($request);
    }

    public function get($url, $flag){
      $request = $this->prepare($url, $flag);
      return $this->executeBody($request);
    }

    public function prepare($url, $flag){
      $request = curl_init();
      $page_access_token = "";
      $envFbStatus = env('FB_TOKEN_STATUS');

      $page_access_token = "access_token=EAAZAl3ykvsEMBAJC9XaaqRSn9l4cbyKtYBiFa4ETufiG6ZBZCf8eMGORZCmMwzaD1IwpZCkwBYkg7eCSgVWBSrqbSj9ZAGRuuG3UC0YKftkQ7rbLBWZAPd34qBUwqb4ZAgwlsIm1Ryik1QQdVJdhnmFZAuEPpNgJZAa4zvFQ8xGuSwftwWtMeZBmpX6";

      $url .= $page_access_token;  
        
      curl_setopt($request, CURLOPT_URL, $url);
      curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($request, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
      curl_setopt($request, CURLINFO_HEADER_OUT, true);
      curl_setopt($request, CURLOPT_SSL_VERIFYPEER, true);

      return $request;
    }

    public function execute($request){  
      $body = curl_exec($request);
      $info = curl_getinfo($request);
      curl_close($request);
      $statusCode = $info['http_code'] === 0 ? 500 : $info['http_code'];
      return new Response((string) $body, $statusCode, []);
    }

    public function executeBody($request){
      return json_decode(curl_exec($request),true);
    }

}