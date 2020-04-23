<?php if (is_live_edit()): ?>
    <!-- Make sure jQuery, jQuery UI, font awesome, and bootstrap 4 are included. TinyMCE is optional. -->
    <link rel="stylesheet" type="text/css" href="<?php print modules_url(); ?>grid/plugin/grideditor.css" />
    <script src="<?php print modules_url(); ?>grid/plugin/jquery.grideditor.js"></script>

    <script>
        //        mw.lib.require('grid_editor');

        $(document).ready(function () {
            $('.mw-edit-grid').on('click', function () {
                $('#<?php print $params['id']; ?> .mw-grid').removeClass('edit');
                $('#<?php print $params['id']; ?> .mw-save-grid').show();
                $(this).hide();

                $('#<?php print $params['id']; ?> .mw-grid').gridEditor({
                    new_row_layouts: [
                        [12],
                        [1, 11],
                        [2, 10],
                        [3, 9],
                        [4, 8],
                        [5, 7],
                        [6, 6],
                        [7, 5],
                        [8, 4],
                        [9, 3],
                        [10, 2],
                        [11, 1]
                    ],
                    row_classes: [{label: 'Example class', cssClass: 'example-class'}],
                    col_classes: [{label: 'Allow drop', cssClass: 'allow-drop'}]
                });
            });

            $('.mw-save-grid').on('click', function () {
                $('#<?php print $params['id']; ?> .mw-grid').addClass('edit');
                $('#<?php print $params['id']; ?> .mw-edit-grid').show();
                $(this).hide();

                // Call this to get the result after the user has done some editing:
                var html = $('#<?php print $params['id']; ?> .mw-grid').gridEditor('getHtml');
                $('#<?php print $params['id']; ?> .mw-grid').gridEditor('remove');

                $('.mw-grid-holder .edit').addClass('changed');
            });
        });
    </script>

    <style>
        .ge-mainControls .ge-addRowGroup{
            max-width: 100%;
            overflow: scroll;
        }
        .mw-grid{
            clear: both;
        }
        .mw-grid > div {
            padding: 3px;
            background: indianred;
            background-origin: content-box;

        }

        .mw-grid > div > div {
            padding: 3px;
            background: lightskyblue;
            background-origin: content-box;

        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="javascript:;" class="mw-save-grid btn btn-success" style="display: none;">Save Grid</a>
                <a href="javascript:;" class="mw-edit-grid btn btn-info">Edit Grid</a>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row m-t-30 mw-grid-holder">
    <div class="col-md-12">
        <div class="mw-grid edit safe-mode nodrop" field="grid-holder-<?php print $params['id'] ?>" rel="module">
            <div class="row">
                <div class="col-sm allow-drop">
                    <p>Lorem ipsum dolores</p>
                </div>
                <div class="col-sm allow-drop">
                    <p>Lorem ipsum dolores</p>
                </div>
                <div class="col-sm allow-drop">
                    <p>Lorem ipsum dolores</p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm allow-drop">
                    <p>Lorem ipsum dolores</p>
                </div>
                <div class="col-sm allow-drop">
                    <p>Lorem ipsum dolores</p>
                </div>
                <div class="col-sm allow-drop">
                    <p>Lorem ipsum dolores</p>
                </div>
            </div>
        </div>
    </div>
</div>