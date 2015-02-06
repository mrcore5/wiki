<?php namespace Mrcore\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_permissions';

	/**
	 * This table does not use automatic timestamps
	 *
	 * @var boolean
	 */
	public $timestamps = false;
}