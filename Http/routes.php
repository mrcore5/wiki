<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route to Home Page Post (/)
$homeExists = Route::getRoutes()->hasNamedRoute('home');
if (!$homeExists) {
    Route::any('/', array(
        'uses' => 'PostController@showPost',
        'as' => 'home',
    ));
}


// Post create routes
Route::get('/post/create', array(
    'uses' => 'EditController@newPost',
    'as' => 'new'
));
Route::post('/post/create', array(
    'uses' => 'EditController@createPost',
    'as' => 'createPost'
));

// Post edit routes
Route::get('/post/{id}/edit', array(
    'uses' => 'EditController@editPost',
    'as' => 'edit'
));
Route::post('/post/{id}/updatePost', array(
    'uses' => 'EditController@updatePost',
    'as' => 'updatePost'
));
Route::post('/post/{id}/updatePostOrg', array(
    'uses' => 'EditController@updatePostOrg',
    'as' => 'updatePostOrg'
));
Route::post('/post/{id}/deletePost', array(
    'uses' => 'EditController@deletePost',
    'as' => 'deletePost'
));
Route::post('/post/{id}/undeletePost', array(
    'uses' => 'EditController@undeletePost',
    'as' => 'undeletePost'
));
Route::post('/post/{id}/updatePostPerms', array(
    'uses' => 'EditController@updatePostPerms',
    'as' => 'updatePostPerms'
));
Route::post('/post/{id}/updatePostAdv', array(
    'uses' => 'EditController@updatePostAdv',
    'as' => 'updatePostAdv'
));
Route::post('/post/{id}/updatePostCreateApp', array(
    'uses' => 'EditController@createApp',
    'as' => 'updatePostCreateApp'
));

// Revision route
Route::get('/revision/{id}', array(
    'uses' => 'PostController@showRevision',
    'as' => 'revision'
));
Route::delete('/revision', array(
    'uses' => 'PostController@deleteRevision',
    'as' => 'deleteRevision'
));

// Primary post route unless named route is enabled
Route::any('/{id}/{slug?}', array(
    'uses' => 'PostController@showPost',
    'as' => 'permalink'
))->where(array('id' => '[0-9]+', 'slug' => '(.*)?'));
// Primary alternate post route unless named route is enabled
// Must be after post edit/create
Route::get('/post/{id}/{slug?}', array(
    'uses' => 'PostController@showPost',
    'as' => 'permalink2'
))->where(array('id' => '[0-9]+', 'slug' => '(.*)?'));


// File route
Route::any('/file/{slug?}', array(
    'before' => 'auth.digest',
    'uses' => 'FileController@fileRouter',
    'as' => 'file'
))->where('slug', '(.*)?');

// Webdav subdomain route
if (Config::get('mrcore.wiki.webdav_base_url')) {
    Route::group(array('domain' => Config::get('mrcore.wiki.webdav_base_url')), function () {
        $verbs = array('GET', 'PUT', 'POST', 'DELETE',
            'PROPFIND', 'PROPPATCH', 'MKCOL', 'COPY',
            'MOVE', 'LOCK', 'UNLOCK', 'OPTIONS', 'USERINFO', 'HEAD');

        Route::match($verbs, '/{slug?}', array(
            'before' => 'auth.digest', #cadaver works with basic auth, but FX filemanager only works with digest
            'uses' => 'FileController@fileRouter',
            'as' => 'webdav'
        ))->where('slug', '(.*)?');
    });
}

// Webdav route
/*
$webdavMethods = array('GET', 'PUT', 'DELETE',
    'PROPFIND', 'PROPPATCH', 'MKCOL','COPY',
    'MOVE', 'LOCK', 'UNLOCK', 'OPTIONS', 'USERINFO');
Route::match($webdavMethods, '/webdav/{slug?}', array(
    'before' => 'auth.basic',
    'uses' => 'FileController@fileRouter',
    'as' => 'webdav'
))->where('slug', '(.*)?');
*/

/*
This is straight up webdav direct integration!  But I use fileRouter above and build my own!
Route::match($webdavMethods, '/file/{id?}/{slug?}', function ($id = null, $slug = null) {
    $rootDirectory = new \Sabre\DAV\FS\Directory('/nwq/linstore/data/mrcore5_dev/index');
    $server = new \Sabre\DAV\Server($rootDirectory);
    $server->setBaseUri('/file');
    $server->exec();
    return Response::make('', http_response_code());
});*/



// Search route

Route::get('/search/ajax', 'SearchController@ajaxSearch');

Route::any('/search/searchbox', array(
    'uses' => 'SearchController@searchMenu',
    'as' => 'searchMenu'
));

Route::any('/search/{slug?}', array(
    'uses' => 'SearchController@search',
    'as' => 'search'
))->where('slug', '(.*)?'); #must be last of the searches



// Net route


// Admin Routes
#Route::group(array('prefix' => 'admin', 'before' => 'auth.admin'), function() {
Route::group(['prefix' => 'admin', 'middleware' => ['auth.admin']], function () {

    // Cannot get resource controllers action() to work
    //<li><a href="{{ action('Mrcore\Wiki\Http\Controllers\UserController@index') }}">Users</a></li>
    // Gives Call to a member function domain() on null
    #Route::resource('user', 'UserController');

    // Admin Router route
    Route::get('router', array(
        'uses' => 'RouterController@showRouter',
        'as' => 'router'
    ));


    // Badges
    Route::get('/badge/data', array(
        'uses' => 'Admin\BadgeController@getData',
        'as' => 'admin.badge.getData'
    ));

    Route::resource('badge', 'Admin\BadgeController', [
        'names' => [
            'index'   => 'admin.badge.index',
            'store'   => 'admin.badge.store',
            'update'  => 'admin.badge.update',
            'destroy' => 'admin.badge.destroy',
        ]
    ]);

    // Frameworks
    Route::get('/framework/data', array(
        'uses' => 'Admin\FrameworkController@getData',
        'as' => 'admin.framework.getData'
    ));

    Route::resource('framework', 'Admin\FrameworkController', [
        'names' => [
            'index'   => 'admin.framework.index',
            'store'   => 'admin.framework.store',
            'update'  => 'admin.framework.update',
            'destroy' => 'admin.framework.destroy',
        ]
    ]);

    // Modes
    Route::get('/mode/data', array(
        'uses' => 'Admin\ModeController@getData',
        'as' => 'admin.mode.getData'
    ));

    Route::resource('mode', 'Admin\ModeController', [
        'names' => [
            'index'   => 'admin.mode.index',
            'store'   => 'admin.mode.store',
            'update'  => 'admin.mode.update',
            'destroy' => 'admin.mode.destroy',
        ]
    ]);

    // Roles
    Route::get('/role/data', array(
        'uses' => 'Admin\RoleController@getData',
        'as' => 'admin.role.getData'
    ));

    Route::resource('role', 'Admin\RoleController', [
        'names' => [
            'index'   => 'admin.role.index',
            'store'   => 'admin.role.store',
            'update'  => 'admin.role.update',
            'destroy' => 'admin.role.destroy',
        ]
    ]);

    // Tags
    Route::get('/tag/data', array(
        'uses' => 'Admin\TagController@getData',
        'as' => 'admin.tag.getData'
    ));

    Route::resource('tag', 'Admin\TagController', [
        'names' => [
            'index'   => 'admin.tag.index',
            'store'   => 'admin.tag.store',
            'update'  => 'admin.tag.update',
            'destroy' => 'admin.tag.destroy',
        ]
    ]);

    // Types
    Route::get('/type/data', array(
        'uses' => 'Admin\TypeController@getData',
        'as' => 'admin.type.getData'
    ));

    Route::resource('type', 'Admin\TypeController', [
        'names' => [
            'index'   => 'admin.type.index',
            'store'   => 'admin.type.store',
            'update'  => 'admin.type.update',
            'destroy' => 'admin.type.destroy',
        ]
    ]);

    // Users
    Route::get('/user/data', array(
        'uses' => 'Admin\UserController@getData',
        'as' => 'admin.user.getData'
    ));

    Route::get('/user/{id}/data', array(
        'uses' => 'Admin\UserController@getUserData',
        'as' => 'admin.user.getUserData'
    ));


    Route::resource('user', 'Admin\UserController', [
        'names' => [
            'index'   => 'admin.user.index',
            'store'   => 'admin.user.store',
            'update'  => 'admin.user.update',
            'destroy' => 'admin.user.destroy',
        ]
    ]);
});


// Login route (these are legacy, use new Mrcore\Auth now)
/*Route::get('/login', array(
    'uses' => 'LoginController@login',
    'as' => 'login'
));
Route::post('/login', array(
    'uses' => 'LoginController@validateLogin',
    'as' => 'validateLogin'
));
Route::post('/login/reset', array(
    'uses' => 'LoginController@resetLogin',
    'as' => 'resetLogin'
));

// Logout route
Route::get('/logout', array(
    'uses' => 'LoginController@logout',
    'as' => 'logout'
));*/




/*
|--------------------------------------------------------------------------
| Legacy Routes
|--------------------------------------------------------------------------
|
| Legacy Routes for mRcore4 Backwards Compatibility
|
*/

// Legacy topic route
Route::get('/topic/{id}/{slug?}', array(
    'uses' => 'PostController@showPost',
    'as' => 'postlegacy'
))->where(array('id' => '[0-9]+', 'slug' => '(.*)?'));

// Legacy files route (plural)
// Redirects to files subdomain
// wget will redirect just fine, curl will show HTML that says redirecting to...
// Unless you pass the -L to curl
/*
if (Config::get('mrcore.webdav_base_url')) {
    Route::any('/files/{slug?}', function($slug = null) {

        $url = '//'.Config::get('mrcore.file_base_url');
        if ($slug) $url .= '/' . $slug;
        if (Request::server('QUERY_STRING')) $url .= '?'.Request::server('QUERY_STRING');
        return Redirect::to($url);

    })->where('slug', '(.*)?');;
}
Route::any('/file/{id?}/{slug?}', function($id = null, $slug = null) {
    return Redirect::route('file', array('id' => $id, 'slug' => $slug));
});
*/


Route::any('/files/{slug?}', ['middleware' => 'web', function ($slug = null) {
    return Redirect::route('file', array('slug' => $slug));
}])->where('slug', '(.*)?');;


# Add /admin/* and and /search and /net and ??

# named
#Route::get('user/profile', array('as' => 'profile', function()

# name and route to controller
#Route::get('user/profile', array('as' => 'profile', 'uses' => 'UserController@showProfile'));

#Route::resource('/', 'TopicController');



/*
|--------------------------------------------------------------------------
| mRcore Router
|--------------------------------------------------------------------------
|
| Will route all other requrest found in the routes database table
|
*/

/*
Route::get('{slug?}', array(
    'uses' => 'PostController@postRouter',
    'as' => 'url'
))->where('slug', '(.*)?');
*/


Route::any('{slug?}', array(
    'uses' => 'PostController@showPost',
    'as' => 'url'
))->where('slug', '(.*)?');







/*
|--------------------------------------------------------------------------
| Laravel Help
|--------------------------------------------------------------------------
*/
// Route Closures
#Route::get('/', function()
#{
#    return View::make('hello');
#});

// Route to Controllers
#Route::get('/', 'HomeController@showWelcome');
#Route::get('user/{id}', 'UserController@showProfile');
#Route::get('foo', array('uses' => 'FooController@method', 'as' => 'name')); #give a name
#Route::get('profile', array('before' => 'auth', 'uses' => 'UserController@showProfile')); #filters
    # You can also specify filters inside your controller
#$url = URL::action('FooController@method'); #this generates a url to your controller


// Resource Controllers (RESTful automatic routes)
// Auto create your controller with: php artisan controller:make PhotoController
#Route::resource('topic', 'TopicController');
#Route::resource('photo', 'PhotoController', array('only' => array('index', 'show'))); #limit resource to a few actions
#Route::resource('photo', 'PhotoController', array('except' => array('create', 'store', 'update', 'delete'))); #all but (exclusion)

// Redirection
#return Redirect::to('user/login');
#return Redirect::to('user/login')->with('message', 'Login Failed'); #with flash data
#return Redirect::route('login'); #named route
#return Redirect::action('HomeController@index'); #to controller


// Binding A Parameter To A Model
// This passes entire User model already narrowed by the ID!!!!!
#Route::model('user', 'User');
#Route::get('profile/{user}', function(User $user)


// 404 error in route
#App::abort(404);
























/*
|--------------------------------------------------------------------------
| These are old mrcore4 routes
|--------------------------------------------------------------------------
*/

/*
// Root Redirect Route
Route::get('/', array('as'=>'home', function ()
{
    return Redirect::to('topic/'.Config::HOME_URL);
}));
Route::get('/topic', function ()
{
    return Redirect::to('topic/'.Config::HOME_URL);
});


// Main Topic Route
Route::get('/topic/{id}/{params?}/{action?}/{action_id?}', array('as'=>'topic', function ($id, $params=null, $action=null, $action_id=null)
{
    require_once('../class/core.class.php');
    eval(Page::load_code());
    if ($view->viewmode_simple) {
        //Simple view mode means to hide mrcore layout (header, search, avatar, menus...)
        //Wiki is still parsed and CSS/Javascript is still present though no <html><body>...tags are present
        //This should just be the wiki content and should look and function like normal because of the css/js
        //Simple mode does include site/user global topics but NOT comments.
        eval(Page::load_code('master'));
        $view->css[] = Page::get_url('master_simple.css');

        if(isset($view->css)) {
            foreach($view->css as $css) {
                echo "    <link rel='stylesheet' type='text/css' href='$css' />\r\n";
            }
        }
        if(isset($view->css_print)) {
            foreach($view->css_print as $css) {
                echo "    <link rel='stylesheet' type='text/css' media='print' href='$css' />\r\n";
            }
        }
        if(isset($view->js)) {
            foreach($view->js as $js) {
                echo "    <script language='javascript' src='$js' type='text/javascript'></script>\r\n";
            }
        }
        echo "<div id='tbwiki_simple'><div><div><div><div><div><div>";
        Parser::parse_wiki($info, $view, $files, $topic, $topic->tbl_post->body);
        echo "</div></div></div></div></div></div></div>";

    } elseif ($view->viewmode_raw) {
        //Raw view mode means to hide all mrcore layout AND exclude all CSS/Javascript
        //So this should look real ugly and any javascript items like header expand/collapse will NOT work
        //This is so I can cause topics produce simple text ajax jason strings if I need too!
        //Raw mode does NOT include site/user global topics or comments, just the requested topics HTML
        Parser::parse_wiki($info, $view, $files, $topic, $topic->tbl_post->body, true, array('paragraph'));

    } else {
        //Default view, load all theme layouts and full site
        eval(Page::load_code('master'));
        $wiki = Parser::parse_wiki($info, $view, $files, $topic, $topic->tbl_post->body, false);
        eval(Page::load_view('master'));
    }
}));


// Edit Topic Route
Route::any('/edit/{params}/{id?}/{name?}', function ($params, $id=null, $name=null)
{
    require_once('../class/core.class.php');
    eval(Page::load_code());
    eval(Page::load_view('master'));
});


// Files Route
Route::get('files/{id}/{slug?}', function ($id)
{
    require_once('../class/core.class.php');
    eval(Page::load_code());
    eval(Page::load_view('master'));
})->where('slug', '(.*)?');


// Search Route
Route::any('/search/{slug?}', function ()
{
    require_once('../class/core.class.php');
    eval(Page::load_code());
    #eval(Page::load_view('search'));
    eval(Page::load_view('master'));
})->where('slug', '(.*)?');


// Login Route
Route::any('login/{params?}', function ($params=null)
{
    require_once('../class/core.class.php');
    eval(Page::load_code());
    eval(Page::load_view('master'));
});


// Net Route
Route::any('net/{slug?}', function ()
{
    require_once('../class/core.class.php');
    eval(Page::load_code());
    eval(Page::load_view('master'));
})->where('slug', '(.*)?');


// User Profile Route
Route::any('/profile/{slug?}', function ()
{
    require_once('../class/core.class.php');
    eval(Page::load_code());
    eval(Page::load_view('master'));
})->where('slug', '(.*)?');


// Admin Route
Route::any('/admin/{slug?}', function ()
{
    require_once('../class/core.class.php');
    eval(Page::load_code());
    eval(Page::load_view('master'));
})->where('slug', '(.*)?');


// Need to fix the indexer_log.html route
#...
#...


// Redirect Route
Route::get('/redirect/{slug?}', function ()
{
    require_once('../class/core.class.php');
    eval(Page::load_code('redirect'));
    eval(Page::load_view('master'));
})->where('slug', '(.*)?');


// AJAX Files Route
Route::any('/ajax/files.ajax/{id}/{slug?}', function ($id)
{
    require_once('../class/core.class.php');
    eval(Page::load_code());
    eval(Page::load_view('ajax/files.ajax'));
})->where('slug', '(.*)?');


// REST Webservice legacy URLs
Route::get('/rest/v1/{slug?}', function ()
{
    require_once('../class/core.class.php');
    eval(Page::load_code());
})->where('slug', '(.*)?');


// AJAX Search Route
Route::any('/ajax/search.ajax.php', function () {
    require_once('../class/core.class.php');
    eval(Page::load_class('topic'));
    if (Input::get('key') == 'c44dbb976265f6d75756475bb7cbdee5') {
        echo "<div id='mstopic'><div><div><div><div><div><div>";
        $topic->tbl_post = Tbl_post::get_topic($info, Config::SEARCHBOX_TOPIC);
        echo Parser::parse_wiki($info, $view, null, $topic, $topic->tbl_post->body);
        echo "</div></div></div></div></div></div></div>";
    } else {
        header("HTTP/1.0 404 Not Found");
    }
});


// AJAX User Info Route
Route::any('/ajax/userinfo.ajax.php', function ()
{
    require_once('../class/core.class.php');
    eval(Page::load_class('topic'));
    ?>
    <? if (Input::get('key') == 'c44dbb976265f6d75756475bb7cbdee5'): ?>
        <div id='muinfo'>
            <table border='0' width='100%'>
                <tbody align='left' valign='top'>
                <tr>
                    <td width='1'>
                        <img src='<?=Page::get_url($info->tbl_user->avatar, true)?>' class='avatar_full' />
                    </td><td>
                        <div id='muinfotext'>
                            <div>
                                <? if ($info->tbl_user->user_topic_id > 0): ?>
                                    <a href='<?=Page::get_url('topic/'.$info->tbl_user->user_topic_id)?>'>
                                        <b><?=$info->tbl_user->first_name?> <?=$info->tbl_user->last_name?></b>
                                    </a>
                                <? else: ?>
                                    <b><?=$info->tbl_user->first_name?> <?=$info->tbl_user->last_name?></b>
                                <? endif ?>
                            </div>
                            <div><?=$info->tbl_user->email?></div>
                            <div id='muinfotexta'>
                                <table>
                                    <tr>
                                        <td><a href='<?=Page::get_url('profile/'.$info->tbl_user->alias)?>'>Account</a></td>
                                        <td><a href='<?=Page::get_url('login/signout')?>'>Sign Out</a></td>
                                    </tr><tr>
                                        <td><a href='<?=Page::get_url('search').'/unread=1' ?>'>Unread (<?=Tbl_topic::get_unread_count($info)?>)</a></td>
                                        <td>
                                            <? if($info->tbl_user->perm_create || $info->admin): ?>
                                                <a href='<?=Page::get_url('edit/newtopic')?>'>New Topic</a>
                                            <? endif ?>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id='mutopic'>
            <div><div><div><div><div><div>
            <?$topic->tbl_post = Tbl_post::get_topic($info, Config::USERINFO_TOPIC)?>
            <?=Parser::parse_wiki($info, $view, null, $topic, $topic->tbl_post->body)?>
            </div></div></div></div></div></div>
        </div>
    <? else: ?>
        <? header("HTTP/1.0 404 Not Found") ?>
    <? endif ?>
    <?
});


// Catch All (must be defined last)
Route::get('{slug?}', function($slug)
{
    // This is where you hit redis for URL rewriting
    require_once('../class/core.class.php');
    Page::redirect(Page::get_url('redirect').'/error');
})->where('slug', '(.*)?');

*/
