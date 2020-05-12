<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartialPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partial_payment', function (Blueprint $table) {
            $table->increments('partial_payment_id');
            $table->integer('partial_payment_fk_booking_id');
            $table->decimal('partial_payment_amount_paid', 8, 2);
            $table->timestamp('partial_payment_created_at')->nullable();
            $table->timestamp('partial_payment_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partial_payment');
    }
}
