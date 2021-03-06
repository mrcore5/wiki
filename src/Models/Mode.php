<?php namespace Mrcore\Wiki\Models;

use Mrcore\Wiki\Traits\CachesModel;
use Mrcore\Foundation\Support\Cache;
use Illuminate\Database\Eloquent\Model;

class Mode extends Model
{
    use CachesModel;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'modes';

    /**
     * This table does not use automatic timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * A mode has many posts
     * ex: $mode->posts->all();
     * @return mixed
     */
    public function posts()
    {
        return $this->hasMany('Mrcore\Wiki\Models\Post');
    }

    /**
     * Get all of the models from the database.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function all($columns = array('*'))
    {
        return Cache::remember(strtolower(get_class()).":all", function () use ($columns) {
            return static::orderBy('constant')->get($columns);
        });
    }

    /*
     * Clear all cache
     *
     */
    public static function forgetCache($id = null)
    {
        Cache::forget(strtolower(get_class()).':all');
        if (isset($id)) {
            Cache::forget(strtolower(get_class()).":$id");
        }
    }
}
