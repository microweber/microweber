<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class MigrateTranslationsTableToNewTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('translations')) {
            $getTranslations = \DB::table('translations')
                ->groupBy(\DB::raw("MD5(translation_key)"))
                ->groupBy('translation_locale')
                ->get();

            if ($getTranslations !== null) {
                $readyTranslations = [];
                foreach ($getTranslations as $translation) {
                    $readyTranslations[] = [
                        'translation_locale' => $translation->translation_locale,
                        'translation_key' => $translation->translation_key,
                        'translation_text' => $translation->translation_text,
                        'translation_namespace' => $translation->translation_namespace,
                        'translation_group' => $translation->translation_group
                    ];
                }

                $import = new \MicroweberPackages\Translation\TranslationImport();
                $import->import($readyTranslations);
            }
        }
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
