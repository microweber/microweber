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
        </div>
        <div class="tab-content" style="overflow: hidden">
            <div class="tab-pane fade show active tab-pane-slide-right" id="tabs-nav-tab-main" role="tabpanel">


                <div class="row d-flex align-items-center w-100">
                    <div class="col">
                        <h5 class="font-weight-bold settings-title-inside"><?php _e("Contact form settings") ?></h5>
                    </div>
                    <div class="col text-right">
                        <a class="btn-link" href="<?php print admin_url('settings?group=email') ?>"
                           target="_blank"> <?php _e("Sending options"); ?></a>
                    </div>
                </div>


                <div class="list-group">
                    <button onclick="$('#nav-tab-manage-fields').click()" type="button"
                            class="list-group-item list-group-item-action">
                        <?php _e("Form fields"); ?>
                    </button>
                    <button onclick="$('#nav-tab-manage-contact-auto-respond').click()" type="button"
                            class="list-group-item list-group-item-action"><?php _e("Auto respond settings"); ?></button>
                    <button onclick="$('#nav-tab-manage-contact-settings').click()" type="button"
                            class="list-group-item list-group-item-action"><?php _e("Contact settings"); ?></button>
                    <button onclick="$('#nav-tab-manage-contact-receivers').click()" type="button"
                            class="list-group-item list-group-item-action"><?php _e("Receivers"); ?></button>
                </div>


            </div>
            <div class="tab-pane fade tab-pane-slide-right" id="tabs-nav-tab-manage-fields" role="tabpanel">


                <button onclick="$('#nav-tab-main').click()" type="button"
                        class="list-group-item list-group-item-action">Back
                </button>

                <h3><?php _e("Form fields"); ?></h3>


                <module type="custom_fields" view="admin" data-for="module" for-id="<?php print $params['id'] ?>"/>

            </div>

            <div class="tab-pane fade tab-pane-slide-right" id="tabs-nav-tab-manage-contact-settings" role="tabpanel">
                <button onclick="$('#nav-tab-main').click()" type="button"
                        class="list-group-item list-group-item-action">Back
                </button>
                <h3><?php _e("Contact settings"); ?></h3>


                <div class="form-group">
                    <label class="form-label font-weight-bold"><?php _e("Contact form name"); ?></label>
                    <small
                        class="text-muted d-block mb-2"><?php _e("What is the name of this contact form?"); ?></small>
                    <input name="form_name" option-group="<?php print $mod_id ?>"
                           value="<?php print get_option('form_name', $mod_id); ?>"
                           class="mw_option_field form-control col-6" type="text"/>
                </div>

            </div>


            <div class="tab-pane fade tab-pane-slide-right" id="tabs-nav-tab-manage-contact-auto-respond"
                 role="tabpanel">
                <button onclick="$('#nav-tab-main').click()" type="button"
                        class="list-group-item list-group-item-action">Back
                </button>
                <h3><?php _e("Auto respond settings"); ?></h3>

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
                <button onclick="$('#nav-tab-main').click()" type="button"
                        class="list-group-item list-group-item-action">Back
                </button>
                <h3><?php _e("Receivers"); ?></h3>


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

        </div>
    </div>


</div>
