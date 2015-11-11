<?php namespace Mrcore\Wiki\Support\Sabredav;

use Request;
use Sabre\DAV;
use Sabre\DAV\Collection;
use Mrcore\Wiki\Support\Filemanager;
use Mrcore\Wiki\Support\Filemanager\Url;

class Directory extends Collection {

	private $path;
	private $abs;
	public $url;

	function __construct($path, $url = null) {
		$this->url = $url;
		$this->path = $path;
		$this->abs = $url->getAbs();
	}

	function getChildren()
	{
		$children = array();
		foreach (scandir($this->abs) as $node) {
			if ($node === '.' || $node === '..') continue;
			$child = $this->getChild($node);
			if (isset($child)) {
				$children[] = $child;
			}
		}
		return $children;
	}


	function getChild($name)
	{
		$url = new Url(Request::url().'/'.$name);
		if ($url->exists()) {
			$allow = false;
			if ($url->getPostID() == $this->url->getPostID()) {
				// node is just a file/folder in parent, not another post
				// they have the same post ID so use parents permissions
				// no need to get permission again, this is an optimization
				$allow = true;
			} else {
				// node is another post inside this post, it has its own permissions
				$allow = $url->isReadable();
			}
			if ($allow) {
				if ($url->isDir()) {
					return new Directory($name, $url);
				} else {
					return new File($url->getAbs());
				}
			}
		} else {
			# I comment this out, or else if we have say a bad symlink in a directory
			# it just dies here and doesn't show any filemanager
			#throw new DAV\Exception\NotFound("The file '$name' could not be found");
		}
	}


	function childExists($name)
	{
		return file_exists($this->abs . '/' . $name);
	}


	function getName()
	{
		return basename($this->abs);
	}

}