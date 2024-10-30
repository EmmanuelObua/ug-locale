<?php

namespace Intanode\UgLocale\Models;

use Illuminate\Database\Eloquent\Model;

class SubCounty extends Model
{
	protected $fillable = ['name', 'county_id'];

	public function county()
	{
		return $this->belongsTo(County::class);
	}

	public function parishes()
	{
		return $this->hasMany(Parish::class);
	}
}
