$(document).ready(function () {

	$(document).on("change", ".js-validation", function() {
		runFieldsValidation(this);
	});

	// this is the id of the form
	$(".js-save-offer").click(function(e) {

			$(".js-validation-messages").html("");

			var ok = true;
			var form = $(".js-edit-offer-form");
			var url = form.attr('action');

			$('.js-edit-offer-form :input').each(function() {
				if ($(this).hasClass('js-validation')) {
					ok = runFieldsValidation(this);
				}
			});

			if (ok) {



                mw.reload_module_everywhere('shop/offers/special_price_field');
                 mw.reload_module('shop/offers/special_price_field');
                 mw.reload_module('shop/offers/special_price_field');

				$.ajax({
						type: "POST",
						dataType: "json",
						url: url,
						data: form.serialize(),
						success: function(msg) {

							if (typeof(msg.data.error_message) !== "undefined") {
								// mw.notification.error(data.error_message);
								$(".js-validation-messages").html(errorMessage(msg.data.error_message));
								//scrollTopModal();
							}
							if (typeof(msg.data.success_edit) !== "undefined") {
								mw.notification.success(TEXT_SUCCESS_SAVE);
								if (typeof(reload_offer_after_save) != 'undefined') {
									reload_offer_after_save();
								}
								editModal.modal.remove();
                            }
						}
					});
			} else {
				// mw.notification.error(TEXT_FILL_ALL_FIELDS);
				$(".js-validation-messages").html(errorMessage(TEXT_FILL_ALL_FIELDS));
				//scrollTopModal();
			}

			e.preventDefault()
	});

	function runFieldsValidation(instance) {

		var ok = true;

		$(instance).removeAttr("style");
		$(instance).parent().find(".js-field-message").html('');

		if ($(instance).val() == "") {
			$(instance).css("border", "1px solid #b93636");
			$(instance).parent().find('.js-field-message').html(errorText(TEXT_FIELD_CANNOT_BE_EMPTY));
			ok = false;
		}

		if ($(instance).hasClass('js-validation-number')) {
			if (isInteger(parseFloat($(instance).val())) == false) {
				$(instance).css("border", "1px solid #b93636");
				$(instance).parent().find('.js-field-message').html(errorText(TEXT_FIELD_MUST_BE_NUMBER));
				ok = false;
			}
		}

		if ($(instance).hasClass('js-validation-float-number')) {
			if (isFloatOrInteger(parseFloat($(instance).val())) == false) {
				$(instance).css("border", "1px solid #b93636");
				$(instance).parent().find('.js-field-message').html(errorText(TEXT_FIELD_MUST_BE_FLOAT_NUMBER));
				ok = false;
			}
		}

		return ok;
	}

	function scrollTopModal() {
		$('.mw_modal_container').scroll();
		$(".mw_modal_container").animate({
			scrollTop: 0
		}, 444);
	}

});

function errorText(text) {
	return '<div class="js-danger-text">' + text + '</div>';
}

function errorMessage(text) {
	return '<div class="js-danger-alert">' + text + '</div>';
}

function uniqueId() {
	function s4() {
		return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
	}
	return s4() + s4();
}

function isFloat(n) {
	return typeof(n)==="number" && n === +n && Math.round(n) !== n;
}

function isInteger(n) {
	return typeof(n)==="number" && n === +n && Math.round(n) === n;
}

function isFloatOrInteger(n) {
	return isFloat(n) || isInteger(n);
}
