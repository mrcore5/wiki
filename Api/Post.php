<?php namespace Mrcore\Wiki\Api;

/**
 * This is the post API layer used from the Mrcore class/facade
 * This layer allows us to change our model columns/properties while
 * maintaining a consistent interface for the wiki users
 */
class Post implements PostInterface
{
    private $model;
    private $global;

    public function id()
    {
        return $this->model->id;
    }

    public function uuid()
    {
        return $this->model->uuid;
    }

    public function title()
    {
        return $this->model->title;
    }

    public function slug()
    {
        return $this->model->slug;
    }

    public function content()
    {
        return $this->model->content;
    }

    public function workbench()
    {
        return $this->model->workbench;
    }

    public function formatID()
    {
        return $this->model->format_id;
    }

    public function formatConstant()
    {
        return $this->model->format->constant;
    }

    public function clicks()
    {
        return $this->model->clicks;
    }
    

    /**
     * Prepare post by decrypting and parsing post content
     * @return post model object
     */
    public function prepare()
    {
        return $this->model->prepare();
    }


    /**
     * Return the actual post model object
     * This is for internal use only.  Using this raw model
     * negates the efforts of an API, please do not use it.
     * @return post model object
     */
    public function getModel()
    {
        return $this->model;
    }


    /**
     * Set the actual post model object
     * For internal use only
     * @param post model object $post
     */
    public function setModel($post)
    {
        if (isset($post)) {
            $this->model = $post;
        }
    }


    public function getGlobal()
    {
        return $this->global;
    }
    public function setGlobal($content)
    {
        $this->global = $content;
    }

    
    /**
     * Check if user has this permission item to this post
     * @param  string  $constant
     * @return boolean
     */
    public function hasPermission($constant)
    {
        return $this->model->hasPermission($constant);
    }


    /**
     * Allows the use of ->id to call ->id()
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name();
    }
}
