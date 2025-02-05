<?php

namespace Intanode\UgLocale\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
	protected $fillable = ['name','region_id'];

	public function region()
	{
	    return $this->belongsTo(Region::class);
	}

	public function counties()
	{
		return $this->hasMany(County::class);
	}
}
