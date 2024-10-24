<?php

namespace Modules\GoogleMaps\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\GoogleMaps\Filament\GoogleMapsModuleSettings;

class GoogleMapsModule extends BaseModule
{
    public static string $name = 'Google Maps Module';
    public static string $icon = 'heroicon-o-map';
    public static string $categories = 'maps, location';
    public static int $position = 1;
    public static string $settingsComponent = GoogleMapsModuleSettings::class;

    public function render()
    {
        $viewData = $this->getViewData();

        $id = "mwgooglemaps-" . $this->params['id'];

        $address = $this->params['data-address'] ?? get_module_option('data-address', $this->params['id']);
        $mapType = get_module_option('data-map-type', $this->params['id']);
        $zoom = get_module_option('data-zoom', $this->params['id']);
        $width = get_module_option('data-width', $this->params['id']);
        $height = get_module_option('data-height', $this->params['id']);

        $viewData['address'] = $address;
        $viewData['mapType'] = $mapType;
        $viewData['zoom'] = $zoom;
        $viewData['width'] = $width;
        $viewData['height'] = $height;
        $viewData['id'] = $id;

        return view('modules.googlemaps::templates.default', $viewData);
    }
}
