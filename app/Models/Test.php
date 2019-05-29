<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    public function questions()
  	{
    	return $this->belongsToMany('App\Models\Question', 'test_question', 'test_id', 'question_id')->withTimestamps();
  	}

  	public function results()
	{
		return $this->hasMany('App\Models\Result');
	}
}
