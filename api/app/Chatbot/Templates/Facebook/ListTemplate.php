<?php

namespace App\Ilinya\Templates\Facebook;


class ListTemplate{

  public static function toArray($elements, $buttons){
    
    $resultButtons = [];
    $resultElements = [];

    foreach ($buttons as $button) {
      $resultButtons[] = $button instanceof ButtonElement? $button->toArray(): $button;
    }

    foreach ($elements as $element) {
      $resultElements[] = $element instanceof GenericElement? $element->toArray(): $element;
    }

    $respose = [
      "type"    => "template",
      "payload" => [
          "template_type"     => "list",
          "sharable"          => false,
          "top_element_style" => "large",
          "elements"          => $resultElements,
          "buttons"           => $resultButtons
      ]
    ];
    return $response;   
  }

}