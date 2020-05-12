<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookedTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booked_targets', function (Blueprint $table) {
            $table->increments('bookedtarget_id');
            $table->integer('bookedtarget_fk_site_id');
            $table->integer('bookedtarget_fk_booking_id');
            $table->string('booked_target_number');
            $table->string('bookedtarget_position');
            $table->string('bookedtarget_time');
            $table->timestamp('bookedtarget_created_at')->nullable();
            $table->timestamp('bookedtarget_updated_at')->nullable();
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
        Schema::dropIfExists('booked_targets');
    }
}
