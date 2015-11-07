<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add columns to users table
        Schema::table('users', function($table)
        {
            // Larave id here
            $table->dropColumn('name');

            // Users uuid
            $table->string('uuid', 36)->unique()->after('id');

            // Laravel password here

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

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->string('name')->after('id');

        $table->drop('uuid');
        $table->drop('first');
        $table->drop('last');
        $table->drop('alias');
        $table->drop('avatar');
        $table->drop('login_at');
        $table->drop('global_post_id');
        $table->drop('home_post_id');
        $table->drop('disabled');
        $table->drop('created_by');
        $table->drop('updated_by');
    }
}

