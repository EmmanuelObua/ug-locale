<?php

namespace Intanode\UgLocale\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
	protected $fillable = ['name'];

	public function counties()
	{
		return $this->hasMany(County::class);
	}
}
