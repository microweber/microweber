<div id="settings-holder">
    <div class="col-xs-12">
        <h5 style="font-weight: bold;">Website Settings</h5>
    </div>

    <script>mw.lib.require('bootstrap3ns');</script>

    <style>

        #color-scheme {
            display: none;
        }

        .theme-color-selector button {
            border: 1px solid transparent;
            width: 30px;
            height: 30px;
            background: #425cbb;
            margin: 3px;
            outline: none !important;
        }

        .theme-color-selector button.active {
            border: 1px solid #0a0a0a;
        }

        .theme-color-selector button[data-color="blue"] {
            background: #428fbb;
        }

        .theme-color-selector button[data-color="brightgreen"] {
            background: #42bb59;
        }

        .theme-color-selector button[data-color="darkorange"] {
            background: #bb8c42;
        }

        .theme-color-selector button[data-color="darkred"] {
            background: #bb5042;
        }

        .theme-color-selector button[data-color="deepred"] {
            background: #bb4242;
        }

        .theme-color-selector button[data-color="green"] {
            background: #54c39d;
        }

        .theme-color-selector button[data-color="mediumblue"] {
            background: #54bcc3;
        }

        .theme-color-selector button[data-color="olivegreen"] {
            background: #92bb42;
        }

        .theme-color-selector button[data-color="pink"] {
            background: #bb426d;
        }

        .theme-color-selector button[data-color="purple"] {
            background: #bc54c3;
        }

        .theme-color-selector button[data-color="orounda-blue"] {
            background: #0086ed;
        }
    </style>

    <script>
        $(document).ready(function () {
            $('#color-scheme').on('change', function () {
                var color = $(this).val();

                if (color == '') {
                    color = '<?php print template_url(); ?>assets/css/theme.css';
                } else {
                    color = '<?php print template_url(); ?>assets/css/theme-' + color + '.css';
                }

                top.$('#theme-color').attr('href', color);
            });


            /*$('.theme-color-selector button').on('mouseover', function () {
             var color = $(this).data('color');

             if (color == '') {
             color = '<?php print template_url(); ?>assets/css/theme.css';
             } else {
             color = '<?php print template_url(); ?>assets/css/theme-' + color + '.css';
             }
             top.$('#theme-color').attr('href', color);
             });*/

            $('.theme-color-selector button').on('click', function () {
                var choosedColor = $(this).data('color');
                console.log($('#color-scheme option[data-color="' + choosedColor + ']"'));

                $('#color-scheme option').prop('selected', false);
                $('#color-scheme option[value="' + choosedColor + '"]').prop('selected', true);
                $('#color-scheme').change();

                $('.theme-color-selector button').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>

    <div class="bootstrap3ns">
        <?php

        $shopping_cart = get_option('shopping-cart', 'mw-template-dream');
        if ($shopping_cart == '') {
            $shopping_cart = 'false';
        } else {
            $shopping_cart = 'true';
        }

        $search_field = get_option('search-field', 'mw-template-dream');
        if ($search_field == '') {
            $search_field = 'false';
        } else {
            $search_field = 'true';
        }

        $preloader = get_option('preloader', 'mw-template-dream');
        if ($preloader == '') {
            $preloader = 'false';
        } else {
            $preloader = 'true';
        }

        $shop1_header_style = get_option('shop1-header-style', 'mw-template-dream');
        if ($shop1_header_style == '') {
            $shop1_header_style = 'clean';
        }

        $shop2_header_style = get_option('shop2-header-style', 'mw-template-dream');
        if ($shop2_header_style == '') {
            $shop2_header_style = 'background';
        }

        $color_scheme = get_option('color-scheme', 'mw-template-dream');
        if ($color_scheme == '') {
            $color_scheme = '';
        }

        $footer = get_option('footer', 'mw-template-dream');
        if ($footer == '') {
            $footer = 'false';
        }

        $stop_transparent_nav = get_option('stop_transparent_nav', 'mw-template-dream');
        if ($stop_transparent_nav == '') {
            $stop_transparent_nav = 'false';
        }
        ?>
        <div class="form-group">
            <div class="col-xs-12">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="mw_option_field" id="stop_transparent_nav" name="stop_transparent_nav" data-option-group="mw-template-dream"
                               value="true" <?php if ($stop_transparent_nav == 'true') {
                            echo 'checked';
                        } ?> /> Stop Transparent of Navigation
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="mw_option_field" id="shopping-cart" name="shopping-cart" data-option-group="mw-template-dream" value="true" <?php if ($shopping_cart == 'true') {
                            echo 'checked';
                        } ?> /> Show shopping cart in header
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="mw_option_field" id="search-field" name="search-field" data-option-group="mw-template-dream" value="true" <?php if ($search_field == 'true') {
                            echo 'checked';
                        } ?> /> Show search field in header
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="mw_option_field" id="preloader" name="preloader" data-option-group="mw-template-dream" value="true" <?php if ($preloader == 'true') {
                            echo 'checked';
                        } ?> /> Turn on Page Preloader
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="select" class="col-lg-2 control-label">Shop Inner 1 Header Style</label>
            <div class="col-lg-10">
                <select name="shop1-header-style" id="shop1-header-style" class="mw_option_field form-control" data-option-group="mw-template-dream">
                    <option value="clean" <?php if ($shop1_header_style == '' OR $shop1_header_style == 'clean') {
                        echo 'selected';
                    } ?>>Clean
                    </option>
                    <option value="background"<?php if ($shop1_header_style == 'background') {
                        echo 'selected';
                    } ?>>Poster With Background
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="select" class="col-lg-2 control-label">Shop Inner 2 Header Style</label>
            <div class="col-lg-10">
                <select name="shop2-header-style" id="shop2-header-style" class="mw_option_field form-control" data-option-group="mw-template-dream">
                    <option value="clean" <?php if ($shop2_header_style == 'clean') {
                        echo 'selected';
                    } ?>>Clean
                    </option>
                    <option value="background"<?php if ($shop2_header_style == '' OR $shop2_header_style == 'background') {
                        echo 'selected';
                    } ?>>Poster With Background
                    </option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label for="select" class="col-lg-2 control-label">Color scheme</label>
            <div class="col-lg-10">
                <div class="theme-color-selector">
                    <button data-color="" <?php if ($color_scheme == '') {
                        echo 'class="active"';
                    } ?>></button>
                    <button data-color="blue" <?php if ($color_scheme == 'blue') {
                        echo 'class="active"';
                    } ?>></button>
                    <button data-color="brightgreen" <?php if ($color_scheme == 'brightgreen') {
                        echo 'class="active"';
                    } ?>></button>
                    <button data-color="darkorange" <?php if ($color_scheme == 'darkorange') {
                        echo 'class="active"';
                    } ?>></button>
                    <button data-color="darkred" <?php if ($color_scheme == 'darkred') {
                        echo 'class="active"';
                    } ?>></button>
                    <button data-color="deepred" <?php if ($color_scheme == 'deepred') {
                        echo 'class="active"';
                    } ?>></button>
                    <button data-color="green" <?php if ($color_scheme == 'green') {
                        echo 'class="active"';
                    } ?>></button>
                    <button data-color="mediumblue" <?php if ($color_scheme == 'mediumblue') {
                        echo 'class="active"';
                    } ?>></button>
                    <button data-color="olivegreen" <?php if ($color_scheme == 'olivegreen') {
                        echo 'class="active"';
                    } ?>></button>
                    <button data-color="pink" <?php if ($color_scheme == 'pink') {
                        echo 'class="active"';
                    } ?>></button>
                    <button data-color="purple" <?php if ($color_scheme == 'purple') {
                        echo 'class="active"';
                    } ?>></button>
                    <button data-color="orounda-blue" <?php if ($color_scheme == 'orounda-blue') {
                        echo 'class="active"';
                    } ?>></button>
                </div>

                <select name="color-scheme" id="color-scheme" class="mw_option_field form-control" data-option-group="mw-template-dream">
                    <option value="" <?php if ($color_scheme == '') {
                        echo 'selected';
                    } ?>>Default
                    </option>

                    <option value="blue"<?php if ($color_scheme == 'blue') {
                        echo 'selected';
                    } ?>>Blue
                    </option>
                    <option value="brightgreen"<?php if ($color_scheme == 'brightgreen') {
                        echo 'selected';
                    } ?>>Bright Green
                    </option>
                    <option value="darkorange"<?php if ($color_scheme == 'darkorange') {
                        echo 'selected';
                    } ?>>Dark Orange
                    </option>
                    <option value="darkred"<?php if ($color_scheme == 'darkred') {
                        echo 'selected';
                    } ?>>Dark Red
                    </option>
                    <option value="deepred"<?php if ($color_scheme == 'deepred') {
                        echo 'selected';
                    } ?>>Deep Red
                    </option>
                    <option value="green"<?php if ($color_scheme == 'green') {
                        echo 'selected';
                    } ?>>Green
                    </option>
                    <option value="mediumblue"<?php if ($color_scheme == 'mediumblue') {
                        echo 'selected';
                    } ?>>Medium Blue
                    </option>
                    <option value="olivegreen"<?php if ($color_scheme == 'olivegreen') {
                        echo 'selected';
                    } ?>>Olive Green
                    </option>
                    <option value="pink"<?php if ($color_scheme == 'pink') {
                        echo 'selected';
                    } ?>>Pink
                    </option>
                    <option value="purple"<?php if ($color_scheme == 'purple') {
                        echo 'selected';
                    } ?>>Purple
                    </option>
                    <option value="orounda-blue"<?php if ($color_scheme == 'orounda-blue') {
                        echo 'selected';
                    } ?>>orounda-blue
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="mw_option_field" id="footer" name="footer" data-option-group="mw-template-dream" value="true" <?php if ($footer == 'true') {
                            echo 'checked';
                        } ?> /> The Footer is turned off for website
                    </label>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- /#settings-holder -->