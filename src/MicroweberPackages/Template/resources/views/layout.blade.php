
<?php

$template_dir = template_dir();

if ($template_dir and is_dir($template_dir)) {
    $template_dir = normalize_path($template_dir, false);
    $template_dir = str_replace('\\', '/', $template_dir);


}

$header_file = $template_dir . '/header.php';
$footer_file = $template_dir . '/footer.php';

?>

<?php
if (file_exists($header_file)) {
    include($header_file);
}
?>

@hasSection('content')
    @yield('content')
@endif


<?php
if (file_exists($footer_file)) {
    include($footer_file);
}
?>
