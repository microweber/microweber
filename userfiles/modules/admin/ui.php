<script>
    mw.require("<?php print mw_includes_url(); ?>css/ui.css");
</script>

<style scoped="scoped">
    #ui-info-table {
        width: 100%;
        max-width: 800px;
        table-layout: fixed;
        border: 1px solid #eee;
        margin: 0;
        border-collapse: collapse;
        margin-bottom: 150px;
    }

    #ui-info-table > tbody > tr > td,
    #ui-info-table > thead > tr > th {
        border: 1px solid #eee;
        padding: 20px;
    }

    #ui-info-table > tbody > tr {
        display: none;
    }

    #info-icon-list li {
        list-style: none;
        float: left;
        text-align: center;
        padding: 20px;
        vertical-align: middle;
        cursor: default;
        width: 150px;
        height: 115px;
    }

    #info-icon-list li:hover {
        color: white;
        background: black
    }

    #info-icon-list li em {
        display: block;
        text-align: center;
        font-style: normal;
        padding: 3px 0 10px 0;
    }

    #info-icon-list li span {
        font-size: 41px;
    }

    .demobox {
        position: relative;
        padding: 20px 0;
        max-width: 600px;
    }

    .demobox:after {
        content: ".";
        display: block;
        clear: both;
        visibility: hidden;
        line-height: 0;
        height: 0;
    }

    .demof1 .demobox .mw-ui-field {
        float: left;
        margin-right: 6px;
    }

    #apinav a {
        display: block;
        padding: 2px;
    }

    #apinav a:hover {
        background: #F0F0F0
    }

    #texample-box,
    #texample-box2,
    #texample-box3 {
        width: 300px;
        height: 200px;
        background: rgba(102, 102, 102, 1);
        margin: 20px auto;
        padding: 80px;
        text-align: center;
    }


</style>


<script>


    $(window).load(function () {
        var uicss = mwd.querySelector('link[href*="/ui.css"]').sheet.cssRules, l = uicss.length, i = 0, html = '';
        var admincss = mwd.querySelector('link[href*="/admin.css"]').sheet.cssRules, al = admincss.length, ai = 0;
        html += '<hr><h3>Admin Icons</h3>'
        for (; ai < al; ai++) {
            var sel = admincss[ai].selectorText;
            if (!!sel && sel.indexOf('.mai-') === 0) {
                var cls = sel.replace(".", '').split(':')[0];
                html += '<li><span class="' + cls + '"></span><em>.' + cls + '</em></li>';
            }
        }

        html += '<hr><h3>UI Icons</h3>'

        for (; i < l; i++) {
            var sel = uicss[i].selectorText;
            if (!!sel && sel.indexOf('.mw-icon-') === 0) {
                var cls = sel.replace(".", '').split(':')[0];
                html += '<li><span class="' + cls + '"></span><em>.' + cls + '</em></li>';
            }
        }

        mw.$('#info-icon-list').html('<ul>' + html + '</ul>');

        mw.$("#ui-info-table h2").each(function (i) {
            var el = this;
            var li = mwd.createElement('li');
            li.innerHTML = "<a href='#?uisection=" + i + "'>" + this.innerHTML + "</a>";

            $("#apinav").append(li)
        });


    });
    mw.on.hashParam('uisection', function () {
        var el = mw.$("#ui-info-table h2")[this]
        if (this) {
            mw.tools.scrollTo(el);
            mw.$("#ui-info-table tbody > tr:visible:first").hide();
            $(mw.tools.firstParentWithTag(el, 'tr')).show();
            mw.$(".mw-tooltip-mwexample").remove()
        }
    })

</script>


<div style="position: fixed;right:0;top:20px;padding:20px;border:1px solid #eee;width: 300px;" id="adminapis">
    <h2><?php _e('Admin apis'); ?></h2>

    <h3><?php _e('Tooltip'); ?></h3>
    <h4>Data - tip text</h4>
    <pre class="tip" data-tip="Some help" data-tipposition="top-center">&lt;div class="tip" data-tip="Some help" data-tipposition="top-center">&lt;/div></pre>
    <h4>Data - tip Selector - '.' and '#' are available</h4>
    <pre class="tip" data-tip=".demobox" data-tipposition="top-left">&lt;div class="tip" data-tip=".demobox" data-tipposition="top-left">&lt;/div></pre>


    <br><br>

    <ol class="mw-ui-box" id="apinav" style="height: 300px;overflow-y: auto;padding-left: 25px;"></ol>

</div>


<table width="800" id="ui-info-table">
    <col width="60%"/>
    <col width="40%"/>
    <tbody>

    <tr>
        <td colspan="2">
            <h2><?php _e('Icons'); ?></h2>


            <div id="info-icon-list"></div>


        </td>
    </tr>
    <tr>
        <td colspan="2" id="dabuttons">
            <h2><?php _e('Buttons'); ?></h2>

            <script>

                __to = function () {
                    if ($("#dabuttons .mw-ui-btn-outline").length === 0) {

                        $("#dabuttons .mw-ui-btn").addClass('mw-ui-btn-outline');
                    }
                    else {

                        $("#dabuttons .mw-ui-btn").removeClass('mw-ui-btn-outline');
                    }

                }

            </script>

            <span class="mw-ui-btn" onclick="__to();"><?php _e('Toggle Outline Type'); ?></span>


            <h3>Default</h3>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small">Small</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium">Medium</a>
            <a href="javascript:;" class="mw-ui-btn">Normal</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big">Big</a>

            <h3>Invert</h3>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-invert">Small</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert">Medium</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-invert">Normal</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-invert">Big</a>

            <h3>Info</h3>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-info">Small</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info">Medium</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-info">Normal</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-info">Big</a>

            <h3>Warn</h3>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-warn">Small</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-warn">Medium</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-warn">Normal</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-warn">Big</a>

            <h3>Important</h3>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-important">Small</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-important">Medium</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-important">Normal</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-important">Big</a>

            <h3>Notification</h3>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-notification">Small</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification">Medium</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-notification">Normal</a>
            <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-notification">Big</a>

            <h3>Button with icon</h3>
            <div class="demobox">


                <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-notification"><span class="mai-website"></span>Small</a>
                <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification"><span class="mai-website"></span>Medium</a>
                <a href="javascript:;" class="mw-ui-btn mw-ui-btn-notification"><span class="mai-website"></span>Normal</a>
                <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-notification"><span class="mai-website"></span>Big</a>

                <br>

                <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-notification"><span class="mw-icon-gear"></span></a>
                <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification"><span class="mw-icon-gear"></span></a>
                <a href="javascript:;" class="mw-ui-btn mw-ui-btn-notification"><span class="mw-icon-gear"></span></a>
                <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-notification"><span class="mw-icon-gear"></span></a>

            </div>


            <h3>Button Navigations</h3>
            <div class="demobox">
                <div class="mw-ui-btn-nav">
                    <a href="javascript:;" class="mw-ui-btn">Home</a>
                    <a href="javascript:;" class="mw-ui-btn active">About</a>
                    <a href="javascript:;" class="mw-ui-btn">Contact</a>
                </div>
            </div>

            <div class="demobox">
                <h4>Fluid</h4>
                <ul class="mw-ui-btn-nav mw-ui-btn-nav-fluid">
                    <li><a href="javascript:;" class="mw-ui-btn">Home</a></li>
                    <li><a href="javascript:;" class="mw-ui-btn active">About</a></li>
                    <li><a href="javascript:;" class="mw-ui-btn">Contact</a></li>
                </ul>
            </div>
            <div class="demobox">
                <div class="mw-ui-btn-vertical-nav">
                    <a href="javascript:;" class="mw-ui-btn">Vertical</a>
                    <a href="javascript:;" class="mw-ui-btn active">Button</a>
                    <a href="javascript:;" class="mw-ui-btn">Navigation</a>
                </div>
            </div>


            <h3>Button Tabs Navigations</h3>
            <div class="demobox" id="demotabsnav">
                <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
                    <a href="javascript:;" class="mw-ui-btn active">Home</a>
                    <a href="javascript:;" class="mw-ui-btn">About</a>
                    <a href="javascript:;" class="mw-ui-btn">Contact</a>
                </div>
                <div class="mw-ui-box">
                    <div class="mw-ui-box-content">Home - Lorem Ipsum</div>
                    <div class="mw-ui-box-content" style="display: none">About - Lorem Ipsum</div>
                    <div class="mw-ui-box-content" style="display: none">Contact - Lorem Ipsum</div>
                </div>
            </div>


            <script>
                $(document).ready(function () {
                    mw.tabs({
                        nav: '#demotabsnav .mw-ui-btn-nav-tabs a',
                        tabs: '#demotabsnav .mw-ui-box-content'
                    });
                });
            </script>

        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h2>Boxes</h2>
            <div class="demobox">


                <?php
                $boxClasses = array('', 'mw-ui-box-invert', 'mw-ui-box-info', 'mw-ui-box-warn', 'mw-ui-box-important', 'mw-ui-box-notification');

                foreach ($boxClasses as $bx) {

                    ?>
                    <br>
                    <div class="mw-ui-box mw-ui-box-content <?php print $bx; ?>"> <?php print lipsum(); ?></div>

                <?php } ?>


            </div>
            <div class="demobox">
                <?php foreach ($boxClasses as $bx) { ?>
                    <br>
                    <div class="mw-ui-box  <?php print $bx; ?> ">
                        <div class="mw-ui-box-header"><span class="mw-icon-gear"></span><span>Box with header and icon</span></div>
                        <div class="mw-ui-box-content"><?php print lipsum(); ?></div>
                    </div>

                <?php } ?>

            </div>
        </td>

    </tr>
    <tr>
        <td colspan="2">
            <h2>Tables</h2>
            <table cellspacing="0" cellpadding="0" class="mw-ui-table" width="100%">
                <tbody>
                <tr>
                    <td>Lorem</td>
                    <td>Ipsum</td>
                    <td>Sit</td>
                    <td>Amet</td>
                    <td>Dolor</td>
                    <td>987987</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Lorem</td>
                    <td>Ipsum</td>
                    <td>Sit</td>
                    <td>Amet</td>
                    <td>Dolor</td>
                    <td>987987</td>
                    <td></td>
                </tr>
                </tbody>
            </table>
            <h3>Table with header</h3>
            <table cellspacing="0" cellpadding="0" class="mw-ui-table" width="100%">
                <thead>
                <tr>
                    <th>Table</th>
                    <th>Head</th>
                    <th>Client</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Orders</th>
                    <th>View</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Lorem</td>
                    <td>Ipsum</td>
                    <td>Sit</td>
                    <td>Amet</td>
                    <td>Dolor</td>
                    <td>987987</td>
                    <td><a href="javascript:;" class="show-on-hover mw-ui-btn mw-ui-btn-invert mw-ui-btn-medium">View on hover</a></td>
                </tr>
                </tbody>
            </table>


            <h3>Table with header and footer</h3>
            <table cellspacing="0" cellpadding="0" class="mw-ui-table" width="100%">
                <thead>
                <tr>
                    <th>Table</th>
                    <th>Head</th>
                    <th>Client</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Orders</th>
                    <th>View</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td>Table</td>
                    <td>Footer</td>
                    <td>Client</td>
                    <td>Country</td>
                    <td>City</td>
                    <td>Orders</td>
                    <td>View</td>
                </tr>
                </tfoot>
                <tbody>
                <tr>
                    <td>Lorem</td>
                    <td>Ipsum</td>
                    <td>Sit</td>
                    <td>Amet</td>
                    <td>Dolor</td>
                    <td>987987</td>
                    <td></td>
                </tr>
                </tbody>
            </table>


            <h3>Simple clean table</h3>
            <table cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic" width="100%">
                <thead>
                <tr>
                    <th>Table</th>
                    <th>Head</th>
                    <th>Client</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Orders</th>
                    <th>View</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td>Table</td>
                    <td>Footer</td>
                    <td>Client</td>
                    <td>Country</td>
                    <td>City</td>
                    <td>Orders</td>
                    <td>View</td>
                </tr>
                </tfoot>
                <tbody>
                <tr>
                    <td>Lorem</td>
                    <td>Ipsum</td>
                    <td>Sit</td>
                    <td>Amet</td>
                    <td>Dolor</td>
                    <td>987987</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Lorem</td>
                    <td>Ipsum</td>
                    <td>Sit</td>
                    <td>Amet</td>
                    <td>Dolor</td>
                    <td>987987</td>
                    <td></td>
                </tr>
                </tbody>
            </table>


            <h3>New table style</h3>
            <table cellspacing="0" cellpadding="0" class="mw-ui-table table-style-2" width="100%">
                <thead>
                <tr>
                    <th>Table</th>
                    <th>Head</th>
                    <th>Client</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Orders</th>
                    <th>View</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Lorem</td>
                    <td>Ipsum</td>
                    <td>Sit</td>
                    <td>Amet</td>
                    <td>Dolor</td>
                    <td>987987</td>
                    <td><a href="javascript:;" class="show-on-hover mw-ui-btn mw-ui-btn-invert mw-ui-btn-medium">View on hover</a></td>
                </tr>
                <tr>
                    <td>Lorem</td>
                    <td>Ipsum</td>
                    <td>Sit</td>
                    <td>Amet</td>
                    <td>Dolor</td>
                    <td>987987</td>
                    <td><a href="javascript:;" class="show-on-hover mw-ui-btn mw-ui-btn-invert mw-ui-btn-medium">View on hover</a></td>
                </tr>
                <tr>
                    <td>Lorem</td>
                    <td>Ipsum</td>
                    <td>Sit</td>
                    <td>Amet</td>
                    <td>Dolor</td>
                    <td>987987</td>
                    <td><a href="javascript:;" class="show-on-hover mw-ui-btn mw-ui-btn-invert mw-ui-btn-medium">View on hover</a></td>
                </tr>
                </tbody>
            </table>

        </td>


    </tr>


    <tr>
        <td colspan="2">
            <h2>Progress Bars</h2>


            <div class="demobox">
                <div class="mw-ui-progress">
                    <div style="width: 33%;" class="mw-ui-progress-bar"></div>
                    <div class="mw-ui-progress-info">Uploading</div>
                    <span class="mw-ui-progress-percent">33%</span>
                </div>
            </div>
            <div class="demobox">
                <div class="mw-ui-progress-small">
                    <div class="mw-ui-progress-bar" style="width: 40%">

                    </div>
                </div>
            </div>


            <div class="demobox">
                <div id="progressdemo"></div>
                <span class="mw-ui-btn" onclick="progressdemo()">Run progress demo</span>
                <script>

                    progressdemo = function () {
                        var pd = mwd.getElementById('progressdemo');
                        var prg = mw.progress({
                            action: 'Loading',
                            element: pd
                        });
                        var k = 0;
                        for (var i = 0; i <= 100; i++) {
                            setTimeout(function () {
                                prg.set(k++);
                                if (k === 100) {
                                    prg.remove();
                                }
                            }, i * 20)

                        }
                    }

                </script>
            </div>

        </td>

    </tr>

    <tr>
        <td colspan="2">
            <h2>Form elements</h2>

            <div class="demobox">
                <label class="mw-switch">
                    <input
                            type="checkbox"
                            name="enabled"
                            data-value-checked="1"
                            data-value-unchecked="0"
                    >
                    <span class="mw-switch-off">OFF</span>
                    <span class="mw-switch-on">ON</span>
                    <span class="mw-switcher"></span>
                </label>
            </div>

            <div class="demobox">
                <label class="mw-switch mw-switch-action">
                    <input
                            type="checkbox"
                            name="enabled"
                            data-value-checked="1"
                            data-value-unchecked="0"
                    >
                    <span class="mw-switch-off">Unpublished</span>
                    <span class="mw-switch-on">Published</span>
                    <span class="mw-switcher"></span>
                </label>
            </div>

            <div class="demobox">
                <label class="mw-switch mw-switch-action">
                    <input
                            type="checkbox"
                            name="enabled"
                            data-value-checked="1"
                            data-value-unchecked="0"
                    >
                    <span class="mw-switch-off">Progressive Rock</span>
                    <span class="mw-switch-on">Progressive House</span>
                    <span class="mw-switcher"></span>
                </label>
            </div>
            <div class="demobox">
                <label class="mw-ui-label">Field</label>
                <input type="text" class="mw-ui-field"/>

            </div>
            <div class="demobox">
                <label class="mw-ui-label">Textarea</label>
                <textarea class="mw-ui-field"></textarea>
            </div>
            <h3>Field sizes and fields with buttons</h3>
            <div class="demof1">
                <div class="demobox">
                    <label class="mw-ui-label">Small</label>
                    <input type="text" class="mw-ui-field mw-ui-field-small"/>
                    <select class="mw-ui-field mw-ui-field-small">
                        <option>Option 1</option>
                        <option>Option 2</option>
                    </select>
                    <span class="mw-ui-btn mw-ui-btn-small"><span class="mw-icon-magnify"></span>Button</span>
                </div>
                <div class="demobox">
                    <label class="mw-ui-label">Medium</label>
                    <input type="text" class="mw-ui-field mw-ui-field-medium"/>
                    <select class="mw-ui-field mw-ui-field-medium">
                        <option>Option 1</option>
                        <option>Option 2</option>
                    </select>
                    <span class="mw-ui-btn mw-ui-btn-medium"><span class="mw-icon-magnify"></span>Button</span>
                </div>
                <div class="demobox">
                    <label class="mw-ui-label">Normal</label>
                    <input type="text" class="mw-ui-field"/>
                    <select class="mw-ui-field">
                        <option>Option 1</option>
                        <option>Option 2</option>
                    </select>
                    <span class="mw-ui-btn"><span class="mw-icon-magnify"></span>Button</span>
                </div>
                <div class="demobox">
                    <label class="mw-ui-label">Big</label>
                    <input type="text" class="mw-ui-field mw-ui-field-big"/>
                    <select class="mw-ui-field mw-ui-field-big">
                        <option>Option 1</option>
                        <option>Option 2</option>
                    </select>
                    <span class="mw-ui-btn mw-ui-btn-big"><span class="mw-icon-magnify"></span>Button</span>
                </div>
            </div>

        </td>

    </tr>
    <tr>
        <td colspan="2">
            <h3>Pure CSS radio buttons and checkboxes </h3>
            <div class="demobox">
                <label class="mw-ui-check">
                    <input type="checkbox" checked="checked"/>
                    <span></span>
                    <span>Checkbox 1</span>
                </label>
                <label class="mw-ui-check">
                    <input type="checkbox"/>
                    <span></span>
                    <span>Checkbox 2</span>
                </label>
            </div>
            <div class="demobox">
                <label class="mw-ui-check">
                    <input type="radio" name="demoradio1" checked="checked"/>
                    <span></span>
                    <span>Radio 1</span>
                </label>
                <label class="mw-ui-check">
                    <input type="radio" name="demoradio1"/>
                    <span></span>
                    <span>Radio 2</span>
                </label>
            </div>
        </td>
    </tr>
    <tr>

        <td colspan="2">
            <h2>Hover Dropdown menus</h2>
            <div class="mw-ui-row">
                <div class="mw-ui-col" style="width: 120px;">
                    <div class="mw-ui-dropdown">
                        <span>No tyles</span>
                        <div class="mw-ui-dropdown-content">
                            Some option
                        </div>
                    </div>

                </div>
                <div class="mw-ui-col">
                    <div class="mw-ui-dropdown">
                        <span class="mw-ui-btn">Button navigation</span>
                        <div class="mw-ui-dropdown-content">
                            <div class="mw-ui-btn-vertical-nav">
                                <span class="mw-ui-btn">Option 1</span>
                                <span class="mw-ui-btn">Option 2</span>
                                <span class="mw-ui-btn">Option 3</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mw-ui-col">
                    <div class="mw-ui-dropdown">
                        <span class=" mw-ui-btn mw-ui-btn-big mw-ui-btn-info">Big Button navigation</span>
                        <div class="mw-ui-dropdown-content">
                            <div class="mw-ui-btn-vertical-nav">
                                <span class="mw-ui-btn mw-ui-btn-big">Option 1</span>
                                <span class="mw-ui-btn mw-ui-btn-big">Option 2</span>
                                <span class="mw-ui-btn mw-ui-btn-big">Option 3</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </td>

    </tr>

    <tr>
        <td colspan="2">
            <h2>Dropdowns onclick</h2>
            <div class="demobox">

                <style scoped="scoped">

                    .demobox {
                        max-width: none;
                    }

                    .mw-dropdown {
                        width: 150px;
                        margin-right: 10px;
                    }


                </style>

                <div class="mw-dropdown mw-dropdown-default">
                    <span class="mw-dropdown-value mw-ui-btn mw-ui-btn-warn mw-ui-btn-big mw-dropdown-val">Choose</span>
                    <div class="mw-dropdown-content">
                        <ul>
                            <li value="1">Option 1</li>
                            <li value="2">Option 2 !!!</li>
                            <li value="3">Option 3</li>
                        </ul>
                    </div>
                </div>
                <div class="mw-dropdown mw-dropdown-default">
                    <span class="mw-dropdown-value mw-ui-btn mw-ui-btn-info mw-dropdown-val">Choose</span>
                    <div class="mw-dropdown-content">
                        <ul>
                            <li value="1">Option 1</li>
                            <li value="2">Option 2 !!!</li>
                            <li value="3">Option 3</li>
                        </ul>
                    </div>
                </div>
                <div class="mw-dropdown mw-dropdown-default">
                    <span class="mw-dropdown-value mw-ui-btn mw-ui-btn-medium mw-dropdown-val">Choose</span>
                    <div class="mw-dropdown-content">
                        <ul>
                            <li value="1">Option 1</li>
                            <li value="2">Option 2 !!!</li>
                            <li value="3">Option 3</li>
                        </ul>
                    </div>
                </div>

                <div class="mw-dropdown mw-dropdown-default" data-value="2">
                    <span class="mw-dropdown-value mw-ui-btn mw-ui-btn-small mw-ui-btn-invert mw-dropdown-val">Choose</span>
                    <div class="mw-dropdown-content">
                        <ul>
                            <li value="1"><a href="javascript:;">Option 1</a></li>
                            <li value="2"><a href="javascript:;">Option 2</a></li>
                            <li value="3"><a href="javascript:;">Option 3</a></li>
                        </ul>
                    </div>
                </div>


                <script>
                    mw.dropdown();
                </script>
            </div>

        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h2>Rich-text Editor</h2>
            <div id="editor-demo">

                <p>Lorem ipsu<em>m dolor sit amet, conse</em>ctetur adipiscing elit. <strong>Sed quis <u>orci pla</u>cer</strong>at, tristique nibh nec, rhoncus libero.</p>

            </div>


            <h3>Editor options and methods</h3>


            Note: the editor must NOT be inside a hidden (display:'none') element.
            <pre>
    mw.editor({
        element:''
        height:320,
        width:'100%',
        addControls: false
        hideControls:false,
        ready: function(){

        }
     })
</pre>


            <ul>
                <li><strong>element</strong>: - <strong>Required</strong>: represents the source element(for example: &lt;textarea>Some content&lt;/textarea>&lt;div>Some content&lt;/div>) - Node
                    element or String (Selector)
                </li>
                <li><strong>height</strong>: - <strong>Optional</strong> <strong>Number</strong> or <strong>String</strong> in CSS Syntax - Default: 320</li>
                <li><strong>width</strong>: - <strong>Optional</strong> <strong>Number</strong> or <strong>String</strong> in CSS Syntax - Default: '100%'</li>
                <li><strong>addControls</strong>: - <strong>Optional</strong>. Represents Element/s that will be appended to the editor's control bar. <strong>String</strong> or <strong>Node</strong>
                    or <strong>function</strong> that returns node - Default: false
                </li>
                <li><strong>hideControls</strong> - <strong>Optional</strong>. <strong>Array</strong>. Controls to hide. Example ['bold', 'italic', 'format', 'ol', 'ul'] - Default false</li>
                <li><strong>ready</strong>: - <strong>Optional</strong>. <strong>Function</strong> to be executed after editor is loaded. Dedault: false</li>
            </ul>


            <br><br>


            <h3>Editor API</h3>
            <p>The function
            <pre>mw.editor </pre>
            returns an iframe node that represents the editor. It has 'atached' one event, one function and a API object.
            </p>
            <h4>Example:</h4>
            <pre>
    myEditor = mw.editor({ element: '#some-div-or-textarea' });

    // 'change' event
    $(myEditor).bind('change', function(){
       console.log( this.value )
    });

    // function setValue, must be used after editor is ready
    // Sets value of the editor nd triggers the change event
    myEditor.setValue('Some &lt;b>value&lt;/b>');
</pre>
            <p> // API myEditor.api - The 'API' object is a refference to mw.wysiwyg object that is inside the editor </p>
            <pre>
            Examples:

            myEditor.api.insert_html('some text or html') - inserts html
            myEditor.api.link()                           - opens link popup
            myEditor.api.unlink()                         - removes link from selection
            myEditor.api.fontSize(16)                     - sets font size in pixels
            myEditor.api.fontColor('#efecec')             - sets font color
            myEditor.api.fontbg('#efecec')                - sets font background color

             ...
          </pre>

            <p> Complete Example:</p>

            <p> The function 'EditorRandomIMG' will insert random image in the selection inside the editor (If selection exists) </p>
            <pre>
             EditorRandomIMG = function(){
               myEditor.api.insert_html('&lt;p>&lt;img src="//lorempixel.com/400/200/nature/" />&lt;/p>');
             }


       </pre>
            <script>
                $(document).ready(function () {

                    DemoEditor = mw.editor({
                        element: '#editor-demo',
                        height: 250,
                        width: '650',
                        addControls: false,
                        hideControls: false,
                        ready: function () {
                            mw.log('Editor is ready');
                        }
                    });


                })


                DemoEditorRandomIMG = function () {
                    DemoEditor.api.insert_html('<p><img src="//lorempixel.com/400/200/nature/?' + mw.random() + '" /></p>');
                }

            </script>


            <span class="mw-ui-btn mw-ui-btn-info" onclick="DemoEditorRandomIMG();event.preventDefault();">Insert Random Image</span>

        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h2>Navigations</h2>


            <ul class="mw-ui-navigation" style="width: 150px;">
                <li><a href="javascript:;" class="active">Home</a></li>
                <li><a href="javascript:;">About</a>
                    <ul>
                        <li><a href="javascript:;">Lorem Ipsum</a></li>
                        <li><a href="javascript:;">Etiam condimentum</a>
                            <ul>
                                <li><a href="javascript:;">Lorem Ipsum</a></li>
                                <li><a href="javascript:;">Etiam condimentum</a></li>
                                <li><a href="javascript:;" class="active">Sed aliquam erat id mauri</a></li>
                                <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
                                <li><a href="javascript:;">Cras interdum enim dolor</a></li>
                                <li><a href="javascript:;">Vestibulum porta eros purus</a></li>
                            </ul>
                        </li>
                        <li><a href="javascript:;" class="active">Sed aliquam erat id mauri</a></li>
                        <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
                        <li><a href="javascript:;">Cras interdum enim dolor</a></li>
                        <li><a href="javascript:;">Vestibulum porta eros purus</a></li>
                    </ul>
                </li>
                <li><a href="javascript:;">Blog</a></li>
                <li><a href="javascript:;">Forum</a></li>
                <li><a href="javascript:;">Help</a></li>
                <li><a href="javascript:;">Contacts</a></li>
            </ul>
            <br><br>
            <ul class="mw-ui-box mw-ui-navigation" style="width: 150px;">
                <li><a href="javascript:;" class="active">Home</a></li>
                <li>
                    <a href="javascript:;">About</a>
                    <ul>
                        <li><a href="javascript:;">Lorem Ipsum</a></li>
                        <li><a href="javascript:;">Etiam condimentum</a>
                            <ul>
                                <li><a href="javascript:;">Lorem Ipsum</a></li>
                                <li><a href="javascript:;">Etiam condimentum</a></li>
                                <li><a href="javascript:;" class="active">Sed aliquam erat id mauri</a></li>
                                <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
                                <li><a href="javascript:;">Cras interdum enim dolor</a></li>
                                <li><a href="javascript:;">Vestibulum porta eros purus</a></li>
                            </ul>
                        </li>
                        <li><a href="javascript:;" class="active">Sed aliquam erat id mauri</a></li>
                        <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
                        <li><a href="javascript:;">Cras interdum enim dolor</a></li>
                        <li>
                            <a href="javascript:;">Vestibulum porta eros purus</a>

                        </li>
                    </ul>
                </li>
                <li><a href="javascript:;">Blog</a></li>
                <li><a href="javascript:;">Forum</a></li>
                <li><a href="javascript:;">Help</a></li>
                <li><a href="javascript:;">Contacts</a></li>
            </ul>
            <br><br>
            <ul class="mw-ui-navigation mw-ui-navigation-horizontal">
                <li><a href="javascript:;" class="active">Home<span class="mw-icon-gear"></span></a></li>
                <li>
                    <a href="javascript:;">About <span class="mw-icon-dropdown"></span></a>
                    <ul>
                        <li><a href="javascript:;">Lorem Ipsum</a></li>
                        <li>
                            <a href="javascript:;">Etiam condimentum</a>
                            <ul>
                                <li><a href="javascript:;">Lorem Ipsum</a></li>
                                <li><a href="javascript:;">Etiam condimentum</a></li>
                                <li><a href="javascript:;" class="active">Sed aliquam erat id mauri</a></li>
                                <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
                                <li><a href="javascript:;">Cras interdum enim dolor</a></li>
                                <li><a href="javascript:;">Vestibulum porta eros purus</a></li>
                            </ul>
                        </li>
                        <li><a href="javascript:;" class="active"><span class="mw-icon-gear"></span> Sed aliquam erat id mauri</a></li>
                        <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
                        <li><a href="javascript:;">Cras interdum enim dolor</a></li>
                        <li><a href="javascript:;">Vestibulum porta eros purus</a></li>
                    </ul>
                </li>
                <li><a href="javascript:;">Blog</a></li>
                <li><a href="javascript:;">Forum</a></li>
                <li><a href="javascript:;">Help</a></li>
                <li><a href="javascript:;">Contacts</a></li>
            </ul>
            <br><br> <br><br><br><br>
            <ul class="mw-ui-box mw-ui-navigation mw-ui-navigation-horizontal">
                <li>
                    <a href="javascript:;" class="active">Home</a>
                    <ul>
                        <li><a href="javascript:;">Lorem Ipsum</a></li>
                        <li>
                            <a href="javascript:;">Etiam condimentum</a>
                            <ul>
                                <li><a href="javascript:;">Lorem Ipsum</a></li>
                                <li><a href="javascript:;">Etiam condimentum</a></li>
                                <li><a href="javascript:;" class="active">Sed aliquam erat id mauri</a></li>
                                <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
                                <li><a href="javascript:;">Cras interdum enim dolor</a></li>
                                <li><a href="javascript:;">Vestibulum porta eros purus</a></li>
                            </ul>
                        </li>
                        <li><a href="javascript:;" class="active">Sed aliquam erat id mauri</a></li>
                        <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
                        <li><a href="javascript:;">Cras interdum enim dolor</a></li>
                        <li><a href="javascript:;">Vestibulum porta eros purus</a></li>
                    </ul>
                </li>
                <li><a href="javascript:;">About</a></li>
                <li><a href="javascript:;">Blog</a></li>
                <li><a href="javascript:;">Forum</a></li>
                <li>
                    <a href="javascript:;">Help</a>
                    <ul>
                        <li><a href="javascript:;" class="active">Lorem Ipsum</a></li>
                        <li><a href="javascript:;">Etiam condimentum</a></li>
                        <li><a href="javascript:;">Sed aliquam erat id mauri</a></li>
                        <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
                        <li><a href="javascript:;">Cras interdum enim dolor</a></li>
                        <li><a href="javascript:;">Vestibulum porta eros purus</a></li>
                    </ul>
                </li>
                <li><a href="javascript:;">Contacts</a></li>

            </ul>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h2>Inline radios and</h2>
            <ul class="mw-ui-inline-list">
                <li><span>Choose</span></li>
                <li>
                    <label class="mw-ui-check">
                        <input type="radio" value="pending" name="order_status" checked="checked">
                        <span></span><span>Option 1</span>
                    </label>
                </li>
                <li>
                    <label class="mw-ui-check">
                        <input type="radio" value="completed" name="order_status">
                        <span></span><span>Option 2</span>
                    </label>
                </li>
            </ul>
            <hr>
            <ul class="mw-ui-inline-list">
                <li><span>Choose</span></li>
                <li>
                    <label class="mw-ui-check">
                        <input type="checkbox" value="pending" name="order_status1" checked="checked">
                        <span></span><span>Option 1</span>
                    </label>
                </li>
                <li>
                    <label class="mw-ui-check">
                        <input type="checkbox" value="completed" name="order_status1">
                        <span></span><span>Option 2</span>
                    </label>
                </li>
            </ul>

        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h2>Modal Window</h2>

            <span class="mw-ui-btn" onclick="mw.modal({})">Default</span>

            <span class="mw-ui-btn" onclick="mw.modal({template:'basic'})">Simple</span>

            <span class="mw-ui-btn" onclick="mw.modalFrame({url:'http://google.com'});">Iframe</span>
            <span class="mw-ui-btn" onclick="modalAPIEXAMPLE()">API EXAMPLE</span>

            <div id="modalAPIEXAMPLE" style="display: none">


                <div class="mw-ui-btn-nav">
                    <span class="mw-ui-btn" onclick="MODALAPI.resize(Math.floor(Math.random()*(700-150+1)+150),Math.floor(Math.random()*(700-150+1)+150))">Resize</span>
                    <span class="mw-ui-btn" onclick="MODALAPI.center()">Center</span>
                    <span class="mw-ui-btn" onclick="MODALAPI.resize(Math.floor(Math.random()*(700-150+1)+150),Math.floor(Math.random()*(700-150+1)+150)).center()">Resize and Center</span>

                </div>

            </div>

            <script>

                modalAPIEXAMPLE = function () {
                    MODALAPI = mw.modal({content: $('#modalAPIEXAMPLE').html()});
                }

            </script>
        </td>
    </tr>

    <tr>
        <td colspan="2">
            <h2>Gallery</h2>

            <script>

                GArrayExample = [];

                for (var i = 1; i <= 10; i++) {
                    GArrayExample.push({
                        img: 'http://lorempixel.com/1000/1000/nature/?' + mw.random(),
                        description: 'Some description for image <b>' + i + '</b>'
                    });
                }

            </script>

            <span class="mw-ui-btn" onclick="mw.gallery(GArrayExample)"> Click to launch </span>

        </td>
    </tr>

    <tr>
        <td colspan="2">
            <h2>Accordion</h2>

            <div class="mw-ui-row">
                <div class="mw-ui-col" style="width: 140px;">
                    <div class="mw-ui-col-container">
                        <div id="accordion-example" onclick="mw.accordion(this);">
                            Basic example
                            <div class="mw-accordion-content">
                                Lorem Ipsum
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <div id="accordion-example2" class="mw-ui-box">
                            <div class="mw-ui-box-header" onclick="mw.accordion('#accordion-example2');">Another Example</div>
                            <div class="mw-accordion-content mw-ui-box-content">
                                Lorem Ipsum
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mw-ui-col">
                    <div class="mw-ui-col-container">
                        <div id="accordion-example3" class="mw-ui-box mw-ui-box-silver-blue active">
                            <div class="mw-ui-box-header" onclick="mw.accordion('#accordion-example3');">
                                <div class="header-holder">
                                    <i class="mai-setting2"></i> Another Example
                                </div>
                            </div>
                            <div class="mw-accordion-content mw-ui-box-content">
                                Lorem Ipsum
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mw-ui-col" style="width: 150px;">
                    <div class="mw-ui-col-container"><span class="mw-ui-btn pull-right" onclick="mw.accordion('#accordion-example2');">Remote control</span></div>
                </div>
            </div>


        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h2>Notification Bubbles</h2>


            <span class="mw-ui-btn" onclick="mw.notification.success('Awesome');">Success</span>
            <span class="mw-ui-btn" onclick="mw.notification.error('Critical');">Error</span>
            <span class="mw-ui-btn" onclick="mw.notification.warning('Beware');">Warning</span>


        </td>
    </tr>
    <tr>
        <td colspan="2">
            <style scoped="scoped">

                .mw-paging {
                    float: left;
                    margin: 10px;
                }

                .mw-paging-small {
                    clear: both;
                }

            </style>
            <h2>Paging</h2>


            <?php

            $types = array('', 'invert', 'info', 'warn', 'important', 'notification');

            foreach ($types as $type) {


                $types2 = array('small', 'medium', '', 'big');

                foreach ($types2 as $type2) {

                    ?>


                    <div class="mw-paging mw-paging-<?php print $type2; ?> mw-paging-<?php print $type; ?>">
                        <a href="javascript:;">1</a>
                        <a href="javascript:;" class="active">2</a>
                        <a href="javascript:;">3</a>

                    </div>


                <?php } ?>
            <?php } ?>

        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h2>Tooltip</h2>


            <div id="exampleholder">


                <br><br>


                <h3>Default usage</h3>

                <pre>

    mw.tooltip({
        content: 'Tooltip &lt;b>content&lt;/b>',
        element: document.querySelector('#some-element')
    });

   </pre>

                <h3>Parameters</h3>

                <div class="op"><h4>content</h4>


                    <p>Required - 'String' or Node element or jQuery object, </p>

                </div>


                <div class="op"><h4>element</h4>


                    <p>Required - String selector, Node element or jQuery object, </p>
                    <p>Element on which the tooltip will be applied</p>

                </div>

                <div class="op">
                    <h4>position</h4>

                    <p> Optional - 'String' - Position of the tooltip on the element. </p>
                    <p>Available options are: </p>
                    <ul>
                        <li>'bottom-left'</li>
                        <li>'bottom-center'</li>
                        <li>'bottom-right'</li>
                        <li>'top-left'</li>
                        <li>'top-center'</li>
                        <li>'top-right'</li>
                        <li>'left-top'</li>
                        <li>'left-bottom'</li>
                        <li>'left-center'</li>
                        <li>'right-top'</li>
                        <li>'right-bottom'</li>
                        <li>'right-center'</li>
                    </ul>
                </div>

                <div class="op"><h4>skin</h4>


                    <p>Optional - String </p>
                    <p>Ads a class to the wrapper of the tooltip, so you can shape it easily. Default skin is 'mw-tooltip-default'</p>


                </div>


                <h3>Examples</h3>


                <div id="texample-box">
                    <span class="mw-ui-btn mw-ui-btn-info" onclick="TooltipExample1()"> Example 1</span>
                </div>

                <br>
                <hr>
                <br>

                <div id="texample-box2">
                    <span class="mw-ui-btn mw-ui-btn-info" onclick="TooltipExample2()"> Example 2</span>
                </div>

                <br>
                <hr>
                <br>

                <div id="texample-box3">
                    <span class="mw-ui-btn mw-ui-btn-info" onclick="TooltipExample3()"> Example 3</span>
                </div>

                <br>
                <hr>
                <br>

                <div id="test-content" style="display: none">

                    <div style="width:350px;">
                        <iframe width="350" height="220" frameborder="0" allowfullscreen="" src="https://www.youtube.com/embed/4zjeKq1l5Wk"></iframe>
                        <h3>Kwon O Chen - Playing the Dragon</h3>
                        <p>First track from the compilation album - 'Asia Chill Out Lounge'</p></div>


                </div>

                <script>

                    $(document).ready(function () {
                        var positions = ['bottom-left', 'bottom-center', 'bottom-right', 'top-right', 'top-left', 'top-center', 'left-top', 'left-bottom', 'left-center', 'right-top', 'right-bottom', 'right-center'],
                            texample = document.getElementById('texample-box'),
                            texample2 = document.getElementById('texample-box2');
                        texample3 = document.getElementById('texample-box3');

                        TooltipExample1 = function () {

                            $.each(positions, function () {

                                var tip = mw.tooltip({
                                    content: this.toString(),
                                    element: texample,
                                    position: this
                                })

                                $(tip).addClass('mw-tooltip-mwexample');


                            });

                        }

                        TooltipExample2 = function () {
                            $.each(positions, function () {
                                var tip = mw.tooltip({
                                    content: this.toString(),
                                    element: texample2,
                                    position: this,
                                    skin: 'dark'
                                });
                                $(tip).addClass('mw-tooltip-mwexample');
                            });

                        }

                        TooltipExample3 = function () {

                            var tip = mw.tooltip({
                                content: mw.$("#test-content").html(),
                                element: texample3,
                                position: 'right-center'
                            });
                            $(tip).addClass('mw-tooltip-mwexample');

                        }


                    })

                </script>

            </div>


        </td>
    </tr>
    <tr>
        <td colspan="2">

            <h2>Color Picker</h2>


            <script>

                $(window).load(function () {
                    pick1 = mw.colorPicker({
                        element: '#ttest',
                        position: 'bottom-left',
                        onchange: function (color) {
                            $("h2").css("color", color);
                        }
                    });
                    pick2 = mw.colorPicker({
                        element: '#resr',
                        onchange: function (color) {
                            $("#mw-admin-main-menu").css("background", color);
                        }
                    });
                    pick3 = mw.colorPicker({
                        element: '#resr2',
                        onchange: function (color) {
                            $("#mw-admin-main-menu").css("background", color);
                        }
                    });
                });

            </script>
            <div style="padding: 50px;">

                <h5>Button trigger</h5>
                <span class="mw-ui-btn mw-ui-btn-info" id="ttest">Choose Color</span>
                <hr>


                <h5>Field</h5>
                <input class="mw-ui-field" id="resr2" placeholder="Eneter color..">

                <hr>
                <h5>Inline</h5>
                <div id="resr" class="mw-ui-box" style="display: inline-block"></div>


            </div>


        </td>
    </tr>
    </tbody>
</table>



