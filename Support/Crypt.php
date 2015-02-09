<?php namespace Mrcore\Modules\Wiki\Support;

use Crypt as LaravelCrypt;
use Config;

class Crypt {

	/**
	 * Encrypt content if use_encryption enabled in config
	 *
	 * @param string unencrypted $content
	 * @return string encrypted $content
	 */
	public static function encrypt($content)
	{
		if (Config::get('mrcore.use_encryption')) {
			return LaravelCrypt::encrypt($content);
		} else {
			return $content;
		}
	}

	/**
	 * Decrypt content if use_encryption enabled in config
	 *
	 * @param string encrypted $content
	 * @return string decrypted $content
	 */
	public static function decrypt($content)
	{
		if (Config::get('mrcore.use_encryption')) {
			return LaravelCrypt::decrypt($content);
		} else {
			return $content;
		}
	}

}