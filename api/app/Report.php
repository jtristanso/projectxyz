<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends APIModel
{
    protected $table = 'reports';
    protected $fillable = ['account_id', 'description', 'status', 'token', 'spreadsheet', 'spreadsheet_title'];
}
