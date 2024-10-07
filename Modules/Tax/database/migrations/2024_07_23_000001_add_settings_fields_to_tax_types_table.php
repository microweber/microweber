<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tax_types')) {
            return;
        }

        Schema::table('tax_types', function (Blueprint $table) {
            if (!Schema::hasColumn('tax_types', 'settings')) {
                $table->text('settings')->nullable();
            }
        });
    }

};
