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
                $table->integer('reply_to_comment_id')->nullable();
                $table->string('rel_type')->nullable();
                $table->string('rel_id')->nullable();
                $table->text('comment_name')->nullable();
                $table->text('comment_email')->nullable();
                $table->text('comment_website')->nullable();
                $table->text('comment_body')->nullable();
                $table->text('comment_subject')->nullable();
                $table->text('from_url')->nullable();
                $table->integer('is_moderated')->nullable();
                $table->integer('is_spam')->nullable();
                $table->integer('is_new')->nullable();
                $table->integer('for_newsletter')->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('edited_by')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->string('session_id')->nullable();
                $table->string('user_ip')->nullable();
            });
        }
    }

}
