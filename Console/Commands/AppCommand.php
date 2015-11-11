<?php namespace Mrcore\Wiki\Console\Commands;

use Mrcore\Foundation\Console\Commands\AppCommand as Command;

/**
 * Mrcore app/module helper command
 * @copyright 2015 Matthew Reschke
 * @license http://mreschke.com/license/mit
 * @author Matthew Reschke <mail@mreschke.com>
 */
class AppCommand extends Command
{
	protected $signature = 'mrcore:wiki:app';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->app = 'wiki';
		$this->ns = 'Mrcore\Wiki';
		$this->path = ['vendor/mrcore/wiki', '../Modules/Wiki'];
		$this->connection = 'mysql';
		$this->seeder = 'WikiSeeder';
		parent::__construct();
	}

}
