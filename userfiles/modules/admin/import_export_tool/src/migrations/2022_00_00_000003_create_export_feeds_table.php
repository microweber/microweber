<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExportFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('export_feeds')) {
            Schema::create('export_feeds', function (Blueprint $table) {

                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->string('export_type')->nullable();
                $table->string('export_format')->nullable();
                $table->integer('split_to_parts')->nullable();
                $table->integer('is_draft')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('export_feeds');
    }
}
