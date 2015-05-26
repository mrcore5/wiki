<?php namespace Mrcore\Modules\Wiki\Models;

use Mrcore\Modules\Foundation\Support\Cache;
use Illuminate\Database\Eloquent\Model;

class Mode extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'modes';

	/**
	 * This table does not use automatic timestamps
	 *
	 * @var boolean
	 */
	public $timestamps = false;

	/**
	 * A mode has many posts
	 * ex: $mode->posts->all();
	 * @return mixed
	 */
	public function posts()
	{
		return $this->hasMany('Mrcore\Modules\Wiki\Models\Post');
	}	

	/**
	 * Find a model by its primary key.  Mrcore cacheable eloquent override.
	 *
	 * @param  mixed  $id
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public static function find($id, $columns = array('*'))
	{
		return Cache::remember(strtolower(get_class()).":$id", function() use($id, $columns) {
			return parent::query()->find($id, $columns);
		});		
	}

	/**
	 * Get all of the models from the database.
	 *
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public static function all($columns = array('*'))
	{
		return Cache::remember(strtolower(get_class()).":all", function() use($columns) {
			return parent::orderBy('constant')->get($columns);
		});
	}

	/*
	 * Clear all cache
	 *
	 */
	public static function forgetCache($id = null)
	{
		Cache::forget(strtolower(get_class()).':all');
		if (isset($id)) Cache::forget(strtolower(get_class()).":$id");
	}

}