<?php namespace Mrcore\Wiki\Support\Sabredav;

use Sabre\DAV;

class File extends DAV\FS\Node implements DAV\IFile
{
    public $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Updates the data
     *
     * @param resource $data
     * @return void
     */
    public function put($data)
    {
        file_put_contents($this->path, $data);
    }

    /**
     * Returns the data
     *
     * @return string
     */
    public function get()
    {
        return fopen($this->path, 'r');
        $value = Request::header('Content-Type');
    }

    /**
     * Delete the current file
     *
     * @return void
     */
    public function delete()
    {
        unlink($this->path);
    }

    /**
     * Returns the size of the node, in bytes
     *
     * @return int
     */
    public function getSize()
    {
        #echo $this->path;
        #exit();
        return filesize($this->path);
    }

    /**
     * Returns the ETag for a file
     *
     * An ETag is a unique identifier representing the current version of the file. If the file changes, the ETag MUST change.
     * The ETag is an arbitrary string, but MUST be surrounded by double-quotes.
     *
     * Return null if the ETag can not effectively be determined
     *
     * @return mixed
     */
    public function getETag()
    {
        return null;
    }

    /**
     * Returns the mime-type for a file
     *
     * If null is returned, we'll assume application/octet-stream
     *
     * @return mixed
     */
    public function getContentType()
    {
        return null;
    }


    /**
     * Get the file extension
     * @return string
     */
    public function getExtension()
    {
        $pathinfo = pathinfo($this->path);
        return strtolower($pathinfo['extension']);
    }

    
    /**
     * Check if this file is an image
     * @return boolean
     */
    public function isImage()
    {
        $ext = $this->getExtension();
        $images = ['jpg', 'jpeg', 'gif', 'png'];
        return in_array($ext, $images);
    }
}
