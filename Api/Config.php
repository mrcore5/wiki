<?php namespace Mrcore\Modules\Wiki\Api;

use Config as ConfigFacade;

class Config implements ConfigInterface
{

	public function host()
	{
		return ConfigFacade::get('mrcore.host');	
	}

	public function base()
	{
		return base_path();
	}

	public function baseUrl()
	{
		return ConfigFacade::get('mrcore.base_url');
	}

	public function files()
	{
		return ConfigFacade::get('mrcore.files');
	}

	public function filesBaseUrl()
	{
		return ConfigFacade::get('mrcore.file_base_url');
	}

}
