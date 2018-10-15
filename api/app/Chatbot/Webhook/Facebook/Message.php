<?php

namespace App\Ilinya\Webhook\Facebook;


class Message{

    private $mId;
    private $text;
    private $attachments;
    private $quickReply;

    public function __construct(array $data){
     $this->mId = $data["mid"];
     $this->text = isset($data["text"]) ? $data["text"] : null;
     $this->attachments = isset($data["attachments"]) ? $data["attachments"] : null;
     $this->quickReply = isset($data["quick_reply"]) ? $data["quick_reply"] : null;
    }

    public function getId(){
        return $this->mId;
    }

    public function getText(){
        return $this->text;
    }

    public function getAttachments(){
        return $this->attachments;
    }

    public function getQuickReply(){
        if($this->quickReply){
            if(strpos($this->quickReply['payload'],"@")){
                $payload = $this->quickReply['payload'];
                list($parameter, $payload) = explode('@', $payload);
                return [
                    "payload"   => '@'.$payload,
                    "parameter" => $parameter
                ];
            }
        }
        return null;
    }
}