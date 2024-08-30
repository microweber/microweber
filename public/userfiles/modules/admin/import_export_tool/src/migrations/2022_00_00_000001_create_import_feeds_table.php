<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('import_feeds')) {
            Schema::create('import_feeds', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->string('import_to')->nullable();
                $table->integer('parent_page')->nullable();
                $table->string('source_type')->nullable();
                $table->text('source_url')->nullable();
                $table->text('source_file')->nullable();
                $table->text('source_file_realpath')->nullable();
                $table->string('source_file_size')->nullable();
                $table->text('source_content_realpath')->nullable();
                $table->dateTime('last_import_start')->nullable();
                $table->dateTime('last_import_end')->nullable();
                $table->dateTime('last_downloaded_date')->nullable();
                $table->integer('download_images')->nullable();
                $table->integer('split_to_parts')->nullable();
                $table->longText('mapped_tags')->nullable();
                $table->text('mapped_content_realpath')->nullable();
                $table->longText('imported_content_ids')->nullable();
                $table->longText('detected_content_tags')->nullable();
                $table->string('content_tag')->nullable();
                $table->string('primary_key')->nullable();
                $table->string('update_items')->nullable();
                $table->string('old_content_action')->nullable();
                $table->integer('count_of_contents')->nullable();
                $table->integer('total_running')->nullable();
                $table->integer('is_draft')->nullable();
                $table->longText('custom_content_data_fields')->nullable();
                $table->longText('category_separators')->nullable();
                $table->longText('category_ids_separators')->nullable();
                $table->longText('category_add_types')->nullable();
                $table->longText('tags_separators')->nullable();
                $table->longText('media_url_separators')->nullable();
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
        Schema::drop('import_feeds');
    }
}
