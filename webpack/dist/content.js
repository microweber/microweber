/******/ (() => { // webpackBootstrap
/*!***********************************************************!*\
  !*** ../userfiles/modules/microweber/api/content/shop.js ***!
  \***********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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

             //   mw.cart.after_modify(data);


                if (typeof c === 'function') {
                    c.call(data);
                }
                mw.cart.after_modify(data,['mw.cart.add']);

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
        $.post(mw.settings.api_url + 'update_cart', data,
            function (data) {

               // mw.trigger('mw.cart.add', [data]);

                if (typeof c === 'function') {
                    c.call(data);
                }
                mw.cart.after_modify(data,['mw.cart.add']);



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
                mw.cart.after_modify(data,['mw.cart.remove']);

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

                mw.cart.after_modify(data,['mw.cart.qty']);



            });

    },

    after_modify: function (data, events_to_trigger) {



        var modules = ["shop/cart", "shop/shipping", "shop/payments"].filter(function(module){
            return !!document.querySelector('[data-type="'+ module +'"');
        });

        var events = ['mw.cart.modify'];

        if(!!events_to_trigger) {
            var events = events.concat(events_to_trigger);
         }



        if(modules.length) {
            mw.reload_modules(modules, function (data) {
                events.forEach(function(item){
                    mw.trigger(item, [data]);
                })
            }, true);
        } else {
            events.forEach(function(item){
                mw.trigger(item, [data]);
            })
        }


        // mw.reload_module('shop/cart');
        // mw.reload_module('shop/shipping');
        // mw.reload_module('shop/payments');



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

/*
    var inner_cart_module = $(root_node).find('[parent-module-id="js-ajax-cart-checkout-process"]')[0];
*/
    var inner_cart_module = $(root_node).find('[id="cart_checkout_js-ajax-cart-checkout-process"]')[0];
    if(inner_cart_module ){
        var check  = $(document).find('[id="'+inner_cart_module.id+'"]').length
        mw.on.moduleReload(inner_cart_module.id);
    }
};

mw.cart.modal.bindStepButtons = function (root_node) {
    if(typeof root_node === 'string') {
        root_node = mw.$(root_node);
    }

    if(root_node[0]._bindStepButtons) {
        return;
    }
    root_node[0]._bindStepButtons = true;

    root_node.find('.js-show-step').on( "mousedown touchstart" , function(  ) {

        var has_error = false;

        var form = mw.tools.firstParentWithTag(this, 'form');
        var prevStep = mw.$('.js-show-step.active', form).data('step');
        var step = this.dataset.step;

        if(prevStep === step) return;


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
        if (step === 'payment-method'  || step === 'preview') {
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

/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvY29udGVudC9zaG9wLmpzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiI7Ozs7OztBQUFBO0FBQ0E7OztBQUdBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLGFBQWE7QUFDYixLQUFLOztBQUVMO0FBQ0E7OztBQUdBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjs7O0FBR2pCO0FBQ0E7QUFDQSxTQUFTOztBQUVUO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7OztBQUlBLGFBQWE7QUFDYixLQUFLOztBQUVMO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxhQUFhO0FBQ2IsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7Ozs7QUFJQSxhQUFhOztBQUViLEtBQUs7O0FBRUw7Ozs7QUFJQTtBQUNBO0FBQ0EsU0FBUzs7QUFFVDs7QUFFQTtBQUNBO0FBQ0E7Ozs7QUFJQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQixhQUFhO0FBQ2IsU0FBUztBQUNUO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7OztBQUdBO0FBQ0E7QUFDQTs7OztBQUlBO0FBQ0E7QUFDQTs7O0FBR0E7Ozs7OztBQU1BLEtBQUs7O0FBRUw7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTs7O0FBR0E7O0FBRUE7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQSxxQkFBcUI7OztBQUdyQjtBQUNBOztBQUVBLHlCQUF5QjtBQUN6QjtBQUNBLHlCQUF5Qjs7QUFFekI7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7OztBQUdBOztBQUVBO0FBQ0E7QUFDQSw2QkFBNkI7O0FBRTdCOzs7QUFHQSxxQkFBcUI7QUFDckI7QUFDQTtBQUNBLHFCQUFxQjtBQUNyQjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTs7OztBQUlBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTs7O0FBR0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTCIsImZpbGUiOiJjb250ZW50LmpzIiwic291cmNlc0NvbnRlbnQiOlsiLy8gSmF2YVNjcmlwdCBEb2N1bWVudFxubXcucmVxdWlyZSgnZm9ybXMuanMnKTtcblxuXG5tdy5jYXJ0ID0ge1xuXG4gICAgYWRkX2FuZF9jaGVja291dDogZnVuY3Rpb24gKGNvbnRlbnRfaWQsIHByaWNlLCBjKSB7XG4gICAgICAgIGlmICh0eXBlb2YoYykgPT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgIHZhciBjID0gZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbi5ocmVmID0gbXcuc2V0dGluZ3MuYXBpX3VybCArICdzaG9wL3JlZGlyZWN0X3RvX2NoZWNrb3V0JztcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gbXcuY2FydC5hZGRfaXRlbShjb250ZW50X2lkLCBwcmljZSwgYyk7XG4gICAgfSxcblxuICAgIGFkZF9pdGVtOiBmdW5jdGlvbiAoY29udGVudF9pZCwgcHJpY2UsIGMpIHtcbiAgICAgICAgdmFyIGRhdGEgPSB7fTtcbiAgICAgICAgaWYgKGNvbnRlbnRfaWQgPT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICBkYXRhLmNvbnRlbnRfaWQgPSBjb250ZW50X2lkO1xuXG4gICAgICAgIGlmIChwcmljZSAhPSB1bmRlZmluZWQgJiYgZGF0YSAhPSB1bmRlZmluZWQpIHtcbiAgICAgICAgICAgIGRhdGEucHJpY2UgPSBwcmljZTtcbiAgICAgICAgfVxuXG4gICAgICAgICQucG9zdChtdy5zZXR0aW5ncy5hcGlfdXJsICsgJ3VwZGF0ZV9jYXJ0JywgZGF0YSxcbiAgICAgICAgICAgIGZ1bmN0aW9uIChkYXRhKSB7XG5cbiAgICAgICAgICAgICAvLyAgIG13LmNhcnQuYWZ0ZXJfbW9kaWZ5KGRhdGEpO1xuXG5cbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGMgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgICAgICAgICAgYy5jYWxsKGRhdGEpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBtdy5jYXJ0LmFmdGVyX21vZGlmeShkYXRhLFsnbXcuY2FydC5hZGQnXSk7XG5cbiAgICAgICAgICAgICAgLy8gIG13LnRyaWdnZXIoJ213LmNhcnQuYWRkJywgW2RhdGFdKTtcbiAgICAgICAgICAgIH0pO1xuICAgIH0sXG5cbiAgICBhZGQ6IGZ1bmN0aW9uIChzZWxlY3RvciwgcHJpY2UsIGMpIHtcbiAgICAgICAgdmFyIGRhdGEgPSBtdy5mb3JtLnNlcmlhbGl6ZShzZWxlY3Rvcik7XG5cblxuICAgICAgICB2YXIgaXNfZm9ybV92YWxpZCA9IHRydWU7XG4gICAgICAgIG13LiQoJ1tyZXF1aXJlZF0sLnJlcXVpcmVkJywgc2VsZWN0b3IpLmVhY2goZnVuY3Rpb24gKCkge1xuXG4gICAgICAgICAgICBpZiAoIXRoaXMudmFsaWRpdHkudmFsaWQpIHtcbiAgICAgICAgICAgICAgICBpc19mb3JtX3ZhbGlkID0gZmFsc2VcblxuICAgICAgICAgICAgICAgIHZhciBpc19mb3JtX3ZhbGlkX2NoZWNrX2FsbF9maWVsZHNfdGlwID0gbXcudG9vbHRpcCh7XG4gICAgICAgICAgICAgICAgICAgIGlkOiAnbXctY2FydC1hZGQtaW52YWxpZC1mb3JtLXRvb2x0aXAtc2hvdycsXG4gICAgICAgICAgICAgICAgICAgIGNvbnRlbnQ6ICdUaGlzIGZpZWxkIGlzIHJlcXVpcmVkJyxcbiAgICAgICAgICAgICAgICAgICAgY2xvc2Vfb25fY2xpY2tfb3V0c2lkZTogdHJ1ZSxcbiAgICAgICAgICAgICAgICAgICAgZ3JvdXA6ICdtdy1jYXJ0LWFkZC1pbnZhbGlkLXRvb2x0aXAnLFxuICAgICAgICAgICAgICAgICAgICBza2luOiAnd2FybmluZycsXG4gICAgICAgICAgICAgICAgICAgIGVsZW1lbnQ6IHRoaXNcbiAgICAgICAgICAgICAgICB9KTtcblxuXG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICBpZiAoIWlzX2Zvcm1fdmFsaWQpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuXG5cbiAgICAgICAgaWYgKHByaWNlICE9IHVuZGVmaW5lZCAmJiBkYXRhICE9IHVuZGVmaW5lZCkge1xuICAgICAgICAgICAgZGF0YS5wcmljZSA9IHByaWNlO1xuICAgICAgICB9XG4gICAgICAgIGlmIChkYXRhLnByaWNlID09IG51bGwpIHtcbiAgICAgICAgICAgIGRhdGEucHJpY2UgPSAwO1xuICAgICAgICB9XG4gICAgICAgICQucG9zdChtdy5zZXR0aW5ncy5hcGlfdXJsICsgJ3VwZGF0ZV9jYXJ0JywgZGF0YSxcbiAgICAgICAgICAgIGZ1bmN0aW9uIChkYXRhKSB7XG5cbiAgICAgICAgICAgICAgIC8vIG13LnRyaWdnZXIoJ213LmNhcnQuYWRkJywgW2RhdGFdKTtcblxuICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgYyA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgICAgICBjLmNhbGwoZGF0YSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIG13LmNhcnQuYWZ0ZXJfbW9kaWZ5KGRhdGEsWydtdy5jYXJ0LmFkZCddKTtcblxuXG5cbiAgICAgICAgICAgIH0pO1xuICAgIH0sXG5cbiAgICByZW1vdmU6IGZ1bmN0aW9uICgkaWQpIHtcbiAgICAgICAgdmFyIGRhdGEgPSB7fVxuICAgICAgICBkYXRhLmlkID0gJGlkO1xuXG4gICAgICAgICQucG9zdChtdy5zZXR0aW5ncy5hcGlfdXJsICsgJ3JlbW92ZV9jYXJ0X2l0ZW0nLCBkYXRhLFxuICAgICAgICAgICAgZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgICAgICB2YXIgcGFyZW50ID0gbXcuJCgnLm13LWNhcnQtaXRlbS0nICsgJGlkKS5wYXJlbnQoKTtcbiAgICAgICAgICAgICAgICBtdy4kKCcubXctY2FydC1pdGVtLScgKyAkaWQpLmZhZGVPdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKHRoaXMpLnJlbW92ZSgpO1xuICAgICAgICAgICAgICAgICAgICBpZiAocGFyZW50LmZpbmQoXCIubXctY2FydC1pdGVtXCIpLmxlbmd0aCA9PSAwKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgIC8vbXcuY2FydC5hZnRlcl9tb2RpZnkoKTtcbiAgICAgICAgICAgICAgICAvLyBtdy5yZWxvYWRfbW9kdWxlKCdzaG9wL2NhcnQnKTtcbiAgICAgICAgICAgICAgICAvLyBtdy5yZWxvYWRfbW9kdWxlKCdzaG9wL3NoaXBwaW5nJyk7XG4gICAgICAgICAgICAgICAgLy8gbXcudHJpZ2dlcignbXcuY2FydC5yZW1vdmUnLCBbZGF0YV0pO1xuICAgICAgICAgICAgICAgIG13LmNhcnQuYWZ0ZXJfbW9kaWZ5KGRhdGEsWydtdy5jYXJ0LnJlbW92ZSddKTtcblxuICAgICAgICAgICAgfSk7XG4gICAgfSxcblxuICAgIHF0eTogZnVuY3Rpb24gKCRpZCwgJHF0eSkge1xuICAgICAgICB2YXIgZGF0YSA9IHt9XG4gICAgICAgIGRhdGEuaWQgPSAkaWQ7XG4gICAgICAgIGRhdGEucXR5ID0gJHF0eTtcbiAgICAgICAgJC5wb3N0KG13LnNldHRpbmdzLmFwaV91cmwgKyAndXBkYXRlX2NhcnRfaXRlbV9xdHknLCBkYXRhLFxuICAgICAgICAgICAgZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgICAgICAvLyBtdy5yZWxvYWRfbW9kdWxlKCdzaG9wL2NhcnQnKTtcbiAgICAgICAgICAgICAgICAvLyBtdy5yZWxvYWRfbW9kdWxlKCdzaG9wL3NoaXBwaW5nJyk7XG4gICAgICAgICAgICAgICAgLy8gbXcudHJpZ2dlcignbXcuY2FydC5xdHknLCBbZGF0YV0pO1xuXG4gICAgICAgICAgICAgICAgbXcuY2FydC5hZnRlcl9tb2RpZnkoZGF0YSxbJ213LmNhcnQucXR5J10pO1xuXG5cblxuICAgICAgICAgICAgfSk7XG5cbiAgICB9LFxuXG4gICAgYWZ0ZXJfbW9kaWZ5OiBmdW5jdGlvbiAoZGF0YSwgZXZlbnRzX3RvX3RyaWdnZXIpIHtcblxuXG5cbiAgICAgICAgdmFyIG1vZHVsZXMgPSBbXCJzaG9wL2NhcnRcIiwgXCJzaG9wL3NoaXBwaW5nXCIsIFwic2hvcC9wYXltZW50c1wiXS5maWx0ZXIoZnVuY3Rpb24obW9kdWxlKXtcbiAgICAgICAgICAgIHJldHVybiAhIWRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJ1tkYXRhLXR5cGU9XCInKyBtb2R1bGUgKydcIicpO1xuICAgICAgICB9KTtcblxuICAgICAgICB2YXIgZXZlbnRzID0gWydtdy5jYXJ0Lm1vZGlmeSddO1xuXG4gICAgICAgIGlmKCEhZXZlbnRzX3RvX3RyaWdnZXIpIHtcbiAgICAgICAgICAgIHZhciBldmVudHMgPSBldmVudHMuY29uY2F0KGV2ZW50c190b190cmlnZ2VyKTtcbiAgICAgICAgIH1cblxuXG5cbiAgICAgICAgaWYobW9kdWxlcy5sZW5ndGgpIHtcbiAgICAgICAgICAgIG13LnJlbG9hZF9tb2R1bGVzKG1vZHVsZXMsIGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICAgICAgZXZlbnRzLmZvckVhY2goZnVuY3Rpb24oaXRlbSl7XG4gICAgICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoaXRlbSwgW2RhdGFdKTtcbiAgICAgICAgICAgICAgICB9KVxuICAgICAgICAgICAgfSwgdHJ1ZSk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICBldmVudHMuZm9yRWFjaChmdW5jdGlvbihpdGVtKXtcbiAgICAgICAgICAgICAgICBtdy50cmlnZ2VyKGl0ZW0sIFtkYXRhXSk7XG4gICAgICAgICAgICB9KVxuICAgICAgICB9XG5cblxuICAgICAgICAvLyBtdy5yZWxvYWRfbW9kdWxlKCdzaG9wL2NhcnQnKTtcbiAgICAgICAgLy8gbXcucmVsb2FkX21vZHVsZSgnc2hvcC9zaGlwcGluZycpO1xuICAgICAgICAvLyBtdy5yZWxvYWRfbW9kdWxlKCdzaG9wL3BheW1lbnRzJyk7XG5cblxuXG4gICAgICAgIGlmKCh0eXBlb2YgZGF0YSA9PSAnb2JqZWN0JykgJiYgdHlwZW9mIGRhdGEuY2FydF9pdGVtc19xdWFudGl0eSAhPT0gJ3VuZGVmaW5lZCcpe1xuICAgICAgICAgICAgJCgnLmpzLXNob3BwaW5nLWNhcnQtcXVhbnRpdHknKS5odG1sKGRhdGEuY2FydF9pdGVtc19xdWFudGl0eSk7XG4gICAgICAgIH1cblxuXG4gICAgICAgIG13LnRyaWdnZXIoJ213LmNhcnQuYWZ0ZXJfbW9kaWZ5JywgZGF0YSk7XG5cblxuXG5cblxuICAgIH0sXG5cbiAgICBjaGVja291dDogZnVuY3Rpb24gKHNlbGVjdG9yLCBjYWxsYmFjaykge1xuICAgICAgICB2YXIgZm9ybSA9IG13LiQoc2VsZWN0b3IpO1xuXG5cbiAgICAgICAgdmFyIHN0YXRlID0gZm9ybS5kYXRhc2V0KFwibG9hZGluZ1wiKTtcbiAgICAgICAgaWYgKHN0YXRlID09ICd0cnVlJykgcmV0dXJuIGZhbHNlO1xuICAgICAgICBmb3JtLmRhdGFzZXQoXCJsb2FkaW5nXCIsICd0cnVlJyk7XG4gICAgICAgIGZvcm0uZmluZCgnLm13LWNoZWNrb3V0LWJ0bicpLmF0dHIoJ2Rpc2FibGVkJywgJ2Rpc2FibGVkJyk7XG4gICAgICAgIGZvcm0uZmluZCgnLm13LWNoZWNrb3V0LWJ0bicpLmhpZGUoKTtcbiAgICAgICAgdmFyIG9iaiA9IG13LmZvcm0uc2VyaWFsaXplKHNlbGVjdG9yKTtcbiAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgIHR5cGU6IFwiUE9TVFwiLFxuICAgICAgICAgICAgdXJsOiBtdy5zZXR0aW5ncy5hcGlfdXJsICsgJ2NoZWNrb3V0JyxcbiAgICAgICAgICAgIGRhdGE6IG9ialxuICAgICAgICB9KVxuICAgICAgICAgICAgLmRvbmUoZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgICAgICBtdy50cmlnZ2VyKCdjaGVja291dERvbmUnLCBkYXRhKTtcblxuXG4gICAgICAgICAgICAgICAgdmFyIGRhdGEyID0gZGF0YTtcblxuICAgICAgICAgICAgICAgIGlmIChkYXRhICE9IHVuZGVmaW5lZCkge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKHNlbGVjdG9yICsgJyAubXctY2FydC1kYXRhLWJ0bicpLnJlbW92ZUF0dHIoJ2Rpc2FibGVkJyk7XG4gICAgICAgICAgICAgICAgICAgIG13LiQoJ1tkYXRhLXR5cGU9XCJzaG9wL2NhcnRcIl0nKS5yZW1vdmVBdHRyKCdoaWRlLWNhcnQnKTtcblxuXG4gICAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2YoZGF0YTIuZXJyb3IpICE9ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy4kKHNlbGVjdG9yICsgJyAubXctY2FydC1kYXRhLWhvbGRlcicpLnNob3coKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LnJlc3BvbnNlKHNlbGVjdG9yLCBkYXRhMik7XG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAodHlwZW9mKGRhdGEyLnN1Y2Nlc3MpICE9ICd1bmRlZmluZWQnKSB7XG5cblxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwoZGF0YTIuc3VjY2Vzcyk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAodHlwZW9mIHdpbmRvd1tjYWxsYmFja10gPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB3aW5kb3dbY2FsbGJhY2tdKHNlbGVjdG9yLCBkYXRhMi5zdWNjZXNzKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy4kKCdbZGF0YS10eXBlPVwic2hvcC9jYXJ0XCJdJykuYXR0cignaGlkZS1jYXJ0JywgJ2NvbXBsZXRlZCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LnJlbG9hZF9tb2R1bGUoJ3Nob3AvY2FydCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LiQoc2VsZWN0b3IgKyAnIC5tdy1jYXJ0LWRhdGEtaG9sZGVyJykuaGlkZSgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13LnJlc3BvbnNlKHNlbGVjdG9yLCBkYXRhMik7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG5cblxuICAgICAgICAgICAgICAgICAgICAgICAgbXcudHJpZ2dlcignbXcuY2FydC5jaGVja291dC5zdWNjZXNzJywgZGF0YTIpO1xuXG5cbiAgICAgICAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2YoZGF0YTIucmVkaXJlY3QpICE9ICd1bmRlZmluZWQnKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgd2luZG93LmxvY2F0aW9uLmhyZWYgPSBkYXRhMi5yZWRpcmVjdDtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9LCAxMClcblxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuXG5cbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIGlmIChwYXJzZUludChkYXRhKSA+IDApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LiQoJ1tkYXRhLXR5cGU9XCJzaG9wL2NoZWNrb3V0XCJdJykuYXR0cigndmlldycsICdjb21wbGV0ZWQnKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LnJlbG9hZF9tb2R1bGUoJ3Nob3AvY2hlY2tvdXQnKTtcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChvYmoucGF5bWVudF9ndyAhPSB1bmRlZmluZWQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgY2FsbGJhY2tfZnVuYyA9IG9iai5wYXltZW50X2d3ICsgJ19jaGVja291dCc7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiB3aW5kb3dbY2FsbGJhY2tfZnVuY10gPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgd2luZG93W2NhbGxiYWNrX2Z1bmNdKGRhdGEsIHNlbGVjdG9yKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGNhbGxiYWNrX2Z1bmMgPSAnY2hlY2tvdXRfY2FsbGJhY2snO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2Ygd2luZG93W2NhbGxiYWNrX2Z1bmNdID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHdpbmRvd1tjYWxsYmFja19mdW5jXShkYXRhLCBzZWxlY3Rvcik7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgZm9ybS5kYXRhc2V0KFwibG9hZGluZ1wiLCAnZmFsc2UnKTtcbiAgICAgICAgICAgICAgICBmb3JtLmZpbmQoJy5tdy1jaGVja291dC1idG4nKS5yZW1vdmVBdHRyKCdkaXNhYmxlZCcpO1xuICAgICAgICAgICAgICAgIGZvcm0uZmluZCgnLm13LWNoZWNrb3V0LWJ0bicpLnNob3coKTtcbiAgICAgICAgICAgICAgICBtdy50cmlnZ2VyKCdtdy5jYXJ0LmNoZWNrb3V0JywgW2RhdGFdKTtcbiAgICAgICAgICAgIH0pO1xuICAgIH1cbn1cblxuXG5cbm13LmNhcnQubW9kYWwgPSB7fVxuXG5tdy5jYXJ0Lm1vZGFsLmluaXQgPSBmdW5jdGlvbiAocm9vdF9ub2RlKSB7XG4gICAgbXcuY2FydC5tb2RhbC5iaW5kU3RlcEJ1dHRvbnMocm9vdF9ub2RlKTtcblxuLypcbiAgICB2YXIgaW5uZXJfY2FydF9tb2R1bGUgPSAkKHJvb3Rfbm9kZSkuZmluZCgnW3BhcmVudC1tb2R1bGUtaWQ9XCJqcy1hamF4LWNhcnQtY2hlY2tvdXQtcHJvY2Vzc1wiXScpWzBdO1xuKi9cbiAgICB2YXIgaW5uZXJfY2FydF9tb2R1bGUgPSAkKHJvb3Rfbm9kZSkuZmluZCgnW2lkPVwiY2FydF9jaGVja291dF9qcy1hamF4LWNhcnQtY2hlY2tvdXQtcHJvY2Vzc1wiXScpWzBdO1xuICAgIGlmKGlubmVyX2NhcnRfbW9kdWxlICl7XG4gICAgICAgIHZhciBjaGVjayAgPSAkKGRvY3VtZW50KS5maW5kKCdbaWQ9XCInK2lubmVyX2NhcnRfbW9kdWxlLmlkKydcIl0nKS5sZW5ndGhcbiAgICAgICAgbXcub24ubW9kdWxlUmVsb2FkKGlubmVyX2NhcnRfbW9kdWxlLmlkKTtcbiAgICB9XG59O1xuXG5tdy5jYXJ0Lm1vZGFsLmJpbmRTdGVwQnV0dG9ucyA9IGZ1bmN0aW9uIChyb290X25vZGUpIHtcbiAgICBpZih0eXBlb2Ygcm9vdF9ub2RlID09PSAnc3RyaW5nJykge1xuICAgICAgICByb290X25vZGUgPSBtdy4kKHJvb3Rfbm9kZSk7XG4gICAgfVxuXG4gICAgaWYocm9vdF9ub2RlWzBdLl9iaW5kU3RlcEJ1dHRvbnMpIHtcbiAgICAgICAgcmV0dXJuO1xuICAgIH1cbiAgICByb290X25vZGVbMF0uX2JpbmRTdGVwQnV0dG9ucyA9IHRydWU7XG5cbiAgICByb290X25vZGUuZmluZCgnLmpzLXNob3ctc3RlcCcpLm9uKCBcIm1vdXNlZG93biB0b3VjaHN0YXJ0XCIgLCBmdW5jdGlvbiggICkge1xuXG4gICAgICAgIHZhciBoYXNfZXJyb3IgPSBmYWxzZTtcblxuICAgICAgICB2YXIgZm9ybSA9IG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aFRhZyh0aGlzLCAnZm9ybScpO1xuICAgICAgICB2YXIgcHJldlN0ZXAgPSBtdy4kKCcuanMtc2hvdy1zdGVwLmFjdGl2ZScsIGZvcm0pLmRhdGEoJ3N0ZXAnKTtcbiAgICAgICAgdmFyIHN0ZXAgPSB0aGlzLmRhdGFzZXQuc3RlcDtcblxuICAgICAgICBpZihwcmV2U3RlcCA9PT0gc3RlcCkgcmV0dXJuO1xuXG5cbiAgICAgICAgdmFyIHByZXZIb2xkZXIgPSBmb3JtLnF1ZXJ5U2VsZWN0b3IoJy5qcy0nICsgcHJldlN0ZXApO1xuXG5cbiAgICAgICAgaWYgKHN0ZXAgPT09ICdjaGVja291dC1jb21wbGV0ZScpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuICAgICAgICBtdy4kKCdpbnB1dCx0ZXh0YXJlYSxzZWxlY3QnLCBwcmV2SG9sZGVyKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGlmICghdGhpcy5jaGVja1ZhbGlkaXR5KCkpIHtcbiAgICAgICAgICAgICAgICAgbXcuJCh0aGlzKS5hZGRDbGFzcygnaXMtaW52YWxpZCcpO1xuICAgICAgICAgICAgICAgIGhhc19lcnJvciA9IDE7XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgIG13LiQodGhpcykucmVtb3ZlQ2xhc3MoJ2lzLWludmFsaWQnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgICAgIGlmIChzdGVwID09PSAncGF5bWVudC1tZXRob2QnICB8fCBzdGVwID09PSAncHJldmlldycpIHtcbiAgICAgICAgICAgIGlmIChoYXNfZXJyb3IpIHtcbiAgICAgICAgICAgICAgICBzdGVwID0gJ2RlbGl2ZXJ5LWFkZHJlc3MnO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIG13LiQoJy5qcy1zaG93LXN0ZXAnKS5yZW1vdmVDbGFzcygnYWN0aXZlJyk7XG4gICAgICAgIG13LiQoJ1tkYXRhLXN0ZXBdJykucmVtb3ZlQ2xhc3MoJ2FjdGl2ZScpO1xuICAgICAgICBtdy4kKCdbZGF0YS1zdGVwPVwiJyArIHN0ZXAgKyAnXCJdJykuYWRkQ2xhc3MoJ2FjdGl2ZScpLnBhcmVudCgpLnJlbW92ZUNsYXNzKCdtdXRlZCcpO1xuICAgICAgICBtdy4kKHRoaXMpLmFkZENsYXNzKCdhY3RpdmUnKTtcbiAgICAgICAgdmFyIHN0ZXAxID0gJy5qcy0nICsgc3RlcDtcbiAgICAgICAgbXcuJCgnLmpzLXN0ZXAtY29udGVudCcpLmhpZGUoKTtcbiAgICAgICAgbXcuJChzdGVwMSkuc2hvdygpO1xuICAgICAgICBpZiAoaGFzX2Vycm9yKSB7XG4gICAgICAgICAgICBtdy5ub3RpZmljYXRpb24ud2FybmluZygnUGxlYXNlIGZpbGwgdGhlIHJlcXVpcmVkIGZpZWxkcycpO1xuICAgICAgICB9XG4gICAgfSk7XG5cbn1cbiJdLCJzb3VyY2VSb290IjoiIn0=