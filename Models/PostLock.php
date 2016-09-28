<?php namespace Mrcore\Wiki\Models;

use Mrcore\Foundation\Support\Cache;
use Illuminate\Database\Eloquent\Model;

class PostLock extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'post_locks';

    /**
     * This table does not use automatic timestamps
     *
     * @var boolean
     */
    public $timestamps = false;
}
