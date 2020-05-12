<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('location_id');
            $table->integer('location_fk_site_id');
            $table->integer('location_fk_booking_id');
            $table->string('location_name');
            $table->string('location_address')->nullable();
            $table->string('location_country')->nullable();
            $table->integer('location_number_of_target');
            $table->string('location_closed_days');
            $table->integer('location_open_days');
            $table->string('location_operation_hour');
            $table->string('location_total_position');
            $table->integer('location_price_each_position');
            $table->timestamp('location_created_at')->nullable();
            $table->timestamp('location_updated_at')->nullable();
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
        Schema::dropIfExists('locations');
    }
}
