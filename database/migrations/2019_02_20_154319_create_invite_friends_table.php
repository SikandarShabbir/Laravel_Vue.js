<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInviteFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invite_friends', function (Blueprint $table) {
            $table->increments('invite_friend_id');
            $table->integer('invitefriend_fk_site_id');
            $table->string('invitefriend_first_name');
            $table->string('invitefriend_last_name');
            $table->string('invitefriend_email');
            $table->timestamp('invitefriend_created_at')->nullable();
            $table->timestamp('invitefriend_updated_at')->nullable();
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
        Schema::dropIfExists('invite_friends');
    }
}
