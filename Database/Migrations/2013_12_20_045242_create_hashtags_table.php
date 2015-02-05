<?php

use Illuminate\Database\Migrations\Migration;

class CreateHashtagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create hashtag table
		Schema::create('hashtags', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

			// Hashtag
			$table->string('hashtag', 50)->unique();

			// Post
			$table->integer('route_id')->unsigned();
			$table->foreign('route_id')->references('id')->on('router');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hashtags');
	}

}