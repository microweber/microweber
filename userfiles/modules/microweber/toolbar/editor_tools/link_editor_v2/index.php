<script>
    mw.require("forms.js");
    mw.require("files.js");
    mw.require("instruments.js");
</script>

<script>
    var currentValue = {};

    mw.ComponentInput = function (data) {
        currentValue = data;
        if(!data) return;
        if(data.targetBlank) {
            $('#url_target').attr('checked', data.targetBlank);
        }
        if(data.url) {
            mw.$('#customweburl').val(data.url);
        }
        if(data.text){
            mw.$('#link-text').val(data.text)
        }
        if(data.targetBlank){
            mw.$('#url_target')[0].checked = data.targetBlank;
        }

        if(data.url){
            Output(currentValue)
        }
    };

    function Output(data) {
        var val = $.extend({}, currentValue, data );
        val.targetBlank = document.getElementById('url_target').checked;
        currentValue = val;
        if(data && data.text) {
            mw.$('#link-text').val(data.text);
        }
        if(mw.ComponentOutput) {
            mw.ComponentOutput(val.url ? val : null);
        } else {
            setTimeout(function (val) {
                mw.ComponentOutput(val.url ? val : null);
            }, 333, val)
        }
    }

    mw.ComponentConfigInitial = false;
    mw.ComponentConfig = function (options) {
        mw.ComponentConfigInitial = true;
        if(typeof options.target !== 'undefined'){
            document.getElementById('target-holder').style.display = options.target ? 'block' : 'none';
        }
        if(typeof options.text !== 'undefined'){
            document.getElementById('link-text-holder').style.display = options.text ? 'block' : 'none';
        }
        if(options.controllers){
            var all = mw.$('.mw-ui-btn-vertical-nav [data-ctype]').hide();
            options.controllers = options.controllers.split(',');
            $.each(options.controllers, function () {
                all.filter('[data-ctype="'+this.trim()+'"]').show();
            });
            all.filter(':visible:first').click();
        }
        if(options.values){
            mw.ComponentInput(options.values);
        }
    };

    var defaults = {
        // controllers: 'page, custom, content, file, email, section, layout'
        controllers: 'page, custom, content, file, section, layout'

    };


    mw.on('ComponentReady', function () {
        setTimeout(function () {
            if(!mw.ComponentConfigInitial) {
                mw.ComponentConfig(defaults)
            }
        }, 333);
    });


    var _created = false;
    var createFilePicker = function () {
        if(!_created){
            _created = true;
            var filepicker = mw.instruments.file({
                mode: 'inline',
                types: 'files'
            });
            mw.$('#file_section').append(filepicker.frame);
            filepicker.handler.on('change', function (e, url) {
                console.log(999, url)
                var filename = url.split('/').pop();

                Output({
                    url: url,
                    text: filename
                });
                delete currentValue.object;
            })
        }
    };

    var is_searching = false;
    var hash = location.hash.replace(/#/g, "") || 'insert_link';
    var dd_autocomplete = function (id) {
        var el = $(id);
        el.on("change input focus", function (event) {
            if (!is_searching) {
                var val = el.val();
                if (event.type === 'focus') {
                    if (el.hasClass('inactive')) {
                        el.removeClass('inactive')
                    }
                    else {
                        return false;
                    }
                }
                // is_active: 'y' fails + is a limit required?
                mw.tools.ajaxSearch({keyword: val, limit: 20}, function () {
                    var lis = [];
                    var json = this;
                    var createLi = function(obj){
                        var li = document.createElement('li');
                        li.className = 'mw-dd-list-result';
                        li.onclick = function (ev) {
                            Output({
                                url: obj.url,
                                text: obj.title,
                                object: obj
                            });
                        };
                        li.innerHTML = "<a href='javascript:;'>" + obj.title + "</a>";
                        return li;
                    };
                    for (var item in json) {
                        var obj = json[item];
                        if (typeof obj === 'object') {
                            lis.push(createLi(obj));
                        }
                    }
                    var ul = el.parent().find("ul");
                    ul.find("li:gt(0)").remove();
                    ul.append(lis);
                });
            }
        });
    };

    $(document).ready(function () {

        mw.tools.dropdown();

        dd_autocomplete('#dd_pages_search');



        $("#email_field").on('input', function () {
            var val = this.value.replace(/\s/g, '');
            if ( val.indexOf('mailto:') === -1 ){
                val = 'mailto:' + val;
            }
            Output({
                url: val
            });
        });
        $("#insert_from_dropdown").click(function () {
            var val = mw.$("#insert_link_list").getDropdownValue();
            Output({
                url: val
            });
            return false;
        });
        LinkTabs = mw.tabs({
            nav: ".mw-ui-btn-vertical-nav a",
            tabs: ".tab",
            onclick: function(tab){
                createFilePicker();
                $(tab).find('input:first').focus();
            }
        });
        $('#url_target').on('input', function () {
            Output(currentValue);
        });
    });


</script>


<style type="text/css">

    [data-ctype]{
        display: none;
    }

    #insert_link_list .mw-dropdown-content{
        position: relative;
    }

    #tabs .tab {
        display: none;
    }

    #mw-popup-insertlink {
        overflow:hidden;
    }


    .media-search-holder .mw-dropdown-content { position: relative; }

    .mw-ui-box-content {
        padding-top: 20px;
    }

    #insert_link_list {
        width: 100%;
    }



    #available_elements {
        max-height: 400px;
        overflow: auto;
        border: 1px solid #eee;
    }

    #available_elements a {
        display: block;
        padding: 10px 12px;
        cursor: pointer;
    }

    #available_elements a:hover {
        background-color: #EEEEEE;
    }

    #available_elements a + a {
        border-top: 1px solid #eee;
    }
    #available_elements a.active{
        background-color: #009cff;
        color:#fff
    }

    #insert_link .mw-dropdown-content{
        position: relative;
    }
    .link-tree{
        padding-top: 10px;
    }

</style>


<div id="mw-popup-insertlink">

    <div class="mw-ui-field-holder" id="link-text-holder">
        <div class="mw-field w100" size="large">
            <input type="text" placeholder="Link text" id="link-text" oninput="Output({text: this.value})">
        </div>
    </div>
    <div class="mw-ui-field-holder" id="target-holder">
        <div class="mw-full-width">
            <label class="mw-ui-check mw-clear">
                <input type="checkbox" id="url_target"><span></span><span><?php _e("Open link in new window"); ?></span>
            </label>
        </div>
    </div>

    <div class="mw-flex-row">
        <div class="mw-flex-col-xs-4 mw-ui-btn-vertical-nav">
            <a class="mw-ui-btn" href="javascript:;" data-ctype="custom"><?php _e("Website URL"); ?></a>
            <a class="mw-ui-btn" href="javascript:;" data-ctype="page"><?php _e("Page from My Website"); ?></a>

            <a class="mw-ui-btn" href="javascript:;" data-ctype="content"><?php _e("Post"); ?>, <?php _e("Category"); ?></a>
            <a class="mw-ui-btn" href="javascript:;" data-ctype="file"><?php _e("File"); ?></a>
            <a class="mw-ui-btn" href="javascript:;" data-ctype="email"><?php _e("Email"); ?></a>
            <a class="mw-ui-btn available_elements_tab_show_hide_ctrl" href="javascript:;" data-ctype="section"><?php _e("Page Section"); ?></a>
            <a class="mw-ui-btn page-layout-btn" style="display: none;" href="javascript:;" data-ctype="layout"><?php _e("Page Layout"); ?></a>
        </div>
        <div class="mw-flex-col-xs-8 mw-ui-box mw-ui-box-content" id="tabs">

            <div class="tab" style="display: block" data-ctype="custom">
                <div class="media-search-holder">
                    <div class="mw-field w100" data-before="URL">
                        <input type="text" id="customweburl"  placeholder="http://..."/>
                    </div>
                </div>
            </div>
            <div class="tab" data-ctype="page">
                <?php
                $unique = uniqid('link-tree-');
                ?>
                <div class="mw-field">
                    <span class="mw-field-prepend"><i class="mw-icon-magnify"></i></span>
                    <input id="link-tree-search" type="text" placeholder="<?php _e('Search'); ?>">
                </div>
                <div class="link-tree" id="<?php print $unique; ?>"></div>
                <script>
                    mw.require('tree.js')
                </script>
                <script>
                    var pagesTreeRefresh = function(){
                        $.get("<?php print api_url('content/get_admin_js_tree_json'); ?>", function(data){
                            pagesTree = new mw.tree({
                                data: data,
                                element: $("#<?php print $unique; ?>")[0],
                                sortable: false,
                                selectable: true,
                                singleSelect: true,
                                filterRemoteURL: '<?php print api_url('content/get_admin_js_tree_json'); ?>'
                            });
                            pagesTreeData = [...data];
                            $(pagesTree).on("selectionChange", function(e, selection){
                                var obj = selection[0];
                                if(obj) {
                                    Output({
                                        url: obj.url,
                                        text: obj.title,
                                        object: obj
                                    });
                                }

                            });
                            $(pagesTree).on("ready", function(){
                                $('#link-tree-search').on('input', function(){
                                    var val = this.value.toLowerCase().trim();
                                    pagesTree.filter(val)
                                });
                            })
                        });
                    };

                    mw.$(document).ready(function () {
                        pagesTreeRefresh();
                        mw.$('#customweburl').on('input', function () {

                            Output({
                                url: this.value.trim()
                            });
                            delete currentValue.object;
                        })
                    });

                </script>
            </div>

            <div class="tab" data-ctype="content">
                <div class="media-search-holder">
                    <div data-value="<?php print site_url(); ?>" id="insert_link_list" class="mw-dropdown mw-dropdown-default active">
                        <input type="text" class="mw-ui-field inactive" id="dd_pages_search" autocomplete="off" placeholder="<?php _e("Click to select"); ?>"/>
                        <span class="mw-icon-dropdown"></span>
                        <div class="mw-dropdown-content">
                            <ul class="">
                                <li class="other-action" value="-1" style="display: none;"></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab" data-ctype="file">
                <div class="media-search-holder">
                    <div id="file_section"></div>
                </div>
            </div>
            <div class="tab" data-ctype="email">
                <div class="media-search-holder">
                    <div class="mw-field w100" data-before="Email">
                        <input type="text" class="mw-ui-field" id="email_field" placeholder="mail@example.com"/>
                    </div>
                </div>
            </div>
            <div class="tab available_elements_tab_show_hide_ctrl" data-ctype="section">

                <div id="available_elements"></div>
                <script>
                    $(document).ready(function () {
                        var available_elements_tab_show_hide_ctrl_counter = 0;
                        var html = [];
                        mw.top().$("h1[id],h12[id],h3[id],h4[id],h5[id],h6[id]", top.document.body).each(function () {
                            available_elements_tab_show_hide_ctrl_counter++;
                            html.push({id: this.id, text: this.textContent});
                            mw.$('#available_elements').append('<a data-href="#' + this.id + '"><strong>' + this.nodeName + '</strong> - ' + this.textContent + '</a>')
                        });
                        mw.$('#available_elements a').on('click', function () {
                            Output({
                                url: mw.top().win.location.href.split('#')[0] + $(this).dataset('href')
                            })
                            mw.$('#available_elements a').removeClass('active')
                            mw.$(this).addClass('active')
                        });
                        if (!available_elements_tab_show_hide_ctrl_counter) {
                            mw.$('.available_elements_tab_show_hide_ctrl').hide();
                        }
                    })
                </script>
            </div>
            <div class="tab page-layout-tab" data-ctype="layout">

                <ul class="mw-ui-box mw-ui-box-content mw-ui-navigation" id="layouts-selector">

                </ul>
                <script>
                    submitLayoutLink = function(){
                        var selected = mw.$('#layouts-selector input:checked');
                        var val = mw.top().win.location.href.split('#')[0] + '#mw@' + selected[0].id;
                        Output({
                            url: val.trim()
                        });
                    };
                    $(document).ready(function () {
                        var layoutsData = [];
                        var layouts = mw.top().$('.module[data-type="layouts"]');
                        layouts.each(function () {
                            layoutsData.push({
                                name: (this.getAttribute('template') || this.dataset.template || '').split('.')[0],
                                element: this,
                                id: this.id
                            })
                        });
                        var list = $('#layouts-selector');
                        $.each(layoutsData, function(){
                            var radio = '<input type="radio" name="layoutradio" id="' + this.id +' "><span></span>';
                            var li = $('<li><label class="mw-ui-check">' + radio + ' ' + this.name + '</label></li>');
                            var el = this.element;
                            li.find('input').on('click', function(){
                                mw.top().tools.scrollTo(el);
                                submitLayoutLink()
                            });
                            list.append(li);
                        });
                        if(layoutsData.length > 0){
                            $('.page-layout-btn').show()
                        }
                    });
                </script>

            </div>
        </div>
    </div>
</div>
