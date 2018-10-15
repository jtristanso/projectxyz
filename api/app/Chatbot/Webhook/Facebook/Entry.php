<?php

namespace App\Ilinya\Webhook\Facebook;

use Illuminate\Http\Request;
use App\Ilinya\Webhook\Facebook\Messaging;

class Entry{

    private $time;
    private $id;
    private $messagings;
    private $standByMode;

    private function __construct(array $data){
        $this->id = $data["id"];
        $this->time = $data["time"];
        $this->messagings = [];
        $this->standByMode = [];
        if(isset($data['messaging'])){
            foreach ($data["messaging"] as $datum) {
                $this->messagings[] = new Messaging($datum);
            }     
        }
        else if(isset($data['standby'])){
            $this->standByMode = $data['standby'];
        }
       
    }
    
    //extracts entries from a Messenger callback
    public static function getEntries(Request $request){
        $entries = [];
        $data = $request->input("entry");
        foreach ((array) $data as $datum) {
            $entries[] = new Entry($datum);
        }
        return $entries;
    }

    public function getTime(){
        return $this->time;
    }

    public function getId(){
        return $this->id;
    }

    public function getMessagings(){
        return $this->messagings;
    }
}