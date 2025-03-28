<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('logs')) {
            return;
        }

        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('level')->nullable();
            $table->text('message')->nullable();
            $table->text('rel_type')->nullable();
            $table->text('rel_id')->nullable();
            $table->string('channel')->default('default')->nullable();
            $table->timestamp('logged_at')->useCurrent()->nullable();

            //legacy fileds
            $table->text('is_system')->nullable();
            $table->text('field')->nullable();
            $table->text('rel')->nullable();
            $table->text('value')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
