<?php
if (!$data) {
    return;
}
 
?>

<?php foreach ($data as $item): ?>
<div class="item content">
    <div class="content-progressbar" style="width: 80%;"></div>
    <div class="mw-ui-row">
        <div class="mw-ui-col">
            <div class="title">Some page title from the our microweber website</div>
            <div class="slug">/page/url/slug</div>
        </div>

        <div class=" mw-ui-col" style="width:30px;">
            <div class="cnt">3</div>
        </div>
    </div>
</div>
<?php endforeach; ?>