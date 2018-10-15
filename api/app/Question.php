<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends APIModel
{
    protected $table = 'questions';
    protected $fillable = ['test_id', 'question', 'answer', 'order'];
}
