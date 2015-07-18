<?php $config = [
	'name' => 'Multilanguage',
	'author' => 'Microweber',
	'no_cache' => true,
	'ui' => true,
	'ui_admin' => true,
	'type' => 'content',
	'categories' => 'language',
	'position' => '13',
	'version' => 0.1,
	'is_system' => true,
  'tables' => function() {
		if (!Schema::hasTable('translations')) {
			Schema::create('translations', function($table) {
				$table->string('lang')->index();
				$table->string('translatable_type')->index();
				$table->bigInteger('translatable_id')->index();
				$table->longText('translation');
			});
		}
  }
];
