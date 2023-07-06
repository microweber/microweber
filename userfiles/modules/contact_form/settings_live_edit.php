<?php
include(__DIR__ . '/settings_javascript.php');
?>

<?php
$mod_id = $params['id'];
if (isset($params['for_module_id'])) {
    $mod_id = $params['for_module_id'];
}
?>

<script>
    mw.lib.require('flag_icons');
</script>


<script>

  function changeBackground() {
    var modal = document.getElementById("myModal");
    modal.classList.add("background-color-white");


  }
</script>

<div id="form_email_options">



    <!-- Button to trigger the modal -->
    <button type="button" onclick="changeBackground();" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">Open Modal</button>

    <!-- Modal -->
    <div class="modal fade modal-right-pane" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header sticky-top px-0 pb-2">
                    <a class="mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-decoration-none" data-bs-dismiss="modal" aria-label="Close">
                        <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                            <g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                                <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>
                                <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                            </g>
                        </svg>
                        <span class="ms-1 font-weight-bold"><?php _e('Back'); ?></span>
                    </a>

                    <h5 class="modal-title modal-title col text-center" id="exampleModalLabel"><?php _e('Modal Title'); ?></h5>

                </div>
                <div class="modal-body">
                    <p>Modal content goes here...</p>
                    <p>Modal content goes here...</p>
                    <p>Modal content goes here...</p>
                    <p>Modal content goes here...</p>
                    <p>Modal content goes here...</p>
                    <p>Modal content goes here...</p>
                    <p>Modal content goes here...</p>
                    <p>Modal content goes here...</p>
                    <p>Modal content goes here...</p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link mw-admin-action-links mw-adm-liveedit-tabs fs-3 me-2" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-link mw-admin-action-links mw-adm-liveedit-tabs fs-3 ms-2">Save</button>
                </div>
            </div>
        </div>
    </div>


    <div class="row d-flex align-items-center w-100">
        <div class="col">
            <h5 class="font-weight-bold settings-title-inside"><?php _e("Contact form settings") ?></h5>
        </div>
        <div class="col text-right">
            <a class="btn-link" href="<?php print admin_url('settings?group=email') ?>" target="_blank"> <?php _e("Sending options"); ?></a>
        </div>
    </div>

    <div class="accordion my-2" id="contact-form-custom-fields-accordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-1">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#contact-form-custom-fields-accordion-1" aria-expanded="true">
                    <?php _e("Form fields"); ?>
                </button>
            </h2>
            <div id="contact-form-custom-fields-accordion-1" class="accordion-collapse collapse" data-bs-parent="#contact-form-custom-fields-accordion" style="">
                <div class="accordion-body pt-0">
                    <module type="custom_fields" view="admin" data-for="module" for-id="<?php print $params['id'] ?>"/>
                </div>
            </div>
        </div>
    </div>


    <div class="accordion my-2" id="contact-form-settings-accordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-1">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#contact-form-settings-accordion-1" aria-expanded="true">
                    <?php _e("Contact settings"); ?>
                </button>
            </h2>
            <div id="contact-form-settings-accordion-1" class="accordion-collapse collapse" data-bs-parent="#contact-form-settings-accordion" style="">
                <div class="accordion-body pt-0">
                    <div class="form-group">
                        <label class="form-label font-weight-bold"><?php _e("Contact form name"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("What is the name of this contact form?"); ?></small>
                        <input name="form_name" option-group="<?php print $mod_id ?>" value="<?php print get_option('form_name', $mod_id); ?>" class="mw_option_field form-control col-6" type="text"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion my-2" id="submit-form-settings-accordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-1">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#submit-form-settings-accordion-1" aria-expanded="true">
                    <?php _e("Auto respond settings"); ?>
                </button>
            </h2>
            <div id="submit-form-settings-accordion-1" class="accordion-collapse collapse " data-bs-parent="#submit-form-settings-accordion" style="">
                <div class="accordion-body pt-0">

                    <h5 class="form-label font-weight-bold mb-3"><?php _e("Auto respond message to user"); ?></h5>
                    <div class="">

                        <div class="form-group mb-3">
                            <label class="form-label"><?php _e("Enable auto respond message to user"); ?></label>
                            <small class="text-muted d-block mb-2"> <?php _e('Allow users to receive "Thank you emails after subscription."'); ?></small>
                        </div>

                        <div class="form-group mb-4">
                            <?php $emailAutorespondEnable = \MicroweberPackages\Option\Facades\Option::getValue('email_autorespond_enable', $mod_id); ?>
                            <div class="form-check form-switch pl-0">
                                <input type="checkbox" module="contact_form" onchange="toggleAutoRespondFields(event)" data-value-checked="y" data-value-unchecked="n" class="mw_option_field form-check-input" name="email_autorespond_enable" option-group="<?php print $mod_id ?>" id="email_autorespond_enable" value="y" <?php if ($emailAutorespondEnable): ?>checked<?php endif; ?>>
                            </div>
                        </div>

                        <script type="text/javascript">
                            toggleAutoRespondFields = function (e) {
                                var el = e.target;
                                if ($(el).is(":checked")) {
                                    $('.js-auto-respond-fields').fadeIn();
                                } else {
                                    $('.js-auto-respond-fields').fadeOut();
                                }

                            };
                        </script>

                        <?php
                        $formBuilder = App::make(\MicroweberPackages\FormBuilder\FormElementBuilder::class);
                        ?>

                        <div class="js-auto-respond-fields" <?php if (!$emailAutorespondEnable): ?> style="display:none"<?php endif; ?>>

                            <div class="form-group">
                                <label class="form-label"><?php _e("Auto respond subject"); ?></label>
                                <small class="text-muted d-block mb-2"><?php _e("Auto responders allows you to set up automated replies to incoming email"); ?> <br/><?php _e("E.x. “Thank you for your request”"); ?></small>

                                <?php
                                echo $formBuilder->textOption('email_autorespond_subject', $mod_id)->attribute('autocomplete', 'off')->module('contact_form');
                                ?>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><?php _e("Auto respond message"); ?></label>
                                <small class="text-muted d-block mb-2"><?php _e("Auto respond e-mail sent back to the user"); ?></small>

                                <!--  <textarea id="editorAM" name="email_autorespond" class="mw_option_field form-control" option-group="<?php /*print $mod_id */?>"><?php /*print get_option('email_autorespond', $mod_id); */?></textarea>
-->
                                <?php
                                echo $formBuilder->textareaOption('email_autorespond', $mod_id)->attribute('autocomplete', 'off')->module('contact_form');
                                ?>

                                <label class="form-label"><span class="ico ismall_warn"></span>
                                    <small><?php _e("Auto respond e-mail sent back to the user"); ?></small>
                                </label>
                            </div>

                            <div class="alert alert-primary auto-respond-custom-sender">
                                <div class="form-group mb-3">
                                    <label class="form-label"><?php _e("Auto respond custom sender"); ?></label>
                                    <small class="text-muted d-block mb-2">
                                        <?php _e('Use custom sender settings for the current contact form.'); ?>
                                        <br />
                                        <?php _e('By default we will use contact form global settings.'); ?>
                                        <a href="<?php print admin_url('/module/view?type=contact_form?tab=settings'); ?>" target="_blank"><?php _e('You can change the contact form global settings here.'); ?></a>
                                    </small>
                                </div>

                                <div class="form-group mb-4">
                                    <?php  $emailAutorespondCustomSender = \MicroweberPackages\Option\Facades\Option::getValue('email_autorespond_custom_sender', $mod_id); ?>
                                    <div class="form-check form-switch pl-0">
                                        <input type="checkbox" onchange="toggleAutorespondCustomSender(event)" data-value-checked="y" data-value-unchecked="n" class="mw_option_field form-check-input" name="email_autorespond_custom_sender" option-group="<?php print $mod_id ?>" id="email_autorespond_custom_sender" value="y" <?php if ($emailAutorespondCustomSender): ?>checked<?php endif; ?>>

                                    </div>
                                </div>

                                <script type="text/javascript">
                                    toggleAutorespondCustomSender = function (e) {
                                        var el = e.target;
                                        if ($(el).is(":checked")) {
                                            $('.js-autorespond-custom-sender').fadeIn();
                                        } else {
                                            $('.js-autorespond-custom-sender').fadeOut();
                                        }

                                    };
                                </script>

                                <div class="js-autorespond-custom-sender" <?php if (!$emailAutorespondCustomSender): ?> style="display:none"<?php endif; ?>>
                                    <div class="form-group">
                                        <label class="form-label"><?php _e("Auto respond from e-mail address"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("The e-mail address which will send the message"); ?></small>
                                        <input name="email_autorespond_from" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_autorespond_from', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label"><?php _e("Auto respond from name"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("e.x. your name, company or brand name"); ?></small>
                                        <input name="email_autorespond_from_name" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_autorespond_from_name', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><?php _e("Auto respond reply to e-mail"); ?></label>
                                <small class="text-muted d-block mb-2"><?php _e("When the user receive the auto respond message they can response back to reply to email."); ?></small>
                                <input name="email_autorespond_reply_to" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_autorespond_reply_to', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
                            </div>

                            <div class="form-group">
                                <module type="admin/components/file_append" title="<?php _e("Auto respond e-mail attachments"); ?>" option_key="email_autorespond_append_files" option_group="<?php print $mod_id ?>"/>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion my-2" id="auto-respond-settings-accordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-1">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#auto-respond-settings-accordion-1" aria-expanded="true">
                    <?php _e("Receivers"); ?>
                </button>
            </h2>
            <div id="auto-respond-settings-accordion-1" class="accordion-collapse collapse " data-bs-parent="#auto-respond-settings-accordion" style="">
                <div class="accordion-body pt-0">
                    <h5 class="font-weight-bold settings-title-inside"><?php _e("Receivers") ?></h5>

                    <div class="form-group mb-3">
                        <label class="form-label"><?php _e("Send contact form data to custom receivers when is submited"); ?></label>
                        <small class="text-muted d-block mb-2">
                            <?php _e('Use custom receivers settings for the current contact form.'); ?>
                            <br />
                            <?php _e('By default we will use contact form global settings.'); ?>
                            <a href="<?php print admin_url('/module/view?type=contact_form?tab=settings'); ?>" target="_blank"><?php _e('You can change the contact form global settings here.'); ?></a>
                        </small>
                    </div>

                    <div class="form-group mb-4">
                        <?php $emailCustomReceivers = \MicroweberPackages\Option\Facades\Option::getValue('email_custom_receivers', $mod_id); ?>
                        <div class="form-check form-switch pl-0">
                            <input type="checkbox" module="contact_form" onchange="toggleCustomReceivers(event)" data-value-checked="y" data-value-unchecked="n"   class="mw_option_field form-check-input" name="email_custom_receivers" option-group="<?php print $mod_id ?>" id="email_custom_receivers" value="y" <?php if ($emailCustomReceivers): ?>checked<?php endif; ?>>
                        </div>
                    </div>

                    <script type="text/javascript">
                        toggleCustomReceivers = function (e) {
                            var el = e.target;
                            if ($(el).is(":checked")) {
                                $('.js-custom-receivers').fadeIn();
                            } else {
                                $('.js-custom-receivers').fadeOut();
                            }

                        };
                    </script>

                    <div class="js-custom-receivers" <?php if (!$emailCustomReceivers): ?> style="display:none"<?php endif; ?>>
                        <div class="form-group">
                            <label class="form-label"><?php _e("To e-mail addresses"); ?></label>
                            <small class="text-muted d-block mb-2"><?php _e("E-mail address of the receivers separated with coma."); ?></small>
                            <input name="email_to" option-group="<?php print $mod_id ?>" value="<?php print get_option('email_to', $mod_id); ?>" class="mw_option_field form-control" type="text"/>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
