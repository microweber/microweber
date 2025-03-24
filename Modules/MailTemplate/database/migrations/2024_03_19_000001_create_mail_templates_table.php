<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('mail_templates')) {
            Schema::create('mail_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();;
                $table->string('type')->nullable();;
                $table->string('from_name')->nullable();;
                $table->string('from_email')->nullable();;
                $table->string('copy_to')->nullable();
                $table->string('subject')->nullable();;
                $table->text('message')->nullable();;
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('mail_templates');
    }
};
