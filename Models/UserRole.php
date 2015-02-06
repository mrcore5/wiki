<?php namespace Mrcore\Models;

use Illuminate\Database\Eloquent\Model;
use Mrcore\Support\Cache;

class UserRole extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_roles';

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
}