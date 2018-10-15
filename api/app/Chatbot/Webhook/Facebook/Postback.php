<?php 
namespace App\Ilinya\Webhook\Facebook;

class Postback{

    private $payload;
    private $title;
    
    public function __construct(array $data){
        $this->payload = $data["payload"];
        $this->title = $data['title'];
    }

    public function getPayload(){
        return $this->payload;
    }

    public function getTitle(){
        return $this->title;
    }

}