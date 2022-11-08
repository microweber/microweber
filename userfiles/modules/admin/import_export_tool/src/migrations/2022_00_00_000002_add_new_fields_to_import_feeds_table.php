<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\Schema;

class AddNewFieldsToImportFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_feeds', function (Blueprint $table) {

            $table->longText('custom_content_data_fields')->nullable();
            $table->longText('category_separators')->nullable();
            $table->longText('category_ids_separators')->nullable();
            $table->longText('category_add_types')->nullable();
            $table->longText('tags_separators')->nullable();
            $table->longText('media_url_separators')->nullable();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
