<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('content_revisions_history')) {
            return;
        }

        Schema::create('content_revisions_history', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rel_type');
            $table->string('rel_id');
            $table->text('field');
            $table->longText('value');
            $table->integer('created_by');
            $table->integer('edited_by');
            $table->string('user_ip');
            $table->string('checksum');
            $table->string('session_id');
            $table->longText('url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_revisions_history');
    }
};
