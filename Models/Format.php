<?php namespace Mrcore\Modules\Wiki\Models;

use Mrcore\Modules\Wiki\Support\Cache;
use Illuminate\Database\Eloquent\Model;

class Format extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'formats';

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
		Cache::forget('formats_id-name');
		Cache::forget('formats');
	}


	/**
	 * Get all formats
	 *
	 * @return array of formats
	 */
	public static function getAll()
	{
		return Cache::remember("formats", function()
		{
			return Format::orderBy('order')->get();
		});
	}


	/**
	 * Get all formats as array
	 *
	 * @return assoc array of formats
	 */
	public static function allArray($keyField = 'id', $valueField = 'name')
	{
		$function = function() use ($keyField, $valueField) {
			return Format::orderBy('order')->get()->lists($valueField, $keyField);
		};
		
		//Only cache if using default id/name
		if ($keyField == 'id' && $valueField == 'name') {
			return Cache::remember("formats_$keyField-$valueField", $function);
		} else {
			return $function;
		}
	}	
}