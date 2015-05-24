<?php namespace Mrcore\Modules\Wiki\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AppGitCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'mrcore:wiki:app:git';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install or update an app from git.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$url = $this->argument('url');
		$app = $this->argument('app');
		
		if (isset($app)) {
			$tmp = explode('/', $app);
			$vendor = $tmp[0];
			$package = $tmp[1];
		} else {
			$tmp = explode('/', $url);
			$vendor = $tmp[count($tmp) -2];
			$package = $tmp[count($tmp) -1];
			$app = "$vendor/$package";
		}
		$namespace = studly_case($vendor)."\\".studly_case($package);
		$path = studly_case($vendor)."/".studly_case($package);
		$path = base_path().'/../Apps/'.$path;

		$existed = true;
		if (!is_dir($path)) {
			$existed = false;
			exec("mkdir -p $path");
		}
		$path = realpath($path);
		
		if (!$existed) {
			// Git clone
			exec("cd $path && git clone $url .");
		} else {
			// Git pull
			exec("cd $path && git pull");
		}

		// Composer update
		exec("cd $path && composer update");
		exec("cd $path && composer dump-autoload -o");

		// Npm install
		if (file_exists("$path/package.json")) {
			exec("cd $path && npm update");
		}

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('url', InputArgument::REQUIRED, 'Github Repository URL'),
			array('app', InputArgument::OPTIONAL, 'App name in laravel vendor/package format'),
		);
	}
	
}