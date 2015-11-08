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
        // This is actually an update to the existing stock laravel 5.1 table
        // I have to maintain legacy mrcore5 migrations

        // Add columns to users table
        Schema::table('users', function($table)
        {
            // Larave id here
            $table->dropColumn('name');

            // Drop here because legacy mrcore5 has another update migration to add this
            $table->dropColumn('remember_token');

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
        Schame::table('users', function($table))
        {
            $table->string('name')->after('id');

            $table->dropColumn('uuid');
            $table->dropColumn('first');
            $table->dropColumn('last');
            $table->dropColumn('alias');
            $table->dropColumn('avatar');
            $table->dropColumn('login_at');
            $table->dropColumn('global_post_id');
            $table->dropColumn('home_post_id');
            $table->dropColumn('disabled');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        }
    }
}


