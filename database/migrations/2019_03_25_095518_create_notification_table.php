<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
			$table->increments('notification_id');
			$table->integer('notification_fk_site_id');
			$table->string('notification_subject');
			$table->string('notification_message');
            $table->string('notification_type');
            $table->tinyinteger('notification_is_admin')->default(0);
			$table->timestamp('notification_created_at')->nullable();
			$table->timestamp('notification_updated_at')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
