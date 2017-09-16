<?php only_admin_access(); ?>

<div class="module-live-edit-settings">
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

        #testimonials-list .ui-sortable-placeholder{
          background: rgba(204, 199, 191, 1);
          outline: 1px dotted rgba(186, 192, 216, 1);
          min-height: 100px;
          visibility: visible !important;
        }

        #testimonials-list .ui-sortable-helper td + td *{
          color: transparent !important;
        }

        #testimonials-list .ui-sortable-helper td {
            display: inline-block;
            border: none;

        }

        .ttab {
            display: none;
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
            width: 100px;
            border-left-width: 1px;
            border-left-style: solid;
        }

        #ttabnav {
            width: 100%;
        }

        .testimonial-client-image {
            max-width: 100px;
            max-height: 100px;
        }

    </style>
    <script>
        $(document).ready(function () {
            window.TTABS = window.TTABS || mw.tabs({
                    nav: "#ttabnav a",
                    tabs: ".ttab"
                });

        });


        function saveChanges() {
            $('form#add-testimonial-form').submit()
        }

    </script>
    <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs" id="ttabnav">
        <a class="mw-ui-btn active" href="javascript:;"><?php _e('Explore'); ?></a>

<!--        <a class="mw-ui-btn saveButton" href="javascript:;" onclick="saveChanges()" id="add-testimonial">--><?php //_e('Save'); ?><!--</a>-->
        <a class="mw-ui-btn addNewButton" href="javascript:;" onclick="add_testimonial()" id="add-testimonial">+ <?php _e('Add new'); ?></a>

        <a class="mw-ui-btn" href="javascript:;"><?php _e('Options'); ?></a>

    </div>
    <div class="mw-ui-box mw-ui-box-content">
        <div class="ttab" style="display: block;">
            <module type="testimonials/list" id="list-testimonials"/>
        </div>
        <div class="ttab">
            <module type="testimonials/edit" id="edit-testimonials" edit-id="0"/>
        </div>
        <div class="ttab">
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
            <?php

            /*

             <div class="mw-ui-row-nodrop">
               <div class="mw-ui-col">
                 <div class="mw-ui-col-container">
                   <label class="mw-ui-label"><strong>Open</strong> Quote Image</label>
                   <div class="previewquote" id="openquote-preview">
                     <?php if($openquote != false and $openquote != ''){  ?>
                     <img src="<?php print $openquote;  ?>" style="max-width:60px;max-height:60px;" alt="" />
                     <?php  } ?>
                   </div>
                   <span class="mw-ui-btn" id="openquote"><span class="mw-icon-upload"></span>Select Image</span>
                   <input type="hidden" class="mw_option_field" name="openquote" data-option-group="fourtestimonials" value="<?php if($openquote != false and $openquote != ''){  ?><?php print $openquote;  ?><?php  } ?>" />
                 </div>
               </div>
               <div class="mw-ui-col">
                 <div class="mw-ui-col-container">
                   <label class="mw-ui-label"><strong>Close</strong> Quote Image</label>
                   <div class="previewquote" id="closequote-preview">
                     <?php if($openquote != false and $openquote != ''){  ?>
                     <img src="<?php print $openquote;  ?>" style="max-width:60px;max-height:60px;" alt="" />
                     <?php  } ?>
                   </div>
                   <span class="mw-ui-btn" id="closequote"><span class="mw-icon-upload"></span>Select Image</span>
                   <input type="hidden" class="mw_option_field" name="closequote" data-option-group="fourtestimonials" value="<?php if($openquote != false and $openquote != ''){  ?><?php print $openquote;  ?><?php  } ?>" />
                 </div>
               </div>
             </div>
             <hr>


            */


            ?>
            <div class="mw-ui-field-holder">
                <label class="mw-ui-label"><?php _e('Show testimonials for project'); ?></label>
                <module type="testimonials/project_select" id="project-select-testimonials" option-group="<?php print $params['id'] ?>"/>
            </div>
            <div class="mw-ui-field-holder">
                <label class="mw-ui-label"><?php _e('Maximum number of testimonials to display'); ?></label>
                <input type="text" class="mw-ui-field mw-ui-field-medium mw_option_field" name="testimonials_limit" value="<?php print $testimonials_limit; ?>" style="width:50px;text-align: center"/>
            </div>
            <div class="mw-ui-field-holder">
                <label class="mw-ui-label"><?php _e('Maximum number of characters to display'); ?></label>
                <input type="text" class="mw-ui-field mw-ui-field-medium mw_option_field" name="limit" value="<?php print $limit; ?>" style="width:50px;text-align: center"/>
            </div>
            <module type="admin/modules/templates" simple=true/>
        </div>
    </div>
</div>
