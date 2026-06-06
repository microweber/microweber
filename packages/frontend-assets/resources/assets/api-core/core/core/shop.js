// JavaScript Document


mw.cart = {
    add_and_checkout: function (content_id, price, c) {
        if (typeof c == "undefined") {
            var c = function () {
                window.location.href =
                    mw.settings.api_url + "shop/redirect_to_checkout";
            };
        }
        return mw.cart.add_item(content_id, price, c);
    },

    add_and_show_modal: function (content_id, price, c, fields) {

        mw.cart.add_item(content_id, price, c, fields);

        var checkoutUrl = mw.settings.api_url + "shop/redirect_to_checkout";

        const dlg = mw.dialog({
            content:"<div class='mw-cart-item-added-modal text-center'> <i class='mdi mdi-check-circle-outline'></i> <h4>" + mw.lang("Item added to cart") + "</h4> </div>",
            title: mw.lang("Item added to cart"),
            template: "mw_modal_basic",
            footer: [
                mw
                    .element(
                        `<button type="button" class="btn btn-primary" data-action="checkout" onclick="window.location.href='${checkoutUrl}'">${mw.lang(
                            "Checkout"
                        )}</button>`
                    )
                    .get(0),
            ],
        });
    },

    add_item: function (content_id, price, c, fields) {
        var data = {};
        if (content_id == undefined) {
            return;
        }

        data.content_id = content_id;

        if (price != undefined && data != undefined) {
            data.price = price;
        }

        // task-2026-06-06-cfcart — merge product custom-field selections
        // (Size, Session Focus, …) gathered from the add-to-cart form so they
        // travel with the item and the backend can capture them + apply any
        // price modifiers. Previously add_item sent only content_id + price,
        // so every selection was silently dropped from the cart and order.
        if (fields && typeof fields === "object") {
            for (var fieldKey in fields) {
                if (
                    Object.prototype.hasOwnProperty.call(fields, fieldKey) &&
                    fieldKey !== "content_id" &&
                    fieldKey !== "price"
                ) {
                    data[fieldKey] = fields[fieldKey];
                }
            }
        }

        $.post(mw.settings.api_url + "update_cart", data, function (data) {
            mw.cart.handle_warnings(data);
            //   mw.cart.after_modify(data);

            if (typeof c === "function") {
                c.call(data);
            }
            mw.cart.after_modify(data, ["mw.cart.add"]);
            mw.trigger("cartAddItem", data);

            //  mw.trigger('mw.cart.add', [data]);
        });
    },

    add: function (selector, price, c) {
        var data = mw.form.serialize(selector);

        var is_form_valid = true;
        mw.$("[required],.required", selector).each(function () {
            if (!this.validity.valid) {
                is_form_valid = false;

                var is_form_valid_check_all_fields_tip = mw.tooltip({
                    id: "mw-cart-add-invalid-form-tooltip-show",
                    content: "This field is required",
                    close_on_click_outside: true,
                    group: "mw-cart-add-invalid-tooltip",
                    skin: "warning",
                    element: this,
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
        $.each(data, function (k, v) {
            formData.append(k, v);
        });

        $.ajax({
            url: mw.settings.api_url + "update_cart",
            dataType: "text",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            type: "post",
            success: function (data) {
                mw.cart.handle_warnings(data);
                // mw.trigger('mw.cart.add', [data]);

                if (typeof c === "function") {
                    c.call(data);
                }
                mw.cart.after_modify(data, ["mw.cart.add"]);
                mw.trigger("cartAddItem", data);
            },
        });
    },

    remove: function ($id) {
        var data = {};
        data.id = $id;

        $.post(mw.settings.api_url + "remove_cart_item", data, function (data) {
            var parent = mw.$(".mw-cart-item-" + $id).parent();
            mw.$(".mw-cart-item-" + $id).fadeOut(function () {
                mw.$(this).remove();
                if (parent.find(".mw-cart-item").length == 0) {
                }
            });
            //mw.cart.after_modify();
            // mw.reload_module('shop/cart');
            // mw.reload_module('shop/shipping');
            // mw.trigger('mw.cart.remove', [data]);
            mw.cart.after_modify(data, ["mw.cart.remove"]);
            mw.trigger("cartRemoveItem", data);
        });
    },

    qty: function ($id, $qty) {
        var data = {};
        data.id = $id;
        data.qty = $qty;
        $.post(
            mw.settings.api_url + "update_cart_item_qty",
            data,
            function (data) {
                // mw.reload_module('shop/cart');
                // mw.reload_module('shop/shipping');
                // mw.trigger('mw.cart.qty', [data]);

                mw.cart.handle_warnings(data);

                if (data && typeof data.error !== "undefined") {
                    if (typeof data.message !== "undefined") {
                        mw.notification.warning(data.message);
                    }
                }

                mw.cart.after_modify(data, ["mw.cart.qty"]);
                mw.trigger("cartModify", data);
            }
        );
    },

    handle_warnings: function (data) {
        var warnings = [];

        if (typeof data === "string") {
            try {
                data = JSON.parse(data);
            } catch (e) {
                return;
            }
        }

        if (data && Array.isArray(data.warnings)) {
            warnings = data.warnings;
        } else if (data && data.data && Array.isArray(data.data.warnings)) {
            warnings = data.data.warnings;
        }

        warnings.forEach(function (warning) {
            var message = typeof warning === "string" ? warning : warning && warning.message;

            if (message && typeof mw.notification !== "undefined" && typeof mw.notification.warning === "function") {
                mw.notification.warning(message);
            }
        });
    },

    after_modify: function (data, events_to_trigger) {
        var modules = ["shop/cart", "shop/shipping", "shop/payments"].filter(
            function (module) {
                return !!document.querySelector('[data-type="' + module + '"');
            }
        );

        var events = ["mw.cart.modify"];

        if (!!events_to_trigger) {
            var events = events.concat(events_to_trigger);
        }

        if (modules.length) {
            mw.reload_modules(
                modules,
                function (data) {
                    events.forEach(function (item) {
                        mw.trigger(item, [data]);
                    });
                },
                true
            );
        } else {
            events.forEach(function (item) {
                mw.trigger(item, [data]);
            });
        }

        // mw.reload_module('shop/cart');
        // mw.reload_module('shop/shipping');
        // mw.reload_module('shop/payments');

        if (
            typeof data == "object" &&
            typeof data.cart_items_quantity !== "undefined"
        ) {
            // TASK-022 / TICKET-CZ / AI-40 (cycle-59 2026-05-08): keep
            // the cart badge hidden when the count drops to 0 (and
            // un-hide when it rises above 0). The blade renders the
            // span with `hidden` + `aria-hidden="true"` for empty
            // carts; this block keeps that state synced after an
            // add-to-cart / remove that fires `mw.cart.after_modify`.
            var $badge = $(".js-shopping-cart-quantity");
            var qty    = parseInt(data.cart_items_quantity, 10) || 0;
            $badge.html(data.cart_items_quantity);
            $badge.attr("data-cart-count", qty);
            if (qty <= 0) {
                $badge.attr("hidden", "hidden");
                $badge.attr("aria-hidden", "true");
            } else {
                $badge.removeAttr("hidden");
                $badge.removeAttr("aria-hidden");
            }
        }

        mw.trigger("mw.cart.after_modify", data);
        mw.trigger("cartModify", data);
    },

    checkout: function (selector, callback, beforeRedirect) {
        if (!beforeRedirect) {
            beforeRedirect = function () {
                return new Promise(function () {
                    resolve();
                });
            };
        }

        var form = mw.$(selector);
        $(document).trigger("checkoutBeforeProcess", form);

        var state = form.attr("data-loading");
        if (state == "true") return false;
        form.attr("data-loading", "true");
        form.find(".mw-checkout-btn").attr("disabled", "disabled");
        form.find(".mw-checkout-btn").hide();

        setTimeout(function () {
            var form = mw.$(selector);
            var obj = mw.form.serialize(form);

            $.ajax({
                type: "POST",
                url: mw.settings.api_url + "checkout",
                data: obj,
                error: function (xhr, ajaxOptions, thrownError) {
                    mw.errorsHandle(JSON.parse(xhr.responseText));
                    form.attr("data-loading", "false");
                    form.find(".mw-checkout-btn").removeAttr("disabled");
                    form.find(".mw-checkout-btn").show();
                },
            }).done(function (data) {
                mw.trigger("checkoutDone", data);

                var data2 = data;

                if (data != undefined) {
                    mw.$(selector + " .mw-cart-data-btn").removeAttr(
                        "disabled"
                    );
                    mw.$('[data-type="shop/cart"]').removeAttr("hide-cart");

                    if (typeof data2.error != "undefined") {
                        mw.$(selector + " .mw-cart-data-holder").show();
                        if (typeof data2.error.address_error != "undefined") {
                            var form_with_err = form;
                            var isModalForm =
                                $(form_with_err).attr("is-modal-form");

                            if (isModalForm) {
                                mw.cart.modal.showStep(
                                    form_with_err,
                                    "delivery-address"
                                );
                            }
                            mw.notification.error(
                                "Please fill your address details"
                            );
                        }

                        mw.response(selector, data2);
                    } else if (typeof data2.success != "undefined") {
                        if (typeof callback === "function") {
                            callback.call(data2.success);
                        } else if (typeof window[callback] === "function") {
                            window[callback](selector, data2.success);
                        } else {
                            mw.$('[data-type="shop/cart"]').attr(
                                "hide-cart",
                                "completed"
                            );
                            mw.reload_module("shop/cart");
                            mw.$(selector + " .mw-cart-data-holder").hide();
                            mw.response(selector, data2);
                        }

                        if (typeof data2.redirect != "undefined") {
                            setTimeout(function () {
                                beforeRedirect().then(function () {
                                    window.location.href = data2.redirect;
                                });
                            }, 100);
                            return;
                        } else {
                            mw.trigger("mw.cart.checkout.success", data2);
                            mw.trigger("checkoutSuccess", [data]);
                        }
                    } else if (parseInt(data) > 0) {
                        mw.$('[data-type="shop/checkout"]').attr(
                            "view",
                            "completed"
                        );
                        mw.reload_module("shop/checkout");
                    } else {
                        if (obj.payment_gw != undefined) {
                            var callback_func = obj.payment_gw + "_checkout";
                            if (typeof window[callback_func] === "function") {
                                window[callback_func](data, selector);
                            }
                            var callback_func = "checkout_callback";
                            if (typeof window[callback_func] === "function") {
                                window[callback_func](data, selector);
                            }
                        }
                    }
                }
                form.attr("data-loading", "false");
                form.find(".mw-checkout-btn").removeAttr("disabled");
                form.find(".mw-checkout-btn").show();
                mw.trigger("checkoutResponse", data);
            });
        }, 1500);
    },
};

// audit-test 2026-05-07 Cart per-issue follow-up #3 (TICKET-AQ Option B):
// Delegated click listener for Cart/Product templates that expose an
// Add-to-cart button as a data-attribute set instead of an inline onclick=.
// Fixes the `O'Brien Hardware` apostrophe-break (Blade-escape correctly
// handles HTML-attr context but not the JS-string sub-context that
// `onclick="...add_and_show_modal('{{$title}}')..."` builds), AND closes
// the strict-CSP `script-src 'self'` blocker on inline-onclick.
//
// Templates opt in by adding:
//   <button class="mw-add-to-cart-btn"
//           data-content-id="{{ $for_id }}"
//           data-price="{{ $v }}"
//           data-title="{{ $title }}">…</button>
// And for out-of-stock buttons (cycle-36 finding #12 — disabled buttons
// don't fire click, so we use aria-disabled instead and a data-alert):
//   <button class="mw-add-to-cart-disabled-btn" aria-disabled="true"
//           data-alert-message="{{ ... }}">…</button>
document.addEventListener("click", function (e) {
    var disabledBtn = e.target.closest(
        ".mw-add-to-cart-disabled-btn"
    );
    if (disabledBtn) {
        e.preventDefault();
        var msg =
            disabledBtn.getAttribute("data-alert-message") ||
            mw.lang("This item cannot be ordered");
        mw.alert(msg);
        return;
    }

    var btn = e.target.closest(".mw-add-to-cart-btn");
    if (btn) {
        e.preventDefault();
        var contentId = btn.getAttribute("data-content-id") || "";
        var price = btn.getAttribute("data-price") || "";
        var title = btn.getAttribute("data-title") || "";

        // task-2026-06-06-cfcart — collect the product's custom-field
        // selections (Size, Session Focus, …) from the enclosing add-to-cart
        // holder so they're sent with the item. Without this only
        // data-content-id/data-price were posted and every option (and its
        // price modifier) was dropped. Enforce required fields client-side too.
        var fields = {};
        var holder = btn.closest(".mw-add-to-cart-holder") || btn.form || null;
        var invalidField = null;
        if (holder) {
            holder
                .querySelectorAll("input[name], select[name], textarea[name]")
                .forEach(function (input) {
                    if (
                        (input.type === "radio" || input.type === "checkbox") &&
                        !input.checked
                    ) {
                        return;
                    }
                    var name = input.getAttribute("name");
                    if (!name) {
                        return;
                    }
                    if (input.required && !String(input.value || "").trim()) {
                        invalidField = invalidField || input;
                    }
                    fields[name] = input.value;
                });
        }
        if (invalidField) {
            if (typeof invalidField.reportValidity === "function") {
                invalidField.reportValidity();
            } else {
                mw.alert(mw.lang("Please fill in the required options."));
            }
            return;
        }

        if (typeof mw.cart !== "undefined" && typeof mw.cart.add_and_show_modal === "function") {
            mw.cart.add_and_show_modal(contentId, price, title, fields);
        }
    }
});

if (typeof mw.cart.modal == "undefined") {
    mw.cart.modal = {};
}
if (typeof mw.cart.modal.init == "undefined") {
    mw.cart.modal.init = function (root_node) {
        mw.cart.modal.bindStepButtons(root_node);

        /*
            var inner_cart_module = $(root_node).find('[parent-module-id="js-ajax-cart-checkout-process"]')[0];
        */
        var inner_cart_module = $(root_node).find(
            '[id="cart_checkout_js-ajax-cart-checkout-process"]'
        )[0];
        if (inner_cart_module) {
            var check = $(document).find(
                '[id="' + inner_cart_module.id + '"]'
            ).length;
            mw.on.moduleReload(inner_cart_module.id);
        }
    };
}
if (typeof mw.cart.modal.bindStepButtons == "undefined") {
    mw.cart.modal.bindStepButtons = function (root_node) {
        if (typeof root_node === "string") {
            root_node = mw.$(root_node);
        }

        if (root_node[0]._bindStepButtons) {
            return;
        }
        root_node[0]._bindStepButtons = true;

        var checkout_form = $(root_node).find("form").first();

        $("body").on("click", ".js-show-step", function () {
            var step = $(this).attr("data-step");

            mw.cart.modal.showStep(checkout_form, step);
        });
    };

    mw.cart.modal.showStep = function (form, step) {
        var prevStep = mw.$(".js-show-step.active", form).data("step");

        if (prevStep === step) return;

        var prevHolder = $(form)
            .find(".js-" + prevStep)
            .first();

        $(form).attr("is-modal-form", true);

        if (step === "checkout-complete") {
            return;
        }

        var validate = function (callback) {
            var hasError = false;
            mw.$("input,textarea,select", prevHolder).each(function () {
                if (!this.checkValidity()) {
                    mw.$(this).addClass("is-invalid");
                    hasError = true;
                } else {
                    mw.$(this).removeClass("is-invalid");
                }
            });
            if (step === "payment-method" || step === "preview") {
                if (hasError) {
                    step = "delivery-address";
                    callback.call(undefined, hasError, undefined, step);
                }
            }
            if (step === "payment-method") {
                $.post(
                    mw.settings.api_url + "checkout/validate",
                    mw.serializeFields(prevHolder),
                    function (data) {
                        if (!data.valid) {
                            step = "delivery-address";
                        }
                        callback.call(undefined, !data.valid, undefined, step);
                    }
                ).fail(function (data) {
                    mw.errorsHandle(data);
                });
            } else {
                callback.call(undefined, hasError, undefined, step);
            }
        };

        validate(function (hasError, message, step) {
            if (hasError) {
                message =
                    message ||
                    mw.lang("Please fill properly the required fields");
                mw.notification.warning(message);
            }

            mw.$(".js-show-step").removeClass("active");
            mw.$("[data-step]").removeClass("active");
            mw.$('[data-step="' + step + '"]')
                .addClass("active")
                .parent()
                .removeClass("muted");
            mw.$(this).addClass("active");
            var step1 = ".js-" + step;
            mw.$(".js-step-content").hide();
            mw.$(step1).show();
        });
    };
}
