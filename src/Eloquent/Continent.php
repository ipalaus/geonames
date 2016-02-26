<?php namespace Ipalaus\Geonames\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Continent extends Model {

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
	protected $table = 'geonames_continents';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'code';

	/* -(  Relationships  )-------------------------------------------------- */

	public function countries()
	{
		return $this->hasMany('Ipalaus\Geonames\Eloquent\Country', 'continent');
	}

}