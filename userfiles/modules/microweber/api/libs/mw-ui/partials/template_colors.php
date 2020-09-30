<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/css/bootstrap-colorpicker.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/js/bootstrap-colorpicker.min.js"></script>

<style>
    <?php if (!isset($_GET['full_width'])): ?>
    .color-scheme-options {
        position: fixed;
        right: 0;
        bottom: 0;
        width: 300px;
        border: 1px solid silver;
        height: 40vh;
        overflow-y: scroll;
    }
    <?php endif; ?>

    .color-scheme-options {
        background: #fff;
        padding: 10px;
    }

    .color-scheme-options h6,
    .color-scheme-options label {
        color: #000;
    }

    .color-scheme-options input {
        background: #fff !important;
    }

    .color-scheme-options .input-group-text {
        padding: 0 10px;
    }

    .color-scheme-options .input-group {
        margin-bottom: 20px;

    .color-scheme-options .btn-danger {
        color: #fff !important;
        background-color: #ff4f52 !important;
        border-color: #ff4f52 !important;
    }
</style>
<div class="color-scheme-options d-none-x">
    <?php
    $cookies = $_COOKIE;
    $vars = [
        'white' => '#fff',
        'black' => '#000',
        'silver' => '#bcbfc2',

        'primary' => '#4592ff',
        'secondary' => '#eeefef',
        'success' => '#3dc47e',
        'info' => '#e1f1fd',
        'warning' => '#ffc107',
        'danger' => '#ff4f52',
        'light' => '#f8f9fa',
        'dark' => '#2b2b2b',

        'body-bg' => '#fff',
        'body-color' => '#212529'
    ];

    if ($cookies) {
        foreach ($cookies as $key => $cookie) {
            if ($key != 'color_scheme') {
                if ($key and strstr($key, '__var_')) {
                    $k = str_replace('__var_', '', $key);
                    $vars[$k] = $cookie;
                }
            }
        }
    }
    ?>
    <?php
    $site_url_uri = explode('userfiles/', $_SERVER['REQUEST_URI']);
    $site_url = "http://" . $_SERVER['SERVER_NAME'] . $site_url_uri[0];
    ?>
    <script>
        $(document).ready(function () {
            $(".js-color").on("change", function () {
                $val = ($(this).val());
                $key = ($(this).attr('name'));
                $.cookie('__var_' + $key, $val);
                reload_main_css();
                $(this).next().find('i').css({'background': $val})
            });

            //$('.js-color').colorpicker();
        });

        function reload_main_css() {
            $('#main-css-style').attr("href", $('#main-css-style').attr("href") + "?id=" + new Date().getMilliseconds());

            var current = window.parent.$('head').find('link[href*="css/main.php"]');
            if (current.length == 0) {
                var append = '<link rel="stylesheet" id="main-css-style" href="<?php echo $site_url; ?>userfiles/modules/microweber/api/libs/mw-ui/grunt/plugins/ui/css/main.php">';
                window.parent.$('head').append(append);
            }

            window.parent.$('#main-css-style').attr("href", '<?php echo $site_url; ?>userfiles/modules/microweber/api/libs/mw-ui/grunt/plugins/ui/css/main.php' + "?id=" + new Date().getMilliseconds());
        }

        function reset_main_css() {
            var c = document.cookie.split("; ");
            for (i in c) {
                document.cookie = /^[^=]+/.exec(c[i])[0] + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
            }
            reload_main_css();
        }
    </script>

    <div class="form-group">
        <label for="exampleSelect1color">Color Scheme</label>
        <select name="color_scheme" class="form-control js-color" id="exampleSelect1color">
            <option value="">none</option>
            <option value="litera">litera</option>
            <option value="simplex">simplex</option>
            <option value="materia">materia</option>
            <option value="sandstone">sandstone</option>
            <option value="slate">slate</option>
            <option value="superhero">superhero</option>
            <option value="cosmo">cosmo</option>
            <option value="flatly">flatly</option>
            <option value="lumen">lumen</option>
            <option value="spacelab">spacelab</option>
            <option value="minty">minty</option>
            <option value="lux">lux</option>
            <option value="sketchy">sketchy</option>
            <option value="pulse">pulse</option>
            <option value="solar">solar</option>
            <option value="cyborg">cyborg</option>
            <option value="darkly">darkly</option>
        </select>
    </div>

    <?php foreach ($vars as $k => $v) : ?>
        <?php if ($k != 'color_scheme'): ?>
            <script>
                $(document).ready(function () {
                    $('.color-picker-<?php echo $k; ?>').colorpicker();
                });
            </script>

            <h6>$<?php print $k ?></h6>

            <div class="input-group">
                <input type="text" class="form-control js-color color-picker-<?php echo $k; ?>" name="<?php print $k ?>" value="<?php print $v ?>"/>
                <span class="input-group-append">
                <span class="input-group-text colorpicker-input-addon"><i style="background: <?php print $v ?>;"></i></span>
            </span>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>


    <br>

    <div class="d-flex justify-content-between">
        <button onclick="reset_main_css()" class="btn btn-sm btn-danger">Reset</button>
        <?php if (!is_file(__DIR__ . '/../grunt/plugins/ui/css/main_compiled.css')): ?>
            <a href="?generate_styles" class="btn btn-sm btn-danger">Generate style file</a>
        <?php endif; ?>
    </div>

    <?php
    if (isset($_GET['generate_styles'])) {
        if (!is_file(__DIR__ . '/../grunt/plugins/ui/css/main_compiled.css')) {
            @file_put_contents(__DIR__ . '/../grunt/plugins/ui/css/main_compiled.css', '');
        }
    }
    ?>
</div>