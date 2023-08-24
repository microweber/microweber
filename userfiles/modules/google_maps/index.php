<?php
if($params['type'] != 'google_maps'){
    return;
}

$address = '';
if (isset($params['data-address'])) {
    $address = $params['data-address'];
} else {
    $address =  get_option('data-address', $params['id']);
}
if($address == false or $address == ''){
    if (isset($params['parent-module-id'])) {

        $address = $params['parent-module-id'];
        $address =  get_option('data-address',$address);
    }
}

// new fields
$map_type =  get_option('data-map-type', $params['id']);
$country =  get_option('data-country', $params['id']);
$city =  get_option('data-city', $params['id']);
$street =  get_option('data-street', $params['id']);
$zip =  get_option('data-zip', $params['id']);
$zoom =  get_option('data-zoom', $params['id']);
$size_x =  get_option('data-size-x', $params['id']);
$size_y =  get_option('data-size-y', $params['id']);

// roadmap, satellite, hybrid, and terrain
$maptype  =  get_option('data-map-type', $params['id']);
if($maptype == false or $maptype == ''){
    $maptype = 'roadmap';
}

$address_parts = [];
if($country){
    $address_parts[] = $country;
}
if ($city) {
    $address_parts[] = $city;
}
if ($street){
    $address_parts[] = $street;
}
if ($zip){
    $address_parts[] = $zip;
}

if(!empty($address_parts)){
    $address = implode(',', $address_parts);
}



$map_style = false;

if(isset($params['map_style'])) {
    $map_style = $params['map_style'];
} else {
    $map_style =  get_option('map_style', $params['id']);

}

$map_style_param = 'silver_style';

if($map_style == 'dark_style') {
    $map_style_param = 'element:geometry%7Ccolor:0x212121&style=element:labels.icon%7Cvisibility:off&style=element:labels.text.fill%7Ccolor:0x757575&style=element:labels.text.stroke%7Ccolor:0x212121&style=feature:administrative%7Celement:geometry%7Ccolor:0x757575&style=feature:administrative.country%7Celement:labels.text.fill%7Ccolor:0x9e9e9e&style=feature:administrative.land_parcel%7Cvisibility:off&style=feature:administrative.locality%7Celement:labels.text.fill%7Ccolor:0xbdbdbd&style=feature:poi%7Celement:labels.text.fill%7Ccolor:0x757575&style=feature:poi.park%7Celement:geometry%7Ccolor:0x181818&style=feature:poi.park%7Celement:labels.text.fill%7Ccolor:0x616161&style=feature:poi.park%7Celement:labels.text.stroke%7Ccolor:0x1b1b1b&style=feature:road%7Celement:geometry.fill%7Ccolor:0x2c2c2c&style=feature:road%7Celement:labels.text.fill%7Ccolor:0x8a8a8a&style=feature:road.arterial%7Celement:geometry%7Ccolor:0x373737&style=feature:road.highway%7Celement:geometry%7Ccolor:0x3c3c3c&style=feature:road.highway.controlled_access%7Celement:geometry%7Ccolor:0x4e4e4e&style=feature:road.local%7Celement:labels.text.fill%7Ccolor:0x616161&style=feature:transit%7Celement:labels.text.fill%7Ccolor:0x757575&style=feature:water%7Celement:geometry%7Ccolor:0x000000&style=feature:water%7Celement:labels.text.fill%7Ccolor:0x3d3d3d&size=480x360';
} elseif ($map_style = 'silver_style') {
    $map_style_param = 'element:geometry%7Ccolor:0xf5f5f5&style=element:labels.icon%7Cvisibility:off&style=element:labels.text.fill%7Ccolor:0x616161&style=element:labels.text.stroke%7Ccolor:0xf5f5f5&style=feature:administrative.land_parcel%7Celement:labels.text.fill%7Ccolor:0xbdbdbd&style=feature:poi%7Celement:geometry%7Ccolor:0xeeeeee&style=feature:poi%7Celement:labels.text.fill%7Ccolor:0x757575&style=feature:poi.park%7Celement:geometry%7Ccolor:0xe5e5e5&style=feature:poi.park%7Celement:labels.text.fill%7Ccolor:0x9e9e9e&style=feature:road%7Celement:geometry%7Ccolor:0xffffff&style=feature:road.arterial%7Celement:labels.text.fill%7Ccolor:0x757575&style=feature:road.highway%7Celement:geometry%7Ccolor:0xdadada&style=feature:road.highway%7Celement:labels.text.fill%7Ccolor:0x616161&style=feature:road.local%7Celement:labels.text.fill%7Ccolor:0x9e9e9e&style=feature:transit.line%7Celement:geometry%7Ccolor:0xe5e5e5&style=feature:transit.station%7Celement:geometry%7Ccolor:0xeeeeee&style=feature:water%7Celement:geometry%7Ccolor:0xc9c9c9&style=feature:water%7Celement:labels.text.fill%7Ccolor:0x9e9e9e&size=480x360';
}

if($address == false or $address == ''){
    $address = "One Infinite Loop, Cupertino, CA 95014, United States";
}

$address = html_entity_decode($address);
$address = strip_tags($address);

$zoom = false;
$pin = false;
if (isset($params['data-zoom'])) {

    $zoom = $params['data-zoom'];

} else {
    $zoom =  get_option('data-zoom', $params['id']);
    $pin =  get_option('data-pin', $params['id']);
}
if($zoom == false or $zoom == ''){
    $zoom = "14";
}

$pinEncoded = false;
if($pin == false or $pin == ''){
    $pinEncoded = urlencode($pin);
}
$map_style_param = '';

?>

<div class="relative mw-prevent-interaction">
    <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
            src="https://maps.google.com/maps?f=q&amp;hl=en&amp;<?php if($pinEncoded): ?>center=<?php echo $pinEncoded;?>&amp;<?php endif; ?>geocode=&amp;time=&amp;date=&amp;ttype=&amp;q=<?php print urlencode($address); ?>&amp;ie=UTF8&amp;om=1&amp;s=AARTsJpG68j7ib5XkPnE95ZRHLMVsa8OWg&amp;spn=0.011588,0.023174&amp;z=<?php print intval($zoom); ?>&amp;style=<?php print $map_style_param ?>$maptype=<?php print $maptype ?>&amp;output=embed"></iframe>


    <div contentEditable="false" class="iframe_fix" <?php if(  mw()->user_manager->session_get('editmode') == true ) { ?>style="display: block;"<?php } ?>></div>
</div>



