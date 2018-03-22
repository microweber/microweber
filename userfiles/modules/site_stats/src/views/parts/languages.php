<?php
if (!$data) {
    return;
}
?>

<?php foreach ($data as $item): ?>
    <div class="item location">
        <div class="location-progressbar" style="width: <?php print $item['sessions_percent']; ?>%;"></div>
        <div class="mw-ui-row">
            <div class="mw-ui-col">
                <div class="title"><?php print $item['language']; ?></div>
            </div>

            <div class=" mw-ui-col" style="width:30px;">
                <div class="cnt"><?php print $item['sessions_count']; ?></div>
            </div>
        </div>
    </div>
<?php endforeach; ?>