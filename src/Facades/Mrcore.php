<?php namespace Mrcore\Wiki\Facades;

/**
 * @see \Mrcore\Mrcore\Mrcore
 */
class Mrcore extends \Illuminate\Support\Facades\Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Mrcore\Wiki\Api\Mrcore';
    }
}
