<?php namespace Mrcore\Wiki\Api;

use Config;
use Module;

/**
 * This is the main mrcore API interface
 * used by all "wiki app/workbench" developers
 */
class Mrcore implements MrcoreInterface
{
    private $post;
    private $router;
    private $user;
    private $layout;
    private $config;

    public function __construct(PostInterface $post, RouterInterface $router, UserInterface $user, LayoutInterface $layout, ConfigInterface $config)
    {
        $this->post = $post;
        $this->router = $router;
        $this->user = $user;
        $this->layout = $layout;
        $this->config = $config;
    }

    // Main API categories
    public function post()
    {
        return $this->post;
    }
    public function router()
    {
        return $this->router;
    }
    public function user()
    {
        return $this->user;
    }
    public function layout()
    {
        return $this->layout;
    }
    public function config()
    {
        return $this->config;
    }


    /**
     * Return this, required since Mrcore may be a facade
     * @return Mrcore object instance
     */
    public function getInstance()
    {
        return $this;
    }

    /**
     * Return the laravel/mrcore lifecycle dump
     * @return string of html
     */
    public function lifecycle()
    {
        // Lifecycle is deprecated, but Module::trace() exists
        return Module::trace();
    }

    /**
     * Return the mrcore public path for this public asset
     * This was created to allow greater control over http vs https
     */
    public function asset($file)
    {
        //http://mrcore5.xendev1.dynatronsoftware.com/file/16/test.txt
        // Baseurl remove the http: or http, just //xys.com
        return Config::get('app.url') . '/file/' . $this->post()->id . '/' . $file;
    }

    /**
     * Return the path of a file in the currnet post folder
     */
    public function file($file)
    {
        if (substr($file, 0, 1) == '/') {
            return Config::get('mrcore.wiki.files').$file;
        } elseif (is_numeric(substr($file, 0, 1))) {
            return Config::get('mrcore.wiki.files').'/index/'.$file;
        } else {
            return Config::get('mrcore.wiki.files').'/index/'.$this->post()->id().'/'.$file;
        }
    }

    /**
     * var_dump helper
     */
    public function vd($object)
    {
        dump($object);
    }

    /**
     * var_dump and die helper
     */
    public function dd($object)
    {
        dd($object);
    }
}
