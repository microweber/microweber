<?php
if (!user_can_access('module.contact_form.index')) {
    return;
}
?>

<?php


include(__DIR__ . '/settings_javascript.php');
?>

<?php
$mod_id = $params['id'];
if (isset($params['for_module_id'])) {
    $mod_id = $params['for_module_id'];
}

$for_module = $config['module'];
if (isset($params['for_module'])) {
    $for_module = $params['for_module'];
}

$for_module_id = $mod_id;
?>



<div id="form_email_options">


    <div>

        <div class="nav nav-tabs" id="nav-tab" role="tablist" style="display:none">
            <button class="nav-link active" id="nav-tab-main" data-bs-toggle="tab" data-bs-target="#tabs-nav-tab-main"
                    type="button" role="tab">Main
            </button>
            <button class="nav-link" id="nav-tab-manage-fields" data-bs-toggle="tab"
                    data-bs-target="#tabs-nav-tab-manage-fields" type="button"
                    role="tab"> <?php _e("Form fields"); ?> </button>
            <button class="nav-link" id="nav-tab-manage-contact-settings" data-bs-toggle="tab"
                    data-bs-target="#tabs-nav-tab-manage-contact-settings" type="button"
                    role="tab"><?php _e("Contact settings"); ?> </button>
            <button class="nav-link" id="nav-tab-manage-contact-auto-respond" data-bs-toggle="tab"
                    data-bs-target="#tabs-nav-tab-manage-contact-auto-respond" type="button"
                    role="tab"><?php _e("Auto respond settings"); ?> </button>
            <button class="nav-link" id="nav-tab-manage-contact-receivers" data-bs-toggle="tab"
                    data-bs-target="#tabs-nav-tab-manage-contact-receivers" type="button"
                    role="tab"><?php _e("Receivers"); ?></button>


            <button class="nav-link" id="nav-tab-manage-contact-advanced" data-bs-toggle="tab"
                    data-bs-target="#tabs-nav-tab-manage-contact-advanced" type="button"
                    role="tab"><?php _e("Advanced"); ?></button>


            <button class="nav-link" id="nav-tab-manage-contact-templates" data-bs-toggle="tab"
                    data-bs-target="#tabs-nav-tab-manage-contact-templates" type="button"
                    role="tab"><?php _e("Templates"); ?></button>
        </div>
        <div class="tab-content" style="overflow: hidden">
            <div class="tab-pane fade show active tab-pane-slide-right" id="tabs-nav-tab-main" role="tabpanel">


                <div class="row d-flex align-items-center w-100 mb-3">
                    <div class="col">
                        <h5 class="font-weight-bold settings-title-inside mb-1"><?php _e("Contact form settings") ?></h5>
                    </div>
                    <div class="col text-right">
                        <a class="mw-admin-action-links mw-adm-liveedit-tabs font-weight-bold" href="<?php print admin_url('settings?group=email') ?>"
                           target="_blank"> <?php _e("Sending options"); ?></a>
                    </div>
                </div>


                <div class="list-group">
                    <button onclick="$('#nav-tab-manage-fields').click()" type="button"
                            class="list-group-item list-group-item-action mw-list-group-accordion-buttons">
                        <?php _e("Form fields"); ?>
                    </button>
                    <button onclick="$('#nav-tab-manage-contact-auto-respond').click()" type="button"
                            class="list-group-item list-group-item-action mw-list-group-accordion-buttons"><?php _e("Auto respond settings"); ?></button>
                    <button onclick="$('#nav-tab-manage-contact-settings').click()" type="button"
                            class="list-group-item list-group-item-action mw-list-group-accordion-buttons"><?php _e("Contact settings"); ?></button>
                    <button onclick="$('#nav-tab-manage-contact-receivers').click()" type="button"
                            class="list-group-item list-group-item-action mw-list-group-accordion-buttons"><?php _e("Receivers"); ?></button>


                    <button onclick="$('#nav-tab-manage-contact-advanced').click()" type="button"
                            class="list-group-item list-group-item-action mw-list-group-accordion-buttons"><?php _e("Advanced"); ?></button>


                    <button onclick="$('#nav-tab-manage-contact-templates').click()" type="button"
                            class="list-group-item list-group-item-action mw-list-group-accordion-buttons"><?php _e("Templates"); ?></button>
                </div>


            </div>
            <div class="tab-pane fade tab-pane-slide-right" id="tabs-nav-tab-manage-fields" role="tabpanel">

               <div class="d-flex align-items-center mb-3">
                   <button class="col col btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start text-start" onclick="$('#nav-tab-main').click()">
                       <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10"><circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle><path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path></g></svg>
                       <span class="ms-1 font-weight-bold"><?php _e("Back"); ?></span>
                   </button>

                   <label class="col-5 form-label text-center font-weight-bold mb-0 fs-3">
                       <?php _e("Form fields"); ?>
                   </label>

                   <div class="col">

                   </div>
               </div>


                <module type="custom_fields" view="admin" data-for="module" for-id="<?php print $for_module_id ?>" />

            </div>

            <div class="tab-pane fade tab-pane-slide-right" id="tabs-nav-tab-manage-contact-settings" role="tabpanel">
                <div class="d-flex align-items-center mb-3">
                    <button class="col btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start" onclick="$('#nav-tab-main').click()">
                        <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10"><circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle><path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path></g></svg>
                        <span class="ms-1 font-weight-bold"><?php _e("Back"); ?></span>
                    </button>
                    <label class="col-5 form-label text-center font-weight-bold mb-0 fs-3"><?php _e("Contact settings"); ?></label>

                    <div class="col">

                    </div>
                </div>


                <div class="form-group">
                    <label class="form-label font-weight-bold"><?php _e("Contact form name"); ?></label>
                    <small
                        class="text-muted d-block mb-2"><?php _e("What is the name of this contact form?"); ?></small>
                    <input name="form_name" option-group="<?php print $mod_id ?>"
                           value="<?php print get_option('form_name', $mod_id); ?>"
                           class="mw_option_field form-control col-6" type="text"/>
                </div>

                <module type="settings/list" for_module="<?php print($for_module) ?>" for_module_id="<?php print $for_module_id ?>"/>


            </div>


            <div class="tab-pane fade tab-pane-slide-right" id="tabs-nav-tab-manage-contact-auto-respond"
                 role="tabpanel">
                <div class="d-flex align-items-center mb-3">
                    <button class="col btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start" onclick="$('#nav-tab-main').click()">
                        <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10"><circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle><path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path></g></svg>
                        <span class="ms-1 font-weight-bold"><?php _e("Back"); ?></span>
                    </button>
                    <label class="col-5 form-label text-center font-weight-bold mb-0 fs-3"><?php _e("Auto respond settings"); ?></label>

                    <div class="col">

                    </div>
                </div>

                <div class=" pt-0">

                    <h5 class="form-label font-weight-bold mb-3"><?php _e("Auto respond message to user"); ?></h5>
                    <div class="">

                        <div class="form-group mb-3">
                            <label class="form-label"><?php _e("Enable auto respond message to user"); ?></label>
                            <small
                                class="text-muted d-block mb-2"> <?php _e('Allow users to receive "Thank you emails after subscription."'); ?></small>
                        </div>

                        <div class="form-group mb-4">
                            <?php $emailAutorespondEnable = \MicroweberPackages\Option\Facades\Option::getValue('email_autorespond_enable', $mod_id); ?>
                            <div class="form-check form-switch pl-0">
                                <input type="checkbox" module="contact_form" onchange="toggleAutoRespondFields(event)"
                                       data-value-checked="y" data-value-unchecked="n"
                                       class="mw_option_field form-check-input" name="email_autorespond_enable"
                                       option-group="<?php print $mod_id ?>" id="email_autorespond_enable" value="y"
                                       <?php if ($emailAutorespondEnable): ?>checked<?php endif; ?>>
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

                        <div
                            class="js-auto-respond-fields" <?php if (!$emailAutorespondEnable): ?> style="display:none"<?php endif; ?>>

                            <div class="form-group">
                                <label class="form-label"><?php _e("Auto respond subject"); ?></label>
                                <small
                                    class="text-muted d-block mb-2"><?php _e("Auto responders allows you to set up automated replies to incoming email"); ?>
                                    <br/><?php _e("E.x. “Thank you for your request”"); ?></small>

                                <?php
                                echo $formBuilder->textOption('email_autorespond_subject', $mod_id)->attribute('autocomplete', 'off')->module('contact_form');
                                ?>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><?php _e("Auto respond message"); ?></label>
                                <small
                                    class="text-muted d-block mb-2"><?php _e("Auto respond e-mail sent back to the user"); ?></small>

                                <!--  <textarea id="editorAM" name="email_autorespond" class="mw_option_field form-control" option-group="<?php /*print $mod_id */ ?>"><?php /*print get_option('email_autorespond', $mod_id); */ ?></textarea>
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
                                        <br/>
                                        <?php _e('By default we will use contact form global settings.'); ?>
                                        <a href="<?php print admin_url('/module/view?type=contact_form?tab=settings'); ?>"
                                           target="_blank"><?php _e('You can change the contact form global settings here.'); ?></a>
                                    </small>
                                </div>

                                <div class="form-group mb-4">
                                    <?php $emailAutorespondCustomSender = \MicroweberPackages\Option\Facades\Option::getValue('email_autorespond_custom_sender', $mod_id); ?>
                                    <div class="form-check form-switch pl-0">
                                        <input type="checkbox" onchange="toggleAutorespondCustomSender(event)"
                                               data-value-checked="y" data-value-unchecked="n"
                                               class="mw_option_field form-check-input"
                                               name="email_autorespond_custom_sender"
                                               option-group="<?php print $mod_id ?>"
                                               id="email_autorespond_custom_sender" value="y"
                                               <?php if ($emailAutorespondCustomSender): ?>checked<?php endif; ?>>

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

                                <div
                                    class="js-autorespond-custom-sender" <?php if (!$emailAutorespondCustomSender): ?> style="display:none"<?php endif; ?>>
                                    <div class="form-group">
                                        <label
                                            class="form-label"><?php _e("Auto respond from e-mail address"); ?></label>
                                        <small
                                            class="text-muted d-block mb-2"><?php _e("The e-mail address which will send the message"); ?></small>
                                        <input name="email_autorespond_from" option-group="<?php print $mod_id ?>"
                                               value="<?php print get_option('email_autorespond_from', $mod_id); ?>"
                                               class="mw_option_field form-control" type="text"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label"><?php _e("Auto respond from name"); ?></label>
                                        <small
                                            class="text-muted d-block mb-2"><?php _e("e.x. your name, company or brand name"); ?></small>
                                        <input name="email_autorespond_from_name" option-group="<?php print $mod_id ?>"
                                               value="<?php print get_option('email_autorespond_from_name', $mod_id); ?>"
                                               class="mw_option_field form-control" type="text"/>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label"><?php _e("Auto respond reply to e-mail"); ?></label>
                                <small
                                    class="text-muted d-block mb-2"><?php _e("When the user receive the auto respond message they can response back to reply to email."); ?></small>
                                <input name="email_autorespond_reply_to" option-group="<?php print $mod_id ?>"
                                       value="<?php print get_option('email_autorespond_reply_to', $mod_id); ?>"
                                       class="mw_option_field form-control" type="text"/>
                            </div>

                            <div class="form-group">
                                <module type="admin/components/file_append"
                                        title="<?php _e("Auto respond e-mail attachments"); ?>"
                                        option_key="email_autorespond_append_files"
                                        option_group="<?php print $mod_id ?>"/>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="tab-pane fade tab-pane-slide-right" id="tabs-nav-tab-manage-contact-receivers" role="tabpanel">
               <div class="d-flex align-items-center mb-3">
                   <button class="col btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start" onclick="$('#nav-tab-main').click()">
                       <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10"><circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle><path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path></g></svg>
                       <span class="ms-1 font-weight-bold"><?php _e("Back"); ?></span>
                   </button>
                   <label class="col-5 form-label text-center font-weight-bold mb-0 fs-3"><?php _e("Receivers"); ?></label>

                   <div class="col">

                   </div>
               </div>

                <div class="pt-0">
                    <h5 class="font-weight-bold settings-title-inside"><?php _e("Receivers") ?></h5>

                    <div class="form-group mb-3">
                        <label
                            class="form-label"><?php _e("Send contact form data to custom receivers when is submited"); ?></label>
                        <small class="text-muted d-block mb-2">
                            <?php _e('Use custom receivers settings for the current contact form.'); ?>
                            <br/>
                            <?php _e('By default we will use contact form global settings.'); ?>
                            <a href="<?php print admin_url('/module/view?type=contact_form?tab=settings'); ?>"
                               target="_blank"><?php _e('You can change the contact form global settings here.'); ?></a>
                        </small>
                    </div>

                    <div class="form-group mb-4">
                        <?php $emailCustomReceivers = \MicroweberPackages\Option\Facades\Option::getValue('email_custom_receivers', $mod_id); ?>
                        <div class="form-check form-switch pl-0">
                            <input type="checkbox" module="contact_form" onchange="toggleCustomReceivers(event)"
                                   data-value-checked="y" data-value-unchecked="n"
                                   class="mw_option_field form-check-input" name="email_custom_receivers"
                                   option-group="<?php print $mod_id ?>" id="email_custom_receivers" value="y"
                                   <?php if ($emailCustomReceivers): ?>checked<?php endif; ?>>
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

                    <div
                        class="js-custom-receivers" <?php if (!$emailCustomReceivers): ?> style="display:none"<?php endif; ?>>
                        <div class="form-group">
                            <label class="form-label"><?php _e("To e-mail addresses"); ?></label>
                            <small
                                class="text-muted d-block mb-2"><?php _e("E-mail address of the receivers separated with coma."); ?></small>
                            <input name="email_to" option-group="<?php print $mod_id ?>"
                                   value="<?php print get_option('email_to', $mod_id); ?>"
                                   class="mw_option_field form-control" type="text"/>
                        </div>

                    </div>
                </div>

            </div>



            <div class="tab-pane fade tab-pane-slide-right" id="tabs-nav-tab-manage-contact-advanced" role="tabpanel">
               <div class="d-flex align-items-center mb-3">
                   <button class="col btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start" onclick="$('#nav-tab-main').click()">
                       <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10"><circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle><path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path></g></svg>
                       <span class="ms-1 font-weight-bold"><?php _e("Back"); ?></span>
                   </button>

                   <label class="col-5 form-label text-center font-weight-bold mb-0 fs-3"><?php _e("Advanced"); ?></label>

                   <div class="col">

                   </div>
               </div>


                <div class="pt-0">
                    <module type="contact_form/manager/assign_list_to_module" data-for-module="<?php print($for_module) ?>" data-for-module-id="<?php print $for_module_id ?>"/>
                    <hr class="thin"/>

                    <h5 class="font-weight-bold mb-3"><?php _e("Contact form advanced settings") ?></h5>

                    <module type="admin/mail_providers/integration_select" option_group="contact_form"/>

                    <hr class="thin"/>


<!--                    <div class="form-group">-->
<!--                        <label class="form-label">--><?php //_e("Button text"); ?><!--</label>-->
<!--                        <small-->
<!--                            class="text-muted d-block mb-2">--><?php //_e("Send message"); ?><!--</small>-->
<!--                        <input name="button_text" option-group="--><?php //print $mod_id ?><!--"-->
<!--                               value="--><?php //print get_option('button_text', $mod_id); ?><!--"-->
<!--                               class="mw_option_field form-control" type="text"/>-->
<!--                    </div>-->


                    <div class="form-group">
                        <label class="form-label"><?php _e("Thank you message"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("Write your thank you message"); ?></small>
                        <input name="thank_you_message" option-group="<?php print $mod_id ?>"
                               value="<?php print get_option('thank_you_message', $mod_id); ?>"
                               class="mw_option_field form-control" type="text"/>
                    </div>

                    <hr class="thin"/>

                    <div class="form-group">
                        <label class="form-label"><?php _e("Newsletter") ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("Show the newsletter subscription checkbox?") ?></small>

                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" parent-reload="true" name="newsletter_subscription" id="newsletter_subscription" value="y" data-value-unchecked="n" data-value-checked="y" class="mw_option_field form-check-input" option-group="<?php print $for_module_id ?>" <?php if (get_option('newsletter_subscription', $for_module_id) == 'y'): ?>checked<?php endif; ?> />
                            <label class="custom-control-label" for="newsletter_subscription"><?php _e("Enable newsletter checkbox"); ?></label>
                        </div>
                    </div>

                    <hr class="thin"/>

                    <module type="contact_form/privacy_settings" simple="true"/>

                    <?php if ($for_module_id != 'contact_form_default') : ?>
                        <br/>
                        <div class="form-group">
                            <label class="control-label"><?php _e("Captcha settings") ?></label>
                            <small class="text-muted d-block mb-2"><?php _e("Setup your captcha preferences from ") ?><a href="<?php print admin_url('module/view?type=captcha'); ?>" target="_blank"><?php _e("Captcha module") ?></a></small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="disable_captcha" id="disable_captcha" value="y" option-group="<?php print $for_module_id ?>" class="mw_option_field form-check-input" <?php if (get_option('disable_captcha', $for_module_id) == 'y'): ?>checked <?php endif; ?>/>
                                <label class="custom-control-label" for="disable_captcha"><?php _e("Disable Code Verification ex"); ?>.: <img src="<?php print mw_includes_url(); ?>img/code_verification_example.jpg" alt="" style="margin-top: -8px;"/></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label"><?php _e("Redirect URL"); ?></label>
                            <small class="text-muted d-block mb-2"><?php _e("Redirect to URL after submit for example for “Thank you” page") ?></small>
                            <input name="email_redirect_after_submit" option-group="<?php print $for_module_id ?>" value="<?php print get_option('email_redirect_after_submit', $for_module_id); ?>" class="mw_option_field form-control" type="text"/>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
            <div class="tab-pane fade tab-pane-slide-right" id="tabs-nav-tab-manage-contact-templates" role="tabpanel">
                <div class="d-flex align-items-center mb-3">
                    <button class="col btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start" onclick="$('#nav-tab-main').click()">
                        <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10"><circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle><path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path></g></svg>
                        <span class="ms-1 font-weight-bold"><?php _e("Back"); ?></span>
                    </button>

                    <label class="col-5 form-label text-center font-weight-bold mb-0 fs-3"><?php _e("Templates"); ?></label>

                    <div class="col">

                    </div>
                </div>


                <div class="pt-0">
                    <module type="admin/modules/templates" for-module="<?php print $for_module ?>" for-module-id="<?php print $for_module_id ?>"/>

                </div>

            </div>

        </div>
    </div>


</div>
