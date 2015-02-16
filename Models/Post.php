<?php namespace Mrcore\Modules\Wiki\Models;

use DB;
use URL;
use Auth;
use Input;
use Config;
use Session;
use Mrcore\Models\User;
use Mreschke\Helpers\String;
use Mrcore\Support\Indexer;
use Mrcore\Modules\Wiki\Support\Crypt;
use Mrcore\Modules\Wiki\Support\Cache;
use Illuminate\Database\Eloquent\Model;
use Mrcore\Modules\Wiki\Parser\Wiki as WikiParser;
use Mrcore\Modules\Wiki\Parser\Php as PhpParser;
use Mrcore\Modules\Wiki\Parser\PhpW as PhpWParser;
use Mrcore\Modules\Wiki\Parser\Html as HtmlParser;
use Mrcore\Modules\Wiki\Parser\HtmlW as HtmlWParser;
use Mrcore\Modules\Wiki\Parser\Text as TextParser;


class Post extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posts';

	/**
	 * Single dimension permissions array
	 *
	 * @var array
	 */
	private $permissions;


	/**
	 * Flag weather or not the current $this->content has been decrypted yet
	 */
	private $decrypted;

  
	/**
	 * Many-to-many badges relationship 
	 * Usage: foreach ($post->badges as $badge) $badge->name
	 */
	public function badges()
	{
		return $this->belongsToMany('Mrcore\Modules\Wiki\Models\Badge', 'post_badges');
	}


	/**
	 * Many-to-many badges relationship 
	 * Usage: foreach ($post->tags as $tag) $tag->name
	 */
	public function tags()
	{
		return $this->belongsToMany('Mrcore\Modules\Wiki\Models\Tag', 'post_tags');
	}


	/**
	 * A post has one format
	 * Usage: $post->format->name
	 */
	public function format()
	{
		return $this->hasOne('Mrcore\Modules\Wiki\Models\Format', 'id', 'format_id');
	}


	/**
	 * A post has one type
	 * Usage: $post->type->constant
	 */
	public function type()
	{
		return $this->hasOne('Mrcore\Modules\Wiki\Models\Type', 'id', 'type_id');
	}


	/**
	 * Eager loading posts.framework_id to frameworks table
	 * Usage: $post->framework->name
	 */
	public function framework()
	{
		return $this->belongsTo('Mrcore\Modules\Wiki\Models\Framework');
	}


	/**
	 * Eager loading posts.mode_id to modes table
	 * Usage: $post->mode->name
	 */
	public function mode()
	{
		return $this->belongsTo('Mrcore\Modules\Wiki\Models\Mode');
	}


	/**
	 * Eager loading posts.created_by to users table
	 * Usage: $post->creator->alias
	 */
	public function creator()
	{
		return $this->belongsTo('Mrcore\Models\User', 'created_by');
	}


	/**
	 * Eager loading posts.updated_by to users table
	 * Usage: $post->updater->alias
	 */
	public function updater()
	{
		return $this->belongsTo('Mrcore\Models\User', 'updated_by');
	}

	/**
	 * Find a model by its primary key.  Mrcore cacheable eloquent override.
	 *
	 * @param  mixed  $id
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Model|static|null
	 */
	public static function find($id, $columns = array('*'))
	{
		return Cache::remember(strtolower(get_class()).":$id", function() use($id, $columns) {
			return parent::find($id, $columns);
		});		
	}

	/**
	 * Get all of the models from the database.
	 *
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public static function all($columns = array('*'))
	{
		#dd($this->where());
		return Cache::remember(strtolower(get_class()).":all", function()  {
			return parent::orderBy('constant')->get($columns);
		});
	}	

	/*
	 * Clear all cache
	 *
	 */
	public static function forgetCache($id = null)
	{
		Cache::forget(strtolower(get_class()).':all');
		Cache::forget(strtolower(get_class()).'/titles:all');
		if (isset($id)) Cache::forget(strtolower(get_class()).":$id");
	}	

	/**
	 * Decrypt $this->content
	 */
	public function decrypt()
	{
		$this->content = Crypt::decrypt($this->content);
		$this->decrypted = true;
	}
	
	/**
	 * Encrypt $this->content
	 */
	public function encrypt()
	{
		$this->content = Crypt::encrypt($this->content);
		$this->decrypted = false;
	}

	/**
	 * Get the primary URL for the given post
	 *
	 * @param int $postID
	 * @param object $route optional default $route object for this post
	 * @return string of full url
	 */
	public static function route($postID, $route = null)
	{
		if (is_null($route)) {
			$route = Router::findDefaultByPost($postID);
		}
		if (isset($route)) {
			if ($route->static) {
				//Static route enabled, this is a static named route
				return URL::route('url', array('slug' => $route->slug));	
			} else {
				//Default route disabled means use permalink /42/actual-slug
				return URL::route('permalink', array('id' => $postID, 'slug' => $route->slug));
			}
		} else {
			return URL::route('permalink', array('id' => $postID));
		}
	}

	/**
	 * Get an assoc array of all undeleted posts
	 * Used for Text_Wiki freelink rule
	 *
	 * @return assoc array of all undeleted posts [id]=title
	 */
	public static function allTitles()
	{
		return Cache::remember(strtolower(get_class()).'/titles:all', function() 
		{
			return Post::where('deleted', false)->get()->lists('id', 'title');
		});
	}


	/**
	 * Parse this post in its native format
	 * Will decrypt $this->content if not already decrypted
	 * $data should already be decrypted
	 *
	 * @param string $data optional decrypted content to parse instead of $this->content
	 * @return if $data = null then none, else returns parsed $data
	 */
	public function parse($data = null)
	{
		# Setup the Parser
		$format = strtolower($this->format->constant);
		if ($format == 'wiki') {
			$parser = new WikiParser();
			$parser->userID = Auth::user()->id;
			$parser->postID = $this->id;
			$parser->postCreator = $this->created_by;
			$parser->isAuthenticated = Auth::check();
			$parser->isAdmin = Auth::admin();
		
		} elseif ($format == 'php') {
			$parser = new PhpParser();

		} elseif ($format == 'phpw') {
			$parser = new PhpWParser();
			$parser->userID = Auth::user()->id;
			$parser->postID = $this->id;
			$parser->postCreator = $this->created_by;
			$parser->isAuthenticated = Auth::check();
			$parser->isAdmin = Auth::admin();
			#$parser->disabledRules = array('Html', 'Newline', 'Paragraph');
			$parser->disabledRules = array('Html');

		} elseif ($format == 'html') {
			$parser = new HtmlParser();

		} elseif ($format == 'htmlw') {
			$parser = new HtmlWParser();
			$parser->userID = Auth::user()->id;
			$parser->postID = $this->id;
			$parser->postCreator = $this->created_by;
			$parser->isAuthenticated = Auth::check();
			$parser->isAdmin = Auth::admin();
			#$parser->disabledRules = array('Html', 'Newline', 'Paragraph');
			$parser->disabledRules = array('Html');

		} elseif ($format == 'text' || $format == 'md') {
			$parser = new TextParser();
		}

		// Decrypt content if not already decrypted
		if (!isset($data) && !$this->decrypted) {
			$this->decrypt();
		}

		// Parse Post Content
		if (isset($data)) {
			return $parser->parse($data);
		} else {
			$this->content = $parser->parse($this->content);
		}
	}


	/**
	 * Parse and prepare post helper
	 * Parse post and add site/user globals
	 *
	 * @param $includeGlobals bool
	 * @return \Post
	 */
	public function prepare($includeGlobals = true)
	{
		// Parse Post
		$this->parse();

		if ($includeGlobals) {
			// Add User Global Content
			if (isset(Auth::user()->global_post_id)) {
				if ($this->id != Auth::user()->global_post_id) {
					$userGlobal = Post::find(Auth::user()->global_post_id);
					if (isset($userGlobal)) {
						$userGlobal->parse();
						$this->content = $userGlobal->content . $this->content;
					}
				}
			}

			// Add Site Global Content
			if (Config::get('mrcore.global') > 0) {
				if ($this->id != Config::get('mrcore.global')) {
					$global = Post::find(Config::get('mrcore.global'));
					if (isset($global)) {
						$global->parse();
						$this->content = $global->content . $this->content;
					}
				}
			}		
		}
		return $this;
	}


	/**
	 * Create teaser from $data
	 *
	 * @param string @data is unencrypted new post content
	 * @return string is unencrypted teaser
	 */
	public function createTeaser($data)
	{
		//Remove Some Tags and Data First

		//This REMOVES all contents between the <xxx> and </xxx> tags
		//Different than a strip HTML which leavs the text just removes the tags
		#$data = preg_replace('"<html>(\n|.?)*</html>"', '', $data); //This one crashed sometimes, would just kill all script here
		#$data = preg_replace('"<php>.*?</php>"sim', '', $data); //Beautiful multi line strip
		$data = preg_replace('"<auth>.*?</auth>"sim', '', $data); //Beautiful multi line strip
		$data = preg_replace('"<priv>.*?</priv>"sim', '', $data); //Beautiful multi line strip
		$data = preg_replace('"<html>.*?</html>"sim', '', $data); //Beautiful multi line strip
		$data = preg_replace('"<php>.*?</php>"sim', '', $data); //Beautiful multi line strip
		$data = preg_replace('"<phpw>.*?</phpw>"sim', '', $data); //Beautiful multi line strip

		$start = stripos($data, "<teaser>");
		$end = stripos($data, "</teaser>");
		if (($start && $end) || substr($data,0, 8) == '<teaser>') {
			// Found <teaser>...</teaser>
			// Parse teaser and save parsed HTML to teaser field so I don't have to parse it on every view
			#$data = $this->parse(substr($data, $start+8, $end-$start-8));
			//this->parse is erroring, fix later
			$data = substr($data, $start+8, $end-$start-8);

		} else {
			//No <teaser> defined, create teaser from stripped trimmed body
			$data = strip_tags($data); //Remove HTML if makeing generic teaser
			$data = preg_replace('"\[\[(.|\n)*?\]\]"', '', $data); //Strip [[xxxx]]
			$data = preg_replace('"\[(.|\n)*?\]"', '', $data); //Strip [xxxx]
			$data = preg_replace('"\(\((.|\n)*?\)\)"', '', $data); //Strip ((xxxx))
			$data = preg_replace('"\+(.|\n)*?\n"', '', $data); //Strip +xxx
			
			#$data = preg_replace('"\#\#(.|)*?\|"', '', $data); //Strip ##xxxx|
			#$data = preg_replace('"\|\|\~"', '', $data); //Strip ||~
			#$data = preg_replace('"\|\|"', '', $data); //Strip ||
			#$data = preg_replace('"\/\/"', '', $data); //Strip //
			#$data = preg_replace('"\*\*"', '', $data); //Strip **
			#$data = preg_replace('"\'\'\'"', '', $data); //Strip '''
			#$data = preg_replace('"\_\_"', '', $data); //Strip __
			
			#$preg = array(
			#    '"\*\*|"',
				
			$data = preg_replace('"\*\*|\'\'\'|\_\_|\/\/|\|\|\~|\|\||\#\#(.|)*?\|"', '', $data);
			
			$data = preg_replace('"\* "', '', $data); //Strip *space
			$data = preg_replace('"\#\#"', '', $data); //Strip ##
			$data = preg_replace('"\# "', '', $data); //Strip #space 
			$data = preg_replace('"\`\`"', '', $data); //Strip ``
			$data = preg_replace('"\{\{"', '', $data); //Strip {{
			$data = preg_replace('"\}\}"', '', $data); //Strip }}
			$data = preg_replace('" \\\"', '', $data); //Strip  \ (space \)
			$data = preg_replace('"\@\@"', '', $data); //Strip @@
			$data = preg_replace('"\-\-\-"', '', $data); //Strip ---
			$data = preg_replace('"\-\-\-\-"', '', $data); //Strip ----
			$data = preg_replace('"\+\+\+"', '', $data); //Strip +++
			$data = preg_replace('"\r\n"', ' ', $data); //Strip \r\n with space
			#$data = preg_replace('"= "', '', $data); //Strip center tags
			#$data = preg_replace('", ,"', '', $data); //Strip dual comma space comma

			$teaserLength = Config::get('mrcore.teaser_length', 500);
			if (strlen($data) >= $teaserLength) {
				$data = trim(substr($data, 0, $teaserLength)).'...';
			}
		}

		return $data;
	}


	/**
	 * Get accessible posts based on search criteria
	 * Main search/browser post function
	 */
	public static function getSearchPosts($query, $params)
	{
		/*
		SELECT
			p.*
		FROM
			posts p
			LEFT OUTER JOIN post_permissions pp on p.id = pp.post_id
			LEFT OUTER JOIN permissions perms on pp.permission_id = perms.id
			LEFT OUTER JOIN user_roles ur on pp.role_id = ur.role_id
		WHERE
			(perms.constant = 'read' or created_by = 3)
			AND (ur.user_id = 3 or ur.user_id is null)


		select
			p.*,
			count(*) as cnt,
			sum(weight) as weight
		from
			post_indexes pi
			INNER JOIN posts p on p.id = pi.post_id
		where
			word in ('dynatron', 'cluster', 'storag', 'xen', 'network')

		group by pi.post_id
		having cnt >= 5 -- comment out for or
		order by weight desc

		*/

		// Parse parameters
		$badges = array();
		$tags = array();
		$types = array();
		$formats = array();
		$unread = false;
		$hidden = false;
		$deleted = false;
		$sort = 'relevance';
		foreach ($params as $param => $value) {
			if (preg_match('/badge(.*)/i', $param, $matches)) $badges[] = $matches[1];
			if (preg_match('/tag(.*)/i', $param, $matches)) $tags[] = $matches[1];
			if (preg_match('/type(.*)/i', $param, $matches)) $types[] = $matches[1];
			if (preg_match('/format(.*)/i', $param, $matches)) $formats[] = $matches[1];
			if ($param == 'sort') $sort = $value;
			if ($param == 'unread') $unread = true;
			if ($param == 'hidden') $hidden = true;
			if ($param == 'deleted') $deleted = true;
		}


		// Search for search work in post indexes table
		if ($query) {
			$posts = DB::table('post_indexes')
				->join('posts', 'post_indexes.post_id', '=', 'posts.id');
		} else {
			$posts = DB::table('posts');
		}


		// Filter posts by read permissions (or user is creator)
		if (!Auth::admin()) {
			$posts = $posts
			->leftJoin('post_permissions', 'posts.id', '=', 'post_permissions.post_id')
			->leftJoin('permissions', 'post_permissions.permission_id', '=', 'permissions.id')
			->leftJoin('user_roles', 'post_permissions.role_id', '=', 'user_roles.role_id')
			->where(function($query) {
				$query->where('permissions.constant', '=', 'read')
					->orWhere('posts.created_by', '=', Auth::user()->id);
			})
			->where(function($query) {
				$query->where('user_roles.user_id', '=', Auth::user()->id)
					->orWhereNull('user_roles.user_id');
			});
		}

		// Filter deleted and hidden
		//fix unread
		$posts->where('deleted', $deleted);
		$posts->where('hidden', $hidden);

		// Filter by Types
		if (count($types) > 0) {
			$posts->where(function($sql) use ($types) {
				foreach ($types as $type) {
					$sql->orWhere('type_id', $type);
				}

			});
		}

		// Filter by Formats
		if (count($formats) > 0) {
			$posts->where(function($sql) use ($formats) {
				foreach ($formats as $format) {
					$sql->orWhere('format_id', $format);
				}

			});
		}

		// Filter by Badges
		if (count($badges)> 0) {
			$posts = $posts->join('post_badges', 'posts.id', '=', 'post_badges.post_id');
			$posts->where(function($sql) use ($badges) {
				foreach ($badges as $badge) {
					$sql->orWhere('post_badges.badge_id', $badge);
				}
			});
		}


		// Filter by Tags
		if (count($tags)> 0) {
			$posts = $posts->join('post_tags', 'posts.id', '=', 'post_tags.post_id');
			$posts->where(function($sql) use ($tags) {
				foreach ($tags as $tag) {
					$sql->orWhere('post_tags.tag_id', $tag);
				}
			});
		}


		// Filter by word search query
		// Must be last becasue of order bys
		if ($query) {
			$words = array_values(Indexer::stemText($query));
			$posts->where(function($sql) use ($words) {
				foreach ($words as $word) {
					if (Config::get('mrcore.use_encryption')) $word = md5($word);
					$sql->orWhere(function($sql2) use ($word) {
						$sql2->where('post_indexes.word', '=', $word);
					});
				};
			});
			if (!preg_match('/or/i', $query)) {
				// If using AND we include this having
				$posts->having('cnt', '>=', count($words));
			}
		}

		// Order by
		if ($sort == 'updatednew') {
			$posts->orderBy('posts.updated_at', 'desc');
		} elseif ($sort == 'updatedold') {
			$posts->orderBy('posts.updated_at', 'asc');
		} elseif ($sort == 'creatednew') {
			$posts->orderBy('posts.created_at', 'desc');
		} elseif ($sort == 'createdold') {
			$posts->orderBy('posts.created_at', 'asc');
		} elseif ($sort == 'titleaz') {
			$posts->orderBy('posts.title', 'asc');
		} elseif ($sort == 'titleza') {
			$posts->orderBy('posts.title', 'desc');
		} elseif ($sort == 'mostviews') {
			$posts->orderBy('posts.clicks', 'desc');
		} else {
			if ($query) {
				// Relevance
				$posts->orderBy('weight', 'desc');
			} else {
				// NO query, so relevance is updated_at
				$posts->orderBy('posts.updated_at', 'desc');
			}
		}
	

		// If query, just include group by after order by
		if ($query) $posts->groupBy('post_indexes.post_id');

		// Return results
		#echo $posts->toSql(); #sql debug
		if ($query) {
			$posts = $posts
				->select(DB::raw("posts.*, count(*) as cnt, sum(weight) as weight"))
				->paginate(Config::get('mrcore.search_pagesize'));
		} else {
			$posts = $posts
				->select('posts.*')
				->paginate(Config::get('mrcore.search_pagesize'));
		}
		
		// Set paginate baseUrl to whats in app.url, for https vs http issue
		#dd(Config::get('app.url') . '/' . \Request::path());
		#$posts->setBaseUrl(Config::get('app.url') . '/' . \Request::path());

		$posts->setPath(Config::get('app.url') . '/' . \Request::path());


		#var_dump(DB::getQueryLog()); #sql debug
		#$posts = Post::take(10)->get();
		return $posts;
	}


	/**
	 * Get post permissions (read/write/comment) for current user
	 *
	 * @return simple array of permission constants
	 */
	private function getPermissions()
	{
		if (Auth::admin()) {
			$this->permissions = array();
		} else {
			
			// OH crap, problems with this idea and unit testing
			// because in the web, requests run in issolation, so all classes are new
			// but in a unit test classes are in the same memory space, so this $post class
			// persists across each unit test :(, so $this->permissions is set once and then never
			// changed for the entire unit test, so all post permissions are out of wake
			// See http://developers.blog.box.com/2012/07/03/unit-testing-with-static-variables/

			// I actually keep this here because web is always a separate processes
			// for unit testing I just disable caching and I don't have this issue

			// Computationally expensive, set $this->permissions once and reuse
			// So I can call $post->hasPermissions('read') multiple times in a request
			// and it will only hit the db once, then use the saved $this->permissions variable afterwards
			// This has issues during unit testing with cache enabled, ends up only storing $this->permissions
			// once and never hitting db again, even while going to multiple pages and different users :(
			// So I must disable cache for unit testing or else we get class persistance issues.
			if (!isset($this->permissions)) {

				#This one does not do anything with post owner
				#So if post has 0 perms but post owner=user it will still return blank array
				#If you use this, you must check if post owner=user in hasPermission function instead
				#I LIKE THIS BETTER, SIMPLER DB QUERY (don't have to join posts table...)
				/*
				SELECT
					perm.constant
				FROM
					post_permissions pp
					LEFT OUTER JOIN permissions perm on pp.permission_id = perm.id
					LEFT OUTER JOIN user_roles ur on pp.role_id = ur.role_id
				WHERE
					pp.post_id = 14
					AND ur.user_id = 6
				;*/
				$id = $this->id;
				$postPermissions = DB::table('post_permissions')
					->join('permissions', 'post_permissions.permission_id', '=', 'permissions.id')
					->join('user_roles', 'post_permissions.role_id', '=', 'user_roles.role_id')
					->where('post_permissions.post_id', '=', $id)
					->where('user_roles.user_id', '=', Auth::user()->id)
					->select('permissions.constant')
					->distinct()
					->get();

				
				#If post has 0 perms, but post owner=user then it fakes one READ entry
				#shouldn't if fake a READ, WRITE, COMMENT...etc?
				#I thinks this is not the best place for this, I think it should be in hasPermission() below
				/*
				SELECT
					ifnull(perm.constant, 'read') as constant
				FROM
					posts p
					LEFT OUTER JOIN post_permissions pp on p.id = pp.post_id
					LEFT OUTER JOIN permissions perm on pp.permission_id = perm.id
					LEFT OUTER JOIN user_roles ur on pp.role_id = ur.role_id
				WHERE
					p.id = 14
					AND ur.user_id = 6 OR p.created_by = 6
				;*/

				/*$postPermissions = DB::table('posts')
					->leftJoin('post_permissions', 'posts.id', '=', 'post_permissions.post_id')
					->leftJoin('permissions', 'post_permissions.permission_id', '=', 'permissions.id')
					->leftJoin('user_roles', 'post_permissions.role_id', '=', 'user_roles.role_id')
					->where('posts.id', '=', $id)
					->where(function($query) {
						$query->where('user_roles.user_id', '=', Auth::user()->id)
							->orWhere('posts.created_by', '=', Auth::user()->id);
					})
					->select(DB::raw("ifnull(permissions.constant, 'read') as constant"))
					->distinct()
					->get();
				*/

				#Show query as converted SQL
				#$queries = DB::getQueryLog();
				#$last_query = end($queries);
				#d($last_query);

				// Convert results to single dimensional array of permission constants
				$this->permissions = array();
				foreach ($postPermissions as $permission) {
					$this->permissions[] = $permission->constant;
				}
			}
		}
		return $this->permissions;
	}


	/**
	 * Check if user has this permission item to this post
	 * Returns true if super admin or user is owner
	 *
	 * @return boolean
	 */
	public function hasPermission($constant)
	{
		if (Auth::admin()) {
			// Super admin is always true
			return true;
		} else {
			if ($this->created_by == Auth::user()->id) {
				// Post owner is always true
				return true;
			} else {
				if (in_array(strtolower($constant), $this->getPermissions())) {
					return true;
				}
			}
		}
		return false;
	}


	/**
	 * Determine post permissions based on UUID
	 * Return true if post accessible
	 *
	 * @return boolean
	 */
	public function uuidPermission()
	{
		if ($this->hasPermission('read')) return true;

		if (Input::has('uuid') && $this->shared) {
			$uuid = Input::get('uuid');
			if (strlen($uuid) == 32) $uuid = String::uuidToGuid($uuid);
			if (strtolower($uuid) == strtolower($this->uuid)) {
				// UUID match and public sharing url enabled
				$uuids = Session::get('uuids');
				$uuids = array_add($uuids, $uuid, true); #adds only if not exist
				Session::set('uuids', $uuids);
				return true;
			} else {
				// Wrong UUID
				return false;
			}
		} elseif (isset(Session::get('uuids')[$this->uuid]) && $this->shared) {
			// UUID found in session of previously used UUIDS, allow access
			return true;
		} else {
			// NO UUID defined or in session, check post permissions
			return false;
		}

	}

	/**
	 * Increment route clicks (views)
	 *
	 * @return void
	 */
	public function incrementClicks()
	{
		// We cannot simply run a $this->clicks +=1 then $this->save()
		// because if cache is enabled, then $this is a cahced copy, so incrementing
		// a cached copy does nothing.  So to increment we need to run a separate query.
		DB::table('posts')->where('id', $this->id)->increment('clicks', 1);
		
		// If we are not using cache, the above will update our table
		// and this will update our current object, for display
		// just don't run a $this->save() if you will increment twice
		$this->clicks += 1;

		// If you have cache enabled, the above $this->clicks += 1 does nothing
		// so the views display does not show you the actual click count, but the database
		// is accurate.
	}

}
