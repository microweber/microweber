<?php

    $settings = get_option('settings', $params['id']);

    $defaults = array(
        'images' => '',
        'primaryText' => 'A Magic Slider',
        'secondaryText' => 'Nunc blandit malesuada.',
        'url' => '',
        'urlText' => '',
        'skin' =>'default'
    );
    $settings = get_option('settings', $params['id']);

    $json = json_decode($settings, true);

    if(isset($json) == false or count($json) == 0){
      $json = array(0 => $defaults);
    }
    $mrand = 'slider-' . uniqid();
?>
<script>
    mw.moduleCSS('<?php print $config['url_to_module']; ?>style.css');
</script>
<script>mw.moduleJS('<?php print $config['url_to_module']; ?>magic.slider.js');</script>

<div class="magic-slider" id="<?php print $mrand; ?>">
    <div class="magic-slider-slides">
      <?php
        foreach($json as $slide){

            if(!isset($slide['skin']) or $slide['skin'] == ''){
              $slide['skin'] = 'default';
            }
            if(isset($slide['images'])){
              $slide['images'] = explode(',', $slide['images']);
            }
            else{
              $slide['images'] = array();
            }
            include $config['path_to_module'] . 'skins/' . $slide['skin']. '.php';
        }
      ?>
    </div>
    <?php if(count($json) > 1){ ?>
      <span class="magic-slider-next"></span>
      <span class="magic-slider-previous"></span>
    <?php } ?>
</div>

<script>

  $(document).ready(function(){
    $(document.getElementById('<?php print $mrand; ?>')).magicSlider({
    <?php if(count($json) > 1){ ?>    autoRotate:true     <?php } ?>
    });
  });

</script>
