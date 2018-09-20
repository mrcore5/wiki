<?php namespace Mrcore\Wiki\Models;

use Mrcore\Wiki\Traits\CachesModel;
use Mrcore\Foundation\Support\Cache;
use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    use CachesModel;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'revisions';

    /**
     * This table does not use automatic timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * A revision has one post
     * Usage: $revision->post
     */
    public function post()
    {
        return $this->hasOne('Mrcore\Auth\Models\User', 'id', 'post_id');
    }

    /**
     * A revision has one creator
     * Usage: $revision->creator
     */
    public function creator()
    {
        return $this->hasOne('Mrcore\Auth\Models\User', 'id', 'created_by');
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
