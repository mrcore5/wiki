<?php

use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create permission items table
		Schema::create('permissions', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

			// Posts id, increments=auto_increment+primary key
			$table->increments('id');

			// Permission item
			$table->string('name', 50);

			// Permission item constant
			$table->string('constant', 50)->index(); #cannot be unique

			// Is user permission or post permission
			$table->boolean('user_permission')->default(true);

		});

		// Create user permissions table
		// Links users to permission items
		Schema::create('user_permissions', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

			$table->integer('user_id')->unsigned();
			$table->integer('permission_id')->unsigned();
			
			$table->primary(array('user_id', 'permission_id'));
			
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('permission_id')->references('id')->on('permissions');

		});

		// Create post role permissions table
		// Links posts to permission items
		Schema::create('post_permissions', function ($table) 
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

			$table->integer('post_id')->unsigned();
			$table->integer('permission_id')->unsigned();
			$table->integer('role_id')->unsigned();

			$table->primary(array('post_id', 'permission_id', 'role_id'));

			$table->foreign('post_id')->references('id')->on('posts');
			$table->foreign('permission_id')->references('id')->on('permissions');
			$table->foreign('role_id')->references('id')->on('roles');

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_permissions');
		Schema::drop('post_permissions');
		Schema::drop('permissions');
	}

}