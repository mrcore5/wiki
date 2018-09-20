<?php

use Illuminate\Database\Migrations\Migration;

class CreateRouterTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create routes table
        Schema::create('router', function ($table) {
            // MySQL InnoDB Engine
            $table->engine = 'InnoDB';

            // Users id, increments=auto_increment+primary key
            $table->increments('id');

            // Route URL pattern
            $table->string('slug', 100)->index(); #->unique(); NO because named routes can be disabled

            // Route to post id
            // do NOT relate to posts table
            $table->integer('post_id')->nullable()->index();

            // Route to url
            $table->string('url', 255)->nullable();

            // Default route
            $table->boolean('default')->default(true)->index();

            // Static route
            $table->boolean('static')->default(false)->index();

            // Redirect route
            $table->boolean('redirect')->default(false)->index();

            // Click count
            $table->integer('clicks')->default(0);

            // Disabled
            $table->boolean('disabled')->default(false)->index();

            // Password is 60 because Hash::make('yourpass') is a 60 char hash
            $table->string('password', 60)->nullable();

            // Expiration date
            $table->dateTime('expiration')->nullable();

            // Created By
            $table->integer('created_by')->default(1);

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
        Schema::drop('router');
    }
}
