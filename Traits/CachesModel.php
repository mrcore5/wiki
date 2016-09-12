<?php namespace Mrcore\Wiki\Traits;

use Mrcore\Foundation\Support\Cache;

trait CachesModel
{
	/**
	 * Find a model by its primary key.  Mrcore cacheable eloquent override.
	 *
	 * @param  mixed  $id
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
	 */
	public static function find($id, $columns = array('*'))
	{
		// mReschke override to cache results
		$cacheID = $id;
		if (is_array($cacheID)) $cacheID = implode('-', $cacheID);
		return Cache::remember(strtolower(get_class()).":$cacheID", function() use($id, $columns) {
			return static::query()->find($id, $columns); // Use this instead of parent::find()
		});
	}

	// DID NOT add ->all() method here even though I
	// cache it in many models.  Because in my models I add custom orderby

}
