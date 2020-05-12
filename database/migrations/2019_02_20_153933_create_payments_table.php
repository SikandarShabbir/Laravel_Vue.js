<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('payment_id');
            $table->integer('payment_fk_site_id');
            $table->integer('payment_fk_booking_id');
            $table->integer('payment_card_number');
            $table->string('payment_expiry_date');
            $table->string('payment_status');
            $table->string('payment_type');
            $table->integer('payment_total_amount');
            $table->timestamp('payment_created_at')->nullable();
            $table->timestamp('payment_updated_at')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
