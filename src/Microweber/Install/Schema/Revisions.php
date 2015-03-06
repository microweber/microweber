<?php
namespace Microweber\Install\Schema;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class Revisions
{
    protected $table;

    public function __construct()
    {
        $this->table = Config::get('sofa_revisionable.table', 'revisions');
    }

    function seed()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('action', 255);
            $table->string('table_name', 255);
            $table->integer('row_id')->unsigned();
            $table->binary('old')->nullable();
            $table->binary('new')->nullable();
            $table->string('user', 255)->nullable();
            $table->string('ip')->nullable();
            $table->string('ip_forwarded')->nullable();
            $table->timestamp('created_at');

            $table->index('action');
            $table->index(['table_name', 'row_id']);
        });
    }

}