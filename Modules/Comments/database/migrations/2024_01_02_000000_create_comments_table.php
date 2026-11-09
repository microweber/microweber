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
                $table->longText('comment_body')->nullable();

                $table->string('comment_name')->nullable();
                $table->string('comment_email')->nullable();
                $table->string('comment_website')->nullable();
                $table->string('user_agent')->nullable();

                $table->integer('reply_to_comment_id')->nullable();

                $table->integer('created_by')->nullable();
                $table->string('session_id')->nullable();
                $table->string('user_ip')->nullable();

                $table->integer('is_new')->nullable()->default(1);
                $table->integer('is_moderated')->nullable()->default(0);

                $table->integer('is_spam')->nullable();
                $table->integer('is_reported')->nullable();

                $table->timestamps();
            });
        } else {
            // The table may already exist as an older/minimal version (e.g. the
            // microweber-update "old version 2.13" migration creates comments
            // without user_agent). When that ran first the create above was
            // skipped and newer columns were never added — e.g. "table comments
            // has no column named user_agent" on a fresh install. Add any missing.
            Schema::table('comments', function (Blueprint $table) {
                $columns = [
                    'rel_type' => fn () => $table->string('rel_type')->nullable(),
                    'rel_id' => fn () => $table->string('rel_id')->nullable(),
                    'comment_subject' => fn () => $table->string('comment_subject')->nullable(),
                    'comment_body' => fn () => $table->longText('comment_body')->nullable(),
                    'comment_name' => fn () => $table->string('comment_name')->nullable(),
                    'comment_email' => fn () => $table->string('comment_email')->nullable(),
                    'comment_website' => fn () => $table->string('comment_website')->nullable(),
                    'user_agent' => fn () => $table->string('user_agent')->nullable(),
                    'reply_to_comment_id' => fn () => $table->integer('reply_to_comment_id')->nullable(),
                    'created_by' => fn () => $table->integer('created_by')->nullable(),
                    'session_id' => fn () => $table->string('session_id')->nullable(),
                    'user_ip' => fn () => $table->string('user_ip')->nullable(),
                    'is_new' => fn () => $table->integer('is_new')->nullable()->default(1),
                    'is_moderated' => fn () => $table->integer('is_moderated')->nullable()->default(0),
                    'is_spam' => fn () => $table->integer('is_spam')->nullable(),
                    'is_reported' => fn () => $table->integer('is_reported')->nullable(),
                ];
                foreach ($columns as $name => $add) {
                    if (!Schema::hasColumn('comments', $name)) {
                        $add();
                    }
                }
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
