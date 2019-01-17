<script>
    mw.require('ui.css')
    mw.require('tree.js')
    mw.lib.require('material_icons')
</script>
<link rel="stylesheet" href="http://localhost/mw1/userfiles/modules/microweber/css/fonts/mw-icons-mind/line/style.css">
<link rel="stylesheet" href="http://localhost/mw1/userfiles/modules/microweber/css/fonts/mw-icons-mind/solid/style.css">
<style>
    body {
        font-family: 'Open Sans', sans-serif !important;
    }

    html, body {
        height: 100%;
        min-height: 100%;
    }

    .mw__example > .mw-ui-box:not(.oa) {
        overflow: auto;
    }

    .mw__example {
        margin: 50px auto;
        padding: 50px 0;
        border-bottom: 1px solid #E0E4EB;
        position: relative;
    }

    .kholder {
        max-width: 1200px;
        margin: auto;
        box-shadow: 0 2px 6px rgba(0, 0, 0, .2);
        padding: 30px;
    }

    .mw__example {
        max-width: 800px;
        margin: 20px auto;
    }

    code {
        white-space: pre;
    }

    .cpbtn {
        position: absolute;
        z-index: 2;
        top: 60px;
        right: 0px;
        box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.10);
    }

    .mw-text {
        font-size: 100%;
        line-height: 1.44em;
    }

    .mw-text > h1 {
        font-size: 220%;
    }

    .mw-text > h2 {
        font-size: 200%;
    }

    .mw-text > h3 {
        font-size: 180%;
    }

    .mw-text > h4 {
        font-size: 160%;
    }

    .mw-text > h5 {
        font-size: 140%;
    }

    .mw-text > h6 {
        font-size: 120%;
    }

    .mw-text > p,
    .mw-text > img,
    .mw-text > h1,
    .mw-text > h2,
    .mw-text > h3,
    .mw-text > h4,
    .mw-text > h5,
    .mw-text > h6 {
        margin-bottom: .6em;
    }

    .mw-ui-box + .mw-ui-box {
        margin-top: 1em;
    }

    .menu {
        position: fixed;
        top: 90px;
        right: 20px;
        max-height: -webkit-calc(100vh - 100px);
        max-height: calc(100vh - 100px);
        overflow: auto;
        height: auto;
    }


</style>

<script>

    <?php   $apiurl = api_url();   ?>


    KS = {
        copy: function (value) {
            var tempInput = document.createElement("input");
            tempInput.style = "position: absolute; left: -1000px; top: -1000px";
            tempInput.value = value;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
        },
        demo: function (el) {
            var parent = $(el).parents(".mw__example");
            var tabsnav = $(".mw-ui-btn-nav-tabs a", parent);
            var i = tabsnav.index(el);
            tabsnav.removeClass('active').eq(i).addClass('active');
            parent.children('[data-view],[data-source]').hide().eq(i).show()
        },
        tags: function () {

            var alltags = {
                all: 0
            };
            $(".mw__example").each(function () {
                var ctags = this.dataset.tags.split(',');
                $.each(ctags, function () {
                    alltags.all++;
                    var item = this.toLowerCase();
                    alltags[item] = alltags[item] || 0;
                    alltags[item]++;
                })
            });
            //alltags.sort()
            $.each(alltags, function (key, val) {
                $(".menu").append('<li><a href="#' + key + '">' + key.toUpperCase() + ' <span class="mw-color-warn">(' + val + ')</span></a></li>')
            })

        },
        init: function () {
            $(".mw__example").not('.ready').each(function () {
                $(this).addClass('ready')
                if ($("[data-view]", this).length) {
                    var cp = document.createElement('span');
                    cp.className = 'mw-ui-btn mw-ui-btn-small cpbtn tip';
                    cp.dataset.tip = 'Copy snippet';
                    cp.innerHTML = '<span class="mw-icon-app-copy-outline"></span>';

                    var htmlo = $(this).html();
                    var html = htmlo.replace(/\</g, '&lt;').replace(/ data-view=\"\"/g, '');
                    var div = $('<div class="mw-ui-box mw-ui-box-content" data-source style="display:none;"><pre>' + html + '</pre></div>');
                    $(this).append(div);
                    $(this).prepend('<div class="mw-ui-btn-nav mw-ui-btn-nav-tabs"><a onclick="KS.demo(this)" class="mw-ui-btn active">Preview</a><a href="javascript:;" onclick="KS.demo(this)" class="mw-ui-btn">Source</a></div>');
                    cp.onclick = function () {
                        KS.copy(htmlo);
                        mw.notification.success('Copied');
                    }
                    div.after(cp)
                }

            })
            KS.handleHash()
            KS.tags()
        },
        hash: function () {
            var hash = decodeURIComponent(location.hash.replace(/\#/g, ''));
            if (hash == 'all' || hash == '') {
                $(".mw__example").show()
            }
            else {
                $(".mw__example").hide().filter('[data-tags*=",' + hash + '"],[data-tags*="' + hash + ',"],[data-tags="' + hash + '"]').show()
            }
            $(".menu a").removeClass('active').filter('[href="#' + hash + '"]').addClass('active');
        },
        handleHash: function () {
            $(window).on('load hashchange', function () {
                KS.hash()
            })
        },


    }


    KS.handleHash()

    $(document).ready(function () {
        KS.init()

    })

</script>


<ul class="mw-ui-box mw-ui-navigation menu"></ul>

<div class="kholder">
    <?php

    $dir = modules_path() . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'kitchensink';
    $files = scandir($dir);
    foreach ($files as $file) {
        if (strpos($file, '.ks.php') !== false) {
            include $dir . '/' . $file;
        }
    }
    ?>
</div>




