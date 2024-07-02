<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
            });
        }
        if (Schema::hasTable('media')) {
            if (!Schema::hasColumn('media', 'model_type')) {


                Schema::table('media', function (Blueprint $table) {
                    $table->string('model_type')->nullable()->index();
                });
            }

            if (!Schema::hasColumn('media', 'model_id')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->string('model_id')->nullable()->index();
                });
            }
            if (!Schema::hasColumn('media', 'uuid')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->uuid()->nullable()->unique();
                });
            }

            if (!Schema::hasColumn('media', 'name')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->string('name')->nullable();
                });
            }


            if (!Schema::hasColumn('media', 'collection_name')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->string('collection_name')->nullable();
                });
            }

            if (!Schema::hasColumn('media', 'file_name')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->string('file_name')->nullable();
                });
            }
            if (!Schema::hasColumn('media', 'mime_type')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->string('mime_type')->nullable();
                });
            }
            if (!Schema::hasColumn('media', 'disk')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->string('disk')->nullable();
                });
            }
            if (!Schema::hasColumn('media', 'conversions_disk')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->string('conversions_disk')->nullable();
                });
            }
            if (!Schema::hasColumn('media', 'size')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->unsignedBigInteger('size')->nullable();
                });
            }
            if (!Schema::hasColumn('media', 'manipulations')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->json('manipulations')->nullable();
                });
            }
            if (!Schema::hasColumn('media', 'custom_properties')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->json('custom_properties')->nullable();
                });
            }
            if (!Schema::hasColumn('media', 'generated_conversions')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->json('generated_conversions')->nullable();
                });
            }
            if (!Schema::hasColumn('media', 'responsive_images')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->json('responsive_images')->nullable();
                });
            }
            if (!Schema::hasColumn('media', 'order_column')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->unsignedInteger('order_column')->nullable()->index();
                });
            }
            if (!Schema::hasColumn('media', 'created_at')) {
                Schema::table('media', function (Blueprint $table) {
                    $table->nullableTimestamps();
                });
            }
        }


    }
};


