<?php namespace Mrcore\Modules\Wiki\Support\Filemanager;

use Config;
use Mrcore\Modules\Wiki\Models\Post;
use Mrcore\Modules\Wiki\Models\Router;


class Symlink {

	private $post;
	private $originalRoute;
	private $route;
	private $files;


	public function __construct(Post $post, $originalRoute)
	{
		$this->post = $post;
		$this->originalRoute = $originalRoute;
		$this->route = Router::findDefaultByPost($post->id);
		$this->files = Config::get('mrcore.wiki.files');
	}


	public function manage()
	{
		$return = null;
		if ($this->originalRoute->static && !$this->route->static) {
			$slug = $this->originalRoute->slug;
		} else {
			$slug = $this->route->slug;
		}

		$path = '';
		$i = 0;
		$segments = explode("/", $slug);
		foreach ($segments as $segment) {
			$path .= $segment;
			$fullpath = $this->files.'/'.$path;

			$segRoute = Router::where('slug', $path)
				->where('disabled', false)
				->where('static', true)
				->first();

			if (isset($segRoute)) {
				if ($this->post->symlink) {
					if (!file_exists($fullpath)) {
						$this->createSymlink(str_repeat("../", $i).'index/'.$this->post->id, $path);
						#$this->createSymlink($this->files.'/index/'.$this->post->id, $path);
					}
				} else {
					/*
					if ($i == count($segments) -1 ) {
						echo "3 ";
						if (is_link($fullpath)) {
							echo "remove '$path' ";
							$this->removeSymlink($path);
						}elseif (is_dir($fullpath)) {
							$moveRoute = Router::where('slug', $path)
								->where('disabled', false)
								->where('static', true)
								->first();
							if (isset($moveRoute)) {
								echo "movedir $path to $moveRoute->post_id";
								#$this->moveDirectory($path, $this->files.'/index/'.$moveRoute->post_id);

								//mv test/* to index/2 then remove test then symlink test to ../index/2
	  						} else {
	  							echo "3a";
	  						}
						}
					}
					*/
				}
			} else {
				// Segment not found as a post, so make directory
				if ($this->post->symlink) {
					if (!file_exists($fullpath)) {
						$this->createDirectory($path);
					}
				} else {
					/*echo "5 ";
					if (is_link($fullpath)) {
						$remove = true;
						$files = scandir($fullpath);
						foreach ($files as $file) {
							if (is_link($fullpath.'/'.$file)) {
								echo $file;
								$remove = false;
								break;
							}
						}
						if ($remove) {
							echo "remove $path ";
							$this->removeSymlink($path);
						}
					}elseif (is_dir($fullpath)) {
						echo "killdir $path ";
						$this->removeDirectory($path);
					}
					*/

				}
			}

			$path .= '/';
			$i++;
		}

		return $return;

	}


	public function createSymlink($src, $dest)
	{
		exec("cd '$this->files'; /bin/ln -s $src $dest", $output);
	}

	public function removeSymlink($path)
	{
		exec("cd '$this->files'; /bin/rm -rf $path", $output);
	}

	public function createDirectory($path)
	{
		exec("cd '$this->files'; /bin/mkdir -p $path", $output);
	}

	public function removeDirectory($path)
	{
		// rmdir Only remove if empty
		exec("cd '$this->files'; /bin/rmdir $path", $output);
	}

	public function moveDirectory($src, $dest)
	{
		exec("cd '$this->files'; /bin/mv -f $src/* $dest/", $output);
	}

}