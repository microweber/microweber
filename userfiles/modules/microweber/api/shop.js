
// JavaScript Document
mw.require('forms.js');


mw.cart = {

    add_and_checkout: function (content_id, price, c) {
        if (typeof(c) == 'undefined') {
          var c = function(){
            window.location.href=mw.settings.api_url+'shop/redirect_to_checkout';
          }
        }
      return  mw.cart.add_item(content_id, price, c);
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
                console.log(data)
                mw.reload_module('shop/cart');
                if (typeof c === 'function') {
                    c.call(data);
                }
                mw.trigger('mw.cart.add', [data]);
            });
    },

    add: function (selector, price, c) {
        var data = mw.form.serialize(selector);


		var is_form_valid = true;
		$('[required],.required',selector).each(function()
		{

			if(!this.validity.valid)
			{
				is_form_valid = false

				var is_form_valid_check_all_fields_tip = mw.tooltip({
					id: 'mw-cart-add-invalid-form-tooltip-show',
					content: 'This field is required',
					close_on_click_outside: true,
					group: 'mw-cart-add-invalid-tooltip',
					skin:'warning',
					element: this
				});


				 return false;
			}
		});

		 if(!is_form_valid){
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

                mw.reload_module('shop/cart');
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
                    $(this).remove();
                    if (parent.find(".mw-cart-item").length == 0) {
                        mw.reload_module("shop/cart");
                    }
                });
                mw.trigger('mw.cart.remove', [data]);
            });
    },


    qty: function ($id, $qty) {
        var data = {}
        data.id = $id;
        data.qty = $qty;
        $.post(mw.settings.api_url + 'update_cart_item_qty', data,
            function (data) {
                mw.reload_module('shop/cart');
                mw.trigger('mw.cart.qty', [data]);
            });

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

            							setTimeout(function(){
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
