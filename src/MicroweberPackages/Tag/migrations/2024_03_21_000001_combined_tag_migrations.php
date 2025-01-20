<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CombinedTagMigrations
    extends Migration
{
    public function up()
    {
        // Create tagging_tagged table if not exists
        if (!Schema::hasTable('tagging_tagged')) {
            Schema::create('tagging_tagged', function (Blueprint $table) {
                $table->increments('id');
                if (config('tagging.primary_keys_type') == 'string') {
                    $table->string('taggable_id', 36)->index();
                } else {
                    $table->integer('taggable_id')->unsigned()->index();
                }
                $table->string('taggable_type', 125)->index();
                $table->string('tag_name', 125);
                $table->string('tag_slug', 125)->index();
            });
        }

        // Create tagging_tag_groups table if not exists
        if (!Schema::hasTable('tagging_tag_groups')) {
            Schema::create('tagging_tag_groups', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug', 125)->index();
                $table->string('name', 125);
            });
        }

        // Create tagging_tags table if not exists
        if (!Schema::hasTable('tagging_tags')) {
            Schema::create('tagging_tags', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug', 125)->index();
                $table->string('name', 125);
                $table->boolean('suggest')->default(false);
                $table->integer('count')->unsigned()->default(0);
                $table->integer('tag_group_id')->unsigned()->nullable();
            });
        }

        // Add foreign key if not exists
        if (Schema::hasTable('tagging_tags')) {
            Schema::table('tagging_tags', function (Blueprint $table) {
                // Check if foreign key exists
                $foreignKeys = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableForeignKeys($table->getTable());

                $hasForeignKey = false;
                foreach ($foreignKeys as $foreignKey) {
                    if ($foreignKey->getName() === 'tagging_tags_tag_group_id_foreign') {
                        $hasForeignKey = true;
                        break;
                    }
                }

                if (!$hasForeignKey) {
                    $table->foreign('tag_group_id')->references('id')->on('tagging_tag_groups');
                }
            });
        }

        // Add description column if not exists
        if (Schema::hasTable('tagging_tags') && !Schema::hasColumn('tagging_tags', 'description')) {
            Schema::table('tagging_tags', function (Blueprint $table) {
                $table->text('description')->nullable();
            });
        }

        // Add locale column if not exists
        if (Schema::hasTable('tagging_tags') && !Schema::hasColumn('tagging_tags', 'locale')) {
            Schema::table('tagging_tags', function (Blueprint $table) {
                $table->string('locale', 5)->nullable();
            });
        }
    }

};
