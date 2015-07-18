<?php

$config = array();
$config['name'] = "Menu";
$config['description'] = "Navigation menu for pages and links.";
$config['author'] = "Microweber";
$config['ui'] = true;
$config['ui_admin'] = true;
$config['categories'] = "navigation";
$config['position'] = 15;
$config['version'] = 0.5;

$config['tables'] = function() {
  if (!Schema::hasTable('menus')) {
    Schema::create('menus', function($table) {
      $table->text('title');
      $table->string('item_type');
      $table->integer('parent_id');
      $table->integer('content_id');
      $table->integer('categories_id');
      $table->integer('position');
      $table->timestamps();
      $table->boolean('is_active');
      $table->longText('description');
      $table->text('url');
    });
  }
};
