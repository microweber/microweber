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
                $table->string('name');
                $table->string('type');
                $table->string('from_name');
                $table->string('from_email');
                $table->string('copy_to')->nullable();
                $table->string('subject');
                $table->text('message');
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
