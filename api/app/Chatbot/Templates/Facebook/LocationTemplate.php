<?php

namespace App\Ilinya\Templates\Facebook;


class LocationTemplate{
  
    public static function toArray($title, $lat, $long){
      $url = "https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.bing.com%2Fmaps%2Fdefault.aspx%3Fv%3D2%26pc%3DFACEBK%26mid%3D8100%26where1%3D".$lat."%252C%2B".$long."%26FORM%3DFBKPL1%26mkt%3Den-US&h=ATMKDos6rffIdT8a2jNPuZ8yp1ABDo7hGGfJjp29gEZIZhvECK4VXayS1N-_lRcRMWEXa7jh80hCetBNlBDzd7gPZGZLNGFJKhS0ewY9_6RmLlNgUGY&s=1&enc=AZML3vPu4YhvQ0Dn-Uo1I6ZecznlOYjnoSRPeHF5eWz4oo1O2YauhB75Ue5lPIENEfenurowVFWX71Ouh3Y1_OyQ";
      $response = [
        "attachment" => [
          "title"     => $title,
          "url"       => $url,
          "type"      => "location",
          "payload"   => [
            "coordinates" => [
                "lat"   => $lat,
                "long"  => $long
            ]
          ]
        ]
      ];
      return $response;
    }
}