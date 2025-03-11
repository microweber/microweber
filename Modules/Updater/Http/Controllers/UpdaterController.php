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
        $branch = $request->get('branch', 'master');

        $updaterHelper = app(UpdaterHelper::class);

        // Check if we can update
        $updateMessages = $updaterHelper->getCanUpdateMessages();
        if (!empty($updateMessages)) {
            return redirect()->back()->with('error', 'Cannot update: ' . implode(', ', $updateMessages));
        }

        $updateCacheFolderName = 'standalone-updater/' .  rand(222, 444) . time() . '/';
        $updateCacheDir = public_path() . '/' .$updateCacheFolderName;
        $updateCacheDirRedicrect = site_url() . $updateCacheFolderName;


        // Create standalone updater
        $updaterHelper->copyStandaloneUpdater($updateCacheDir);

        // Redirect to the standalone updater
        return redirect($updateCacheDirRedicrect.'standalone-updater.php?branch=' . $branch);
    }
}
