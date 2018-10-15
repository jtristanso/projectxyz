<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table ='accounts';
    protected $hidden = array('password');

    public function user_type()
    {
        return $this->belongsTo('App\UserType');
    }
    public function user_informations()
    {
        return $this->hasMany('App\UserInformation');
    }
}
