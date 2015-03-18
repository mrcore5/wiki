<?php namespace Mrcore\Modules\Wiki\Facades;

/**
 * @see \Mrcore\Mrcore\Mrcore
 */
class Mrcore extends \Illuminate\Support\Facades\Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'Mrcore\Modules\Wiki\Api\Mrcore'; }

}
