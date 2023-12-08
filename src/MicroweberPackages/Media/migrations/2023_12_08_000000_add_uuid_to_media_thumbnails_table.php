<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUuidToMediaThumbnailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('media_thumbnails')) {
            if (!Schema::hasColumn('media_thumbnails', 'uuid')) {
                Schema::table('media_thumbnails', function (Blueprint $table) {
                    $table->uuid('uuid')->nullable();
                });

                // assign uuid to existing records
                $mediaThumbnails = \MicroweberPackages\Media\Models\MediaThumbnail::whereNull('uuid')->get();
                if ($mediaThumbnails) {
                    foreach ($mediaThumbnails as $mediaThumbnail) {

                        $mediaThumbnail->uuid = $mediaThumbnail->newUniqueId();
                        $mediaThumbnail->save();
                    }

                }
            }
        }
    }

}
