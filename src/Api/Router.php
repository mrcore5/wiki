<?php namespace Mrcore\Wiki\Api;

/**
 * This is the router API layer used from the Mrcore class/facade
 * This layer allows us to change our model columns/properties while
 * maintaining a consistent interface for the wiki users
 */
class Router implements RouterInterface
{
    private $model;
    private $responseCode;
    private $responseRedirect;

    public function id()
    {
        return $this->model->id;
    }

    public function slug()
    {
        return $this->model->slug;
    }

    public function postID()
    {
        return $this->model->post_id;
    }

    public function url()
    {
        return $this->model->url;
    }

    public function clicks()
    {
        return $this->model->clicks;
    }

    public function responseCode($value = null)
    {
        if (isset($value)) {
            $this->responseCode = $value;
        }
        return $this->responseCode;
    }

    public function responseRedirect($value = null)
    {
        if (isset($value)) {
            $this->responseRedirect = $value;
        }
        return $this->responseRedirect;
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
