<!DOCTYPE HTML>
<html>
<head>
    <title><?php _e('Installation'); ?></title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Language" Content="en">
    <meta name="robots" content="noindex">
    <?php get_favicon_tag(); ?>
    <?php print mw_header_scripts() ?>


    <link type="text/css" rel="stylesheet" media="all" href="<?php print asset('vendor/microweber-packages/microweber-filament-theme/microweber-filament-theme.css'); ?>"/>
    <link type="text/css" rel="stylesheet" media="all" href="<?php print asset('vendor/microweber-packages/frontend-assets/css/install.css'); ?>"/>

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

            if (typeof(mw.marketplace_dialog_jquery_ui) != 'undefined') {
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
                        if (typeof(data) == 'object' && typeof(data.install_step) != undefined) {
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
            $.get('<?php print $log_file_url ?>', function (data) {
                var data = data.replace(/\r/g, '');
                var arr = data.split('\n'),
                    l = arr.length,
                    i = 0,
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


<main class="w-100 h-100vh ">

    @hasSection('content')
        @yield('content')
    @endif

</main>





<div id="dialog-message-marketplace" title="Marketplace items" style="display: none">

</div>
<?php print mw_footer_scripts() ?>


</body>
</html>
