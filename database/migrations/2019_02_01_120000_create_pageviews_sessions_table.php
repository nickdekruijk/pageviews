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
            $table->ipAddress('ip')->nullable();
            $table->string('agent')->nullable();
            $table->boolean('parsed')->default(0)->index();
            $table->string('country', 5)->nullable();
            $table->string('region', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('postal', 20)->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
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
        Schema::drop(config('pageviews.database_prefix', 'pageviews_') . 'sessions');
    }
}
