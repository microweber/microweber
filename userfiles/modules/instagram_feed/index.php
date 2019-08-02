<?php
$template = get_option('data-template', $params['id']);

$username = get_option('username', $params['id']);
if (!isset($username) or $username == false or $username == '') {
    $username = 'bummer.frenchie.wild';
}

$number_of_items = get_option('number_of_items', $params['id']);
if (!isset($number_of_items) or $number_of_items == false or $number_of_items == '') {
    $number_of_items = 3;
}

if ($template == false and isset($params['template'])) {
    $template = $params['template'];
}
if ($template != false) {
    $template_file = module_templates($config['module'], $template);
} else {
    $template_file = module_templates($config['module'], 'default');

}

$html = mw()->http->url('https://instagram.com/' . $username . '/')->set_cache(1800)->get();
preg_match('/_sharedData = ({.*);<\/script>/', $html, $matches);
$profile_data = json_decode($matches[1])->entry_data->ProfilePage[0]->graphql->user;
$profile_data = json_encode($profile_data);
$profile_data = json_decode($profile_data, true);

$photos = [];

if (isset($profile_data['edge_owner_to_timeline_media']) and isset($profile_data['edge_owner_to_timeline_media']['edges'])) {
    $count = 0;
    foreach ($profile_data['edge_owner_to_timeline_media']['edges'] as $key => $photo) {
        if ($photo['node']['is_video'] == false) {
            $count++;

            if ($count <= $number_of_items) {
                $photos[$count] = $photo['node'];
                $photos[$count]['module_original'] = $photo['node']['display_url'];
                $photos[$count]['module_thumbnail'] = $photo['node']['thumbnail_src'];

                if (isset($photo['node']['edge_media_to_caption']['edges'][0]['node']['text'])) {
                    $photos[$count]['module_caption'] = $photo['node']['edge_media_to_caption']['edges'][0]['node']['text'];
                } else {
                    $photos[$count]['module_caption'] = '';
                }
            }
        }
    }
}


if ($template_file != false and is_file($template_file)) {
    include($template_file);
}
if (!$photos and is_admin()) {
    print lnotif("Click here to edit Instagram feed");
}
