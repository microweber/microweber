<!DOCTYPE HTML>
<html>
<head>
    <title><?php _e('Installation'); ?></title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Language" Content="en">
    <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>default.css"/>
    <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/ui.css"/>
    <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/admin.css"/>
    <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/components.css"/>
    <link type="text/css" rel="stylesheet" media="all" href="<?php print mw_includes_url(); ?>css/install.css"/>
    <script type="text/javascript" src="<?php print mw_includes_url(); ?>api/jquery.js"></script>
    <?php
    $rand = uniqid();
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $defhost = strpos($_SERVER['HTTP_USER_AGENT'], 'Linux') ? 'localhost' : '127.0.0.1';

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
            $("input[name='table_prefix']").bind('keydown', function (e) {
                if (( e.keycode || e.which ) == 32) {
                    e.preventDefault();
                }
            });

            showForm($("select[name='db_driver']")[0]);
            $('#form_<?php print $rand; ?>').submit(function () {

                if (this.elements["admin_password"].value != this.elements["admin_password2"].value) {
                    alert("<?php _e("Passwords don't match."); ?>");
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

                        if (typeof(data) == 'object' && typeof(data.install_step) != undefined) {
                            install_step = data.install_step;
                            make_install_on_steps(install_step_orig_data);
                        }

                        else {
                            if (data.indexOf('Warning') !== -1) {
                                installprogressStop()
                            }
                            if (data == 'done') {

                                setTimeout(function () {

                                    window.location.href = "<?php print admin_url(); ?>";

                                }, 2000);


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
                bar = $(".mw-ui-progress-bar", holder),
                percent = $(".mw-ui-progress-percent", holder),
                reset = typeof reset === 'undefined' ? true : reset;

            if (reset === true) {
                bar.width('0%');
                percent.html('0%');
                holder.fadeIn();
            }

            <?php $log_file_url = userfiles_url() . 'install_log.txt'; ?>
            $.get('<?php print $log_file_url ?>', function (data) {
                var data = data.replace(/\r/g, '');
                var arr = data.split('\n'),
                    l = arr.length,
                    i = 0,
                    last = arr[l - 2],
                    percentage = Math.round(((l - 1) / 60) * 100);
                bar[0].style.width = percentage + '%';
                percent.html(percentage + '%');
                if (last == 'done') {
                    percent.html('0%');
                    installprogressStop();
                    $("#installinfo").html('');
                }
                else {
                    $("#installinfo").html(last);
                    setTimeout(function () {
                        installprogress(false);
                    }, 1000);
                }

            });
        }

        installprogressStop = function () {
            var holder = $('#installprogressbar'),
                bar = $(".mw-ui-progress-bar", holder),
                percent = $(".mw-ui-progress-percent", holder);
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
                    $('select[name="default_template"] > option:selected').removeAttr('selected').next('option').attr('selected', 'selected').trigger('change');
                }
            });

            $("#prev").click(function () {
                var nextElement = $('select[name="default_template"] > option:selected').prev('option');
                if (nextElement.length > 0) {
                    $('select[name="default_template"] > option:selected').removeAttr('selected').prev('option').attr('selected', 'selected').trigger('change');
                }
            });

            $("#screenshot_preview").on('click', function () {
                var bg_img = $(this).find('#theImg').data('src');
            });
        })
    </script>
</head>
<body>


<div class="installholder">
    <small class="version">v. <?php print MW_VERSION ?></small>
    <div class="mw-ui-box">
        <div class="mw-ui-box-header">
            <?php if ($pre_configured): ?>
                <h2>Setup your website</h2>
            <?php else: ?>
                <a href="http://Microweber.com" target="_blank" id="logo"><span class="mw-icon-mw"></span></a>
            <?php endif; ?>


            <div id="mw_log" class="error mw-ui-box mw-ui-box-content" style="display: none"></div>
            <div class="mw_install_progress">
                <div class="mw-ui-progress" id="installprogressbar" style="display: none">
                    <div class="mw-ui-progress-bar" style="width: 0%;"></div>
                    <div class="mw-ui-progress-info"><?php _e('Installing'); ?></div>
                    <span class="mw-ui-progress-percent">0%</span>
                </div>
                <div id="installinfo"></div>
            </div>
        </div>
        <div class="mw-ui-box-content">
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

                            if (!extension_loaded('xml')) {
                                $check_pass = false;
                                $server_check_errors['xml'] = _e('The lib-xml PHP extension must be loaded', true);
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
                                    <h3>
                                        <?php _e('Server check'); ?>
                                    </h3>
                                    <h4>
                                        <?php _e('There are some errors on your server that will prevent Microweber from working properly'); ?>
                                    </h4>
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
                                ?>

                                <form method="post" id="form_<?php print $rand; ?>" autocomplete="off">

                                    <div class="mw-ui-row" id="install-row">
                                        <div>
                                            <div class="mw-ui-col-container">
                                                <div id="mw_db_setup_toggle" <?php if ($hide_db_setup == true): ?> style="display:none;" <?php endif; ?>>
                                                    <?php if (!$hide_db_setup): ?>
                                                        <h2><?php _e('Database Server'); ?></h2>
                                                    <?php else: ?>
                                                        <h2><span class="mw-ui-btn" onclick="$('#mw_db_setup_toggle').toggle();"><?php _e('Database Server'); ?></span></h2>
                                                    <?php endif; ?>

                                                    <div class="hr"></div>
                                                    <div class="mw-ui-field-holder">
                                                        <label class="mw-ui-label">
                                                            Database Engine
                                                            <span data-help="Choose the database type"><span class="mw-icon-help-outline mwahi tip"></span></span></label>

                                                        <select class="mw-ui-field" name="db_driver" onchange="showForm(this)" autocomplete="off">
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
                                                        <div class="mw-ui-field-holder">
                                                            <label class="mw-ui-label">
                                                                <?php _e('Hostname'); ?>
                                                                <span data-help="<?php _e('The address of your database server'); ?>"><span class="mw-icon-help-outline mwahi"></span></span></label>
                                                            <input type="text" class="mw-ui-field" autofocus name="db_host" value="<?php if (isset($config['host'])) {
                                                                print $config['host'];
                                                            } ?>"/>
                                                        </div>
                                                        <div class="mw-ui-field-holder">
                                                            <label class="mw-ui-label">
                                                                <?php _e('Username'); ?>
                                                                <span data-help="<?php _e('The username of your database.'); ?>"><span class="mw-icon-help-outline mwahi tip"></span></span></label>
                                                            <input type="text" class="mw-ui-field" name="db_user" value="<?php if (isset($config['username'])) {
                                                                print $config['username'];
                                                            } ?>"/>
                                                        </div>
                                                        <div class="mw-ui-field-holder">
                                                            <label class="mw-ui-label">
                                                                <?php _e('Password'); ?>
                                                            </label>
                                                            <input type="password" class="mw-ui-field"
                                                                   name="db_pass"
                                                                   value="<?php if (isset($config['password'])) {
                                                                       print $config['password'];
                                                                   } ?>"/>
                                                        </div>
                                                        <div class="mw-ui-field-holder">
                                                            <label class="mw-ui-label">
                                                                <?php _e('Database'); ?>
                                                                <span
                                                                        data-help="<?php _e('The name of your database.'); ?>"><span
                                                                            class="mw-icon-help-outline mwahi tip"></span></span></label>
                                                            <input type="text" class="mw-ui-field"
                                                                   name="db_name" id="db_name_value"
                                                                   value="<?php if (isset($config['database'])) {
                                                                       print $config['database'];
                                                                   } ?>"/>
                                                        </div>
                                                    </div>
                                                    <div id="db-form-sqlite" style="display:none">
                                                        <div class="mw-ui-field-holder">
                                                            <label class="mw-ui-label">
                                                                <?php _e('Database file'); ?>
                                                                <span data-help="<?php _e('A writable file path that may be relative to the root of your Microweber installation'); ?>">
                                                                    <span class="mw-icon-help-outline mwahi tip"></span>
                                                                </span>
                                                            </label>
                                                            <input type="text" class="mw-ui-field" autofocus name="db_name_sqlite" value="<?php if (isset($config['db_name_sqlite'])) {
                                                                print $config['db_name_sqlite'];
                                                            } ?>"/>
                                                        </div>
                                                    </div>
                                                    <div class="mw-ui-field-holder">
                                                        <label class="mw-ui-label"><?php _e('Table Prefix'); ?><span data-help="<?php _e('Change this If you want to install multiple instances of Microweber to this database. Only latin letters and numbers are allowed.'); ?>"><span
                                                                        class="mw-icon-help-outline mwahi tip"></span></span></label>
                                                        <input type="text" class="mw-ui-field" name="table_prefix" value="<?php if (isset($config['prefix'])) {
                                                            print $config['prefix'];
                                                        } ?>" onblur="prefix_add(this)"/>
                                                    </div>
                                                </div>

                                                <div>
                                                    <?php
                                                    $templates_opts = array('remove_hidden_from_install_screen' => true);
                                                    $templates = site_templates($templates_opts);
                                                    ?>
                                                    <?php if (is_array($templates) and !empty($templates)): ?>
                                                        <div class="mw-ui-field-holder">
                                                            <label class="mw-ui-label bold center" style="margin-bottom:20px; color: #0086db; font-size: 18px;"><?php print 'Choose Your Template'; ?> <span data-help="<?php print 'Choose default site template'; ?>"><span
                                                                            class="mw-icon-help-outline mwahi tip"></span></span></label>

                                                            <div class="mw-ui-row">
                                                                <div class="mw-ui-col" style="width:40px;">
                                                                    <button class="mw-ui-btn mw-ui-btn-info change-templ-btn" type="button" id="prev"><i class="mw-icon-arrowleft"></i></button>
                                                                </div>
                                                                <div class="mw-ui-col">
                                                                    <select class="mw-ui-field" name="default_template"
                                                                            id="default_template">
                                                                        <?php foreach ($templates as $template): ?>
                                                                            <?php if (isset($template['dir_name']) and isset($template['name'])): ?>
                                                                                <option <?php if (isset($template['is_default']) and ($template['is_default']) != false): ?> selected="selected" <?php endif; ?>
                                                                                        value="<?php print $template['dir_name']; ?>" <?php if (isset($template['screenshot']) and ($template['screenshot']) != false): ?> data-screenshot="<?php print $template['screenshot']; ?>" <?php endif; ?>><?php print $template['name']; ?></option>
                                                                            <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="mw-ui-col" style="width:40px; text-align: right;">
                                                                    <button class="mw-ui-btn mw-ui-btn-info change-templ-btn" type="button" id="next"><i class="mw-icon-arrowright"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <script>
                                                    $(document).ready(function () {
                                                        setscreenshot()
                                                        $('#default_template').change(function () {
                                                            setscreenshot()
                                                        });
                                                    });

                                                    function setscreenshot() {
                                                        var scrshot = ($('#default_template').children('option:selected').data('screenshot'));
                                                        $('#theImg').remove();

                                                        if (typeof(scrshot) != 'undefined') {
                                                            $('#screenshot_preview').append('<div id="theImg"></div>');
                                                            $('#theImg').css('background-image', 'url(' + scrshot + ')');
                                                            $('#theImg').attr('data-src', scrshot);
                                                        }
                                                    }
                                                </script>

                                                <div id="screenshot_preview" style="margin:10px 0;"></div>
                                                <?php if ($pre_configured): ?>

                                                <?php endif; ?>

                                                <div class="mw-ui-field-holder pull-left">
                                                    <label class="mw-ui-check">
                                                        <input name="with_default_content" type="checkbox" checked="checked" value="1">
                                                        <span></span>&nbsp;
                                                        <?php _e('Install the template with default content'); ?>
                                                        <span data-help="<?php _e('If checked, some default content will be added.'); ?>"><span class="mw-icon-help-outline mwahi tip"></span></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="admin-user" <?php if ($pre_configured == true): ?><?php endif; ?>>

                                            <div class="mw-ui-col-container">
                                                <div class="admin-setup">
                                                    <?php if ($pre_configured != true): ?>
                                                        <h2><?php _e('Create Admin user'); ?></h2>
                                                        <div class="hr"></div>
                                                    <?php endif; ?>

                                                    <label class="mw-ui-label bold center" style="margin-top:30px; color: #0086db; font-size: 18px;"><?php print 'Login Information'; ?></label>


                                                    <div class="mw-ui-row" style="margin-top:20px;">
                                                        <div class="mw-ui-col" style="padding-right:10px;">
                                                            <div class="mw-ui-field-holder">
                                                                <label class="mw-ui-label">
                                                                    <?php _e('Admin username'); ?>
                                                                </label>
                                                                <input type="text" class="mw-ui-field" tabindex="2"
                                                                       name="admin_username" <?php if (isset($config['admin_username']) == true and isset($config['admin_username']) != ''): ?> value="<?php print $config['admin_username'] ?>" <?php endif; ?> />
                                                            </div>
                                                            <div class="mw-ui-field-holder">
                                                                <label class="mw-ui-label">
                                                                    <?php _e('Admin password'); ?>
                                                                </label>
                                                                <input type="password" class="mw-ui-field" tabindex="4"
                                                                       name="admin_password" <?php if (isset($config['admin_password']) == true and isset($config['admin_password']) != ''): ?> value="<?php print $config['admin_password'] ?>" <?php endif; ?> />
                                                            </div>

                                                            <div class="mw-ui-field-holder pull-left">
                                                                <label class="mw-ui-check">
                                                                    <input name="subscribe_for_update_notification" type="checkbox" checked="checked" value="1"><span></span>&nbsp;
                                                                    <?php _e('Update nofitication'); ?>
                                                                    <span data-help="<?php _e('If checked, you will get update notifiactions when new version is avaiable.'); ?>"><span class="mw-icon-help-outline mwahi tip"></span></span>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="mw-ui-col" style="padding-left:10px;">
                                                            <div class="mw-ui-field-holder">
                                                                <label class="mw-ui-label">
                                                                    <?php _e('Admin email'); ?>
                                                                </label>
                                                                <input type="text" class="mw-ui-field" tabindex="3"
                                                                       name="admin_email" <?php if (isset($config['admin_email']) == true and isset($config['admin_email']) != ''): ?> value="<?php print $config['admin_email'] ?>" <?php endif; ?> />
                                                            </div>
                                                            <div class="mw-ui-field-holder">
                                                                <label class="mw-ui-label">
                                                                    <?php _e('Repeat password'); ?>
                                                                </label>
                                                                <input type="password" class="mw-ui-field" tabindex="5"
                                                                       name="admin_password2" <?php if (isset($config['admin_password']) == true and isset($config['admin_password']) != ''): ?> value="<?php print $config['admin_password'] ?>" <?php endif; ?> />
                                                            </div>
                                                            <a name="create-admin"></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <?php $default_content_file = mw_root_path() . '.htaccess'; ?>
                                    <?php if ($pre_configured == true): ?>
                                        <input type="hidden" name="clean_pre_configured" value="1">
                                    <?php endif; ?>

                                    <div class="mw_clear"></div>
                                    <input type="hidden" name="make_install" value="1"
                                           id="is_installed_<?php print $rand; ?>">
                                    <input type="hidden" value="UTC" name="default_timezone"/>
                                    <input type="submit" name="submit"
                                           class="mw-ui-btn mw-ui-btn-big mw-ui-btn-info pull-right"
                                           value="<?php _e('Install'); ?>">


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
                        <a href="<?php print site_url() ?>" class="mw-ui-btn">
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
</body>
</html>