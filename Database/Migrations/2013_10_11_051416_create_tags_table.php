<?php

use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create tags table
		Schema::create('tags', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

			// Posts id, increments=auto_increment+primary key
			$table->increments('id');

			// Tag name
			$table->string('name', 50);

			// Image
			$table->string('image', 20)->nullable();

			// Tags post id
			$table->integer('post_id')->unsigned()->nullable();
			$table->foreign('post_id')->references('id')->on('posts');

		});


		// Create post tags table
		Schema::create('post_tags', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

            $table->integer('post_id')->unsigned();
            $table->integer('tag_id')->unsigned();
			$table->primary(array('post_id', 'tag_id'));
            
            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('tag_id')->references('id')->on('tags');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('post_tags');
		Schema::drop('tags');
	}

}