<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function($table)
        {
            // MySQL InnoDB Engine
            $table->engine = 'InnoDB';

            // User id, increments=auto_increment+primary key
            $table->increments('id');

            // User uuid
            $table->string('uuid', 36)->unique();

			// User email (username)
			$table->string('email')->unique();

            // User Password
            $table->string('password', 60);

            // User first name
            $table->string('first', 25);

            // User first name
            $table->string('last', 25);

            // User alias name
            $table->string('alias', 50)->unique();

            // User alias name
            // Just filename (avatar42.png)
            $table->string('avatar', 20)->default('avatar1.png');

            // Last Login date
            $table->dateTime('login_at');

            // Users Global post id
            $table->integer('global_post_id')->unsigned()->nullable();
            #$table->foreign('global_post_id')->references('id')->on('posts');

            // Users Home post id
            $table->integer('home_post_id')->unsigned()->nullable();
            #$table->foreign('home_post_id')->references('id')->on('posts');

            // Default enabled
            $table->boolean('disabled')->default(false)->index();

            // Created By (user_id)
            $table->integer('created_by');

            // Updated By (user_id)
			$table->integer('updated_by');

            // Created_at and updated_at timestamps
            $table->timestamps();

            // This was before laravel introduced remember_token
            // So that is added later in 2014_04_16_161519_update_users_table

		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}


