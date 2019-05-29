<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    public function answers()
  	{
    	return $this->hasMany('App\Models\Answer', 'question_id');
  	}

  	public function tests()
  	{
    	return $this->belongsToMany('App\Models\Test', 'test_question', 'question_id', 'test_id')->withTimestamps();
  	}
}
