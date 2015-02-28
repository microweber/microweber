<?php

    /************************************************
                    Template Options
    *************************************************/

    $color_scheme       = get_option('color-scheme', 'mw-template-liteness');
	

	
    $font               = get_option('font', 'mw-template-liteness');
    $bgimage            = get_option('bgimage', 'mw-template-liteness');
    $custom_css_json    = get_option('custom_css_json', 'mw-template-liteness');

    $custom_bg          = get_option('custom_bg', 'mw-template-liteness');
    $custom_bg_position = get_option('custom_bg_position', 'mw-template-liteness');
    $custom_bg_size     = get_option('custom_bg_size', 'mw-template-liteness');

    /* Color scheme */
    if($color_scheme == ''){  $color_scheme = 'default';  }
?>

<link rel="stylesheet" id="colorscss" href="<?php print template_url(); ?>css/colors/<?php print $color_scheme; ?>.css" type="text/css" />


<?php
    /* Custom defined colors */
    if($custom_css_json != '' and $custom_css_json != '{}' and json_decode($custom_css_json) != false){
        $selectors = liteness_template_colors_selectors();
        $json = json_decode($custom_css_json, true);
        $final = '';
        foreach($json as $item => $value){
          $final .= $selectors[$item.'_bg'].'{background-color:'.$value.'}' . "\n";
          $final .= $selectors[$item.'_color'].'{color:'.$value.'}' . "\n";
        }

         /*************************************************************************************
            Add some extra space inside the content boxes when body background is not white.
         *************************************************************************************/


        if(isset($json['third'])){
            $bodybg = strtolower($json['third']);
            if($bodybg != '#ffffff' and $bodybg != '' and $bodybg != 'transparent' and  $color_scheme != 'transparent'){
               $final.= '.box-container {padding: 20px; }.box-container .box-container{  padding: 0; }'. "\n";
            }
            if($bodybg == '#ffffff' and $color_scheme != 'transparent'){
              $final.= '.box-container {padding: 0px; }.box-container .box-container{  padding: 0; }'. "\n";
            }
        }
        print '<style id="customcolorscss" type="text/css">'.$final.'</style>';
     }
     else{
        print '<style id="customcolorscss" type="text/css"></style>';
     }
?>

<?php

   /* Custom background image */

   $custom_bg_css = '';
   if($custom_bg != ''){
     $custom_bg_css .= 'body.bgimagecustom{background-image:url('.$custom_bg.');}.box-container{padding:20px;}';
   }
   if($custom_bg != ''){
      if($custom_bg_position !=''){
         $custom_bg_css .= 'body.bgimagecustom{background-position:'.$custom_bg_position.';}';
      }
      if($custom_bg_size !=''){
         $custom_bg_css .= 'body.bgimagecustom{background-size:'.$custom_bg_size.';}';
      }
   }
   print '<style id="custom_bg" type="text/css">'.$custom_bg_css.'</style>';
?>


<style type="text/css">
   .module-navigation-default li a.active,
    #header .module-navigation-default li a.active,
    .module-navigation-default li a:hover,
    .module-navigation-default li:hover a,
    #header .module-navigation-default li a:hover,
    #header .module-navigation-default li:hover a,
    .module-navigation-default li a:focus,
    #header .module-navigation-default li a:focus{ color: white; } 
</style>