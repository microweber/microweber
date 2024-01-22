<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHtmlFieldNotificationsMailsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $notifications_mails_log_table = 'notifications_mails_log';

        if (Schema::hasTable($notifications_mails_log_table)) {
            if (!Schema::hasColumn($notifications_mails_log_table, 'html')) {
                Schema::table($notifications_mails_log_table, function (Blueprint $table) {
                    $table->longText('html')->nullable();
                });
            }
        }


    }


}
