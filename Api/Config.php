<?php namespace Mrcore\Modules\Wiki\Api;

use Config as ConfigFacade;

class Config implements ConfigInterface
{

	public function host()
	{
		// Get hostname, so if server_name is
		// mrcore5.lindev.mreschke.com, return just mreschke.net
		$root = $_SERVER['SERVER_NAME'];
		$tmp = explode(".", $root);
		$host = $tmp[count($tmp) -2].'.'.$tmp[count($tmp) -1];
		return $host;
	}

	public function base()
	{
		return base_path();
	}

	public function baseUrl()
	{
		return substr(ConfigFacade::get('app.url'), strpos(ConfigFacade::get('app.url'), '://') +1);
	}

	public function files()
	{
		return ConfigFacade::get('mrcore.wiki.files');
	}

	public function filesBaseUrl()
	{
		substr(ConfigFacade::get('app.url'), strpos(ConfigFacade::get('app.url'), '://') +3).'/file';
	}

}
