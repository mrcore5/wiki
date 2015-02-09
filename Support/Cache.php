<?php namespace Mrcore\Modules\Wiki\Support;

use Config;
use Closure;
use Cache as LaravelCache;
 
class Cache {

	/**
	 * Mrcore Laravel cache remember function
	 * Use this instead of laravel default Cache::remember
	 * because this one obeys my use_cache config variable and expires time
	 */
	public static function remember($key, Closure $callback)
	{
		$key = self::adjustKey($key);
		if (Config::get('mrcore.use_cache')) {
			return LaravelCache::remember($key, Config::get('mrcore.cache_expires'), $callback);
		} else {
			return $callback();
		}
	}

	public static function forget($key)
	{
		$key = self::adjustKey($key);
		LaravelCache::forget($key);
	}

	private static function adjustKey($key)
	{
		$key = str_replace("\\", "/", $key);
		$key = "mrcore/cache::$key";
		return $key;		
	}

}
