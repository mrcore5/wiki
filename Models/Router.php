<?php namespace Mrcore\Modules\Wiki\Models;

use DB;
use Mrcore\Modules\Wiki\Support\Cache;
use Illuminate\Database\Eloquent\Model;

class Router extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'router';

	/**
	 * A route has one creator
	 * Usage: $router->creator->alias
	 */
	public function creator()
	{
		return $this->hasOne('Mrcore\Models\User', 'id', 'created_by');
	}

	/**
	 * Find default enalbed route by route id
	 * @param  int $id route id
	 * @return router eloquent object
	 */
	public static function findDefault($id)
	{
		return Cache::remember(strtolower(get_class()).":$id", function() use($id) {
			return Router::where('id', $id)
				->where('default', true)
				->where('disabled', false)
				->first();
		});
	}

	/**
	 * Get default route for this post ID
	 *
	 * @return router eloquent object
	 */
	public static function findDefaultByPost($postID)
	{
		return Cache::remember(strtolower(get_class())."/post:$postID", function() use($postID) {
			return Router::where('post_id', $postID)
				->where('default', true)
				->where('disabled', false)
				->first();
		});
	}

	/**
	 * Alias to findDefaultByPost
	 */
	public static function byPost($postID)
	{
		return Router::findDefaultByPost($postID);
	}

	/**
	 * Get route from router table by slug
	 * This would be a static route
	 *
	 * @return router eloquent object
	 */
	public static function bySlug($slug)
	{
		return Cache::remember(strtolower(get_class())."/slug:$slug", function() use($slug) {
			return Router::where('slug', $slug)
				->where('disabled', false)
				->where('static', true)
				->first();
		});
	}

	/*
	 * Clear all cache
	 *
	 */
	public static function forgetCache($id = null)
	{
		if (isset($id)) Cache::forget(strtolower(get_class()).":$id");
		if (isset($id)) Cache::forget(strtolower(get_class())."/post:$id");
		if (isset($id)) Cache::forget(strtolower(get_class())."/slug:$id");
	}	

	/**
	 * Increment route clicks (views)
	 *
	 * @return void
	 */
	public function incrementClicks()
	{
		//use this if you decie not to use cache for routes
		#$this->timestamps = false;
		#$this->clicks += 1;
		#$this->save();

		// We cannot simply run a $this->clicks +=1 then $this->save()
		// because if cache is enabled, then $this is a cahced copy, so incrementing
		// a cached copy does nothing.  So to increment we need to run a separate query.
		DB::table('router')->where('id', $this->id)->increment('clicks', 1);
		
		// If we are not using cache, the above will update our table
		// and this will update our current object, for display
		// just don't run a $this->save() if you will increment twice
		$this->clicks += 1;

	}

	public static function getRoutes()
	{
		$routes = Router::
			  orderBy('static', 'desc')
			->orderBy('default', 'desc')
			->orderBy('slug')
			->get();
		return $routes;
	}
	
}
