<?php

namespace App\Ilinya\Webhook\Facebook;
use App\Ilinya\Webhook\Facebook\Postback;
use App\Ilinya\Webhook\Facebook\Message;


class Messaging
{
    public static $TYPE_MESSAGE = "message";

    private $senderId;
    private $recipientId;
    private $timestamp;
    private $message;
    private $messageArray;
    private $type;
    private $postback;

    public function __construct(array $data)
    {
        $this->senderId = $data["sender"]["id"];
        $this->recipientId = $data["recipient"]["id"];
        $this->timestamp = $data["timestamp"];
        
        if(isset($data["message"])) {
            $this->type = "message";
            $this->messageArray = $data['message'];
            $this->message = new Message($data["message"]);
        }
        else if (isset($data["postback"])) {
            $this->type = "postback";
            $this->postback = new Postback($data["postback"]);
        }
        else if(isset($data['read'])){
            //Code Here
            $this->type = "read";
        } 
        else if(isset($data['delivery'])){
            $this->type = "delivery";
            //Code Here
        }   
    }

    public function getSenderId(){
        return $this->senderId;
    }

    public function getRecipientId(){
        return $this->recipientId;
    }

    public function getTimestamp(){
        return $this->timestamp;
    }

    public function getMessage(){
        return $this->message;
    }

    public function getMessageArray(){
        return $this->messageArray;
    }

    public function getType(){
        return $this->type;
    }

    public function getPostback(){
        return $this->postback;
    }

}