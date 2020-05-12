<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('days', function (Blueprint $table) {
            $table->increments('day_id');
            $table->integer('day_number');
            $table->string('day_name');
            $table->string('day_start_time');
            $table->string('day_end_time');
            $table->timestamp('day_created_at')->nullable();
            $table->timestamp('day_updated_at')->nullable();
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
        Schema::dropIfExists('days');
    }
}
