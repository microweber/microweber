<?php

namespace Modules\Updater\Http\Controllers;

use MicroweberPackages\Admin\Http\Controllers\AdminController;
use Modules\Updater\Services\UpdaterHelper;
use Illuminate\Http\Request;

class UpdaterController extends AdminController
{
    /**
     * Start the update process
     */
    public function updateNow(Request $request)
    {
        $version = $request->get('version', 'master');

        $updaterHelper = app(UpdaterHelper::class);

        // Check if we can update
        $updateMessages = $updaterHelper->getCanUpdateMessages();
        if (!empty($updateMessages)) {
            return redirect()->back()->with('error', 'Cannot update: ' . implode(', ', $updateMessages));
        }

        if(is_dir( public_path() . '/standalone-updater')){
            rmdir_recursive(public_path() . '/standalone-updater',false);
        }

        $updateCacheFolderName = 'standalone-updater/' .  rand(222, 444) . time() . '/';
        $updateCacheDir = public_path() . '/' .$updateCacheFolderName;
        $updateCacheDirRedicrect = site_url() . $updateCacheFolderName;


        // Create standalone updater
        $updaterHelper->copyStandaloneUpdater($updateCacheDir);

        // Redirect to the standalone updater
        return redirect($updateCacheDirRedicrect.'index.php?branch=' . $version . '&installVersion=' . $version);
    }
}
