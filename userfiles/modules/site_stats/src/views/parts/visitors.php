<?php
if (!$data) {
    return;
}
?>

<?php foreach ($data as $item): ?>
    <?php
    $os = strtolower($item['browser_os']);
    if ($os == 'ios') {
        $os = 'apple';
    } elseif ($os == 'windows') {
        $os = 'windows';
    } elseif ($os == 'linux') {
        $os = 'linux';
    } else {
        $os = 'question-circle';
    }
    ?>

    <div class="item visitor">
        <div class="top-row">
            <div class="flag"><span class="flag-icon flag-icon-<?php print strtolower($item['country_code']); ?> tip" data-tip="<?php print $item['country_name']; ?>"></span></div>
            <div class="visitor-name"><?php print $item['user_ip']; ?></div>
            <div class="timestamp tip" data-tip="<?php print $item['updated_at']; ?>"><?php print mw()->format->ago($item['updated_at']); ?></div>
        </div>
        <div class="clearfix"></div>

        <div class="current-page-row">
            <div class="current-page">
                <?php
                if (isset($item['title'])) {
                    echo $item['title'];
                } else {
                    echo 'Undefined';
                }
                ?>
                <div class="tip pull-right" data-tip="Browser: <?php print $item['browser_name']; ?>">
                    <i class="fa fa-<?php print strtolower($item['browser_name']); ?>"></i>
                </div>
                <div class="tip pull-right m-r-5" data-tip="OS: <?php print $item['browser_os']; ?>">
                    <i class="fa fa-<?php print $os; ?>"></i>
                </div>
            </div>
        </div>

        <div class="mw-ui-row">
            <div class="mw-ui-col" style="width:30px;">
                <div class="referral">
                    <img src="https://static.gosquared.com/images/livestats/dashboard/icon_ref_direct_16x16.png" class="referral_icon" title="Referred by <?php print $item['referrer_id']; ?>">
                </div>
            </div>

            <div class=" mw-ui-col">
                <ul class="page-dots">
                    <?php foreach ($item['views_data'] as $view): ?>
                        <li class="page-circle" style="background-color:rgba(0,0,0,0.3)"><a href="<?php print $view['url'] ?>" title="<?php print $view['url'] ?>">&nbsp;<a></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endforeach; ?>