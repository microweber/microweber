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
            $table->string('rel_type')->nullable();
            $table->string('rel_id')->nullable();
            $table->text('field')->nullable();
            $table->longText('value')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('edited_by')->nullable();
            $table->string('user_ip')->nullable();
            $table->string('checksum')->nullable();
            $table->string('session_id')->nullable();
            $table->longText('url')->nullable();
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
