<?php

namespace Intanode\UgLocale\Models;

use Illuminate\Database\Eloquent\Model;

class Parish extends Model
{

	private $format;

	public function __construct()
	{
	    parent::__construct();
	    $this->format = config('uglocale.date_format', 'd M Y, h:i A');
	}

	protected $fillable = ['name', 'sub_county_id'];

	public function subCounty()
	{
		return $this->belongsTo(SubCounty::class);
	}

	public function villages()
	{
		return $this->hasMany(Village::class);
	}

	public function getCreatedAtAttribute()
	{
	    return \Carbon\Carbon::parse($this->attributes['created_at'])->format($this->format);
	}

	public function getUpdatedAtAttribute()
	{
	    return \Carbon\Carbon::parse($this->attributes['updated_at'])->format($this->format);
	}
}
