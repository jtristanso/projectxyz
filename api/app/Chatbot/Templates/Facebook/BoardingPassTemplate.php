<?php

namespace App\Ilinya\Templates\Facebook;

/**
* 
*/
class BoardingPassTemplate
{

  public static function toArray($text, $buttons){
    
    $resultButtons = [];
    foreach ($buttons as $button) {
      $resultButtons[] = $button instanceof ButtonElement? $button->toArray(): $button;
    }

    $response =  [
      "attachment"  => [
        "type"      => 'template',
        "payload"   =>  [
          "template_type" => "airline_boardingpass",
          "intro_message" => null,
          "buttons"       => $resultButtons
        ]
      ]
    ];
    return $response;
  }

}