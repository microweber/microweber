<?php

$for_module = $_POST['for_module'];
$readmeMd = module_dir($for_module). 'README.md';
$readmeMd = file_get_contents($readmeMd);

$Parsedown = new Parsedown();
$text = $Parsedown->text($readmeMd);
?>

<?php echo $text; ?>