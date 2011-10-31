mw.users = {};

mw.users.get = function() {
	alert('get');

};

mw.users.AjaxLogin = function($redirect_to) {
	if ($redirect_to == false) {
		$back_location = (window.location.href);
	} else {
		$back_location = ($redirect_to);
	}

	$backto = Base64.encode($back_location)
	mw.box.remove();
	mw.box.overlay();
	mw.box.ajax( {
		url : '{SITEURL}users/user_action:login_ajax/back_to:' + $backto,
		width : 350,
		height : 280,
		id : 'ajax_login'
	});
	msRoundedField();
}


mw.users.register = function($form_selector, $callback) {
	$data = ($($form_selector).serialize());

	$.ajax( {
		type : "POST",
		url : "{SITE_URL}api/user/register",
		data : $data,
		dataType: 'json',
		success : function(msg) {
			//alert("Data: " + msg);
		 // $('.cart_items_qty').html(msg);
 
		if (typeof  $callback == 'function') {
			$callback.call(this, msg);
		} else {
			$($callback).fadeOut();
		}
		
		
		
		
			
		}
	});

}

mw.users.login = function($form_selector, $callback) {
	$data = ($($form_selector).serialize());

	$.ajax( {
		type : "POST",
		url : "{SITE_URL}api/user/login",
		data : $data,
		dataType: 'json',
		success : function(msg) {
			//alert("Data: " + msg);
		 // $('.cart_items_qty').html(msg);
 
		if (typeof  $callback == 'function') {
			$callback.call(this, msg);
		} else {
			//$($callback).fadeOut();
		}
		
		
		
		
			
		}
	});

}


mw.users.save = function($form_selector, $callback) {
	$data = ($($form_selector).serialize());

	$.ajax( {
		type : "POST",
		url : "{SITE_URL}api/user/save",
		data : $data,
		dataType: 'json',
		success : function(msg) {
			//alert("Data: " + msg);
		 // $('.cart_items_qty').html(msg);
 
		if (typeof  $callback == 'function') {
			$callback.call(this, msg);
		} else {
			$($callback).fadeOut();
		}
		
		
		
		
			
		}
	});

}


mw.users.ChangePass = function() {
	// $back_location = (window.location.href);
	// $backto = Base64.encode($back_location)
	mw.box.remove();
	mw.box.overlay();
	$.post('{SITEURL}users/user_action:password/', function(data) {
		var checker = document.createElement('div');
		checker.innerHTML = data;
		if ($(checker).find("meta").length == 0) {
			mw.box.html( {
				width : 370,
				height : 340,
				html : data,
				id : 'change_password_modal_window'
			});
		} else {
			window.location.href = '{SITEURL}users/user_action:login/';
		}

	});

}

mw.users.LogOut = function() {

	$.ajax( {
		async : false,
		url : "{SITEURL}fb_login/logout",
		context : document.body,
		success : function() {

		}
	});

	$.ajax( {
		async : false,
		url : "{SITEURL}api/user/logOut",
		context : document.body,
		success : function() {
			window.location.reload();
		}
	});

}

mw.users.UserMessage = new function() {

	this.servicesUrl = '{SITEURL}ajax_helpers/message_';

	/* ~~~ private methods ~~~ */

	this._beforeSend = function() {

		var isValid;
		if ($("#message_form").hasClass("error")) {
			isValid = false;
		} else {
			isValid = true;
		}

		return isValid;
	};

	this._afterSend = function() {
		window.location.reload();
	};

	/* ~~~ public methods ~~~ */

	this.send = function() {

		var requestOptions = {
			// url : this.servicesUrl + 'send',
			url : '{SITEURL}api/user/message_send',
			clearForm : true,
			type : 'post',
			beforeSubmit : this._beforeSend,
			success : function(response) {
				mw.box.remove();
				// mwbox.notification(response)
			window.location.reload();
		}
		};

		$('#message_form').ajaxSubmit(requestOptions);
		return false;
	};

	this.sendQuick = function($form) {
		$form = $($form).parents("form");
		var $form = $($form);
		var requestOptions = {
			url : '{SITEURL}api/user/message_send',
			clearForm : true,
			type : 'post',
			beforeSubmit : this._beforeSend,
			success : function(response) {

				mw.box.remove();
				mw.box.alert("<h2 style='text-align:center'>" + response
						+ "</h2>")
			}
		};

		$form.ajaxSubmit(requestOptions);
		return false;
	};

	this.read = function(id) {
		$.post('{SITEURL}api/user/message_read', {
			id : id
		}, function(response) {
			if (parseInt(response) == parseInt(id)) {
				// $('#messageReadControl-'+id).attr({
				// title: 'Mark as unread',
				// onclick: 'UserMessage.unread('+id+');'
				// });
				// $('#messageReadControl-'+id).click(function(){
				// UserMessage.unread(id);
				// });
				// mw.utils.elementBlink('messageReadControl-'+id);
				$('#messageItem-' + id).removeClass('messageUnread').addClass(
						'messageRead');
			}
		});
	};

	this.unread = function(id) {
		$.post('{SITEURL}api/user/message_unread', {
			id : id
		}, function(response) {
			if (parseInt(response) == parseInt(id)) {
				// $('#messageReadControl-'+id).attr({
				// title: 'Mark as read',
				// onclick: 'UserMessage.read('+id+');'
				// });
				// $('#messageReadControl-'+id).click(function(){
				// UserMessage.read(id);
				// });
				// show that something happens
				// mw.utils.elementBlink('messageReadControl-'+id);
				$('#messageItem-' + id).removeClass('messageRead').addClass(
						'messageUnread');

			}
		});
	};

	this.del = function(id, itemContainerId) {

		$.post(

		'{SITEURL}api/user/message_delete', {
			id : id
		}, function(response) {
			if (parseInt(response) == parseInt(id)) {
				$('#' + itemContainerId).fadeOut(300, function() {
					$('#' + itemContainerId).remove();
				});
			}
		});
	};

	this._getSelected = function() {

		// sdfsd-123
		var ids = [];
		var counter = 0;

		$("input[id*='messageReadControl-']").each(
				function() {
					if ($(this).is(":checked")) {
						ids[counter] = $(this).attr("id").replace(
								'messageReadControl-', '');
						counter++;
					}
				});

		return ids;
	};

	this.readSelected = function() {
		var ids = this._getSelected();
		for ( var i = 0; i < ids.length; i++) {
			this.read(ids[i]);
		}

	};

	this.unreadSelected = function() {
		var ids = this._getSelected();
		for ( var i = 0; i < ids.length; i++) {
			this.unread(ids[i]);
		}
	};

	this.deleteSelected = function() {
		var ids = this._getSelected();
		for ( var i = 0; i < ids.length; i++) {
			this.del(ids[i], 'messageItem-' + ids[i]);
		}
	};

	this.selectAll = function() {

		$("input[id*='messageReadControl-']").check();
	};

	this.deselectAll = function() {

		$("input[id*='messageReadControl-']").uncheck();
	};

	this.compose = function(to, conversation) {

		var params = '';
		if (to) {
			params += '/to:' + to;
		}
		if (conversation) {
			params += '/conversation:' + conversation;
		}
		// mwbox.displayAjax(this.servicesUrl + 'send_form' + params, 400, 300);
		mw.box.remove();
		/*
		 * mw.box.ajax( { url : '{SITEURL}dashboard/action:message_compose/' +
		 * params, width : 400, height : 360, id : 'messagecompose' });
		 */

		var params = {
			module : "messages/compose",
			to : to,
			conversation : conversation
		}

		$.post("{SITEURL}api/module", params, function(data) {

			mw.box.html( {
				html : data,
				width : 430,
				height : 360,
				id : 'messagecompose'
			})
		})

	};

}

mw.users.log_delete = function(post_id, hide_element_or_callback) {
	var answer = confirm("Are you sure you want to delete this?");

	if (answer) {

		$.post('{SITEURL}api/user/delete_log_item', {
			id : post_id
		}, function(response) {

			// if (response == 'yes') {
				if (typeof (hide_element_or_callback) == 'function') {
					hide_element_or_callback.call(this);
				} else {
					$(hide_element_or_callback).fadeOut();
				}
				// } else {
				// alert(response);
				// }

			});

	}

}

// Message class
mw.users.UserNotification = new function() {

	this.servicesUrl = '{SITEURL}ajax_helpers/';

	/* ~~~ private methods ~~~ */

	this.read = function(id) {

		if ($('#notificationItem-' + id).hasClass('messageUnread')) {
			$('#notificationItem-' + id).removeClass('messageUnread');
			$.post('{SITEURL}api/user/notification_read', {
				id : id
			}, function(response) {
				if (parseInt(response) == parseInt(id)) {

					$('#notificationItem-' + id).addClass('messageRead');
					// $('#notificationItem-' + id).fadeIn(400);

				}
			});
		}
	};

	this.remove = function(id) {
		$.post('{SITEURL}api/user/notification_delete', {
			id : id
		}, function(response) {
			if (parseInt(response) == parseInt(id)) {
				$('#notificationItem-' + id).fadeOut(300, function() {
					$('#notificationItem-' + id).remove();
				});
			}
		});

	};

}

// Follow class
mw.users.FollowingSystem = new function() {

	// this.servicesUrl = '{SITEURL}ajax_helpers/',
			this.servicesUrl = '{SITEURL}api/user/followingSystem',
			/* ~~~ private methods ~~~ */

			this._follow = function(follower_id, follow, special, cancel) {
				$.post(this.servicesUrl, {
					follower_id : follower_id,
					follow : follow,
					cancel : cancel,
					special : special
				}, function(response) {
					// alert("Response: " + response);
						if (response.length > 0) {

							if ((response.valueOf()) == 'login required') {

								mw.users.AjaxLogin();

							} else {
								$(".box-ico-follow").hide();
								$(".box-ico-unfollow").hide();
								mw.box.notification( {
									html : response
								});
							}

						}
					});
			};

	/* ~~~ public methods ~~~ */

	this.follow = function(follower_id, special, hide_element_or_callback) {
		this._follow(follower_id, 1, special);

		if (typeof (hide_element_or_callback) == 'function') {
			hide_element_or_callback.call(this);
		} else {
			$(hide_element_or_callback).fadeOut();
		}

	};

	this.unfollow = function(follower_id, itemContainerId) {
		// this._follow(follower_id, 0);
		this._follow(follower_id, 0, 0, 1);
		/*
		 * $('#' + itemContainerId).fadeOut(300, function() { $('#' +
		 * itemContainerId).remove(); });
		 */
		$(itemContainerId).fadeOut();

	};
	this.cancel_relationship = function(follower_id, itemContainerId) {
		this._follow(follower_id, 0, 0, 1);
		/*
		 * $('#' + itemContainerId).fadeOut(300, function() { $('#' +
		 * itemContainerId).remove(); });
		 */
		$(itemContainerId).fadeOut();

	};

	this.makeSpecial = function(followers_ids) {
		/*
		 * for ( var i = 0; i < followers_ids.length; i++) {
		 *  }
		 */
		this._follow(followers_ids, 1, 1);
	};

}

// Message class

mw.users.delete_user =  function($id, hide_element_or_callback) {
	var answer = confirm("Are you sure you want to delete this user?");

	if (answer) {

		$.post('{SITEURL}api/user/delete_user', {
			id : $id
		}, function(response) {

			// if (response == 'yes') {
				if (typeof (hide_element_or_callback) == 'function') {
					hide_element_or_callback.call(this);
				} else {
					$(hide_element_or_callback).fadeOut();
				}
				// } else {
				// alert(response);
				// }

			});

	}
}

mw.users.User = new function() {

	this.servicesUrl = '{SITEURL}ajax_helpers/',

	/* ~~~ private methods ~~~ */

	this._beforeSend = function() {

		var isValid;
		if ($("#update-status").hasClass("error")) {
			isValid = false;
		} else {
			isValid = true;
		}

		return isValid;
	}

	this._afterSend = function(hide_element_or_callback) {

		setTimeout(function() {

			$("#update-status").fadeOut();
			$("#update-status").fadeIn();
			$("#update-status-done").fadeIn();
			$("#update-status-done").fadeOut(5000);

			if (typeof (hide_element_or_callback) == 'function') {
				hide_element_or_callback.call(this);
			} else {
				$(hide_element_or_callback).fadeOut();
			}

		}, 500);

	}

	/* ~~~ public methods ~~~ */

	this.statusUpdate = function(form, hide_element_or_callback) {
		if (!form) {
			form = $('#update-status');
		} else {
			form = $(form);
		}
		var requestOptions = {
			url : '{SITEURL}api/user/status_update',
			clearForm : false,
			async : false,
			type : 'post',
			beforeSubmit : this._beforeSend,
			success : this._afterSend(hide_element_or_callback)
		};
		form.ajaxSubmit(requestOptions);
		return false;
	};

}

mw.users.Dashboard = new function() {

	this.servicesUrl = '{SITEURL}ajax_helpers/';

	this.getCounts = function(statusesIds, contentsIds) {
		$.post(this.servicesUrl + 'dashboardCounts', {
			statusesIds : statusesIds,
			contentsIds : contentsIds
		}, function(response) {

			// load votes stats
				var stats = response['statuses'];
				for ( var i = 0; i < stats['votes'].length; i++) {
					var itemId = stats['votes'][i]['item_id'];
					var etemValue = stats['votes'][i]['votes_total'];
					$('#status-votes-' + itemId).html(etemValue);
				}
				for ( var i = 0; i < stats['comments'].length; i++) {
					var itemId = stats['comments'][i]['item_id'];
					var etemValue = stats['comments'][i]['comments_total'];
					$('#status-comments-' + itemId).html(etemValue);
				}

				// load comments stats
				stats = response['contents'];
				for ( var i = 0; i < stats['votes'].length; i++) {
					var itemId = stats['votes'][i]['item_id'];
					var etemValue = stats['votes'][i]['votes_total'];
					$('#content-votes-' + itemId).html(etemValue);
				}
				for ( var i = 0; i < stats['comments'].length; i++) {
					var itemId = stats['comments'][i]['item_id'];
					var etemValue = stats['comments'][i]['comments_total'];
					$('#content-comments-' + itemId).html(etemValue);
				}

			}, "json");
	};

	this.comment = function() {
		alert("Add comment");
	};

	this.vote = function() {
		alert("Vote item");
	};

}
