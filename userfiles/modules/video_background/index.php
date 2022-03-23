<?php
    $url = get_option('url', $params['id']);
    if(!isset($url)) {
        if (isset($params['url'])) {
            $url = $params['url'];
        }
    }
?>
<style>
    #<?php print $params['id']; ?>,
    #video-<?php print $params['id']; ?>{
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        left: 0;
        top: 0;
        z-index: 0;
        pointer-events: none;
    }
</style>
<video id="video-<?php print $params['id']; ?>" playsinline autoplay muted loop src="<?php print $url ?>"></video>
