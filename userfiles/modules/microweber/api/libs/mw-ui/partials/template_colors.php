<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/css/bootstrap-colorpicker.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.2.0/js/bootstrap-colorpicker.min.js"></script>

<style>
    .color-scheme-options {
        position: fixed;
        right: 0;
        bottom: 0;
        width: 300px;
        border: 1px solid silver;
        padding: 10px;
        height: 40vh;
        overflow-y: scroll;
        background: #fff;
    }

    .color-scheme-options input {
        background: #fff !important;
    }

    .color-scheme-options .input-group-text {
        padding: 0 10px;
    }

    .color-scheme-options .input-group {
        margin-bottom: 20px;
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
            if ($key and strstr($key, '__var_')) {
                $k = str_replace('__var_', '', $key);
                $vars[$k] = $cookie;
            }
        }
    }
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



    <?php foreach ($vars as $k => $v) { ?>
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
    <?php } ?>

    <br>

    <button onclick="reset_main_css()" class="btn btn-sm btn-danger">Reset</button>
</div>