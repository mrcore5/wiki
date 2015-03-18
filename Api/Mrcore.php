<?php namespace Mrcore\Modules\Wiki\Api;

use Config;

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
	public function post()   { return $this->post;   }
	public function router() { return $this->router; }
	public function user()   { return $this->user;   }
	public function layout() { return $this->layout; }
	public function config() { return $this->config; }


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
		return "Deprecated";
		#return \Lifecycle::dump();
	}

	/**
	 * Return the mrcore public path for this public asset
	 * This was created to allow greater control over http vs https
	 */
	public function asset($file)
	{
		$baseUrl = Config::get('mrcore.base_url');
		return $baseUrl . '/' . $file;
	}


	/**
	 * Return the path of a file in the currnet post folder
	 */
	public function file($file)
	{
		if (substr($file, 0, 1) == '/') {
			return Config::get('mrcore.files').$file;
		} elseif (is_numeric(substr($file, 0, 1))) {
			return Config::get('mrcore.files').'/index/'.$file;
		} else {
			return Config::get('mrcore.files').'/index/'.$this->post()->id().'/'.$file;
		}
	}


	/**
	 * var_dump helper
	 */
	public function vd($object) {
		echo "<pre>";
		var_dump($object);
		echo "</pre>";
	}


	/**
	 * var_dump and die helper
	 */
	public function dd($object) {
		$this->vd($object);
		exit();
	}
	
}