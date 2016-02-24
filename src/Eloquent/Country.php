<?php namespace Ipalaus\Geonames\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Country extends Model {

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'geonames_countries';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'iso_alpha2';

	/* -(  Relationships  )-------------------------------------------------- */

	public function continent()
	{
		return $this->belongsTo('Ipalaus\Geonames\Eloquent\Continent');
	}

	public function names()
	{
		return $this->hasMany('Ipalaus\Geonames\Eloquent\Name');
	}

}
