<?php
$logotype = get_option('logotype', $params['id']);
$logoimage = get_option('logoimage', $params['id']);
$text = get_option('text', $params['id']);
$font_family = get_option('font_family', $params['id']);
$font_size = get_option('font_size', $params['id']);
if ($font_size == false) {
    $font_size = 30;
}



$default = '';
if (isset($params['data-defaultlogo'])) {
    $default = $params['data-defaultlogo'];
}
if ($logoimage == false or $logoimage == '') {
    if(isset($params['image'])){
       $logoimage = $params['image'];
    }
    else{
        $logoimage = $default;
    }


}


$font_family_safe = str_replace("+", " ", $font_family);
if($font_family_safe == ''){
  $font_family_safe = 'inherit';
}




?>
<?php  if($font_family_safe != 'inherit'){ ?>

    <script>mw.require('//fonts.googleapis.com/css?family=<?php print $font_family; ?>&filetype=.css', true);</script>

<?php } ?>

<?php



    if(isset($params['template'])){
       $template = $params['template'];
    }
    else{
        $template = 'default';
    }



    include  'templates/' . $template . '.php';

 ?>







