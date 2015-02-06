<?php namespace Mrcore\Models;

use Illuminate\Database\Eloquent\Model;

class PostRead extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'post_reads';

	/**
	 * This table does not use automatic timestamps
	 *
	 * @var boolean
	 */
	public $timestamps = false;
}