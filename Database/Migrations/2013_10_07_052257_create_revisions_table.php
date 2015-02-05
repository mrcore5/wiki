<?php

use Illuminate\Database\Migrations\Migration;

class CreateRevisionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create posts table
		Schema::create('revisions', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

			// Posts id, increments=auto_increment+primary key
			$table->increments('id');

			// Post ID
			$table->integer('post_id')->unsigned();
			$table->foreign('post_id')->references('id')->on('posts');

			// Revision (number, starts at 1 and increments for same post_id)
			// If 0 it means uncommited revision, editing in progress
			$table->integer('revision')->default(0)->index();;

			// Posts title
			$table->string('title');

			// Posts content
			$table->longtext('content');

			// Posts comment (like a commit comment)
			$table->text('comment')->nullable();

			// Created By (user_id)
			$table->integer('created_by')->unsigned();
			$table->foreign('created_by')->references('id')->on('users');

			// Automatic created_at and updated_at columns
			$table->dateTime('created_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('revisions');
	}

}