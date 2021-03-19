<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


if (!class_exists('AddIndexToTranslationTables', false)) {


    class AddIndexToTranslationTables extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            // Addd indexes
            try {
                Schema::table('translation_texts', function (Blueprint $table) {
                    $table->index('translation_key_id');
                    $table->index('translation_text');
                    $table->index('translation_locale');
                });
            } catch (Exception $e) {

            }

//            // Add unique
//            try {
//                Schema::table('translation_texts', function (Blueprint $table) {
//                    $table->unique(['translation_key_id'])->change();
//                });
//            } catch (Exception $e) {
//
//            }

            // Add new indexes
            try {
                Schema::table('translation_keys', function (Blueprint $table) {
                    $table->index('translation_namespace');
                    $table->index('translation_key');
                    $table->index('translation_group');
                });
            } catch (Exception $e) {

            }


//            // Drop old uniques
//            try {
//                Schema::table('translation_keys', function (Blueprint $table) {
//                    $table->dropUnique('translation_keys_translation_key_unique');
//                });
//            } catch (Exception $e) {
//
//            }

            /* // Add new unique
             try {

                 // If we support utf8 bin
                 Schema::table('translation_keys', function (Blueprint $table) {
                     $table->text('translation_key')->collation('utf8_bin')->change();
                 });

                 // Then make unique
                 Schema::table('translation_keys', function (Blueprint $table) {
                     $table->unique(['translation_key', 'translation_group', 'translation_namespace'], 'translation_kgn_unique')->change();
                 });

             } catch (Exception $e) {

             }*/
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
}