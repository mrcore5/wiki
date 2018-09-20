<?php namespace Mrcore\Wiki\Models;

use Mrcore\Wiki\Traits\CachesModel;
use Mrcore\Foundation\Support\Cache;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use CachesModel;

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
        return $this->hasOne('Mrcore\Wiki\Models\Post', 'id', 'post_id');
    }

    /**
     * A comment has one creator
     * Usage: $comment->creator->alias
     */
    public function creator()
    {
        return $this->hasOne('Mrcore\Auth\Models\User', 'id', 'created_by');
    }

    /**
     * A comment has one updator
     * Usage: $comment->updater->alias
     */
    public function updater()
    {
        return $this->hasOne('Mrcore\Auth\Models\User', 'id', 'updated_by');
    }

    /*
     * Clear all cache
     *
     */
    public static function forgetCache($id = null)
    {
        if (isset($id)) {
            Cache::forget(strtolower(get_class()).":$id");
        }
    }
}
