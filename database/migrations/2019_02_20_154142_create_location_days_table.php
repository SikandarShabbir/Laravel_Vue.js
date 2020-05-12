<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_days', function (Blueprint $table) {
            $table->increments('location_day_id');
            $table->integer('locationday_fk_site_id');
            $table->integer('locationday_fk_location_id');
            $table->integer('locationday_fk_days_id');
            $table->string('locationday_start_time');
            $table->string('locationday_end_time');
            $table->string('locationday_is_open')->default(1);
            $table->timestamp('locationday_created_at')->nullable();
            $table->timestamp('locationday_updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('location_days');
    }
}
