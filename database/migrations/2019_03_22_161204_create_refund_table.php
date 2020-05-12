<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund', function (Blueprint $table) {
            $table->increments('refund_id');
            $table->integer('refund_fk_site_id');
            $table->integer('refund_fk_booking_id');
            $table->string('refund_card_number')->nullable();
            $table->string('refund_cheque_number')->nullable();
            $table->string('refund_type');
            $table->decimal('refund_total_amount', 8, 2);
            $table->timestamp('refund_created_at')->nullable();
            $table->timestamp('refund_updated_at')->nullable();
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
        Schema::dropIfExists('refund');
    }
}
