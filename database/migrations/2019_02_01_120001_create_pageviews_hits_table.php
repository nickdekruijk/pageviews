<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageviewsHitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('pageviews.database_prefix', 'pageviews_') . 'hits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamp('time')->index();
            $table->integer('session_id')->unsigned();
            $table->string('url')->nullable();
            $table->string('referer')->nullable();
            $table->boolean('parsed')->default(0)->index();

            $table->foreign('session_id')->references('id')->on(config('pageviews.database_prefix', 'pageviews_') . 'sessions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(config('pageviews.database_prefix', 'pageviews_') . 'hits');
    }
}
