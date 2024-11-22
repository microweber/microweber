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
            Schema::table('content_data', function (Blueprint $table) {
                $table->index('rel_type');
                $table->index('rel_id');
                $table->index('field_name');
                $table->fullText('field_value');
            });
        } catch (Exception $e) {

        }


    }

};
