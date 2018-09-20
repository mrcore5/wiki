<?php namespace Mrcore\Wiki\Api;

interface ConfigInterface
{
    public function host();

    public function base();

    public function baseUrl();

    public function files();

    public function filesBaseUrl();
}
