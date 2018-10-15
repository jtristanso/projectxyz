<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends APIModel
{
    protected $table = 'answers';
    protected $fillable = ['question_id', 'account_id', 'answer', 'answer_status'];
}
