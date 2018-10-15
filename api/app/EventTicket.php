<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventTicket extends APIModel
{
    protected $table = 'event_tickets';
    protected $fillable = ['code', 'account_id', 'event_id', 'payment_status', 'payment_method', 'qr_code_url'];
}
