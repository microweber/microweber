<?php

namespace Modules\GoogleMaps\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\GoogleMaps\Filament\GoogleMapsModuleSettings;

class GoogleMapsModule extends BaseModule
{
    public static string $name = 'Google Maps Module';
    public static string $module = 'google_maps';
    public static string $icon = 'heroicon-o-map';
    public static string $categories = 'maps, location';
    public static int $position = 1;
    public static string $settingsComponent = GoogleMapsModuleSettings::class;
    public static string $templatesNamespace = 'modules.google_maps::templates';

    public function render()
    {
        $viewData = $this->getViewData();

        $address = $this->params['data-address'] ?? '';
        $mapType = get_module_option('data-map-type', $this->params['id']) ?? $this->params['data-map-type'] ?? 'roadmap';
        $zoom = get_module_option('data-zoom', $this->params['id']) ?? $this->params['data-zoom'] ?? $this->params['zoom'] ?? 17;
        $width = get_module_option('data-width', $this->params['id'] ?? $this->params['data-width'] ?? $this->params['width'] ?? '100%');
        $height = get_module_option('data-height', $this->params['id']) ?? $this->params['data-height'] ?? $this->params['height'] ?? '400';

        $addressPartsFromParams = [];
        $addressPartsFromParams['country'] = $this->params['data-country'] ?? '';
        $addressPartsFromParams['city'] = $this->params['data-city'] ?? '';
        $addressPartsFromParams['street'] = $this->params['data-street'] ?? '';
        $addressPartsFromParams['zip'] = $this->params['data-zip'] ?? '';


        $addressPartsFromOptions = [];

        $country = get_module_option('data-country', $this->params['id']) ?? '';
        $city = get_module_option('data-city', $this->params['id']) ?? '';
        $street = get_module_option('data-street', $this->params['id']) ?? '';
        $zip = get_module_option('data-zip', $this->params['id']) ?? '';

        $addressPartsFromOptions['country'] = $country;
        $addressPartsFromOptions['city'] = $city;
        $addressPartsFromOptions['street'] = $street;
        $addressPartsFromOptions['zip'] = $zip;

        //has values in $addressPartsFromOptions
        if($addressPartsFromParams['country'] || $addressPartsFromParams['city'] || $addressPartsFromParams['street'] || $addressPartsFromParams['zip']){
            $address = $addressPartsFromParams['country'] . ', ' . $addressPartsFromParams['city'] . ', ' . $addressPartsFromParams['street'] . ', ' . $addressPartsFromParams['zip'];
            $address = str_replace(',,', ',', $address);
            $address = trim($address, ',');
        }

        if ($addressPartsFromOptions['country'] || $addressPartsFromOptions['city'] || $addressPartsFromOptions['street'] || $addressPartsFromOptions['zip']) {
            $address = $addressPartsFromOptions['country'] . ', ' . $addressPartsFromOptions['city'] . ', ' . $addressPartsFromOptions['street'] . ', ' . $addressPartsFromOptions['zip'];
            $address = str_replace(',,', ',', $address);
            $address = trim($address, ',');
        }

        if(!$address){
            $address = '1 Infinite Loop in Cupertino, California, United States.';
        }
        $viewData['mapType'] = $mapType;
        $viewData['zoom'] = $zoom;
        $viewData['width'] = $width;
        $viewData['height'] = $height;
        $id = 'mw-map-' . $this->params['id'];
        $viewData['id'] = $id;
        $viewData['address'] = $address;




        if (!$zoom) {
            $viewData['zoom'] = 18;
        }

        $viewName = $this->getViewName($viewData['template'] ?? 'default');
        return view($viewName, $viewData);
     }
}
