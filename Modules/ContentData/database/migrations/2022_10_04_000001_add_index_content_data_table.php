<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        try {
            if (!Schema::hasTable('content_data')) { return; }

            Schema::table('content_data', function (Blueprint $table) {
                $table->index('rel_type');
                $table->index('rel_id');
                $table->index('field_name');
                $table->fullText('field_value');
            });
        } catch (Exception $e) {

        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            Schema::table('content_data', function (Blueprint $table) {
                $table->dropIndex(['rel_type']);
                $table->dropIndex(['rel_id']);
                $table->dropIndex(['field_name']);
                $table->dropFullText(['field_value']);
            });
        } catch (Exception $e) {
            // Index may not exist
        }
    }

};
