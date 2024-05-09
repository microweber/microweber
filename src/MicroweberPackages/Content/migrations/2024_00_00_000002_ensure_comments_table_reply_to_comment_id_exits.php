<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class EnsureCommentsTableReplyToCommentIdExits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('comments')) {
            Schema::table('comments', function ($table) {
                if (!Schema::hasColumn('comments', 'reply_to_comment_id')) {
                    $table->integer('reply_to_comment_id')->nullable();
                }

                if (!Schema::hasColumn('comments', 'is_spam')) {
                    $table->integer('is_spam')->nullable();
                }

                if (!Schema::hasColumn('comments', 'is_new')) {
                    $table->integer('is_new')->nullable();
                }

                if (!Schema::hasColumn('comments', 'for_newsletter')) {
                    $table->integer('for_newsletter')->nullable();
                }
            });
        }
    }

}
