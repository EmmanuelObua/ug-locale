<?php

namespace Intanode\UgLocale\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{

	private $format;

	public function __construct()
	{
	    parent::__construct();
	    $this->format = config('uglocale.date_format', 'd M Y, h:i A');
	}

	protected $fillable = ['name','region_id'];

	public function region()
	{
	    return $this->belongsTo(Region::class);
	}

	public function counties()
	{
		return $this->hasMany(County::class);
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
