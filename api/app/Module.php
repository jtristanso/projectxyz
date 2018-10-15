<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends APIModel
{
    protected $table = 'modules';
    protected $fillable = ['parent_id', 'icon', 'path', 'rank', 'users'];
}
