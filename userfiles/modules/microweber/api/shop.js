// JavaScript Document
mw.require('forms.js');

mw.product = {
    quick_view: function(product_id, dialog_title) {
        $.get(mw.settings.api_url + 'product/quick-view', {id:product_id},
            function (html) {
                mw.dialog({
                    title: dialog_title,
                    width: 960,
                    content: html,
                    onremove: function () {

                    },
                    name: 'product-quick-view'
                });
            }
        );
    }
};

mw.cart = {

    add_and_checkout: function (content_id, price, c) {
        if (typeof(c) == 'undefined') {
            var c = function () {
                window.location.href = mw.settings.api_url + 'shop/redirect_to_checkout';
            }
        }
        return mw.cart.add_item(content_id, price, c);
    },

    add_item: function (content_id, price, c) {
        var data = {};
        if (content_id == undefined) {
            return;
        }

        data.content_id = content_id;

        if (price != undefined && data != undefined) {
            data.price = price;
        }

        $.post(mw.settings.api_url + 'update_cart', data,
            function (data) {

                //   mw.cart.after_modify(data);


                if (typeof c === 'function') {
                    c.call(data);
                }
                mw.cart.after_modify(data, ['mw.cart.add']);
                mw.trigger('cartAddItem', data);

                //  mw.trigger('mw.cart.add', [data]);
            });
    },

    add: function (selector, price, c) {
        var data = mw.form.serialize(selector);


        var is_form_valid = true;
        mw.$('[required],.required', selector).each(function () {

            if (!this.validity.valid) {
                is_form_valid = false

                var is_form_valid_check_all_fields_tip = mw.tooltip({
                    id: 'mw-cart-add-invalid-form-tooltip-show',
                    content: 'This field is required',
                    close_on_click_outside: true,
                    group: 'mw-cart-add-invalid-tooltip',
                    skin: 'warning',
                    element: this
                });


                return false;
            }
        });

        if (!is_form_valid) {
            return;
        }


        if (price != undefined && data != undefined) {
            data.price = price;
        }
        if (data.price == null) {
            data.price = 0;
        }

        var formData = new FormData();
        $.each(data, function (k,v) {
            formData.append(k,v);
        });

        $.ajax({
            url:mw.settings.api_url + 'update_cart',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            type: 'post',
            success: function (data) {

                // mw.trigger('mw.cart.add', [data]);

                if (typeof c === 'function') {
                    c.call(data);
                }
                mw.cart.after_modify(data, ['mw.cart.add']);
                mw.trigger('cartAddItem', data);


            }
         });
    },

    remove: function ($id) {
        var data = {}
        data.id = $id;

        $.post(mw.settings.api_url + 'remove_cart_item', data,
            function (data) {
                var parent = mw.$('.mw-cart-item-' + $id).parent();
                mw.$('.mw-cart-item-' + $id).fadeOut(function () {
                    mw.$(this).remove();
                    if (parent.find(".mw-cart-item").length == 0) {

                    }
                });
                //mw.cart.after_modify();
                // mw.reload_module('shop/cart');
                // mw.reload_module('shop/shipping');
                // mw.trigger('mw.cart.remove', [data]);
                mw.cart.after_modify(data, ['mw.cart.remove']);
                mw.trigger('cartRemoveItem', data);

            });
    },

    qty: function ($id, $qty) {
        var data = {}
        data.id = $id;
        data.qty = $qty;
        $.post(mw.settings.api_url + 'update_cart_item_qty', data,
            function (data) {
                // mw.reload_module('shop/cart');
                // mw.reload_module('shop/shipping');
                // mw.trigger('mw.cart.qty', [data]);

                if(data && typeof(data.error) !== 'undefined'){
                    if(typeof(data.message) !== 'undefined'){
                        mw.notification.warning(data.message);
                    }
                }

                mw.cart.after_modify(data, ['mw.cart.qty']);
                mw.trigger('cartModify', data);


            });

    },

    after_modify: function (data, events_to_trigger) {


        var modules = ["shop/cart", "shop/shipping", "shop/payments"].filter(function (module) {
            return !!document.querySelector('[data-type="' + module + '"');
        });

        var events = ['mw.cart.modify'];

        if (!!events_to_trigger) {
            var events = events.concat(events_to_trigger);
        }


        if (modules.length) {
            mw.reload_modules(modules, function (data) {
                events.forEach(function (item) {
                    mw.trigger(item, [data]);
                })
            }, true);
        } else {
            events.forEach(function (item) {
                mw.trigger(item, [data]);
            })
        }


        // mw.reload_module('shop/cart');
        // mw.reload_module('shop/shipping');
        // mw.reload_module('shop/payments');


        if ((typeof data == 'object') && typeof data.cart_items_quantity !== 'undefined') {
            $('.js-shopping-cart-quantity').html(data.cart_items_quantity);
        }


        mw.trigger('mw.cart.after_modify', data);
        mw.trigger('cartModify', data);


    },

    checkout: function (selector, callback, beforeRedirect) {

        if (!beforeRedirect) {
            beforeRedirect = function () {
                return new Promise(function (){
                    resolve();
                });
            };
        }

        var form = mw.$(selector);
        $(document).trigger("checkoutBeforeProcess", form);


        var state = form.dataset("loading");
        if (state == 'true') return false;
        form.dataset("loading", 'true');
        form.find('.mw-checkout-btn').attr('disabled', 'disabled');
        form.find('.mw-checkout-btn').hide();

        setTimeout(function () {

            var form = mw.$(selector);
            var obj = mw.form.serialize(form);


            $.ajax({
                type: "POST",
                url: mw.settings.api_url + 'checkout',
                data: obj,
                error: function (xhr, ajaxOptions, thrownError) {
                     mw.errorsHandle(JSON.parse(xhr.responseText))
                    form.dataset("loading", 'false');
                    form.find('.mw-checkout-btn').removeAttr('disabled');
                    form.find('.mw-checkout-btn').show();

                }
            })
                .done(function (data) {
                    mw.trigger('checkoutDone', data);

                    var data2 = data;

                    if (data != undefined) {
                        mw.$(selector + ' .mw-cart-data-btn').removeAttr('disabled');
                        mw.$('[data-type="shop/cart"]').removeAttr('hide-cart');


                        if (typeof(data2.error) != 'undefined') {
                            mw.$(selector + ' .mw-cart-data-holder').show();
                            if (typeof(data2.error.address_error) != 'undefined') {
                                var form_with_err = form;
                                var isModalForm = $(form_with_err).attr('is-modal-form')

                                if (isModalForm) {
                                    mw.cart.modal.showStep(form_with_err, 'delivery-address');
                                }
                                mw.notification.error('Please fill your address details');

                            }

                            mw.response(selector, data2);
                        } else if (typeof(data2.success) != 'undefined') {


                            if (typeof callback === 'function') {
                                callback.call(data2.success);

                            } else if (typeof window[callback] === 'function') {
                                window[callback](selector, data2.success);
                            } else {

                                mw.$('[data-type="shop/cart"]').attr('hide-cart', 'completed');
                                mw.reload_module('shop/cart');
                                mw.$(selector + ' .mw-cart-data-holder').hide();
                                mw.response(selector, data2);
                            }





                            if (typeof(data2.redirect) != 'undefined') {

                                setTimeout(function () {
                                    beforeRedirect().then(function (){
                                        window.location.href = data2.redirect;
                                    });
                                }, 100);
                                return;
                            } else {
                                mw.trigger('mw.cart.checkout.success', data2);
                                mw.trigger('checkoutSuccess', [data]);

                            }


                        } else if (parseInt(data) > 0) {
                            mw.$('[data-type="shop/checkout"]').attr('view', 'completed');
                            mw.reload_module('shop/checkout');
                        } else {
                            if (obj.payment_gw != undefined) {
                                var callback_func = obj.payment_gw + '_checkout';
                                if (typeof window[callback_func] === 'function') {
                                    window[callback_func](data, selector);
                                }
                                var callback_func = 'checkout_callback';
                                if (typeof window[callback_func] === 'function') {
                                    window[callback_func](data, selector);
                                }
                            }
                        }

                    }
                    form.dataset("loading", 'false');
                    form.find('.mw-checkout-btn').removeAttr('disabled');
                    form.find('.mw-checkout-btn').show();
                    mw.trigger('checkoutResponse', data);
                });

        }, 1500);
    }
}

if (typeof(mw.cart.modal) == 'undefined') {
    mw.cart.modal = {};
}
if (typeof(mw.cart.modal.init) == 'undefined') {
    mw.cart.modal.init = function (root_node) {
        mw.cart.modal.bindStepButtons(root_node);

        /*
            var inner_cart_module = $(root_node).find('[parent-module-id="js-ajax-cart-checkout-process"]')[0];
        */
        var inner_cart_module = $(root_node).find('[id="cart_checkout_js-ajax-cart-checkout-process"]')[0];
        if (inner_cart_module) {
            var check = $(document).find('[id="' + inner_cart_module.id + '"]').length
            mw.on.moduleReload(inner_cart_module.id);
        }
    };
}
if (typeof(mw.cart.modal.bindStepButtons) == 'undefined') {

    mw.cart.modal.bindStepButtons = function (root_node) {
        if (typeof root_node === 'string') {
            root_node = mw.$(root_node);
        }

        if (root_node[0]._bindStepButtons) {
            return;
        }
        root_node[0]._bindStepButtons = true;

        var checkout_form = $(root_node).find('form').first();


        $('body').on("mousedown touchstart", '.js-show-step', function () {
            var step = $(this).attr('data-step');

            mw.cart.modal.showStep(checkout_form, step);


        });
    };

    mw.cart.modal.showStep = function (form, step) {


        var prevStep = mw.$('.js-show-step.active', form).data('step');

        if (prevStep === step) return;

        var prevHolder = $(form).find('.js-' + prevStep).first();

        $(form).attr('is-modal-form', true);

        if (step === 'checkout-complete') {
            return;
        }

        var validate = function (callback) {
            var hasError = false;
            mw.$('input,textarea,select', prevHolder).each(function () {
                if (!this.checkValidity()) {
                    mw.$(this).addClass('is-invalid');
                    hasError = true;
                } else {
                    mw.$(this).removeClass('is-invalid');
                }
            });
            if (step === 'payment-method' || step === 'preview') {
                if (hasError) {
                    step = 'delivery-address';
                    callback.call(undefined, hasError, undefined, step);
                }
            }
            if (step === 'payment-method') {
                $.post(mw.settings.api_url + 'checkout/validate', mw.serializeFields(prevHolder), function (data) {
                    if (!data.valid) {
                        step = 'delivery-address';
                    }
                    callback.call(undefined, !data.valid, undefined, step);

                }).fail(function (data) {
                    mw.errorsHandle(data)
                });
            } else {
                callback.call(undefined, hasError, undefined, step);
            }
        };

        validate(function (hasError, message, step) {
            if (hasError) {
                message = message || mw.lang('Please fill properly the required fields');
                mw.notification.warning(message);
            }

            mw.$('.js-show-step').removeClass('active');
            mw.$('[data-step]').removeClass('active');
            mw.$('[data-step="' + step + '"]').addClass('active').parent().removeClass('muted');
            mw.$(this).addClass('active');
            var step1 = '.js-' + step;
            mw.$('.js-step-content').hide();
            mw.$(step1).show();

        });


    };
}

mw.cart.modal.bindStepButtons__old = function (root_node) {
    if (typeof root_node === 'string') {
        root_node = mw.$(root_node);
    }

    if (root_node[0]._bindStepButtons) {
        return;
    }
    root_node[0]._bindStepButtons = true;

    root_node.find('.js-show-step').on("mousedown touchstart", function () {

        var has_error = false;

        var form = mw.tools.firstParentWithTag(this, 'form');
        var prevStep = mw.$('.js-show-step.active', form).data('step');
        var step = this.dataset.step;

        if (prevStep === step) return;


        var prevHolder = form.querySelector('.js-' + prevStep);


        if (step === 'checkout-complete') {
            return;
        }
        mw.$('input,textarea,select', prevHolder).each(function () {
            if (!this.checkValidity()) {
                mw.$(this).addClass('is-invalid');
                has_error = 1;
            } else {
                mw.$(this).removeClass('is-invalid');
            }
        });
        if (step === 'payment-method' || step === 'preview') {
            if (has_error) {
                step = 'delivery-address';
            }
        }
        mw.$('.js-show-step').removeClass('active');
        mw.$('[data-step]').removeClass('active');
        mw.$('[data-step="' + step + '"]').addClass('active').parent().removeClass('muted');
        mw.$(this).addClass('active');
        var step1 = '.js-' + step;
        mw.$('.js-step-content').hide();
        mw.$(step1).show();
        if (has_error) {
            mw.notification.warning('Please fill the required fields');
        }
    });

}
