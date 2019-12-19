<?php only_admin_access(); ?>
<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="mw-ui mw-ui-box-content">
    <div class="mw-ui-box mw-ui-box-content">
        <h2><?php _e("Mail Templates"); ?></h2>
        <br/>
        <div class="mw-ui-row">
            <div class="mw-ui-col">
                <div class="mw-ui-col-container">

                    <script>
                        function edit_mail_template(template_id) {
                            $('#list-mail-templates').slideUp();

                            // append edit
                            $('#list-mail-templates').after('<div type="admin/mail_templates/edit" data_template_id="' + template_id + '" id="edit-mail-template"></div>');
                            mw.reload_module("#edit-mail-template");
                        }
                    </script>

         <!--           <a class="mw-ui-btn" href="javascript:edit_mail_template('');">
                        Create new template
                    </a>
                    <br/>
                    <br/>-->


                    <?php
$mail_template_type = '';
                    if(isset($params['mail_template_type'])){
                        $mail_template_type  =$params['mail_template_type'];
                    }
                    ?>


                    <module type="admin/mail_templates/list" mail_template_type="<?php print $mail_template_type ?>" id="list-mail-templates"/>

                </div>
            </div>
        </div>
    </div>
</div>