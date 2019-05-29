<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    public function test()
  	{
    	return $this->belongsTo('App\Models\Test');
  	}

  	public function user()
  	{
    	return $this->belongsTo('App\Models\User');
  	}
}
