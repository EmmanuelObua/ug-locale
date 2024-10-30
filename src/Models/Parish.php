<?php

namespace Intanode\UgLocale\Models;

use Illuminate\Database\Eloquent\Model;

class Parish extends Model
{
	protected $fillable = ['name', 'sub_county_id'];

	public function subCounty()
	{
		return $this->belongsTo(SubCounty::class);
	}

	public function villages()
	{
		return $this->hasMany(Village::class);
	}
}
