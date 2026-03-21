<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(Schema::hasTable('testimonials')) {

            Schema::table('testimonials', function (Blueprint $table) {
                if (!Schema::hasColumn('testimonials', 'client_image')) {
                    $table->string('client_image')->nullable();
                }

                if (!Schema::hasColumn('testimonials', 'rel_type')) {
                    $table->string('rel_type')->nullable();
                }

                if (!Schema::hasColumn('testimonials', 'rel_id')) {
                    $table->string('rel_id')->nullable();
                }
                if (!Schema::hasColumn('testimonials', 'settings')) {
                    $table->longText('settings')->nullable();
                }

                if (!Schema::hasColumn('testimonials', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }

                if (!Schema::hasColumn('testimonials', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
            });


        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('testimonials')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $columns = ['client_image', 'rel_type', 'rel_id', 'settings', 'updated_at', 'created_at'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('testimonials', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }


};
