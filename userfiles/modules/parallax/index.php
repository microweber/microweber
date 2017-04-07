<link rel="stylesheet" type="text/css" href="<?php print $config['url_to_module'] ?>css/parallax.css" />

<?php
if (get_option('parallax', $params['id'])) {
    $parallax = get_option('parallax', $params['id']);
} else {
    $parallax = $config['url_to_module'] . 'images/parallax-default.jpg';
}

if (get_option('text', $params['id'])) {
    $infoBox = get_option('text', $params['id']);
} else {
    $infoBox = 'Lorem Ipsum е елементарен примерен текст, използван в печатарската и типографската индустрия. Lorem Ipsum е индустриален стандарт от около 1500 година, когато неизвестен печатар взема няколко печатарски букви и ги разбърква, за да напечата с тях книга с примерни шрифтове.';
}
?>
<style>
    .parallax {
        background-image: url("<?= $parallax; ?>");
    }

</style>

<div class="row module-parallax">
    <div class="col-xs-12 col-md-6">
        <div class="parallax"></div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="info-box">
            <div class="middle-content">
                <div class="row">
                    <div class="col-xs-12 col-md-3">
                        <img src="<?= (get_option('info-image', $params['id']) ? get_option('info-image', $params['id']) : 'http://wallpapercave.com/wp/qFrboOZ.jpg') ?>" alt="<?= get_option('title', $params['id']) ?>" />
                    </div>
                    <div class="col-xs-12 col-md-9">
                        <h1><?= (get_option('title', $params['id']) ? get_option('title', $params['id']) : 'Some title') ?></h1>
                        <p>
                            <?= $infoBox ?>
                        </p>
                        <a href="<?= (get_option('button-url', $params['id']) ? get_option('button-url', $params['id']) : '#') ?>" class="btn btn-default"><?= (get_option('button-text', $params['id']) ? get_option('button-text', $params['id']) : 'Button') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (is_admin()): ?>
    <?php print notif("Click here to edit the Parallax"); ?>
<?php endif; ?>