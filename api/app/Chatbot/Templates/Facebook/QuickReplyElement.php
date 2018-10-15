<?php

namespace App\Ilinya\Templates\Facebook;

class QuickReplyElement{
  
  /*
    @String
    @N
  */
  protected $title;
  
  /*
    @Enum
    @R
  */
  protected $contentType;

  /*
    @String
    @N
  */
  protected $payload;


  function __construct($title){
    $this->title = $title;
  }

  public static function title($title){
    return new static($title);
  }

  public function contentType($contentType){
    $this->contentType = $contentType;
    return $this;
  }

  public function payload($payload){
    $this->payload = $payload;
    $this->toArray();
    return $this;
  }

  public function toArray(){
    $response =  ($this->contentType == "location")?[
      "content_type"  => $this->contentType
    ]:[
      "title"         => $this->title,
      "content_type"  => $this->contentType,
      "payload"       => $this->payload
    ];
    return $response;
  }
}