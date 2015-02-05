<?php

use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		// Create post formats table
		Schema::create('formats', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

			// Posts id, increments=auto_increment+primary key
			$table->increments('id');

			// Post format name
			$table->string('name', 50);

			// Post format constant
			$table->string('constant', 50)->unique();

			// Order
			$table->smallInteger('order')->index();

		});

		
		// Create post types table
		Schema::create('types', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

			// Posts id, increments=auto_increment+primary key
			$table->increments('id');

			// Post type name
			$table->string('name', 50);

			// Post type constant
			$table->string('constant', 50)->unique();
		});

		
		// Create post frameworks table
		Schema::create('frameworks', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

			// Posts id, increments=auto_increment+primary key
			$table->increments('id');

			// Post type name
			$table->string('name', 50);

			// Post type constant
			$table->string('constant', 50)->unique();

		});


		// Create post modes table
		Schema::create('modes', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

			// Posts id, increments=auto_increment+primary key
			$table->increments('id');

			// Post type name
			$table->string('name', 50);

			// Post type constant
			$table->string('constant', 50)->unique();
		});


		// Create posts table
		Schema::create('posts', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

			// Posts id, increments=auto_increment+primary key
			$table->increments('id');

			// Posts UUID
			$table->string('uuid', 36)->unique();

			// Posts title
			$table->string('title', 100);

			// Posts slug
			$table->string('slug', 100);

			// Posts content (longtext is 4,294,967,295 chars or 4gb)
			// mediumtext is 16,777,215 or 16mb
			// text is 65,535 chars or 64kb
			$table->longtext('content');

			// Workbench
			$table->string('workbench', 50)->nullable();

			// Contains PHP code
			$table->boolean('contains_script');

			// Contains HTML code
			$table->boolean('contains_html');

			// Post format
			$table->integer('format_id')->unsigned();
			$table->foreign('format_id')->references('id')->on('formats');

			// Post type
			$table->integer('type_id')->unsigned();
			$table->foreign('type_id')->references('id')->on('types');

			// Post framework
			$table->integer('framework_id')->unsigned()->nullable();
			$table->foreign('framework_id')->references('id')->on('frameworks');

			// Post mode
			$table->integer('mode_id')->unsigned();
			$table->foreign('mode_id')->references('id')->on('modes');

			// File Symlink enabled
			$table->boolean('symlink')->default(false);

			// Shared (enabled public uuid url)
			$table->boolean('shared')->default(false);

			// Post hidden
			$table->boolean('hidden')->default(false)->index();

			// Post deleted
			$table->boolean('deleted')->default(false)->index();

			// Password is 60 because Hash::make('yourpass') is a 60 char hash
			$table->string('password', 60)->nullable();

			// Click count
			$table->integer('clicks')->default(0);

			// Last indexed date
			$table->dateTime('indexed_at');

			// Created By (user_id)
			$table->integer('created_by')->unsigned();
			$table->foreign('created_by')->references('id')->on('users');

			// Updated By (user_id)
			$table->integer('updated_by')->unsigned();
			$table->foreign('updated_by')->references('id')->on('users');

			// Automatic created_at and updated_at columns
			$table->timestamps();
		});


		// Create posts locks table
		Schema::create('post_locks', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

            $table->integer('post_id')->unsigned();
            $table->integer('user_id')->unsigned();
			$table->primary(array('post_id', 'user_id'));
            
            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('user_id')->references('id')->on('users');

            $table->dateTime('locked_at');
		});


		// Create read status table
		Schema::create('post_reads', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

            $table->integer('post_id')->unsigned();
            $table->integer('user_id')->unsigned();
			$table->primary(array('post_id', 'user_id'));
            
            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('user_id')->references('id')->on('users');
            
		});


		// Create indexes table
		Schema::create('post_indexes', function ($table)
		{
			// MySQL InnoDB Engine
			$table->engine = 'InnoDB';

            $table->integer('post_id')->unsigned();
            $table->string('word', 32);
			$table->primary(array('post_id', 'word'));
            
            $table->foreign('post_id')->references('id')->on('posts');

            $table->integer('weight');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('post_indexes');
		Schema::drop('post_reads');
		Schema::drop('post_locks');
		Schema::drop('posts');
		Schema::drop('modes');
		Schema::drop('frameworks');
		Schema::drop('types');
		Schema::drop('formats');
	}

}