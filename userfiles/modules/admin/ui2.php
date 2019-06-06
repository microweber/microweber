

<div class="mw-ui mw-ui-box-content" id="doc-box">
    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
        <a class="mw-ui-btn mw-ui-btn-big active" href="#css">CSS Components</a>
        <a class="mw-ui-btn mw-ui-btn-big" href="#js">JavaScript Components</a>
    </div>
    <div class="mw-ui-box mw-ui-box-content">
        <ul class="mw-ui-box mw-ui-navigation mw-ui-navigation-horizontal demonav" id="nav1">

        </ul>
        <script>

            $(document).ready(function(){
                $('#doc-box > .mw-ui-box').each(function(){
                    var scope = $(this);
                    $('.ui_section', scope).hide()
                    $(".ui_section", scope).each(function(){
                        var a = $("<a/>");
                        var html = $(this).children("h2").text();
                        var id = html.toLowerCase().replace(/\s/g, '-');
                        a.html(html);
                        a.attr('href', '#'+id);
                        a.on('click', function(){
                            $('.ui_section', scope).hide()
                            $($(this).attr('href'), scope).show()
                            return false;
                        });
                        this.id = id;

                        $(".demonav", scope).append(a)
                        a.wrap('<li>')
                    });
                })

            })

        </script>
        <style>

        .ui_section{
            padding-bottom: 40px;
            margin-bottom: 40px;
            border-bottom: 1px solid #B0B0B0
        }

        .ui_section .mw-ui-btn{
            vertical-align: middle;
        }

        </style>



            <?php include "ui/buttons.php"; ?>

            <?php include "ui/navigations.php"; ?>

            <?php include "ui/grid.php"; ?>
            <?php include "ui/accordion.php"; ?>



    </div>

    <div class="mw-ui-box" style="display: none">
        <ul class="mw-ui-box mw-ui-navigation mw-ui-navigation-horizontal  demonav" id="nav2">

        </ul>
        <div class="ui_section">
            <h2>Module settings</h2>
            <br>

            <div id="test"></div>


        </div>

        <div class="ui_section">
            <h2>Dialog</h2>
            <br>

            <button class="mw-ui-btn" onclick="mw.dialog({title:'opala', height: 'auto', content:'asdsad'});">Default</button>
            <button class="mw-ui-btn" onclick="mw.dialogIframe({ url: 'https://www.youtube.com/embed/L0gakjjel3E' });">Iframe</button>
            <button class="mw-ui-btn" onclick="mw.dialogIframe({ url: '<?php print site_url(); ?>', autoHeight: true });">Iframe Auto height</button>


        </div>


        <script>
            mw.require('dialog.js');
            mw.require('prop_editor.js');
            mw.require('module_settings.js');
        </script>

    </div>
</div>
<script>

    $(document).ready(function () {
        mw.tabs({
            nav:$("#doc-box>.mw-ui-btn-nav>a"),
            tabs:$("#doc-box>.mw-ui-box")
        }).set(0);

        var settings = new mw.moduleSettings({
            element:'#test',
            header:'<i class="mw-icon-drag"></i> Move <a class="pull-right" data-action="remove"><i class="mw-icon-close"></i></a>',
            data:[
                {name:'test 1', size:1, icon:'<i class="mw-icon-product"></i>'},
                {name:'test 2', size:2, icon:'<i class="mw-icon-live"></i>'}
            ],
            schema:[
                {
                    interface:'text',
                    label:['Name'],
                    id:'name'
                },
                {
                    interface:'number',
                    label:['Enter size'],
                    id:'size'
                }
                ,
                {
                    interface:'icon',
                    label:['Some icon'],
                    id:'icon'
                }

            ]
        });

    })

</script>
