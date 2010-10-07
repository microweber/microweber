mw.comments = {};

mw.comments = new function() {

	this.servicesUrl = '{SITEURL}/ajax_helpers/',

	/* ~~~ private methods ~~~ */

	this._beforeSend = function() {

		var isValid;
		if ($(".commentForm").hasClass("error")) {
			isValid = false;
		} else {
			isValid = true;
		}

		return isValid;
	}

	this._afterSend = function(t, tt, updateElementId) {
		// getComments(t, tt, updateElementId);
	}

	/* ~~~ public methods ~~~ */

	this.postComment = function(form, t, tt, updateElementId) {

		var requestOptions = {
			url : '{SITEURL}api/comments/' + 'comments_post',
			clearForm : true,
			type : 'post',
			beforeSubmit : this._beforeSend,
			success : this._afterSend(t, tt, updateElementId)
		};

		form.ajaxSubmit(requestOptions);
		return false;
	};

	this.getComments = function($to_table, $to_table_id, $placeholder) {

		$.post("{SITEURL}api/comments/comments_list", {
			to_table : $to_table,
			to_table_id : $to_table_id
		}, function(data) {
			$($placeholder).html(data);
		});
	};

}

$(document).ready(
		function() {

			$('.commentForm').submit(
					function() {
						mw.comments.postComment($(this), $(this).find(
								"input[name='to_table']").val(), $(this).find(
								"input[name='to_table_id']").val(), $(this)
								.find("input[name='related_list']").val());
						$(this).fadeOut();

						$(this).find('.commentFormSuccess').fadeIn();

						return false;
					});
			// mw.users.Dashboard.getCounts(<?php echo $statuses_ids_json;?>,
			// <?php echo $contents_ids_json;?>);
		});
