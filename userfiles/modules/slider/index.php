<?php
$settings = get_module_option('settings', $params['id']);
$module_template = get_module_option('data-template', $params['id']);

if (!$module_template and isset($params['template'])) {
    $module_template = $params['template'];
}

$module_template = str_replace('..', '', $module_template);
if (!$module_template OR $module_template == '') {
    $module_template = 'bxslider-skin-1';
}
$defaults = array(
    'images' => '',
    'primaryText' => lang('A Slider', 'modules/slider'),
    'secondaryText' => 'Your text here.',
    'seemoreText' => 'See more',
    'url' => '',
    'urlText' => '',
    'skin' => 'bxslider-skin-1'
);
$data = array();

$json = json_decode($settings, true);
if (isset($json) == false or count($json) == 0) {
    $json = array(0 => $defaults);
}


$mrand = 'mw-slider-' . uniqid();

foreach ($json as $slide) {
    if (isset($slide['images'])) {
        $slide['images'] = is_array($slide['images']) ? $slide['images'] : explode(',', $slide['images']);
    } else {
        $slide['images'] = array();
    }

    if (!isset($slide['seemoreText'])) {
        $slide['seemoreText'] = 'See more';
    }


    if (!isset($slide['skin']) or $slide['skin'] == '') {
        $slide['skin'] = 'default';
    }


    $module_template_clean = str_replace('.php', '', $module_template);
    $default_skins_path = $config['path_to_module'] . 'templates/' . $module_template_clean . '/skins';
    $template_skins_path = template_dir() . 'modules/slider/templates/' . $module_template_clean . '/skins';

    $skin_file_from_template = template_dir() . 'modules/slider/templates/' . $module_template_clean . '/skins/' . $slide['skin'] . '.php';
    $skin_file_from_template = normalize_path($skin_file_from_template, false);
    $skin_default_from_template = template_dir() . 'modules/slider/templates/' . $module_template_clean . '/skins/default.php';
    $skin_default_from_template = normalize_path($skin_default_from_template, false);
    $skin_default = $config['path_to_module'] . 'templates/' . $module_template_clean . '/skins/default.php';
    $skin_default = normalize_path($skin_default, false);

    if (is_file($skin_file_from_template)) {
        $skin_file = $skin_file_from_template;
    } elseif (is_file($skin_default_from_template)) {
        $skin_file = $skin_default_from_template;
    } else {
        $skin_file = $skin_default;
    }

    if (!isset($slide['skin_file'])) {
        $slide['skin_file'] = $skin_file;
    }

    $data[] = $slide;
}


if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}
if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'bxslider-skin-1');
}

include('options.php');

if (is_file($template_file)) {
    include($template_file);
}
?>

<?php if ($engine == 'slickslider'): ?>
<?php endif; ?>

<?php if ($engine == 'bxslider'): ?>
    <script>mw.lib.require('bxslider');</script>

    <script>
        $(document).ready(function () {
            var bxPager = '<?php print $pager ? $pager : 'undefined'; ?>';
            if ($('.bxSlider', '#<?php print $params['id'] ?>').children().length > 1) {
                bxPager = 'false';
            }
            $('.bxSlider', '#<?php print $params['id'] ?>').bxSlider({
                preventDefaultSwipeY: false,
                preventDefaultSwipeX: false,

                pager: bxPager,
                controls: <?php print $controls ? $controls : 'undefined'; ?>,
                infiniteLoop: <?php print $loop ? $loop : 'undefined'; ?>,
                adaptiveHeight: <?php print $adaptiveHeight ? $adaptiveHeight : 'undefined'; ?>,
                auto: <?php print $autoplay ? $autoplay : 'false'; ?>,
                autoHover: '<?php print $pauseOnHover ? $pauseOnHover : 'undefined'; ?>',
                pause: '<?php print $autoplaySpeed ? $autoplaySpeed : '3000'; ?>',
                hideControlOnEnd:  <?php print $hideControlOnEnd ? $hideControlOnEnd : 'undefined'; ?>,
                mode: '<?php print $mode ? $mode : 'undefined'; ?>',
                prevText: '<?php print $prevText ? $prevText : ''; ?>',
                nextText: '<?php print $nextText ? $nextText : ''; ?>',
                // touchEnabled: <?php print $touchEnabled ? $touchEnabled : 'true'; ?>,
                touchEnabled: false,
                captions: true,
                onSliderLoad: function () {
                    mw.trigger("mw.bxslider.onSliderLoad");
                },
                <?php if(isset($pagerCustom) AND $pagerCustom != ''): ?>
                pagerCustom: '#<?php print $params['id'] ?> .<?php print $pagerCustom; ?>'
                <?php endif; ?>
            });
        });
    </script>
<?php endif; ?>


<?php if ($engine == 'slickslider'): ?>
    <script>mw.lib.require('slick');</script>
    <script>
        $(document).ready(function () {
            var config = {
                rtl:$('html').attr("dir") == "rtl",
                dots: <?php print $pager; ?>,
                arrows: <?php print $controls; ?>,
                infinite: <?php print $loop; ?>,
                adaptiveHeight: <?php print $adaptiveHeight; ?>,
                autoplaySpeed: <?php print $autoplaySpeed; ?>,
                //speed: '<?php print $speed; ?>',
                speed: 500,

                pauseOnHover: <?php print $pauseOnHover; ?>,
                autoplay: <?php print $autoplay; ?>,
                slidesPerRow: <?php print $slidesPerRow; ?>,
                slidesToShow: <?php print $slides_xl; ?>,
                slidesToScroll: <?php print $slides_xl; ?>,
                centerMode: <?php print $centerMode; ?>,
                centerPadding: '<?php print $centerPadding; ?>',
                draggable: <?php print $draggable; ?>,
                fade: <?php print $fade; ?>,
                focusOnSelect: <?php print $focusOnSelect; ?>,
                responsive: [
                    {
                        breakpoint: 1199,
                        settings: {
                            slidesToShow: <?php print $slides_lg; ?>,
                            slidesToScroll: <?php print $slides_lg; ?>
                        }
                    }, {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: <?php print $slides_md; ?>,
                            slidesToScroll: <?php print $slides_md; ?>
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: <?php print $slides_sm; ?>,
                            slidesToScroll: <?php print $slides_sm; ?>
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: <?php print $slides_xs; ?>,
                            slidesToScroll: <?php print $slides_xs; ?>
                        }
                    }
                ]
            };
            var stime = 0;
            mw.onLive(function () {
                stime = 500;
            });

            if ($('.slickSlider', '#<?php print $params['id'] ?>').find('.slick-active').length > 0) {
                $('.slickSlider', '#<?php print $params['id'] ?>').slick('unslick');
            }

            setTimeout(function () {
                $('.slickSlider', '#<?php print $params['id'] ?>').slick(config);
            }, stime)
        });
    </script>
<?php endif; ?>
<?php print lnotif("Click here to manage slides"); ?>
