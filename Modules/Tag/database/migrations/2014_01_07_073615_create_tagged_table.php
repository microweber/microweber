<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaggedTable extends Migration {

	public function up() {
		Schema::create('tagging_tagged', function(Blueprint $table) {
			$table->increments('id');
			if(config('tagging.primary_keys_type') == 'string') {
				$table->string('taggable_id', 36)->index();
			} else {
				$table->integer('taggable_id')->unsigned()->index();
			}
			$table->string('taggable_type', 125)->index();
			$table->string('tag_name', 125);
			$table->string('tag_slug', 125)->index();
		});
	}

	public function down() {
		Schema::dropIfExists('tagging_tagged');
	}
}
