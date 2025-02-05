<?php

namespace Intanode\UgLocale\Models;

use Illuminate\Database\Eloquent\Model;

class SubCounty extends Model
{

	private $format;

	public function __construct()
	{
	    parent::__construct();
	    $this->format = config('uglocale.date_format', 'd M Y, h:i A');
	}

	protected $fillable = ['name', 'county_id'];

	public function county()
	{
		return $this->belongsTo(County::class);
	}

	public function parishes()
	{
		return $this->hasMany(Parish::class);
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
