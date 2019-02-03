<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageViewsSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('pageviews.database_prefix', 'pageviews_') . 'sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('time')->index();
            $table->ipAddress('ip')->nullable();
            $table->string('country', 5)->nullable();
            $table->string('city', 50)->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->boolean('is_ajax')->nullable();
            $table->string('user_agent')->nullable();
            $table->boolean('is_mobile')->nullable();
            $table->boolean('is_desktop')->nullable();
            $table->boolean('is_bot')->nullable();
            $table->string('bot')->nullable();
            $table->string('os', 20)->nullable();
            $table->string('os_family', 20)->nullable();
            $table->string('browser_family', 20)->nullable();
            $table->string('browser', 20)->nullable();
            $table->string('browser_language_family', 20)->nullable();
            $table->string('browser_language', 20)->nullable();
            $table->string('device', 20)->nullable();
            $table->string('brand', 20)->nullable();
            $table->string('model', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(config('pageviews.database_prefix', 'pageviews_') . 'sessions');
    }
}
