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
        if (!Schema::hasTable('comments')) {
            Schema::create('comments', function (Blueprint $table) {
                $table->id();
                $table->string('rel_type')->nullable();
                $table->string('rel_id')->nullable();

                $table->string('comment_subject')->nullable();
                $table->longText('comment_body')->nullable();;

                $table->string('comment_name')->nullable();
                $table->string('comment_email')->nullable();
                $table->string('comment_website')->nullable();

                $table->integer('reply_to_comment_id')->nullable();

                $table->integer('created_by')->nullable();
                $table->string('session_id')->nullable();
                $table->string('user_ip')->nullable();
                $table->string('user_agent')->nullable();
                $table->integer('is_new')->nullable()->default(1);
                $table->integer('is_moderated')->nullable()->default(0);

                $table->integer('is_spam')->nullable();
                $table->integer('is_reported')->nullable();


                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
