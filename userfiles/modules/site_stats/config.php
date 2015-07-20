<?php
$config = array();
$config['name'] = "Site stats";
$config['author'] = "Microweber";
$config['ui_admin'] = false;

$config['ui'] = false;
$config['position'] = 30;
$config['version'] = 0.3;
$config['type'] = "stats";

$config['tables']['stats_users_online'] = function() {
  if (!Schema::hasTable('translations')) {
    Schema::create('translations', function($table) {
      $table->integer('created_by');
      $table->integer('view_count')->default(1);
      $table->string('user_ip');
      $table->visit_date('date');
      $table->time('visit_time');
      $table->string('last_page');
      $table->string('session_id');
    });
  }
};
