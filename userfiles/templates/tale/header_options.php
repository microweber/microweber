<?php

    /************************************************
                    Template Options
    *************************************************/

    $tpl_prefix = 'mw-template-tale';


    $color_scheme       = get_option('color-scheme', $tpl_prefix);


	
    $font               = get_option('font', $tpl_prefix);
    $bgimage            = get_option('bgimage', $tpl_prefix);
    $custom_css_json    = get_option('custom_css_json', $tpl_prefix);

    $custom_bg          = get_option('custom_bg', $tpl_prefix);
    $custom_bg_position = get_option('custom_bg_position', $tpl_prefix);
    $custom_bg_size     = get_option('custom_bg_size', $tpl_prefix);
	$kuler_colors       = get_option('kuler_colors', $tpl_prefix);

    /* Color scheme */
    if($color_scheme == ''){  $color_scheme = 'default';  }
?>
<?php if($color_scheme != 'kuler'): ?>
<link rel="stylesheet" id="colorscss" href="<?php print template_url(); ?>css/colors/<?php print $color_scheme; ?>.css" type="text/css" />
<?php else: ?>
<link rel="stylesheet" id="colorscss" href="<?php print template_url(); ?>css/colors/kuler.php?colors=<?php print $kuler_colors; ?>" type="text/css" />
<?php endif ?>

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


