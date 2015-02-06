<?php namespace Mrcore\Models;

use Illuminate\Database\Eloquent\Model;
use Mrcore\Support\Cache;

class Router extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'router';


	/**
	 * Eager loading router.created_by to users table
	 * Usage: $router->creator->alias
	 */
	public function creator()
	{
		return $this->belongsTo('Mrcore\Models\User', 'created_by');
	}

	/**
	 * Find default enalbed route by route id
	 * @param  int $id route id
	 * @return router eloquent object
	 */
	public static function findDefault($id)
	{
		return Cache::remember(strtolower(get_class())."_default_$id", function() use($id) {
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
		return Cache::remember(strtolower(get_class())."_default_post_$postID", function() use($postID) {
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
		return Cache::remember(strtolower(get_class())."_static_$slug", function() use($slug) {
			return Router::where('slug', $slug)
				->where('disabled', false)
				->where('static', true)
				->first();
		});
	}

	/**
	 * Increment route clicks (views)
	 *
	 * @return void
	 */
	public function incrementClicks()
	{
		$this->timestamps = false;
		$this->clicks += 1;
		$this->save();
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
