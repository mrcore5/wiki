<?php namespace Mrcore\Modules\Wiki\Console\Commands;

use DB;
use App;
use Config;
use Exception;
use Illuminate\Console\Command;

class DbCommand extends Command
{
	protected $name = 'Db';
	protected $package = 'Mrcore/Modules/Wiki';
	protected $version = '1.0.0';
	protected $description = 'Db Helper Commands';
	protected $signature = 'mrcore:wiki:db
		{action : migrate, seed, reseed, rollback, refresh},
	';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->connection = Config::get('database.connections.'.Config::get('database.default'));
		$this->connection['name'] = Config::get('database.default');
		$this->path = realpath(__DIR__.'/../../');
		$this->seeder = 'WikiSeeder';

		// Get path in apps or vendors, relative to artisan command
		$this->relativePath = "../Modules/Wiki";
		if (!file_exists($this->relativePath)) {
			$this->relativePath = "vendor/mrcore/wiki";
		}
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$method = $this->argument('action');
		if (method_exists($this, $method)) {
			$this->$method();
		} else {
			$this->error("$method() method not found");
		}
	}

	/**
	 * Migrate database
	 */
	protected function migrate()
	{
		$this->createDatabase();
		$this->call('migrate', [
			'--database' => $this->connection['name'],
			'--path' => "$this->relativePath/Database/Migrations/"
		]);
	}

	/**
	 * Seed database
	 */
	protected function seed()
	{
		if (App::environment() === 'production') {
			throw new Exception("You cannot seed in production");
		}
		$this->call('db:seed', [
			'--database' => $this->connection['name'],
			'--class' => $this->seeder
		]);
	}

	/**
	 * Refresh then seed database
	 */
	protected function reseed()
	{
		if (App::environment() === 'production') {
			throw new Exception("You cannot seed in production");
		}
		$this->refresh();
		$this->seed();
	}

	/**
	 * Rollback migrations
	 */
	protected function rollback()
	{
		$this->call('migrate:rollback', [
			'--database' => $this->connection['name']
		]);
	}

	/**
	 * Refresh migrations (rollback all, then migrate)
	 */
	protected function refresh()
	{
		if (App::environment() === 'production') {
			throw new Exception("You cannot rollback in production");
		}
		$this->rollback();
		$this->migrate();
	}

	/**
	 * Create database if not exists
	 */
	protected function createDatabase()
	{
		// Laravel DB cannot connect without a valid database, so this is a chicken egg problem
		// Use raw mysql to create the database
		$conn = $this->connection;
dd($conn);
		// Create connection
		$handle = new \mysqli($conn['host'], $conn['username'], $conn['password']);
		if ($handle->connect_error) {
			dd("Connection failed: ".$handle->connect_error);
		}

		// Create database
		$sql = "CREATE DATABASE IF NOT EXISTS $conn[database]";
		if ($handle->query($sql) === TRUE) {
			$this->info("Database $conn[database] created successfully");
		} else {
			dd("Error creating database $conn[database]: ".$handle->error);
		}
		$handle->close();
	}

}
