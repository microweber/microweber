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
            });
        }
    }

}
