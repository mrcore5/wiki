<?php namespace Mrcore\Modules\Wiki\Support\Filemanager;

use Route;
use Config;
use Request;
use URL as LaravelUrl;
use Mrcore\Modules\Wiki\Models\Post;

class Url {
	private $absBase;
	private $path;
	private $segments;
	private $postID;
	private $permissions;

	/**
	 * Instantiate url class
	 *
	 * @param string $absBase is filesystem base path of files directory
	 * @param string $urlBase is base url to files (/ or /files...)
	 * @param string $url is full url to file/folder including http://subdomain.domain...
	 */
	public function __construct($url)
	{
		$this->absBase = Config::get('mrcore.files');
		if ($this->getProtocol() == "webdav") {
			$urlBase = Config::get('mrcore.webdav_base_url');
		} else {
			$urlBase = Config::get('mrcore.base_url').'/'.Route::currentRouteName();
		}
		if (preg_match("'$urlBase/(.*)'i", $url, $matches)) {
			$this->path = urldecode($matches[1]);
		}
		if (isset($this->path)) {
			$this->segments = explode('/', $this->path);
		}
	}


	/**
	 * Get the full http url including http://domain.host
	 *
	 * @return string of full http url
	 */
	public function getUrl()
	{
		return Request::url();
	}


	/**
	 * Get the full url parent (level minus one)
	 *
	 * @return string full parent url
	 */
	public function getUrlParent()
	{
		$segments = explode('/', $this->getUrl());
		array_pop($segments);
		return implode($segments, '/');
	}


	/**
	 * Get the url array of segments
	 *
	 * @return array of url segments
	 */
	public function getSegments()
	{
		return $this->segments;
	}


	/**
	 * Return the http method (verb) if this url
	 * GET, PUT, DELETE, MKCOL, COPY, MOVE, PROPFIND
	 *
	 * @return string lowercase http method verb
	 */
	public function getMethod()
	{
		return strtolower(Request::server('REQUEST_METHOD'));
	}


	/**
	 * Check method
	 *
	 * @return bool if method match
	 */
	public function isMethod($method)
	{
		return strtolower($method) == $this->getMethod();
	}


	/**
	 * Returns http or webdav which is identified by the url itself
	 *
	 * @return string lowercase http or webdav
	 */
	public function getProtocol()
	{
		if (Route::currentRouteName() == 'webdav') {
			return 'webdav';
		} else {
			return 'http';
		}
	}

	
	/**
	 * Get just the path of the URL.
	 * Example: 'myfolder', 'myfolder/file.txt', 'myfolder/anotherfolder'
	 * Notice no leading / or trailing /.  Blank if at root, not a /
	 *
	 * @param bool $encode optional if true will encode full path for links
	 * @return string of only the path
	 */
	public function getPath($encode = false)
	{
		if ($encode) {
			return $this->encode($this->path);
		} else {
			return $this->path;
		}
	}


	/**
	 * Get encoded link to full URL file path
	 * Used for actual a href link to child
	 *
	 * @param string $childName just the child name
	 * @return string encoded url to path
	 */
	public function getLink($childName)
	{
		$path = $this->encode($this->path) . '/' . $this->encode($childName);
		if (substr($path, 0, 1) != '/') $path = '/'.$path;
		return LaravelURL::route('file').$path;
	}


	/**
	 * Encodes a full path besides the /
	 */
	public function encode($data)
	{
		//urlencode turns spaces into +, good for web visuals
		//rawurlencode turns spaces into %20 which is required for webdav
		if ($this->getProtocol() == "webdav") {
			$data = rawurlencode($data);
		} else {
			$data = urlencode($data);
		}

		// Because we may encode several paths, we need to keep the /
		$data = preg_replace('"%2F"', '/', $data);
		return $data;
	}



	/**
	 * Get the abs path base or root directory
	 * This is the base location of all files
	 *
	 * @return string of absolute base or root filesystem path
	 */
	public function getAbsBase()
	{
		if (isset($this->path)) {
			if (is_numeric($this->segments[0])) {
				// URL is /42 an integer
				return $this->absBase . '/index';
			} else {
				return $this->absBase;
			}
		} else {
			return $this->absBase;
		}

	}

	/**
	 * Get the abs path (absolute filesystem path, not a http url)
	 * This is abs base + path
	 *
	 * @return string of absolute filesystem path
	 */
	public function getAbs()
	{
		if (isset($this->path)) {
			return $this->getAbsBase() . '/' . $this->path;
		} else {
			return $this->getAbsBase();	
		}
	}


	/**
	 * Get the postID associated with this path
	 * Will return null if path is virtual or not found
	 *
	 * @return nullable int postID if found or not virtual
	 */
	public function getPostID()
	{
		// Computationally expensive, only gather once and save to $this->postID
		if (!isset($this->postID)) {
			$this->postID = null;
			if ($this->isStatic()) {
				// URL is static so we need to find the postID from the static name
				// Walk down the url until you reach a folder that is symlinked
				// to index/42.  Must walk down not up because posts folders can be nested
				$path = $this->path;
				$segments = $this->segments;
				for ($i = count($segments)-1; $i >= 0; $i--) {
					$abs = $this->getAbsBase(). '/' . $path;
					#echo "Analyze Symlink $abs</br />";
					if (preg_match("'".Config::get('mrcore.files')."/index/(\d+)'", $abs, $matches)) {
						// Found post ID right in path becuase using /index/42/somefile path (non static)
						$this->postID = $matches[1];
						break;
					} else {
						// Using static path, find postID by looking at the first found symlink to index/42
						if (is_dir($abs) && is_link($abs)) {
							$symlink = readlink($abs);
							if (preg_match("'index/(\d+)'", $symlink, $matches)) {
								$this->postID = $matches[1];
								break;
							}
						} else {
							array_pop($segments);
							$path = implode($segments, "/");
						}
					}
				}
			} else {
				// URL is simple /42/something, so 42 is postID
				$this->postID = $this->segments[0];
			}
		}
		return $this->postID;
	}


	/**
	 * Determine if the path exists on the filesystem
	 *
	 * @return bool true if exists
	 */
	public function exists()
	{
		return file_exists($this->getAbs());
	}


	/**
	 * Determine it the path is a directory
	 *
	 * @return bool true if path is a directory
	 */
	public function isDir()
	{
		return is_dir($this->getAbs());
	}


	/**
	 * Determine if the path is a direct /42/somefile.txt integer
	 * or if it uses symlinks /mysymlink/somefile.txt
	 *
	 * @return bool true if url is static symlinked instead of direct integer
	 */
	public function isStatic()
	{
		if (isset($this->path)) {
			if (is_numeric($this->segments[0])) {
				return false;
			} else {
				return true;
			}
		}
		return false;
	}


	/**
	 * Determine if the path is virtual or not
	 * Virtual means it doesn't actually exist on the filesystem
	 * but should exist as a valid path becuase there are subfolders
	 * that use it that are actual folders tied to posts
	 *
	 * @return bool true if isVirtual path
	 */
	public function isVirtual()
	{
		if ($this->getPostID()) {
			return false;
		} else {
			return ($this->exists());
		}
	}


	/**
	 * Determine if the path is readable by the current user
	 *
	 * @return bool true is readable
	 */
	public function isReadable()
	{
		if ($this->exists()) {
			if ($this->getPostID()) {
				$post = Post::find($this->getPostID());
				if (isset($post)) {
					// Check if magic folder
					if (!$post->hasPermission('write')) {
						foreach (Config::get('mrcore.magic_folders') as $magic) {
							if (preg_match('"/'.$magic.'($|/)"i', $this->getPath())) {
								// Found magic folder in url, check if not in magicExceptions
								$foundException = false;
								foreach (Config::get('mrcore.magic_folders_exceptions') as $exception) {
									if (preg_match('"/'.$exception.'($|/)"i', $this->getPath())) {
										$foundException = true;
										break;
									}
								}
								if (!$foundException) {
									// Contains magic and not an exception, user cannot read, access denied
									return false;	
								}
							}
						}
					}

					// UUID Permissions
					return $post->uuidPermission();
				} else {
					// No post id means virtual folder, always allow read
					return true;
				}

			} else {
				// No post id means virtual folder, always allow read
				return true;
			}
		} else {
			return false;
		}

	}


	/**
	 * Determine if the path is writable by the current user
	 *
	 * @return bool true is writable
	 */
	public function isWritable()
	{
		if ($this->exists()) {
			if ($this->getPostID()) {
				$post = Post::find($this->getPostID());
				if (isset($post)) {
					return $post->hasPermission('write');	
				}
				return false;
			} else {
				// No post id means virtual folder, never allow write
				return false;
			}
		} else {
			return false;
		}
	}


	/**
	 * Get just the filename
	 *
	 * @return string of just the filename
	 */
	public function getFilename()
	{
		if ($this->exists() && !$this->isDir()) {
			$pathinfo = pathinfo($this->getAbs());
			return $pathinfo['basename'];
		}
	}


	/**
	 * Get the full folder path to a file
	 *
	 * @return string of just the full folder abs path (not filename)
	 */
	public function getDirname()
	{
		if ($this->exists() && !$this->isDir()) {
			$pathinfo = pathinfo($this->getAbs());
			return $pathinfo['dirname'];
		}
	}


	/**
	 * Get the files extension
	 *
	 * @return string of files extension
	 */
	public function getExtension()
	{
		if ($this->exists() && !$this->isDir()) {
			$pathinfo = pathinfo($this->getAbs());
			return $pathinfo['extension'];
		}
	}


	/**
	 * Get the size of the file in bytes
	 *
	 * @return int size of file in bytes
	 */
	public function getSize()
	{
		if ($this->exists() && !$this->isDir()) {
			return filesize($this->getAbs());
		}
	}


	/**
	 * Get the mime type of the file
	 *
	 * @return string of file mime type
	 */
	public function getMimeType()
	{
		if ($this->exists() && !$this->isDir()) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mimetype = strtolower(finfo_file($finfo, $this->getAbs()));
			finfo_close($finfo);

			// Override, php does not find these mimes correctly
			// Sometimes complex html is seen as text/c-c++
			$ext = strtolower($this->getExtension());
			if ($ext == 'css') {
				return 'text/css';
			} elseif ($ext == 'js') {
				return 'application/javascript';
			} elseif ($ext == 'html' || $ext == 'htm') {
				return 'text/html';
			}

			return $mimetype;
		}
	}


}