<?php

use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create posts table
		Schema::create('comments', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

			// Posts id, increments=auto_increment+primary key
			$table->increments('id');

			// Post ID
			$table->integer('post_id')->unsigned();
			$table->foreign('post_id')->references('id')->on('posts');

			// Posts title
			$table->string('title');

			// Posts content
			$table->text('content');

			// Created By (user_id)
			$table->integer('created_by')->unsigned();
			$table->foreign('created_by')->references('id')->on('users');

			// Updated By (user_id)
			$table->integer('updated_by')->unsigned();
			$table->foreign('updated_by')->references('id')->on('users');

			// Automatic created_at and updated_at columns
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comments');
	}

}