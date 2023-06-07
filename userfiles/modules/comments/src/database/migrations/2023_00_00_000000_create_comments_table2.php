<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateCommentsTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('comments')) {
            Schema::create('comments', function ($table) {
                $table->increments('id');
            });
        }
        Schema::table('comments', function (Blueprint $table) {

            if (!Schema::hasColumn('comments', 'reply_to_comment_id')) {
                $table->integer('reply_to_comment_id')->nullable();
            }
            if (!Schema::hasColumn('comments', 'rel_id')) {
                $table->string('rel_id')->nullable();
            }

            if (!Schema::hasColumn('comments', 'rel_type')) {
                $table->string('rel_type')->nullable();
            }

            if (!Schema::hasColumn('comments', 'comment_name')) {
                $table->text('comment_name')->nullable();
            }

            if (!Schema::hasColumn('comments', 'comment_email')) {
                $table->text('comment_name')->nullable();
            }
            if (!Schema::hasColumn('comments', 'comment_website')) {
                $table->text('comment_website')->nullable();
            }
            if (!Schema::hasColumn('comments', 'comment_body')) {
                $table->text('comment_body')->nullable();
            }
            if (!Schema::hasColumn('comments', 'comment_subject')) {
                $table->text('comment_subject')->nullable();
            }
            if (!Schema::hasColumn('comments', 'from_url')) {
                $table->text('from_url')->nullable();
            }
            if (!Schema::hasColumn('comments', 'is_moderated')) {
                $table->integer('is_moderated')->nullable();
            }

            if (!Schema::hasColumn('comments', 'is_spam')) {
                $table->integer('is_spam')->nullable();
            }
            if (!Schema::hasColumn('comments', 'for_newsletter')) {
                $table->integer('for_newsletter')->nullable();
            }

            if (!Schema::hasColumn('comments', 'is_new')) {
                $table->integer('is_new')->nullable();
            }
            if (!Schema::hasColumn('comments', 'created_by')) {
                $table->integer('created_by')->nullable();
            }
            if (!Schema::hasColumn('comments', 'edited_by')) {
                $table->integer('edited_by')->nullable();
            }

            if (!Schema::hasColumn('comments', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('comments', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
            if (!Schema::hasColumn('comments', 'session_id')) {
                $table->string('session_id')->nullable();
            }
            if (!Schema::hasColumn('comments', 'user_ip')) {
                $table->string('user_ip')->nullable();
            }

        });


    }


}
