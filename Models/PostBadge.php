<?php namespace Mrcore\Wiki\Models;

use Mrcore\Foundation\Support\Cache;
use Illuminate\Database\Eloquent\Model;

class PostBadge extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'post_badges';

    /**
     * This table does not use automatic timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Set post badges to the given array
     *
     */
    public static function set($postID, $badges)
    {
        PostBadge::where('post_id', '=', $postID)->delete();
        if (is_array($badges)) {
            foreach ($badges as $badge) {
                $postBadge = new PostBadge;
                $postBadge->post_id = $postID;
                $postBadge->badge_id = $badge;
                $postBadge->save();
            }
        }
    }
}
