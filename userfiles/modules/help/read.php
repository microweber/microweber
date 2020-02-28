<?php

$for_module = $_POST['for_module'];
$readmeMd = module_dir($for_module). 'HOWTO.md';
if (!is_file($readmeMd)) {
    $readmeMd = module_dir($for_module) . 'README.md';
}

$readmeMd = file_get_contents($readmeMd);

$Parsedown = new Parsedown();
$text = $Parsedown->text($readmeMd);
?>

<style>
    .markdown .h1, h1 {
        font-size: 22px;
    }
    .markdown ul {
        list-style: inside;
        padding-left:10px;
    }
</style>

<div class="markdown">
<?php echo $text; ?>
</div>
