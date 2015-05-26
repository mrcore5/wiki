<?php namespace Mrcore\Modules\Wiki\Models;

use Mrcore\Modules\Foundation\Support\Cache;
use Illuminate\Database\Eloquent\Model;

class PostPermission extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'post_permissions';

	/**
	 * This table does not use automatic timestamps
	 *
	 * @var boolean
	 */
	public $timestamps = false;
}