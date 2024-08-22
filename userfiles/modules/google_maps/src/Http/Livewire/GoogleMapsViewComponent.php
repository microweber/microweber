<?php

namespace MicroweberPackages\Modules\GoogleMaps\Http\Livewire;


namespace MicroweberPackages\Modules\GoogleMaps\Http\Livewire;

use Livewire\Component;

class GoogleMapsViewComponent extends Component
{

//    public function getListeners()
//    {
//        return array_merge($this->listeners, [
//            "refreshComponent" => '$refresh'
//        ]);
//    }


    public $params = [];
    public $address;
    public $map_type;
    public $country;
    public $city;
    public $street;
    public $zip;
    public $zoom;
    public $size_x;
    public $size_y;
    public $map_style;
    public $width;
    public $height;
    public $pinEncoded;
    public $map_style_param;
    public $maptype;
    public $mapId;



    public function render()
    {
        $params = $this->params;
        $this->address = $params['data-address'] ?? get_option('data-address', $params['id']) ?? '';
        if (!$this->address && isset($params['parent-module-id'])) {
            $this->address = get_option('data-address', $params['parent-module-id']);
        }

        $this->map_type = get_option('data-map-type', $params['id']);
        $this->country = get_option('data-country', $params['id']);
        $this->city = get_option('data-city', $params['id']);
        $this->street = get_option('data-street', $params['id']);
        $this->zip = get_option('data-zip', $params['id']);
        $this->zoom = get_option('data-zoom', $params['id']);
        $this->size_x = get_option('data-size-x', $params['id']);
        $this->size_y = get_option('data-size-y', $params['id']);
        $this->maptype = get_option('data-map-type', $params['id']) ?? 'roadmap';

        $address_parts = array_filter([$this->country, $this->city, $this->street, $this->zip]);
        if (!empty($address_parts)) {
            $this->address = implode(',', $address_parts);
        }

        $this->map_style = $params['map_style'] ?? get_option('map_style', $params['id']) ?? 'silver_style';
        $this->map_style_param = $this->map_style == 'dark_style' ? 'element:geometry%7Ccolor:0x212121&style=element:labels.icon%7Cvisibility:off&style=element:labels.text.fill%7Ccolor:0x757575&style=element:labels.text.stroke%7Ccolor:0x212121&style=feature:administrative%7Celement:geometry%7Ccolor:0x757575&style=feature:administrative.country%7Celement:labels.text.fill%7Ccolor:0x9e9e9e&style=feature:administrative.land_parcel%7Cvisibility:off&style=feature:administrative.locality%7Celement:labels.text.fill%7Ccolor:0xbdbdbd&style=feature:poi%7Celement:labels.text.fill%7Ccolor:0x757575&style=feature:poi.park%7Celement:geometry%7Ccolor:0x181818&style=feature:poi.park%7Celement:labels.text.fill%7Ccolor:0x616161&style=feature:poi.park%7Celement:labels.text.stroke%7Ccolor:0x1b1b1b&style=feature:road%7Celement:geometry.fill%7Ccolor:0x2c2c2c&style=feature:road%7Celement:labels.text.fill%7Ccolor:0x8a8a8a&style=feature:road.arterial%7Celement:geometry%7Ccolor:0x373737&style=feature:road.highway%7Celement:geometry%7Ccolor:0x3c3c3c&style=feature:road.highway.controlled_access%7Celement:geometry%7Ccolor:0x4e4e4e&style=feature:road.local%7Celement:labels.text.fill%7Ccolor:0x616161&style=feature:transit%7Celement:labels.text.fill%7Ccolor:0x757575&style=feature:water%7Celement:geometry%7Ccolor:0x000000&style=feature:water%7Celement:labels.text.fill%7Ccolor:0x3d3d3d&size=480x360' : 'element:geometry%7Ccolor:0xf5f5f5&style=element:labels.icon%7Cvisibility:off&style=element:labels.text.fill%7Ccolor:0x616161&style=element:labels.text.stroke%7Ccolor:0xf5f5f5&style=feature:administrative.land_parcel%7Celement:labels.text.fill%7Ccolor:0xbdbdbd&style=feature:poi%7Celement:geometry%7Ccolor:0xeeeeee&style=feature:poi%7Celement:labels.text.fill%7Ccolor:0x757575&style=feature:poi.park%7Celement:geometry%7Ccolor:0xe5e5e5&style=feature:poi.park%7Celement:labels.text.fill%7Ccolor:0x9e9e9e&style=feature:road%7Celement:geometry%7Ccolor:0xffffff&style=feature:road.arterial%7Celement:labels.text.fill%7Ccolor:0x757575&style=feature:road.highway%7Celement:geometry%7Ccolor:0xdadada&style=feature:road.highway%7Celement:labels.text.fill%7Ccolor:0x616161&style=feature:road.local%7Celement:labels.text.fill%7Ccolor:0x9e9e9e&style=feature:transit.line%7Celement:geometry%7Ccolor:0xe5e5e5&style=feature:transit.station%7Celement:geometry%7Ccolor:0xeeeeee&style=feature:water%7Celement:geometry%7Ccolor:0xc9c9c9&style=feature:water%7Celement:labels.text.fill%7Ccolor:0x9e9e9e&size=480x360';

        $this->address = html_entity_decode(strip_tags($this->address)) ?: "One Infinite Loop, Cupertino, CA 95014, United States";

        $this->width = get_option('data-width', $params['id']) ?: ($params['data-width'] ?? '100%');
        $this->height = get_option('data-height', $params['id']) ?: ($params['data-height'] ?? '100%');
        $this->zoom = get_option('data-zoom', $params['id']) ?: ($params['data-zoom'] ?? '14');
        $this->pin = get_option('data-pin', $params['id']) ?: ($params['data-pin'] ?? '');

        $this->pinEncoded = urlencode($this->pin);


        $data = [
            'address' => $this->address,
            'map_type' => $this->map_type,
            'country' => $this->country,
            'city' => $this->city,
            'street' => $this->street,
            'zip' => $this->zip,
            'zoom' => $this->zoom,
            'size_x' => $this->size_x,
            'size_y' => $this->size_y,
            'map_style' => $this->map_style,
            'width' => $this->width,
            'height' => $this->height,
            'pinEncoded' => $this->pinEncoded,
            'map_style_param' => $this->map_style_param,
            'maptype' => $this->maptype,
        ];
        $this->mapId = 'google-map-' . md5(json_encode($data));
        $data['mapId'] = $this->mapId;
        return view('microweber-module-google-maps::render-google-maps', $data);
    }
}
