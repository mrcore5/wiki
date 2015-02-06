<?php namespace Mrcore\Modules\Wiki\Models;

use Mrcore\Modules\Wiki\Support\Cache;
use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'hashtags';

	/**
	 * The primary key column
	 *
	 * @var string
	 */
	protected $primaryKey = 'hashtag';

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

	/**
	 * Get hashtag for the given post ID
	 *
	 * @return hashtag string only
	 */
	public static function findByPost($postID)
	{
		$route = Router::where('post_id', '=', $postID)->where('default', '=', true)->first();
		if (isset($route)) {
			$hashtag = Hashtag::where('route_id', '=', $route->id)->first();
			if (isset($hashtag)) {
				return $hashtag->hashtag;
			}
		}
	}


	/**
	 * Update hashtag for this post ID
	 *
	 * @return boolean false if hashtag already exists
	 */
	public static function updateByPost($postID, $newHashtag)
	{
		if ($newHashtag) {
			$newHashtag = strtolower($newHashtag);
			$hashtag = Hashtag::findByPost($postID);
			if (isset($hashtag)) {
				$originalHashtag = $hashtag;
				if ($hashtag != $newHashtag) {
					// Hashtag changed
					$hashtag = Hashtag::find($newHashtag);
					if (isset($hashtag)) {
						// Hashtag already exists
						return false;
					} else {
						$hashtag = Hashtag::where('hashtag', '=', $originalHashtag)->first();
						$hashtag->hashtag = $newHashtag;
						$hashtag->save();
					}

				}
			} else {
				// New hashtag
				$hashtag = Hashtag::find($newHashtag);
				if (isset($hashtag)) {
					// Hashtag already exists
					return false;
				} else {
					$hashtag = new Hashtag;
					$hashtag->hashtag = $newHashtag;
					$hashtag->route_id = Router::findDefaultByPost($postID)->id;
					$hashtag->save();
				}
			}
		} else {
			// No hashtag entered, delete last used hashtag
			$hashtag = Hashtag::findByPost($postID);
			if (isset($hashtag)) {
				Hashtag::where('hashtag', '=', $hashtag)->delete();
			}
		}
		return true;

	}

}