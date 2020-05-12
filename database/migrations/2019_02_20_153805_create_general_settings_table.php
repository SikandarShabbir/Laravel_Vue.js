<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->increments('setting_id');
            $table->integer('setting_fk_site_id');
            $table->string('setting_language');
            $table->string('setting_primary_color');
            $table->string('setting_secondary_color');
            $table->string('setting_text_color');
            $table->string('setting_text_bg_color');
            $table->string('setting_background');
            $table->string('setting_font');
            $table->string('setting_logo');
            $table->string('setting_mail_host');
            $table->string('setting_mail_username');
            $table->string('setting_mail_password');
            $table->string('setting_mail_encryption');
            $table->tinyInteger('setting_customer_notification_active')->default(1);
            $table->tinyInteger('setting_admin_notification_active')->default(1);
            $table->timestamp('setting_created_at')->nullable();
            $table->timestamp('setting_updated_at')->nullable();
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
        Schema::dropIfExists('general_settings');
    }
}
