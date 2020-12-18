<?php only_admin_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="admin-side-content">
    <script>mw.lib.require('font_awesome5');</script>

    <style type="text/css">
        #testimonials-list tbody tr {
            cursor: move;
            cursor: -moz-grab;
            cursor: -webkit-grab;
            cursor: grab;
        }

        #testimonials-list.dragging {
            cursor: -moz-grabbing;
            cursor: -webkit-grabbing;
            cursor: grabbing;
        }

        #testimonials-list .ui-sortable-helper {
            width: 100% !important;
            display: block;
            background: white;
        }

        #testimonials-list .ui-sortable-placeholder {
            background: rgba(204, 199, 191, 1);
            outline: 1px dotted rgba(186, 192, 216, 1);
            min-height: 100px;
            visibility: visible !important;
        }

        #testimonials-list .ui-sortable-helper td + td * {
            color: transparent !important;
        }

        #testimonials-list .ui-sortable-helper td {
            display: inline-block;
            border: none;

        }

        .previewquote {
            display: block;
            width: 60px;
            height: 60px;
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #efecec;
            margin-bottom: 12px;
        }

        #add-testimonial {
            position: absolute;
            right: 0;
            top: 0;
            border-left-width: 1px;
            border-left-style: solid;
        }

        .testimonial-client-image {
            max-width: 100px;
            max-height: 100px;
        }

        .mw-ui-btn-nav-tabs .mw-ui-btn:nth-child(2) {
            display: none;
        }

    </style>

    <script>
        function saveChanges() {
            $('form#add-testimonial-form').submit()
        }
    </script>

    <?php

    $limit = get_option('limit', $params['id']);
    $testimonials_limit = get_option('testimonials_limit', $params['id']);


    if ($limit == false or $limit == '') {
        $limit = 250;
    }


    $interval = get_option('interval', $params['id']);

    if ($interval == false or $interval == '') {
        $interval = 5;
    }

    if ($interval < 0.2) {
        $interval = 0.2;
    }

    $openquote = get_option('openquote', $params['id']);
    $closequote = get_option('closequote', $params['id']);
    ?>

    <script>
        $(document).ready(function () {
            OpenQuote = mw.uploader({
                filetypes: "images",
                element: "#openquote",
                multiple: false
            });
            CloseQuote = mw.uploader({
                filetypes: "images",
                element: "#closequote",
                multiple: false
            });

            $(OpenQuote).bind("FileUploaded", function (a, b) {
                mw.$("#openquote-preview img").attr("src", b.src);
                mw.$("[name='openquote']").val(b.src).trigger('change');
            });
            $(CloseQuote).bind("FileUploaded", function (a, b) {
                mw.$("#closequote-preview img").attr("src", b.src);
                mw.$("[name='closequote']").val(b.src).trigger('change');
            });

        });
    </script>


    <div class="mw-modules-tabs <?php if ($from_live_edit): ?><?php else: ?><?php endif; ?>">
        <div class="mw-accordion-item js-list-testimonials">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mw-icon-navicon-round"></i> List of Testimonials
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                <div class="mw-ui-field-holder">
                    <a class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded" href="javascript:;" onclick="add_new_testimonial()" id="add-testimonial"><i class="fas fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?></a>
                </div>

                <module type="testimonials/list" id="list-testimonials"/>
            </div>
        </div>

        <div class="mw-accordion-item js-add-new-testimonials">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mw-icon-plus-round"></i> Add New / Edit
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                <div class="mw-ui-field-holder js-add-new-button">
                    <a class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded" href="javascript:;" onclick="add_testimonial()" id="add-testimonial"><i class="fas fa-plus-circle"></i> &nbsp;<?php _e('Add new'); ?></a>
                </div>

                <div class="">
                    <module type="testimonials/edit" id="edit-testimonials" edit-id="0"/>
                </div>
            </div>
        </div>

        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-testimonials-settings">
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label"><?php _e('Show testimonials for project'); ?></label>
                        <module type="testimonials/project_select" id="project-select-testimonials" option-group="<?php print $params['id'] ?>"/>
                    </div>
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label"><?php _e('Maximum number of testimonials to display'); ?></label>
                        <input type="text" class="mw-ui-field mw-full-width mw_option_field" name="testimonials_limit" value="<?php print $testimonials_limit; ?>"/>
                    </div>
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label"><?php _e('Maximum number of characters to display'); ?></label>
                        <input type="text" class="mw-ui-field mw-full-width mw_option_field" name="limit" value="<?php print $limit; ?>"/>
                    </div>
                </div>
                <!-- Settings Content - End -->
            </div>
        </div>

        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mw-icon-beaker"></i> <?php print _e('Templates'); ?>
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>

</div>