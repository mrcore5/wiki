<?php

use Illuminate\Database\Migrations\Migration;

class CreateBadgesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create badges table
		Schema::create('badges', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

			// Posts id, increments=auto_increment+primary key
			$table->increments('id');

			// Badge name
			$table->string('name', 50);

			// Image
			$table->string('image', 20)->nullable();

			// Badges post id
			$table->integer('post_id')->unsigned()->nullable();
			$table->foreign('post_id')->references('id')->on('posts');

		});


		// Create post badges table
		Schema::create('post_badges', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

            $table->integer('post_id')->unsigned();
            $table->integer('badge_id')->unsigned();
			$table->primary(array('post_id', 'badge_id'));
            
            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('badge_id')->references('id')->on('badges');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('post_badges');
		Schema::drop('badges');
	}

}