<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RtcVideo extends APIModel
{
    protected $table = "rtc_videos";
    protected $fillable = ['account_id',  'payload', 'payload_value', 'title'];
}
