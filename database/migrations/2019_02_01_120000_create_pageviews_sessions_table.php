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
        Schema::create(config('pageviews.prefix' . 'sessions', 'pageviews_sessions'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('session_id')->unique()->index();
            $table->ipAddress('ip')->nullable();
            $table->string('country', 5)->nullable();
            $table->string('region', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('postal', 20)->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->string('agent')->nullable();
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
        Schema::drop(config('pageviews.prefix' . 'sessions', 'pageviews_sessions'));
    }
}
