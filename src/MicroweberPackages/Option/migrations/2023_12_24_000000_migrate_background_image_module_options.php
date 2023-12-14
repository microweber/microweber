<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateBackgroundImageModuleOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('options')) {
            $changeKeys = [
                'background_image' => 'data-background-image',
                'background_video' => 'data-background-video',
                'background_color' => 'data-background-color',
            ];
            $getAllOldOptions = \DB::table('options')
                ->where('module', 'background')
                ->whereNotNull('option_value')
                ->whereNotNull('option_key')
                ->whereNotNull('module')
                ->whereNull('is_system')
                ->get();

            if (!empty($getAllOldOptions)) {
                foreach ($getAllOldOptions as $opt) {
                    foreach ($changeKeys as $changeKey => $newKey) {
                        if ($opt && $opt->option_key == $changeKey) {
                            $opt->option_key = $newKey;
                            $opt->save();
                        }
                    }

                }
            }

        }
    }

}
