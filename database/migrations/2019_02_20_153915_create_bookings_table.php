<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('booking_id');
            $table->integer('booking_fk_site_id');
            $table->integer('booking_fk_location_id');
            $table->string('booking_target_start_time');
            $table->string('booking_target_end_time');
            $table->string('booking_first_name');
            $table->string('booking_last_name');
            $table->string('booking_email');
            $table->string('booking_target_date');
            $table->string('booking_phone');
            $table->timestamp('booking_created_at')->nullable();
            $table->timestamp('booking_updated_at')->nullable();
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
        Schema::dropIfExists('bookings');
    }
}
