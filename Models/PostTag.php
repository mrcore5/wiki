<?php namespace Mrcore\Models;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'post_tags';

	/**
	 * This table does not use automatic timestamps
	 *
	 * @var boolean
	 */
	public $timestamps = false;


	/**
	 * Set post tags to the given array
	 *
	 */
	public static function set($postID, $tags)
	{
		PostTag::where('post_id', '=', $postID)->delete();
		if (is_array($tags)) {
			foreach ($tags as $tag) {
				$postTag = new PostTag;
				$postTag->post_id = $postID;
				$postTag->tag_id = $tag;
				$postTag->save();
			}
		}
	}
}