

<div class="form-check">
    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
    <label class="form-check-label" for="flexCheckDefault">
        Default checkbox
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
    <label class="form-check-label" for="flexCheckChecked">
        Checked checkbox
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" type="checkbox" value="" id="flexCheckDisabled" disabled>
    <label class="form-check-label" for="flexCheckDisabled">
        Disabled checkbox
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
    <label class="form-check-label" for="flexRadioDefault1">
        Default radio
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
    <label class="form-check-label" for="flexRadioDefault2">
        Default checked radio
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" type="checkbox" value="" id="flexCheckCheckedDisabled" checked disabled>
    <label class="form-check-label" for="flexCheckCheckedDisabled">
        Disabled checked checkbox
    </label>
</div>

<div class="form-check">
    <input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
    <label class="form-check-label" for="flexCheckIndeterminate">
        Indeterminate checkbox
    </label>
</div>


<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
    <label class="form-check-label" for="flexSwitchCheckDefault">Default switch checkbox input</label>
</div>
<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
    <label class="form-check-label" for="flexSwitchCheckChecked">Checked switch checkbox input</label>
</div>
<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDisabled" disabled>
    <label class="form-check-label" for="flexSwitchCheckDisabled">Disabled switch checkbox input</label>
</div>
<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckCheckedDisabled" checked disabled>
    <label class="form-check-label" for="flexSwitchCheckCheckedDisabled">Disabled checked switch checkbox input</label>
</div>



<div class="card bg-none style-1 mb-0 card-settings">
    <div class="card-header px-0">
        <h5>
            <i class="mdi mdi-file-cabinet text-primary mr-3"></i> <strong>Files</strong>
        </h5>

    </div>
</div>

<div class="mw-filemanager-actions-bar">
    <div>

        <button type="button" class="btn btn-success icon-left"><i class="mdi mdi-plus"></i> Add</button>
        <button type="button" class="btn btn-danger icon-left"><i class="mdi mdi-trash-can-outline"></i> Delete</button>
        <button type="button" class="btn btn-primary icon-left"><i class="mdi mdi-cloud-download"></i> Download</button>

    </div>
    <div>
        <div class="form-inline">
            <div class="form-group mr-1 mb-2">
                <input type="search" class="form-control" placeholder="Search">
            </div>
            <button type="submit" class="btn btn-outline-primary mb-2">Search</button>
        </div>
    </div>
</div>

<div class="card style-1 bg-light mb-3">
    <div class="card-body pt-3"><div id="fm"></div></div>
</div>



<script>mw.require('filemanager.js')</script>



<script>
    mw.FileManager({element:'#fm', selectable: true, multiselect: true})
</script>


<br>
<br>
<br>
<br>
<br>

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

            <button class="mw-ui-btn" onclick="mw.dialog({title:'Dialog title', height: 'auto', content:'Test content'});">Default</button>
            <button class="mw-ui-btn" onclick="mw.dialogIframe({ url: 'https://www.youtube.com/embed/L0gakjjel3E' });">Iframe</button>
            <button class="mw-ui-btn" onclick="mw.dialogIframe({ url: '<?php print site_url(); ?>', autoHeight: true });">Iframe Auto height</button>


        </div>


        <script>
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
