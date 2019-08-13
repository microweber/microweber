// JavaScript Document
mw.require('forms.js');


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

                mw.cart.after_modify(data);


                if (typeof c === 'function') {
                    c.call(data);
                }
                mw.trigger('mw.cart.add', [data]);
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
        $.post(mw.settings.api_url + 'update_cart', data,
            function (data) {

                mw.cart.after_modify(data);

                if (typeof c === 'function') {
                    c.call(data);
                }

                mw.trigger('mw.cart.add', [data]);
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
                mw.cart.after_modify();
                mw.trigger('mw.cart.remove', [data]);
            });
    },


    qty: function ($id, $qty) {
        var data = {}
        data.id = $id;
        data.qty = $qty;
        $.post(mw.settings.api_url + 'update_cart_item_qty', data,
            function (data) {
                mw.cart.after_modify(data);
                mw.trigger('mw.cart.qty', [data]);
            });

    },

    after_modify: function (data) {



        mw.reload_module('shop/cart');
        mw.reload_module('shop/shipping');
        mw.reload_module('shop/payments');



        if((typeof data == 'object') && typeof data.cart_items_quantity !== 'undefined'){
            $('.js-shopping-cart-quantity').html(data.cart_items_quantity);
        }


        mw.trigger('mw.cart.after_modify', data);





    },

    checkout: function (selector, callback) {
        var form = mw.$(selector);


        var state = form.dataset("loading");
        if (state == 'true') return false;
        form.dataset("loading", 'true');
        form.find('.mw-checkout-btn').attr('disabled', 'disabled');
        form.find('.mw-checkout-btn').hide();
        var obj = mw.form.serialize(selector);
        $.ajax({
            type: "POST",
            url: mw.settings.api_url + 'checkout',
            data: obj
        })
            .done(function (data) {
                mw.trigger('checkoutDone', data);


                var data2 = data;

                if (data != undefined) {
                    mw.$(selector + ' .mw-cart-data-btn').removeAttr('disabled');
                    mw.$('[data-type="shop/cart"]').removeAttr('hide-cart');


                    if (typeof(data2.error) != 'undefined') {
                        mw.$(selector + ' .mw-cart-data-holder').show();
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


                        mw.trigger('mw.cart.checkout.success', data2);


                        if (typeof(data2.redirect) != 'undefined') {

                            setTimeout(function () {
                                window.location.href = data2.redirect;
                            }, 10)

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
                mw.trigger('mw.cart.checkout', [data]);
            });
    }
}



mw.cart.modal = {}

mw.cart.modal.init = function (root_node) {


    mw.cart.modal.bindStepButtons(root_node);


    var inner_cart_module = $(root_node).find('[parent-module-id="js-ajax-cart-checkout-process"]')[0];
    var inner_cart_module = $(root_node).find('[id="cart_checkout_js-ajax-cart-checkout-process"]')[0];

    if(inner_cart_module ){
       // mw.log(inner_cart_module.innerHTML);
         var check  = $(document).find('[id="'+inner_cart_module.id+'"]').length


        //mw.log(check);

        mw.on.moduleReload(inner_cart_module.id, function () {
            //alert('Module was reloaded')
        });
    }






    //
    // mw.on('mw.cart.after_modify', function () {
    //
    // });


    // var inner_cart_module = $(root_node).find('[data-type="shop/cart"]');
    // if(inner_cart_module.length > 0){
    //     if(!inner_cart_module.hasClass('cart-modal-module-events-binded')){
    //         var inner_cart_module_id = inner_cart_module.attr('id')
    //
    //         inner_cart_module.addClass('cart-modal-module-events-binded')
    //
    //
    //
    //         mw.on.moduleReload($('[data-type="shop/cart"]')[0], function () {
    //             alert('Module was reloaded')
    //         });
    //
    //     }
    //
    // }



}

mw.cart.modal.bindStepButtons = function (root_node) {

    $( root_node).off( "click",'.js-show-step');
    $( root_node).on( "click",'.js-show-step', function( event ) {




        var has_error = false;
        var step = mw.$(this).data('step');
        var holder = mw.tools.firstParentWithClass(this, 'js-step-content');





        if (step == 'checkout-complete') {
            return;
        }






        mw.$('input,textarea,select', holder).each(function () {
            if (!this.checkValidity()) {
                mw.$(this).addClass('is-invalid');
                // mw.$(this).addClass('error');
                has_error = 1;
            } else {
                mw.$(this).removeClass('is-invalid');
                // mw.$(this).removeClass('error');
            }

        });





        if (step == 'payment-method'  || step == 'preview') {
            if (has_error) {
                step = 'delivery-address'
            }
        }




        mw.$('.js-show-step').removeClass('active');

        mw.$('[data-step]').removeClass('active');
        mw.$('[data-step="' + step + '"]').addClass('active').parent().removeClass('muted');
        mw.$(this).addClass('active');
        step1 = '.js-' + step;
        mw.$('.js-step-content').hide();
        mw.$(step1).show();

        if (!has_error) {

        }

        if (has_error) {
            mw.notification.warning('Please fill the required fields');
        }

    });


/*
    mw.$('.js-show-step').off('click');
    mw.$('.js-show-step').on('click', function () {



    });*/


}
