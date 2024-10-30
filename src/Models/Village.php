<?php

namespace Intanode\UgLocale\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
	protected $fillable = ['name', 'parish_id'];

	public function parish()
	{
		return $this->belongsTo(Parish::class);
	}
}
