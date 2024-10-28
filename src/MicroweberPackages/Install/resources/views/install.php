<!DOCTYPE HTML>
<html>
<head>
    <title><?php _e('Installation'); ?></title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Language" Content="en">
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
    <meta name="robots" content="noindex">
    <?php get_favicon_tag(); ?>
    <?php print mw_header_scripts() ?>


    <link type="text/css" rel="stylesheet" media="all"
          href="<?php print asset('vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'); ?>"/>
    <link type="text/css" rel="stylesheet" media="all"
          href="<?php print asset('vendor/microweber-packages/frontend-assets/build/install.css'); ?>"/>

    <?php


    $rand = uniqid();
    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $defhost = strpos($ua, 'Linux') ? 'localhost' : '127.0.0.1';

    if (!isset($pre_configured)) {
        $pre_configured = false;
    }


    ?>

    <script type="text/javascript">
        function prefix_add(el) {
            var val = el.value.replace(/ /g, '').replace(/[^\w\s]/gi, '');
            el.value = val;
            if (val != '') {
                var last = val.slice(-1);
                if (last != '_') {
                    el.value = el.value + '_';
                }
            }
        }

        function showForm(select) {
            var def = false;
            for (var i = 0; i < select.options.length; i++) {
                var v = select.selectedIndex == i;
                def = def || setFormDisplay(select.options[i].value, v);
            }
            var val = $(select).val();
            if (val == 'sqlite') {
                $('#db_name_value').val('');
            }

            setFormDisplay('', !def);
        }

        function setFormDisplay(id, show) {
            var el = $('#db-form' + (id.length ? '-' + id : ''));
            if (el.length) {
                $(el).css('display', (show ? 'block' : 'none'));
                return show;
            }
            return false;
        }

        $(document).ready(function () {
            getTemplateForInstallScreen()
            //  getTemplatesFromPackageManagerBeforeInstall();
        });

        function installMarketplaceItemByPackageName($name) {
            mw.tools.loading('#demo-one', true, 16000);
            mw.tools.loading('#screenshot_preview', true, 16000);
            mw.notification.success('Please wait... Installing... ' + $name, 16000);
            mw.tools.scrollTo('#demo-one');

            if (typeof (mw.marketplace_dialog_jquery_ui) != 'undefined') {
                mw.marketplace_dialog_jquery_ui.dialog('close');
            }

            $.post("<?php print site_url() ?>", {install_package_by_name: $name}, function (data) {
                mw.notification.success('Template is installed... ' + $name, 16000);
                getTemplateForInstallScreen()
                mw.tools.loading('#demo-one', false);
                mw.tools.loading('#screenshot_preview', false);
                mw.tools.scrollTo('#default_template');
            }).always(function () {

            });
        }

        function selectChange() {
            var selectBox = document.getElementById("default_template");

            var selectedValue = selectBox.options[selectBox.selectedIndex].value;

            if (selectedValue == '_get_more') {
                window.location.href = '<?php echo site_url();?>?select_template=1';
            }
        }

        function getTemplateForInstallScreen() {
            var option = '';

            $.post("<?php print site_url() ?>?get_templates_for_install_screen=1", function (data) {
                $.each(data, function (index, value) {
                    if (value.name && value.dir_name) {

                        var is_selected = false;
                        var screenshot = '';
                        var requrest_template = '';

                        <?php if (isset($_GET['request_template'])): ?>
                        requrest_template = '<?php echo $_GET['request_template'];?>';
                        <?php endif; ?>

                        if (requrest_template == value.dir_name) {
                            is_selected = true;
                        }

                        if (value.is_default && value.is_default == 1 && requrest_template == '') {
                            is_selected = true;
                        }

                        if (value.screenshot) {
                            screenshot = value.screenshot;
                        }

                        if (is_selected) {
                            option += '<option selected="selected" data-screenshot="' + screenshot + '" value="' + value.dir_name + '">' + value.name + '</option>';
                        } else {
                            option += '<option  data-screenshot="' + screenshot + '" value="' + value.dir_name + '">' + value.name + '</option>';
                        }
                    }
                });

                if (window.navigator.onLine) {
                    option += '<option value="_get_more">get more...</option>';
                }

                $("#default_template").html('');
                $("#default_template").append(option);

                setscreenshot()
            });
        }

        function getTemplatesFromPackageManagerBeforeInstall() {
            $.post("<?php print site_url() ?>?get_templates_from_marketplace_for_install_screen=1", function (data) {
                //$( "#demo-one" ).html( data );
            });
        }

        $(document).ready(function () {
            $("input[name='db_prefix']").bind('keydown', function (e) {
                if ((e.keycode || e.which) == 32) {
                    e.preventDefault();
                }
            });

            showForm($("select[name='db_driver']")[0]);
            $('#form_<?php print $rand; ?>').submit(function () {

                if (this.elements["admin_password"].value != this.elements["admin_password2"].value) {
                    alert("<?php _ejs("Passwords don't match."); ?>");
                    return false;
                }
                $('#mw_log').hide();
                installprogress();
                $('.mw-install-holder').slideUp();

                $data = $('#form_<?php print $rand; ?>').serialize();

                make_install_on_steps($data);
                return false;
            });
        });

        install_step = 1;
        install_step_num_fails = 0;
        install_step_orig_data = null;
        make_install_on_steps = function ($data) {
            if (!install_step_orig_data) {
                install_step_orig_data = $data;
            }

            if (install_step) {
                $data = $data + '&install_step=' + install_step;
            }

            $.post("<?php print site_url() ?>", $data,
                function (data) {
                    $('#mw_log').hide().empty();
                    if (data != undefined) {
                        if (typeof (data) == 'object' && typeof (data.install_step) != undefined) {
                            install_step = data.install_step;
                            make_install_on_steps(install_step_orig_data);
                        } else {
                            if (data.indexOf('Warning') !== -1) {
                                installprogressStop()
                            }

                            if (data.indexOf('Error') !== -1) {
                                //  installprogressStop()
                                $('#mw_log').addClass('mw-ui-box-important');
                            }



                            if (data == 'done') {
                                setTimeout(function () {
                                    var redirect_after_install_url = "<?php print admin_url(); ?>?install_done=1";
                                    var adminurlval = $('#admin_url').val().trim();
                                    if (adminurlval != '') {
                                        var redirect_after_install_url = "<?php print site_url(); ?>" + adminurlval + "?install_done=1";
                                    }

                                    window.location.href = redirect_after_install_url;
                                }, 3000);

                                //location.reload();
                            } else {
                                $('#mw_log').html(data).show();
                                $('.mw-install-holder').slideDown();
                            }
                        }
                    }

                    if (!install_step || install_step == 'finalize') {
                        $('#installprogressbar').slideUp();
                    }
                }).fail(function () {
                install_step_num_fails++;
                if (install_step_num_fails < 10) {
                    make_install_on_steps(install_step_orig_data);
                }
            });
        };

        installprogressStopped = false;

        installprogress = function (reset) {
            if (installprogressStopped) {
                installprogressStopped = false;
                return false;
            }

            var holder = $('#installprogressbar'),
                bar = $(".progress-bar", holder),
                percent = $(".progress-bar-percent", holder),
                reset = typeof reset === 'undefined' ? true : reset;

            if (reset === true) {
                bar.width('0%');
                percent.html('0%');
                holder.fadeIn();
            }

            <?php $log_file_url = userfiles_url() . 'install_log.txt'; ?>


            var jqxhr = $.ajax({
                url: "<?php print $log_file_url ?>",
                statusCode: {
                    404: function () {
                        // alert( "page not found" );
                    }
                },
                success:function(data) {

                    data = data.replace(/\r/g, '');

                    if (data.indexOf('Error') !== -1) {
                        return;
                    }



                    var arr = data.split('\n'),
                        l = arr.length,
                        last = arr[l - 2],
                        percentage = Math.round(((l - 1) / 500) * 100);

                    if (percentage > 100) {
                        percentage = 100;
                    }

                    bar[0].style.width = percentage + '%';
                    percent.html(percentage + '%');

                    if (last == 'done') {
                        percent.html('0%');
                        installprogressStop();
                        $("#installinfo").html('');
                    } else {
                        $("#installinfo").html(last);
                        setTimeout(function () {
                            installprogress(false);
                        }, 1000);
                    }


                }
            })
                .done(function (data) {

                })
                .fail(function () {
                    // alert("error");
                })
                .always(function () {
                    // alert("complete");
                });


        }

        installprogressStop = function () {
            var holder = $('#installprogressbar'),
                bar = $(".progress-bar", holder),
                percent = $(".progress-bar-percent", holder);
            holder.fadeOut();
            bar.width('0%');
            percent.html('0%');
            installprogressStopped = true;
        }
    </script>

    <script>
        $(document).ready(function () {
            $("#next").click(function () {
                var nextElement = $('select[name="default_template"] > option:selected').next('option');
                if (nextElement.length > 0) {
                    $('select[name="default_template"]').val(nextElement.val())
                    setscreenshot()
                }
            });

            $("#prev").click(function () {
                var nextElement = $('select[name="default_template"] > option:selected').prev('option');
                if (nextElement.length > 0) {
                    $('select[name="default_template"]').val(nextElement.val())
                    setscreenshot()
                }
            });

            $("#screenshot_preview").on('click', function () {
                var bg_img = $(this).find('#theImg').data('src');
            });

            $("#default_template").on('change', function () {
                setscreenshot()
                selectChange();
            });
        })
    </script>

    <style>
        .mw-ui-btn:not(:active):not(:hover):not(.active):not([class*=active-]):focus {
            outline: 1px solid;
        }
    </style>




</head>
<body>

<div class="installholder">
    <small class="text-muted d-block text-end text-right mb-2">v. <?php print MW_VERSION ?></small>

    <div class="card mb-4">
        <div class="card-header d-block">
            <div class="text-center my-3">
                <?php if ($pre_configured): ?>
                    <h4 class="text-center text-primary">Setup your website</h4>
                <?php else: ?>
                    <a href="http://microweber.com" target="_blank" id="logo"><img
                            src="<?php print asset('vendor/microweber-packages/frontend-assets-libs/img/logo.svg') ?>"
                            style="width: 250px"/></a>
                <?php endif; ?>
            </div>

            <div id="mw_log" class="error" style="display: none"></div>

            <div class="mw_install_progress">
                <div class="progress" id="installprogressbar" style="display: none">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"><span
                            class="progress-bar-percent"></span></div>
                </div>

                <div id="installinfo"></div>
            </div>
        </div>

        <div class="card-body">
            <div class="demo" id="demo-one">
                <div class="description">

                    <div class="mw-install-holder">
                        <?php if ($done == false): ?>
                            <?php
                            $check_pass = true;

                            $server_check_errors = array();
                            if (version_compare(phpversion(), '5.4.0', '<=')) {
                                $check_pass = false;
                                $server_check_errors['php_version'] = _e('You must run PHP 5.4 or greater', true);
                            }

                            if (function_exists('apache_get_modules')) {
                                if (!in_array('mod_rewrite', apache_get_modules())) {
                                    $check_pass = false;
                                    $server_check_errors['mod_rewrite'] = _e('mod_rewrite is not enabled on your server', true);
                                }
                            }

                            if (!extension_loaded('dom')) {
                                $check_pass = false;
                                $server_check_errors['dom'] = _e('The DOM PHP extension must be loaded', true);
                            }

                            if (!extension_loaded('curl')) {
                                $check_pass = false;
                                $server_check_errors['curl'] = _e('The Curl PHP extension must be loaded', true);
                            }

                            if (!extension_loaded('curl')) {
                                $check_pass = false;
                                $server_check_errors['curl'] = _e('The Curl PHP extension must be loaded', true);
                            }

                            if (!extension_loaded('xml')) {
                                $check_pass = false;
                                $server_check_errors['xml'] = _e('The lib-xml PHP extension must be loaded', true);
                            }

                            if (!extension_loaded('curl')) {
                                $check_pass = false;
                                $server_check_errors['curl'] = _e('The Curl PHP extension must be loaded', true);
                            }

                            if (!extension_loaded('json')) {
                                $check_pass = false;
                                $server_check_errors['json'] = _e('The json PHP extension must be loaded', true);
                            }

                            $is_pdo_loaded = false;
                            if (class_exists('PDO', false)) {
                                $is_pdo_loaded = true;
                            }

                            if ($is_pdo_loaded == false) {
                                if (extension_loaded('pdo')) {
                                    $is_pdo_loaded = true;
                                }
                            }

                            if ($is_pdo_loaded == false) {
                                if (defined('PDO::ATTR_DRIVER_NAME')) {
                                    $is_pdo_loaded = true;
                                }
                            }

                            if ($is_pdo_loaded != false) {
                                if (!defined('PDO::MYSQL_ATTR_LOCAL_INFILE')) {
                                    $is_pdo_loaded = false;
                                }
                            }

                            if ($is_pdo_loaded == false) {
                                $check_pass = false;
                                $server_check_errors['pdo'] = 'The PDO MYSQL PHP extension must be loaded';
                            }

                            if (!class_exists('\ZipArchive')) {
                                $check_pass = false;
                                $server_check_errors['zip'] = 'The Zip PHP extension must be loaded';
                            }

                            if (extension_loaded('gd') && function_exists('gd_info')) {
                            } else {
                                $check_pass = false;
                                $server_check_errors['gd'] = _e('The GD extension must be loaded in PHP', true);
                            }

                            if (defined('MW_USERFILES') and is_dir(MW_USERFILES) and !is_writable(MW_USERFILES)) {
                                $check_pass = false;
                                $must_be = MW_USERFILES;
                                $server_check_errors['MW_USERFILES'] = _e('The directory ' . MW_USERFILES . ' must be writable', true);
                            }

                            if (defined('MW_CACHE_ROOT_DIR') and is_dir(MW_CACHE_ROOT_DIR) and !is_writable(MW_CACHE_ROOT_DIR)) {
                                $check_pass = false;
                                $must_be = MW_CACHE_ROOT_DIR;
                                $server_check_errors['MW_CACHE_ROOT_DIR'] = _e('The directory ' . MW_CACHE_ROOT_DIR . ' must be writable', true);
                            }

                            if (defined('MW_CACHE_ROOT_DIR') and is_dir(MW_CACHE_ROOT_DIR) and !is_writable(MW_CACHE_ROOT_DIR)) {
                                $check_pass = false;
                                $must_be = MW_CACHE_ROOT_DIR;
                                $server_check_errors['MW_CACHE_ROOT_DIR'] = _e('The directory ' . MW_CACHE_ROOT_DIR . ' must be writable', true);
                            }

                            if (function_exists('media_base_path') and is_dir(media_base_path()) and !is_writable(media_base_path())) {
                                $check_pass = false;
                                $must_be = media_base_path();
                                $server_check_errors['media_base_path'] = _e('The directory ' . media_base_path() . ' must be writable', true);
                            }
                            ?>

                            <?php if ($check_pass == false): ?>
                                <?php if (!empty($server_check_errors)): ?>
                                    <h5 class="font-weight"><?php _e('Server check'); ?></h5>
                                    <h6 class="font-weight"><?php _e('There are some errors on your server that will prevent Microweber from working properly'); ?></h6>
                                    <ol class="error">
                                        <?php foreach ($server_check_errors as $server_check_error): ?>
                                            <li> <?php print $server_check_error; ?> </li>
                                        <?php endforeach ?>
                                    </ol>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php
                                $hide_db_setup = isset($_REQUEST['basic']);
                                if ($pre_configured) {
                                    $hide_db_setup = true;
                                }

                                $dbDefaultDbname = '';
                                $dbDefaultDbTablePrefix = '';
                                $dbDefaultUsername = '';
                                $dbDefaultPassword = '';
                                $dbDefaultHostname = '';
                                $dbDefaultEngine = 'sqlite';
                                $dbDefaultLang = 'en_US';


                                // hostname
                                if (isset($config['host']) and $config['host']) {
                                    $dbDefaultHostname = $config['host'];
                                }

                                if (isset($config['db_host']) and $config['db_host']) {
                                    $dbDefaultHostname = $config['db_host'];
                                }

                                // db name
                                if (isset($config['database']) and $config['database']) {
                                    $dbDefaultDbname = $config['database'];
                                }
                                if (isset($config['db_name']) and $config['db_name']) {
                                    $dbDefaultDbname = $config['db_name'];
                                }

                                // db username
                                if (isset($config['username']) and $config['username']) {
                                    $dbDefaultUsername = $config['username'];
                                }
                                if (isset($config['db_username']) and $config['db_username']) {
                                    $dbDefaultUsername = $config['db_username'];
                                }

                                // db pass
                                if (isset($config['password']) and $config['password']) {
                                    $dbDefaultPassword = $config['password'];
                                }
                                if (isset($config['db_password']) and $config['db_password']) {
                                    $dbDefaultPassword = $config['db_password'];
                                }

                                // prefix

                                if (isset($config['prefix']) and $config['prefix']) {
                                    $dbDefaultDbTablePrefix = $config['prefix'];
                                }

                                if (isset($config['db_prefix']) and $config['db_prefix']) {
                                    $dbDefaultDbTablePrefix = $config['db_prefix'];
                                }

                                // driver
                                if (isset($config['db_driver']) and $config['db_driver']) {
                                    $dbDefaultEngine = $config['db_driver'];
                                }

                                if (isset($config['site_lang']) and $config['site_lang']) {
                                    $dbDefaultLang = $config['site_lang'];
                                }


                                ?>

                                <form method="post" id="form_<?php print $rand; ?>" autocomplete="on">

                                    <div class="mw-ui-row" id="install-row">
                                        <div>

                                            <?php if ($hide_db_setup == true): ?>

                                                <a href="javascript:$('#mw_db_setup_toggle').toggle()"
                                                   class="btn-link mb-3 text-center">Advanced settings</a>

                                            <?php endif; ?>

                                            <div
                                                id="mw_db_setup_toggle" <?php if ($hide_db_setup == true): ?> style="display:none;" <?php endif; ?>>
                                                <?php if (!$hide_db_setup): ?>
                                                    <h2 style="font-weight: bold; margin-bottom: 15px;"><?php _e('Database Server'); ?></h2>
                                                <?php else: ?>
                                                    <h4>
                                                        <button type="button" class="btn btn-secondary"
                                                                onclick="$('#mw_db_setup_toggle').toggle();"><?php _e('Database Server'); ?></button>
                                                    </h4>
                                                <?php endif; ?>

                                                <hr class="thin" style="margin-bottom:"/>

                                                <div class="form-group">
                                                    <label class="form-label">Database Engine</label>
                                                    <small class="text-muted d-block mb-2">Choose the database
                                                        type</small>

                                                    <select class="form-select" name="db_driver"
                                                            onchange="showForm(this)" autocomplete="off" tabindex="1">
                                                        <?php foreach ($dbEngines as $engine): ?>
                                                            <option value="<?php print $engine; ?>"
                                                                <?php if ($dbDefaultEngine == $engine) {
                                                                    print 'selected';
                                                                } ?>>
                                                                <?php print $dbEngineNames[$engine]; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div id="db-form">
                                                    <div class="form-group">
                                                        <label class="form-label"><?php _e('Hostname'); ?></label>
                                                        <small
                                                            class="text-muted d-block mb-2"><?php _e('The address of your database server'); ?></small>
                                                        <input type="text" class="form-control" autofocus name="db_host"
                                                               tabindex="2"
                                                               value="<?php if ($dbDefaultHostname): ?><?php print $dbDefaultHostname; ?><?php endif; ?>"/>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label"><?php _e('Username'); ?></label>
                                                        <small
                                                            class="text-muted d-block mb-2"><?php _e('The username of your database.'); ?></small>
                                                        <input type="text" class="form-control" name="db_username"
                                                               tabindex="2"
                                                               value="<?php if ($dbDefaultUsername): ?><?php print $dbDefaultUsername; ?><?php endif; ?>"/>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label"><?php _e('Password'); ?></label>
                                                        <small
                                                            class="text-muted d-block mb-2"><?php _e('The password of your database.'); ?></small>
                                                        <input type="password" class="form-control" name="db_password"
                                                               tabindex="2"
                                                               value="<?php if ($dbDefaultPassword): ?><?php print $dbDefaultPassword; ?><?php endif; ?>"/>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label"><?php _e('Database'); ?></label>
                                                        <small
                                                            class="text-muted d-block mb-2"><?php _e('The name of your database.'); ?></small>
                                                        <input type="text" class="form-control" name="db_name"
                                                               id="db_name_value" tabindex="2"
                                                               value="<?php if ($dbDefaultDbname): ?><?php print $dbDefaultDbname; ?><?php endif; ?>"/>
                                                    </div>
                                                </div>

                                                <div id="db-form-sqlite" style="display:none">
                                                    <div class="form-group">
                                                        <label class="form-label"><?php _e('Database file'); ?> </label>
                                                        <small
                                                            class="text-muted d-block mb-2"><?php _e('A writable file path that may be relative to the root of your Microweber installation'); ?></small>
                                                        <input type="text" class="form-control" autofocus
                                                               name="db_name_sqlite" tabindex="2"
                                                               value="<?php if (isset($config['db_name_sqlite'])): ?><?php print $config['db_name_sqlite']; ?><?php endif; ?>"/>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="form-label"><?php _e('Table Prefix'); ?></label>
                                                    <small
                                                        class="text-muted d-block mb-2"><?php _e('Change this If you want to install multiple instances of Microweber to this database. Only latin letters and numbers are allowed.'); ?></small>
                                                    <input type="text" class="form-control" name="db_prefix"
                                                           tabindex="3"
                                                           value="<?php if ($dbDefaultDbTablePrefix): ?><?php print $dbDefaultDbTablePrefix; ?><?php endif; ?>"
                                                           onblur="prefix_add(this)"/>
                                                </div>
                                            </div>


                                            <div>
                                                <?php
                                                // $templates_opts = array('remove_hidden_from_install_screen' => true);
                                                // $templates = site_templates($templates_opts);
                                                // moved by ajax
                                                $templates = [['dir_name' => 'default', 'name' => 'Default']]
                                                ?>

                                                <?php if (is_array($templates) and !empty($templates)): ?>
                                                    <div class="form-group">
                                                        <h5 class="text-primary mb-3 text-center"><?php print 'Choose your preferred design'; ?></h5>

                                                        <div class="row">
                                                            <div class="col-auto">
                                                                <button
                                                                    class="btn btn-primary btn-icon change-templ-btn"
                                                                    type="button" id="prev"><i
                                                                        class="mdi mdi-chevron-left mdi-24px"></i>
                                                                </button>
                                                            </div>

                                                            <div class="col px-0">
                                                                <select class="form-select" name="default_template"
                                                                        id="default_template" tabindex="6">
                                                                    <?php foreach ($templates as $template): ?>
                                                                        <?php if (isset($template['dir_name']) and isset($template['name'])): ?>
                                                                            <option
                                                                                value="<?php print $template['dir_name']; ?>" <?php if (isset($template['screenshot']) and ($template['screenshot']) != false): ?> data-screenshot="<?php print $template['screenshot']; ?>" <?php endif; ?>><?php print $template['name']; ?></option>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>

                                                            <div class="col-auto">
                                                                <button
                                                                    class="btn btn-primary btn-icon change-templ-btn"
                                                                    type="button" id="next"><i
                                                                        class="mdi mdi-chevron-right mdi-24px"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <script>
                                                $(document).ready(function () {
                                                    setscreenshot()
                                                });

                                                function setscreenshot() {
                                                    setTimeout(function () {
                                                        var scrshot = ($('#default_template').children('option:selected').data('screenshot'));
                                                        $('#theImg').remove();

                                                        if (typeof (scrshot) != 'undefined') {
                                                            $('#screenshot_preview').append('<div id="theImg"></div>');
                                                            $('#theImg').css('background-image', 'url(' + scrshot + ')');
                                                            $('#theImg').attr('data-src', scrshot);
                                                        }
                                                    }, 100);
                                                }
                                            </script>

                                            <div id="screenshot_preview" class="mt-4"></div>

                                            <?php if ($pre_configured): ?>

                                            <?php endif; ?>


                                            <div class="text-center">
                                                <a class="btn btn-outline-success my-4 "
                                                   href="<?php echo site_url(); ?>?select_template=1">DISCOVER MORE
                                                    PREMIUM TEMPLATES</a>
                                            </div>
                                            <div class="row mt-3">


                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox my-2">
                                                            <input type="checkbox" class="form-check-input"
                                                                   id="with_default_content" name="with_default_content"
                                                                   value="1" tabindex="7" checked="">
                                                            <label class="custom-control-label"
                                                                   for="with_default_content"><?php _e('Import default content'); ?></label>
                                                        </div>
                                                        <small
                                                            class="text-muted d-block mb-2"><?php _e('If checked, some default content will be added.'); ?></small>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <b><?php _e('Website Default Language'); ?></b>
                                                    <small
                                                        class="text-muted d-block mb-2"><?php _e('Choose the language you want to start with.'); ?></small>
                                                    <?php $currentLang = current_lang(); ?>
                                                    <div class="form-group">
                                                        <?php
                                                        $tm = new \MicroweberPackages\Translation\TranslationPackageInstallHelper();
                                                        $langs = $tm->getAvailableTranslations();
                                                        ?>
                                                        <select name="site_lang" class="form-select" tabindex="8">
                                                            <?php foreach ($langs as $langKey => $langValue): ?>
                                                                <option <?php if ($dbDefaultLang and $dbDefaultLang == $langKey): ?> selected <?php endif; ?>
                                                                    value="<?php echo $langKey; ?>"><?php echo $langValue; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>


                                        <div id="admin-user" <?php if ($pre_configured == true): ?><?php endif; ?>>
                                            <div class="mw-ui-col-container">
                                                <div class="admin-setup">
                                                    <h4 class="form-label"><?php print 'Login Information'; ?></h4>
                                                    <hr class="thin" style="margin-bottom: 15px;">

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label
                                                                    class="form-label"><?php _e('Admin username'); ?></label>
                                                                <input type="text" class="form-control" tabindex="9"
                                                                       name="admin_username" <?php if (isset($config['admin_username']) == true and isset($config['admin_username']) != ''): ?> value="<?php print $config['admin_username'] ?>" <?php endif; ?> />
                                                            </div>

                                                            <div class="form-group">
                                                                <label
                                                                    class="form-label"><?php _e('Admin password'); ?></label>
                                                                <input type="password" class="form-control"
                                                                       tabindex="11"
                                                                       name="admin_password" <?php if (isset($config['admin_password']) == true and isset($config['admin_password']) != ''): ?> value="<?php print $config['admin_password'] ?>" <?php endif; ?> />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label
                                                                    class="form-label"><?php _e('Admin email'); ?></label>
                                                                <input type="text" class="form-control" tabindex="10"
                                                                       name="admin_email" <?php if (isset($config['admin_email']) == true and isset($config['admin_email']) != ''): ?> value="<?php print $config['admin_email'] ?>" <?php endif; ?> />
                                                            </div>

                                                            <div class="form-group">
                                                                <label
                                                                    class="form-label"><?php _e('Repeat password'); ?></label>
                                                                <input type="password" class="form-control"
                                                                       tabindex="12"
                                                                       name="admin_password2" <?php if (isset($config['admin_password']) == true and isset($config['admin_password']) != ''): ?> value="<?php print $config['admin_password'] ?>" <?php endif; ?> />
                                                            </div>

                                                            <a name="create-admin"></a>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <div class="custom-control custom-checkbox my-2">
                                                                    <input type="checkbox" class="form-check-input"
                                                                           id="subscribe_for_update_notification"
                                                                           name="subscribe_for_update_notification"
                                                                           value="1" tabindex="13" checked="">
                                                                    <label class="custom-control-label"
                                                                           for="subscribe_for_update_notification"><?php _e('Update nofitication'); ?></label>
                                                                </div>

                                                                <small
                                                                    class="text-muted d-block mb-2"><?php _e('If checked, you will get update notifications when new version is avaiable.'); ?></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-2 mb-4">
                                            <div class="text-end text-right">
                                                <button type="button" href="javascript:void(0);"
                                                        class="btn btn-link px-0"
                                                        onClick="$('.advanced-options-installation').toggle()"
                                                        tabindex="14">Show advanced options
                                                </button>
                                            </div>

                                            <div class="advanced-options-installation mt-2" style="display:none;">
                                                <div class="form-group">
                                                    <label class="form-label"><?php _e('Admin URL'); ?></label>
                                                    <input type="text" class="form-control" name="admin_url"
                                                           value="admin" id="admin_url" tabindex="15"/>
                                                </div>
                                            </div>


                                           <?php

                                           /* <div class="advanced-options-installation mt-2" style="display:none;">
                                                <div class="form-group">
                                                    <label class="form-label"><?php _e('Configruation save method'); ?></label>

                                                    <select class="form-select" name="config_save_method"
                                                            tabindex="16">
                                                        <option value="env">.env File</option>
                                                        <option value="config_file">Config File</option>

                                                    </select>


                                                </div>
                                            </div>*/
                                           ?>

                                            <div class="advanced-options-installation mt-2" style="display:none;">
                                                <div class="form-group">
                                                    <label class="form-label"><?php _e('Force HTTPS'); ?></label>

                                                    <select class="form-select" name="force_https"
                                                            tabindex="17">
                                                        <option value="">Default</option>
                                                        <option value="1">Yes</option>

                                                    </select>


                                                </div>
                                            </div>

                                            <div class="advanced-options-installation mt-2" style="display:none;">
                                                <div class="form-group">
                                                    <label class="form-label"><?php _e('Enable debug'); ?></label>

                                                    <select class="form-select" name="app_debug"
                                                            tabindex="18">
                                                        <option value="">Default</option>
                                                        <option value="1">Yes</option>

                                                    </select>


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php $default_content_file = mw_root_path() . '.htaccess'; ?>
                                    <?php if ($pre_configured == true): ?>
                                        <input type="hidden" name="clean_pre_configured" value="1">
                                    <?php endif; ?>

                                    <input type="hidden" name="make_install" value="1"
                                           id="is_installed_<?php print $rand; ?>">
                                    <input type="hidden" value="UTC" name="default_timezone"/>

                                    <div class="text-end text-right">
                                        <button type="submit" name="submit" class="btn btn-primary"
                                                dusk="install-button" id="install-button"
                                                tabindex="16"><?php _e('Install'); ?></button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <h2><?php _e('Welcome to your new website!'); ?></h2>
                            <br/>
                            <a href="<?php print site_url() ?>admin" class="mw-ui-btn mw-ui-btn-info pull-left">
                                <?php _e('Login to admin panel'); ?>
                            </a> <a href="<?php print site_url() ?>" class="mw-ui-btn pull-left"
                                    style="margin-left: 20px;">
                                <?php _e('Visit your site'); ?>
                            </a>

                        <?php endif; ?>
                    </div>
                    <div id="mw-install-done" style="display:none">
                        <h2>
                            <?php _e('Installation is completed'); ?>
                        </h2>
                        <br/>
                        <a href="<?php print site_url() ?>" class="  btn btn-primary">
                            <?php _e('Visit your site'); ?>
                        </a>
                        <a href="<?php print site_url() ?>admin" class="mw-ui-btn mw-ui-btn-info">
                            <?php _e('Login to admin panel'); ?>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="dialog-message-marketplace" title="Marketplace items" style="display: none">

</div>
<?php print mw_footer_scripts() ?>


</body>
</html>
