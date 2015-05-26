<?php namespace Mrcore\Modules\Wiki\Models;

use Mrcore\Modules\Foundation\Support\Cache;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'comments';

	/**
	 * This table does not use automatic timestamps
	 *
	 * @var boolean
	 */
	public $timestamps = false;

	/**
	 * A comment has one post
	 * Usage: $comment->post->title
	 */
	public function post()
	{
		return $this->hasOne('Mrcore\Modules\Wiki\Models\Post', 'id', 'post_id');
	}

	/**
	 * A comment has one creator
	 * Usage: $comment->creator->alias
	 */
	public function creator()
	{
		return $this->hasOne('Mrcore\Models\User', 'id', 'created_by');
	}

	/**
	 * A comment has one updator
	 * Usage: $comment->updater->alias
	 */
	public function updater()
	{
		return $this->hasOne('Mrcore\Models\User', 'id', 'updated_by');
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

	/*
	 * Clear all cache
	 *
	 */
	public static function forgetCache($id = null)
	{
		if (isset($id)) Cache::forget(strtolower(get_class()).":$id");
	}

}