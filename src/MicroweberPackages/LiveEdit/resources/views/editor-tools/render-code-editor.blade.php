<div>


    <style>


        .CodeMirror,
        #select_edit_field_wrap {
            height: 100%;
        }

        .htmleditliframe {
            width: 100%;
            height: 120px;
            overflow: hidden;
            position: relative;
        }

        .htmleditliframe:after {
            position: absolute;
            content: '';
            display: block;
            z-index: 1;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .htmleditliframe iframe {
            overflow: hidden;
            width: 300%;
            height: 360px;
            transform: scale(.33333);
            transform-origin: 0 0;
            pointer-events: none;
        }


        .CodeMirror, #select_edit_field_wrap {
            height: calc(100vh - 55px) !important;
        }

        .tab-content .tab-content .CodeMirror, #select_edit_field_wrap {
            height: calc(100vh - 55px) !important;
        }

        .htmleditliframe {
            width: 100%;
            height: 120px;
            overflow: hidden;
            position: relative;
        }

        .htmleditliframe:after {
            position: absolute;
            content: '';
            display: block;
            z-index: 1;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .htmleditliframe iframe {
            overflow: hidden;
            width: 300%;
            height: 360px;
            transform: scale(.33333);
            transform-origin: 0 0;
            pointer-events: none;
        }


    </style>


    <script>

        var targetWindow = mw.top().app.canvas.getWindow();
        var targetDocument = mw.top().app.canvas.getDocument();

        mw.lib.require('codemirror');
        var html_code_area_editor2

        var setHtmlToNode = false;
        var $time_out_handle = 0, html_code_area_editor;

        function setEditorContent() {
            if (setHtmlToNode) {
                var htmlOrigClone = '';
                var htmlOrig = setHtmlToNode.innerHTML;
                var origId = setHtmlToNode.getAttribute('id');

                //var htmlOrigCloneNode = Object.assign({}, setHtmlToNode);
                // var htmlOrigCloneNode = setHtmlToNode ;

                var hasClone = false;
                var htmlOrigCloneNode = false;

                const original = targetDocument.getElementById(origId);
                if (original) {
                    hasClone = true;
                    htmlOrigCloneNode = original.cloneNode(true);

                }


                //relalce .module with [module]
                if (hasClone && htmlOrigCloneNode) {
                    htmlOrigCloneNode.querySelectorAll('.module').forEach(function (el) {

                        el.innerHTML = '[module]'


                    });


                    htmlOrigClone = htmlOrigCloneNode.innerHTML;
                    html_code_area_editor2.setValue(htmlOrigClone);
                } else {
                    html_code_area_editor2.setValue(htmlOrig);
                }


                // html_code_area_editor2.setValue(htmlOrig);
                html_code_area_editor2.refresh();
            } else {
                // disable editor
                html_code_area_editor2.setValue('');
                html_code_area_editor2.refresh();


            }
        }

        function applyHtmlEdit2() {
            var custom_html_code_mirror = document.getElementById("html_code_area_editor2")
            var val = $(custom_html_code_mirror).val();

            if (setHtmlToNode) {
                setHtmlToNode.innerHTML = val;


                var modules_ids = {};
                var modules_list = $('.module', setHtmlToNode);


                $(modules_list).each(function () {
                    var id = $(this).attr('id');
                    if (id) {
                        id = '#' + id;
                    } else {
                        id = $(this).attr('data-type');
                    }
                    if (!id) {
                        id = $(this).attr('type');
                    }
                    modules_ids[id] = true;
                });


                $.each(modules_ids, function (index, value) {
                    targetWindow.mw.reload_module(index);
                });


                mw.top().app.registerChangedState(setHtmlToNode);
            }


        }

        function format_code2() {
            html_code_area_editor2.setSelection({
                    'line': html_code_area_editor2.firstLine(),
                    'ch': 0,
                    'sticky': null
                }, {
                    'line': html_code_area_editor2.lastLine(),
                    'ch': 0,
                    'sticky': null
                },
                {scroll: false});
            //auto indent the selection
            html_code_area_editor2.indentSelection("smart");

            html_code_area_editor2.setSelection({
                    'line': html_code_area_editor2.firstLine(),
                    'ch': 0,
                    'sticky': null
                }, {
                    'line': html_code_area_editor2.firstLine(),
                    'ch': 0,
                    'sticky': null
                },
                {scroll: false});

        }


        $(document).ready(function () {

            html_code_area_editor2 = CodeMirror.fromTextArea(document.getElementById("html_code_area_editor2"), {
                lineNumbers: true,
                gutter: false,
                lineWrapping: true,
                matchTags: {bothTags: true},

                extraKeys: {"Ctrl-Space": "autocomplete"},
                mode: {
                    name: "text/html", globalVars: true
                }
            });

            html_code_area_editor2.setSize("100%", "auto");
            html_code_area_editor2.setOption("theme", 'material');
            html_code_area_editor2.on("change", function (cm, change) {
                $('#html_code_area_editor2').val(cm.getValue());

            });


        });


        mw.top().app.canvas.on('canvasDocumentClick', function () {
            var activeNode = mw.top().app.liveEdit.getSelectedNode();
            var can = mw.top().app.liveEdit.canBeElement(activeNode)
            if (!can) {
                setHtmlToNode = false;
            } else {
                setHtmlToNode = activeNode;
            }
            setEditorContent();
        });

    </script>


    <div id="custom_html_code_mirror_container">
<textarea class="form-select w100" dir="ltr" id="html_code_area_editor2" rows="30">


</textarea>
    </div>


    <div class="mw-css-editor-c2a-nav">
        <div class="btn-group btn-block" role="group">

            <?php /*        <button onclick="format_code2();"  class="btn btn-outline-primary" type="button"><?php _e('Format code'); ?></button>
*/ ?>
            <span onclick="applyHtmlEdit2();" class="btn btn-dark" type="button"><?php _e('Update'); ?></span>
        </div>
    </div>

</div>
