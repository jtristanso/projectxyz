<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResourceViewer extends APIModel
{
    protected $table = 'resource_viewers';
    protected $fillable = ['resource_id', 'account_id'];
}
