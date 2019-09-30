<script>
window.messageToggle = window.messageToggle || function (e) {
        var item = mw.tools.firstParentOrCurrentWithAllClasses(e.target, ['message-holder']);
        if (!mw.tools.hasClass(item, 'active')) {
            var curr = $('.message-data-more', item);
            $('.order-data-more').not(curr).stop().slideUp();
            $('.message-holder').not(item).removeClass('active');
            $(curr).stop().slideToggle();
            $(item).toggleClass('active');
        }
    }

$(document).ready(function () {
    $('.new-close').on('click', function (e) {
        e.stopPropagation();
        var item = mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target, ['comment-holder', 'message-holder', 'order-holder']);
        $(item).removeClass('active')
        $('.mw-accordion-content', item).stop().slideUp(function () {

        });
    });
});
</script>

<div class="messages-holder">
            <?php
            if ($last_messages): ?>
                <?php foreach ($last_messages as $message) : ?>

                    <div class="message-holder" id="message-n-<?php print $message['id'] ?>" onclick="messageToggle(event);">
                        <div class="message-data">
                            <div class="product-image">
                                <span class="product-thumbnail-tooltip"><i class="mai-mail"></i></span>
                            </div>

                            <div class="message-number">
                                <a class="mw-ord-id" href="<?php print admin_url(''); ?>view:modules/load_module:contact_form/load_list:<?php print $message['list_id']; ?>">#<?php print $message['id']; ?></a>
                            </div>

                            <div class="product-name">
                                <?php if (isset($message['custom_fields']) and $message['custom_fields']): ?>
                                    <?php foreach ($message['custom_fields'] as $key => $field): ?>
                                    	<?php $key = strtolower($key); ?>
                                        <?php if ($key == 'name'): ?>
                                            <?php print $field; ?>
                                        <?php elseif ($key == 'first_name'): ?>
                                            <?php print $field; ?>
                                        <?php elseif ($key == 'full_name'): ?>
                                            <?php print $field; ?>
                                        <?php elseif ($key == 'last_name'): ?>
                                            <?php print $field; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <div><?php print date('M m, Y - H:m', strtotime($message['created_at'])); ?></div>

                            <div><?php print mw()->format->ago($message['created_at']); ?></div>
                            
                        </div>

                        <div class="message-data-more mw-accordion-content">
                            <hr/>
                            <p class="title"><?php print _e('Fields'); ?></p>
                            <?php if (isset($message['custom_fields']) and  $message['custom_fields'] and !empty($message['custom_fields'])): ?>
                                <?php $fields_ch = array_chunk($message['custom_fields'], 3, true) ?>
                                <?php foreach ($fields_ch as $key_c => $fields): ?>
                                    <?php $service_keys = array('For Id', 'For', 'Module Name', 'Form Id', 'Submit'); ?>
                                    <div class="pull-left">
                                        <?php foreach ($fields as $key => $field): ?>
                                            <?php if ($field and !in_array(mw()->format->titlelize($key), $service_keys)): ?>
                                                <div class="box">
                                                    <p class="label"><?php print  mw()->format->titlelize($key); ?></p>
                                                    <p class="content">
                                                        <?php
                                                        if (is_array($field)) {
                                                            print  '<div style="padding-left: 20px; padding-bottom: 10px;">' . mw()->format->array_to_ul($field) . '</div>';
                                                        } else {
                                                            print $field;
                                                        }
                                                        ?>


                                                    </p>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <div class="clearfix"></div>
                            
                           <div style="width:auto;text-align:right;">
                            <button type="button"class="mw-ui-btn mw-ui-btn-icon mw-ui-btn-important mw-ui-btn-outline mw-ui-btn-medium"
                          		 onClick="mw.forms_data_manager.delete('<?php print $message['id'] ?>','#message-n-<?php print $message['id'] ?>');">
                          		 <span class="mw-icon-bin"></span>
                              </button>
                            </div>
                        </div>

                        <span class="mw-icon-close new-close tip" data-tip="<?php _e("Close"); ?>" data-tipposition="top-center" onclick=""></span>
                        <div class="clearfix"></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="mw-ui-box">
                    <div class="mw-ui-box-content center p-40"><?php _e('You don\'t have any messages yet.'); ?></div>
                </div>
            <?php endif; ?>

        </div>