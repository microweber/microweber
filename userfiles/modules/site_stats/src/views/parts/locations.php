<?php
if (!$data) {
    return;
}
?>

<?php foreach ($data as $item): ?>
    <div class="item location">
        <div class="location-progressbar" style="width: <?php print $item['sessions_percent']; ?>%;"></div>
        <div class="row d-flex">
            <div class="col-6">
                <div class="flag"><span class="flag-icon flag-icon-<?php print strtolower($item['country_code']); ?>"></span></div>
                <div class="title"><?php print $item['country_name']; ?></div>
            </div>

            <div class="col-6">
                <div class="cnt"><?php print $item['sessions_count']; ?></div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
