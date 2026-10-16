<?php

declare(strict_types=1);

namespace MicroweberPackages\App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use MicroweberPackages\App\Http\Requests\MwApplyUpdatesRequest;
use MicroweberPackages\App\Http\Requests\MwComposerInstallPackageRequest;
use MicroweberPackages\App\Http\Requests\MwInstallMarketItemRequest;
use MicroweberPackages\App\Http\Requests\MwSendAnonymousServerDataRequest;

/**
 * Update / marketplace endpoints formerly registered via api_expose* in other.php.
 */
class UpdateApiController extends Controller
{
    /**
     * ANY api/mw_install_market_item (admin)
     */
    public function installMarketItem(MwInstallMarketItemRequest $request): mixed
    {
        return mw_install_market_item($request->all());
    }

    /**
     * ANY api/mw_apply_updates (admin)
     */
    public function applyUpdates(MwApplyUpdatesRequest $request): mixed
    {
        return mw_apply_updates($request->all());
    }

    /**
     * ANY api/mw_send_anonymous_server_data (admin)
     */
    public function sendAnonymousServerData(MwSendAnonymousServerDataRequest $request): mixed
    {
        return mw_send_anonymous_server_data($request->all());
    }

    /**
     * ANY api/mw_composer_install_package_by_name
     * Public path for install wizard; requires access when already installed.
     */
    public function composerInstallPackageByName(MwComposerInstallPackageRequest $request): mixed
    {
        return mw_composer_install_package_by_name($request->all());
    }
}
