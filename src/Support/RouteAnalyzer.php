<?php namespace Mrcore\Wiki\Support;

use Config;
use Request;
use Mrcore\Wiki\Models\Router as RouterTable;

class RouteAnalyzer
{
    private $route;
    public $responseCode;
    public $responseRedirect;

    public function __construct()
    {
        $this->responseCode = 200;
    }

    /**
     * Route found in routing table
     * @return bool
     */
    public function foundRoute()
    {
        return ($this->responseCode == 200);
    }
    
    /**
     * Route found to require redirection
     * @return boolean
     */
    public function foundRedirect()
    {
        return ($this->responseCode == 301);
    }

    public function notFound()
    {
        return ($this->responseCode == 404);
    }

    /**
     * Get the current urls route
     * @return eloquent router object
     */
    public function currentRoute()
    {
        return $this->route;
    }

    /**
     * Get the route by post id
     * @param  int $id
     * @return eloquent router object
     */
    public function byPost($id)
    {
        return RouterTable::byPost($id);
    }

    /**
     * Get route by examining the current url
     *
     * @param array $reserved
     * @param array $legacy
     * @return void
     */
    public function analyzeUrl($reserved, $legacy)
    {
        $path = Request::path();
        $segments = Request::segments();
        $query = Request::server('QUERY_STRING');
        if ($query) {
            $query = '?' .$query;
        }

        // Url is /
        if (count($segments) == 0) {
            $this->route = RouterTable::byPost(Config::get('mrcore.wiki.home'));
            if (isset($this->route)) {
                // NO increment, I will do later after permission check
                #$this->route->incrementClicks($this->route);
            } else {
                $this->responseCode = 404;
            }
            return;
        }

        $firstSegment = strtolower($segments[0]);
        $secondSegment = count($segments) >= 2 ? $segments[1] : '';

        // Check if reserved path
        if (in_array($firstSegment, $reserved)) {
            $this->responseCode = 202;
            return;
        }

        // Url is /42/anything
        if (is_numeric($firstSegment)) {
            $this->route = RouterTable::byPost($firstSegment);
            if (is_null($this->route)) {
                $this->responseCode = 404;
                return;
            }
        } elseif (in_array($firstSegment, $legacy)) {
            // Url is legacy /topic/42/anything
            $this->route = RouterTable::byPost($secondSegment);
            
            if (is_null($this->route)) {
                $this->responseCode = 404;
                return;
            }
        }

        if (isset($this->route)) {
            // Route found from /42 or legacy, check if static enabled
            // and redirect if needed
            if ($this->route->static) {
                //Static route is enabled, redirect to /actual-slug
                #$this->responseRedirect = array(
                #    'route' => 'url',
                #    'params' => array('slug' => $this->route->slug),
                #    'query' => $query
                #);
                $this->responseRedirect = "/".$this->route->slug.$query;
                $this->responseCode = 301;
                return;
            } else {
                //Static route disabled, use /42/actual-slug
                if ($path != $this->route->post_id . '/' . $this->route->slug) {
                    //URL slug is not accurate, redirect to proper /42/actual-slug
                    /*$this->responseRedirect = array(
                        'route' => 'permalink',
                        'params' => array('id' => $this->route->post_id, 'slug' => $this->route->slug),
                        'query' => $query
                    );*/
                    $this->responseRedirect = "/".$this->route->post_id."/".$this->route->slug.$query;
                    $this->responseCode = 301;
                    return;
                } else {
                    // URL is accurate, good to go as is
                    // NO increment, I will do later after permission check
                    #$this->route->incrementClicks($this->route);
                    return;
                }
            }
        } else {
            // Look up slug in router table
            $slug = $path;
            for ($i = count($segments)-1; $i >= 0; $i--) {
                $this->route = RouterTable::bySlug($slug);
                if (isset($this->route)) {
                    // Route found
                    break;
                } else {
                    // Route not found, step backwards in url segment
                    array_pop($segments);
                    $slug = implode('/', $segments);
                }
            }
            $segments = Request::segments();

            if (isset($this->route)) {
                // Route found
                if ($this->route->url) {
                    // Redirect to external URL
                    // YES increment here, I won't permission check or increment later
                    $this->route->incrementClicks($this->route);
                    $this->responseRedirect = $this->route->url.$query;
                    $this->responseCode = 301;
                    return;
                } elseif ($this->route->redirect) {
                    // This is a static route with redirect true, redirect to default route
                    $route = RouterTable::byPost($this->route->post_id);
                    if (isset($route)) {
                        if ($route->static) {
                            #$this->responseRedirect = array(
                            #    'route' => 'url',
                            #    'params' => array('slug' => $route->slug),
                            #    'query' => $query
                            #);
                            $this->responseRedirect = "/".$route->slug.$query;
                        } else {
                            #$this->responseRedirect = array(
                            #    'route' => 'permalink',
                            #    'params' => array('id' => $route->post_id, 'slug' => $route->slug),
                            #    'query' => $query
                            #);
                            $this->responseRedirect = "/".$route->post_id."/".$route->slug.$query;
                        }
                        $this->responseCode = 301;
                        return;
                    } else {
                        $this->responseCode = 404;
                        return;
                    }
                } else {
                    // This is a named default route like /about
                    // default responseCode is 200 so just increment and return
                    // NO increment, I will do later after permission check
                    #$this->route->incrementClicks($this->route);
                    return;
                }
            } else {
                // Route not found in router
                $this->responseCode = 404;
                return;
            }
        }
    }
}

/*
analyze url

get tbl router entry for that route
get response code, may 404, 401, 202...canot redirect until later in bootstrap
gets the actual $post
    checks post permissions including uuid, 401 if fail
    updates post clicks and route clicks
    set \Layout stuff including mode from $_GET['viewmode']




*/
