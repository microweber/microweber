<?php

namespace MicroweberPackages\LiveEdit\Http\Controllers\Api;

use MicroweberPackages\App\Http\Controllers\Controller;
use MicroweberPackages\LiveEdit\Facades\LiveEditManager;

class LiveEditMenusApi extends Controller
{
    public function getTopRightMenuCardBody()
    {
        return '<div class="card-body" id="user-menu-header">
                        <small>Project</small>
                        <h3>Boris Website</h3>
                        <span class="btn  btn-sm">
                            In Test Period
                        </span>
                        <span class="btn btn-sm btn-primary">
                            Upgrade
                        </span>
                    </div>';
    }

    public function getTopRightMenu()
    {
        $menus = [];

        $topRightMenu = LiveEditManager::getMenu('top_right_menu');
        if (!empty($topRightMenu)) {
            foreach($topRightMenu as $menuItem) {

                $href = '#';
                $hasRoute = $menuItem->getAttribute('route');
                if (!empty($hasRoute)) {
                    $href = route($hasRoute);
                }

                $icon = $menuItem->getAttribute('icon');

                $menus[] = [
                    'title' => $menuItem->getName(),
                    'href'=> $href,
                    'icon_html'=>$icon
                ];
            }
        }

        return $menus;
    }
}
