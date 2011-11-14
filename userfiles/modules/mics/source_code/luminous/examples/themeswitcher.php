<?php 
// Theme switcher example
require_once('helper.inc');

// This isn't an injection or XSS vulnerability. You don't have to worry
// about sanitising this, Luminous won't use it if it's not a valid theme file.
if (!empty($_GET))
  luminous::set('theme', $_GET['theme_switcher']);
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Theme Switcher Example</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type='text/javascript' src='../client/jquery-1.4.2.min.js'></script>
    <?php echo luminous::head_html(); ?>
    
    <script type='text/javascript'>
    
    $(document).ready(function() {
      $('#theme_switcher').change(function(){
        var theme = $(this).val(),
            themes = [<?php 
            // we use PHP to populate the list of known themes. If you think 
            // this is horrible, set up an AJAX interface :)
            echo "'" . wordwrap(join("', '", luminous::themes())) . "'";
            ?>],
            $links = $('link[rel=stylesheet]');
        
        if ($.inArray(theme, themes) == -1)
          return true; 
        // Now we iterate over the known stylesheets and try to determine if
        // it's the index of a Luminous theme. If it is, we swap it out.
        $links.each(function (i, e){
          var href = $(this).attr('href');
          var href_basename = href.replace(/.*\//, '');
          if ($.inArray(href_basename, themes) == -1)
            return;
          $(this).attr('href', href.replace(/\/[^\/]+$/, '/' + theme));
          return true;
        });
        return true;
      });
    });
   
    </script>
  </head>
  
  <body>
  <p>  
  <form action='themeswitcher.php'>
  Change Theme: <select name='theme_switcher' id='theme_switcher'>
  <?php 
  // Build the theme switcher by getting a list of legal themes from Luminous.
  // The luminous_get_html_head() function by default outputs the theme
  // in LUMINOUS_THEME. This can be overridden by the first argument, but as we
  // didn't, that's what we need to check against to determine the default
  // theme for the selector. However, it might not have the .css suffix.
  $default_theme = luminous::setting('theme');
  if (!preg_match('/\.css$/', $default_theme))
    $default_theme .= '.css';
  foreach(luminous::themes() as $theme)  {
    $default = ($theme == $default_theme)? ' selected' : '';
    echo "<option id='{$theme}'{$default}>{$theme}</option>";
  } ?>
  </select>
  <noscript><input type='submit' value='Switch'></noscript>
  </form>
  
  <p>  
  <?php echo luminous::highlight_file('php', __FILE__, $use_cache); ?>
  </body>
</html>
