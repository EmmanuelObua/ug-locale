<?php

namespace Intanode\UgLocale\Models;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{

	private $format;

	public function __construct()
	{
	    parent::__construct();
	    $this->format = config('uglocale.date_format', 'd M Y, h:i A');
	}

	protected $fillable = ['name', 'district_id'];

	public function district()
	{
		return $this->belongsTo(District::class);
	}

	public function subCounties()
	{
		return $this->hasMany(SubCounty::class);
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
