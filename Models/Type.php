<?php namespace Mrcore\Models;

use Illuminate\Database\Eloquent\Model;
use Mrcore\Support\Cache;

class Type extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'types';

	/**
	 * This table does not use automatic timestamps
	 *
	 * @var boolean
	 */
	public $timestamps = false;

	/**
	 * Find a model by its primary key.  Mrcore cacheable eloquent override.
	 *
	 * @param  mixed  $id
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public static function find($id, $columns = array('*'))
	{
		return Cache::remember(strtolower(get_class())."_$id", function() use($id, $columns) {
			return parent::find($id, $columns);
		});		
	}

	/*
	 * Clear all cache
	 *
	 */
	public static function forgetCache()
	{
		Cache::forget('types_id-name');
		Cache::forget('types');
	}

	/**
	 * Get all types
	 *
	 * @return array of types
	 */
	public static function getAll()
	{
		return Cache::remember("types", function()
		{
			return Type::all();
		});
	}

	/**
	 * Get all types as array
	 *
	 * @return assoc array of types
	 */
	public static function allArray($keyField = 'id', $valueField = 'name')
	{
		$function = function() use ($keyField, $valueField) {
			return Type::all()->lists($valueField, $keyField);
		};
		
		//Only cache if using default id/name
		if ($keyField == 'id' && $valueField == 'name') {
			return Cache::remember("types_$keyField-$valueField", $function);
		} else {
			return $function;
		}
	}
}