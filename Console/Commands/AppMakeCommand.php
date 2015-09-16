<?php namespace Mrcore\Modules\Wiki\Console\Commands;

use File;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AppMakeCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'mrcore:wiki:app:make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Make a new mrcore wiki application.';

	protected $vendor;
	protected $package;

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
		$app = $this->argument('app');
		$tmp = explode('/', $app);
		$this->vendor = $tmp[0];
		$this->package = $tmp[1];

		$path = studly_case($this->vendor)."/".studly_case($this->package);
		$path = base_path().'/../Apps/'.$path;

		if (is_dir($path)) {
			$this->error('App already exists');
			exit();
		}

		exec("mkdir -p $path");
		$path = realpath($path);

		// Git clone (and remove .git)
		exec("cd $path && git clone https://github.com/mreschke/mrcore-appstub . && rm -rf .git");

		// Replace file contents
		$files = File::allFiles($path);
		foreach ($files as $file) {
			$this->replace($file);
		}

		// Rename files
		exec("mv $path/Providers/AppstubServiceProvider.php $path/Providers/".studly_case($this->package)."ServiceProvider.php");
		exec("mv $path/Database/Seeds/AppstubSeeder.php $path/Database/Seeds/".studly_case($this->package)."Seeder.php");
		exec("mv $path/Database/Seeds/AppstubTestSeeder.php $path/Database/Seeds/".studly_case($this->package)."TestSeeder.php");
		exec("mv $path/Http/Controllers/AppstubController.php $path/Http/Controllers/".studly_case($this->package)."Controller.php");
		exec("mv $path/Facades/Appstub.php $path/Facades/".studly_case($this->package).".php");
		exec("mv $path/Config/appstub.php $path/Config/$this->package.php");
		exec("mv $path/appstub $path/$this->package");

		// Composer update
		exec("cd $path && composer update");
		exec("cd $path && composer dump-autoload -o");
	}

	protected function replace($file)
	{
		$vendor = $this->vendor;
		$package = $this->package;
		$app = "$vendor/$package";
		$path = studly_case($vendor)."/".studly_case($package);
		$namespace = studly_case($vendor)."\\\\".studly_case($package);
		$doubleNamespace = studly_case($vendor)."\\\\\\\\".studly_case($package);
		$word = studly_case($vendor)." ".studly_case($package);

		// Order is critical
		$this->sed("mrcore/appstub", $app, $file);
		$this->sed("Mrcore Appstub", $word, $file);
		$this->sed("mreschke/mrcore-appstub", $app, $file);
		$this->sed('Mrcore/Appstub', $path, $file);
		$this->sed('Mrcore\\\\\\\\Appstub', $doubleNamespace, $file);
		$this->sed('Mrcore\\\\Appstub', $namespace, $file);
		$this->sed('mrcore:appstub', "$vendor:$package", $file);
		$this->sed('mrcore\.appstub', "$vendor.$package", $file);
		$this->sed('appstub::', "$package::", $file);
		$this->sed('AppstubController', studly_case($package).'Controller', $file);
		$this->sed('AppstubServiceProvider', studly_case($package).'ServiceProvider', $file);
		$this->sed('exists appstub', "exists ".str_replace('-', '_', $package), $file);
		$this->sed('database appstub', "database ".str_replace('-', '_', $package), $file);
		$this->sed('database=appstub', "database=".str_replace('-', '_', $package), $file);
		$this->sed('AppstubTestSeeder', studly_case($package)."TestSeeder", $file);
		$this->sed('Appstub', studly_case($package), $file);
		$this->sed("appstub", $package, $file);
	}

	protected function sed($search, $replace, $file)
	{
		exec("sed -i 's`$search`$replace`g' $file");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('app', InputArgument::REQUIRED, 'App name in laravel vendor/package format'),
		);
	}

}