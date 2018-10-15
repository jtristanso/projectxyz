<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends APIModel
{
    protected $table = 'notification_settings';
    protected $fillable = ['account_id', 'email', 'sms', 'fb_messenger', 'otp'];
}
