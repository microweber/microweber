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



        $address = $this->params['data-address'] ?? get_module_option('data-address', $this->params['id']);
        $mapType = get_module_option('data-map-type', $this->params['id']);
        $zoom = get_module_option('data-zoom', $this->params['id']);
        $width = get_module_option('data-width', $this->params['id']);
        $height = get_module_option('data-height', $this->params['id']);

        $viewData['address'] = $address;

        $country = $this->params['data-country'] ?? get_module_option('data-country', $this->params['id']);
        $city = $this->params['data-city'] ?? get_module_option('data-city', $this->params['id']);
        $street = $this->params['data-street'] ?? get_module_option('data-street', $this->params['id']);
        $zip = $this->params['data-zip'] ?? get_module_option('data-zip', $this->params['id']);

        $viewData['mapType'] = $mapType;
        $viewData['zoom'] = $zoom;
        $viewData['width'] = $width;
        $viewData['height'] = $height;
        $id = 'mw-map-'.$this->params['id'];
        $viewData['id'] = $id;

        if (!$address) {
            $viewData['address'] = $country . ', ' . $city . ', ' . $street . ', ' . $zip;
        }


        if (!$address) {
            $viewData['address'] = 'One loop street, Cupertino, CA';
        }
        if (!$zoom) {
            $viewData['zoom'] = 13;
        }


        return view('modules.googlemaps::templates.default', $viewData);
    }
}
