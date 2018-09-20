<?php namespace Mrcore\Wiki\Api;

interface RouterInterface
{
    public function id();

    public function slug();

    public function postID();

    public function url();

    public function clicks();

    public function responseCode($value = null);

    public function responseRedirect($value = null);
}
