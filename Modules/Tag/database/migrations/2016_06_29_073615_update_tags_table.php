<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateTagsTable extends Migration {

	public function up()
	{
		Schema::table('tagging_tags', function ($table) {
			//$table->foreign('tag_group_id')->references('id')->on('tagging_tag_groups');
		});

	}


	public function down()
	{
        Schema::disableForeignKeyConstraints();
		Schema::table('tagging_tags', function ($table) {
		//	$table->dropForeign('tagging_tags_tag_group_id_foreign');
		});
	}
}
