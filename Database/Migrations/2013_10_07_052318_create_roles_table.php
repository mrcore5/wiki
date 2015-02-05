<?php

use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create roles table
		Schema::create('roles', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB'; 

			// Role id, increment=auto_increment+primary key
			$table->increments('id');

			// Role name
			$table->string('name', 50);

			// Role name constant (for use in code conditions)
			$table->string('constant', 50)->unique()->index();

		});

        // Create user_roles (Many-to-Many relation) table
        Schema::create('user_roles', function($table)
        {
        	// MySQL InnoDB Engine
            $table->engine = 'InnoDB';

            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
			$table->primary(array('user_id', 'role_id'));
            
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::drop('user_roles');
        Schema::drop('roles');
	}

}
