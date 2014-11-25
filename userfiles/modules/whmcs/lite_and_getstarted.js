




mw.utils = {
    disableChecking:false,
    stateloading: function (state, text) {
        var state = state || false;
        var text = text || '';
        mw.$('#the_loading_text').html(text);
        if (state) {
            mw.$("#stateloading").css("visibility", "visible");
            $(mwd.body).addClass("loading");
        }
        else {
            mw.$("#stateloading").css("visibility", "hidden");
            $(mwd.body).removeClass("loading");
        }
    },
    supportsAnimation: function () {
        if (typeof mwd.body.style.animationName !== 'undefined') {
            return true;
        }
        var p = ["Webkit", "Moz", "O", "ms", "Khtml"], l = p.length, i = 0;
        for (; i < l; i++) {
            if (typeof mwd.body.style[p[i]] !== 'undefined') {
                return true;
            }
        }
        return false;
    },
    chatToggle: function () {
        if (typeof $zopim === 'undefined') {
            return false;
        }
        $zopim.livechat.window.toggle();
    },
    tipcreated: false,
    tip: function (node, html, pos) {
        if (!mw.utils.tipcreated) {
            mw.utils.tipcreated = true;
            mw.utils.tooltip = mwd.createElement('div');
            mw.utils.tooltip.className = 'tip';
            mw.utils.tooltip.show = function () {
                mw.utils.tooltip.style.display = 'inline-block';
            }
            mw.utils.tooltip.hide = function () {
                mw.utils.tooltip.style.display = 'none';
            }
            mw.utils.tooltip.hide();
            mwd.body.appendChild(mw.utils.tooltip);
            $(mw.utils.tooltip).mouseenter(function () {
                $(this).addClass("mouseenter")
            });
            $(mw.utils.tooltip).mouseleave(function () {
                $(this).removeClass("mouseenter")
            });
        }
        if (node === null) {
            return false;
        }
        if (node == 'hide') {
            mw.utils.tooltip.hide();
            return false;
        }
        mw.tools.addClass(node, 'has-tip');
        var html = html || $(node).dataset("tip");
        var pos = pos || $(node).dataset("pos");
        if (html == '' || typeof html == 'undefined') {
            return false;
        }
        if (pos == '' || typeof pos == 'undefined') {
            'topcenter';
        }
        var off = $(node).offset();
        mw.utils.tooltip.innerHTML = html;
        mw.utils.positionTooltip(mw.utils.tooltip, off, pos, node);
        mw.utils.tooltip.show();
    },
    positionTooltip: function (tip, off, pos, el) {  //node must have methods .show() & .hide()
        var pos = pos || 'topcenter';
        var tip = tip || mw.utils.tooltip;
        tip.style.opacity = 0;
        tip.show();
        var w = $(tip).outerWidth();
        var h = $(tip).outerHeight();
        var ew = $(el).outerWidth();
        var eh = $(el).outerHeight();

        if (pos == 'topcenter') {
            $(tip).css({
                top: off.top - 12 - h,
                left: off.left - w / 2 + ew / 2
            });
        }
        else if (pos == '') {

        }

        tip.style.opacity = 1;
        tip.hide();
    }
}
issearching = null;


domainDropdownScale = function(){
    var dd = mwd.getElementById('domain_selector');
    if(dd === null){return false;}

    if($(dd).getDropdownValue().contains("microweber.com")){
        mw.$(".freeico").show();
    }
    else{
        mw.$(".freeico").hide();
    }

   // if(mw.$(".domain_form_logged").length > 0){
      var valh = mw.$(".mw-dropdown-value", dd);
      var calc1 = valh.width() + 45;
      dd.style.width = calc1 + 'px';
      mw.$("#domain-search-field").width(mw.$("#select_domain_field_dropdown").width() - calc1 - 30);
  //  }
}


$(document).ready(function () {
    PTABS = mw.$("#plans-and-pricing-tabs");
    $(window).bind("scroll resize", function () {

        $(this).scrollTop() > 102 ? PTABS.addClass("fixed12") : PTABS.removeClass("fixed12");

    });


    $(mwd.getElementById('domain_selector')).bind("change", function () {
        mw.$("#domain-search-field").trigger("change");
        domainDropdownScale()
    });

    $(window).bind("load resize", function(){
      domainDropdownScale();
    });


    mw.$("#domain-search-field").bind("keydown keyup change", function (e) {

        if(mw.utils.disableChecking){
          return false;
        }

        if(e.keyCode === 13){
          return false;
        }

        if (this.value == '') {
            mw.$("#domain-search-ajax-results").attr("class", "");
            mw.$("#domain-search-ajax-results i").attr("class", "");
            issearching = null;
        }
        else {
            var w = e.keyCode;

            if (w === 32) {
                return false;
            }

            if (e.type == 'keyup' || e.type == 'change') {
                if (w != 32 && !e.ctrlKey) {
                    var val = this.value;
                    var val = val.replace(/[`~!@#$%^&*()_|+\=?;:'",<>\{\}\[\]\\\/]/gi, '');
                    var val = val.replace(/-+$|(-)+/g, '-');
                    if (val.indexOf("-") == 0) {
                        var val = val.substring(1);
                    }
                    if (val != '') {
                        this.value = val;

                        if (typeof issearching === 'number') {
                            clearTimeout(issearching);
                        }

                        issearching = setTimeout(function () {
                            var val = mwd.getElementById('domain-search-field').value;
                            if (val.contains(".")) {
                                var ar = val.split(".");
                                var a = ar[1];
                                if (a == 'com' || a == 'net' || a == 'org') {
                                    mwd.getElementById('domain-search-field').value = ar[0];
                                    var val = ar[0];
                                    mw.$("#domain_selector").setDropdownValue("." + a);
                                }
                                else {
                                    if (ar.length === 2) {
                                        mwd.getElementById('domain-search-field').value = ar[0] + "-" + ar[1];
                                    }
                                    else {
                                        var final = "";
                                        for (var i = 0; i < ar.length; i++) {
                                            if (ar[i] != '') {
                                                if ((i + 1) < ar.length) {
                                                    final += ar[i] + "-";
                                                }
                                                else {
                                                    final += ar[i];
                                                }
                                            }
                                        }
                                        mwd.getElementById('domain-search-field').value = final;
                                    }
                                }
                            }


                            var tld = $(mwd.getElementById('domain_selector')).getDropdownValue();

                            mw.$("#domain-search-ajax-results").attr("class", "loading");
                            mw.$("#domain-search-ajax-results i").attr("class", "icon-cog icon-spin");

                            $("#mw-domain-val").val('');

                            mw.whm.domain_check(val + tld, false, function (data) {

                                if (data != null && data != '' && data != undefined) {
                                    if (data.status == "available") {
                                        $("#mw-domain-val").val(val + tld);
                                        mw.$("#domain-search-ajax-results").attr("class", "yes");
                                        mw.$("#domain-search-ajax-results i").attr("class", "icon-check-sign");
                                        mw.$("#user_registration_form").removeClass("unavailable").addClass("available");
                                        mw.$("#hempas").height(80);
                                        mw.$("#blueBtnLoadingLoginSubmit").addClass('activate-80');
                                    }
                                    else if (data.status == "unavailable") {
                                        mw.$("#domain-search-ajax-results").attr("class", "no");
                                        mw.$("#domain-search-ajax-results i").attr("class", "icon-ban-circle");
                                        mw.$("#user_registration_form").addClass("unavailable").removeClass("available");
                                    }
                                    else if (typeof data.status == "undefined") {
                                        mw.$("#domain-search-ajax-results").attr("class", "no");
                                        mw.$("#domain-search-ajax-results i").attr("class", "icon-ban-circle");
                                        mw.$("#user_registration_form").addClass("unavailable").removeClass("available");
                                    }
                                    else {

                                    }
                                }
                                else {
                                    mw.$("#domain-search-ajax-results").attr("class", "");
                                    mw.$("#domain-search-ajax-results i").attr("class", "");
                                    $("#mw-domain-val").val(val + tld);
                                }
                                issearching = null;
                            });
                            if (mw.$("#domain-search-field").val() == '') {
                                mw.$("#domain-search-ajax-results").attr("class", "");
                                mw.$("#domain-search-ajax-results i").attr("class", "");
                                issearching = null;
                            }
                        }, 700);
                    }
                    else {
                        mw.$("#domain-search-ajax-results").removeClass("active");
                        issearching = null;
                    }
                }
            }
        }
    });
    mw.$("#plan_chooser").bind("change", function () {
        var val = $(this).getDropdownValue();
        mw.$("#radio_item_" + val)[0].checked = true;
    });
    mw.$(".domain-search-form").bind("click", function (e) {
        if (mw.tools.hasClass(e.target, 'box-content') || mw.tools.hasClass(e.target, 'box')) {
            mw.$("#domain-search-field").focus()
        }
    });


    var p = mw.$("#the_pass");
    var pk = mw.$("#set_the_pass");

    p.attr("type", "password");

    pk.click(function () {

        if (p.attr("type") == "password") {
            p.attr("type", "text");
            pk.addClass('the-eye-close');
            pk.removeClass('the-eye-open');
            pk.attr("title", "Hide Password");
            p.focus();
            if (!!p[0].setSelectionRange) {
                p[0].setSelectionRange(p[0].value.length, p[0].value.length);
            }

        }
        else {
            p.attr("type", "password");
            pk.removeClass('the-eye-close');
            pk.addClass('the-eye-open');
            pk.attr("title", "Show Password");
            p.focus();
            if (!!p[0].setSelectionRange) {
                p[0].setSelectionRange(p[0].value.length, p[0].value.length);
            }
        }
    });


});


$(document).ready(function () {

mw.$("#blueBtnLoadingLoginSubmit input").bind("click", function(e){
  if(!mw.$("#domain-search-ajax-results").hasClass("yes") || mw.$("#domain-search-field").val() == ''){
    Alert("Please enter valid domain name");
    e.preventDefault();
    return false;
  }
});

    mw.$('#user_registration_form').bind("submit", function () {

       mw.utils.disableChecking = true;

        var _this = this;

        if($(_this).hasClass("submitting")){
          return false;
        }

        if(!mw.$("#domain-search-ajax-results").hasClass("yes") || mw.$("#domain-search-field").val() == ''){
          Alert("Please enter valid domain name");
          return false;
        }
        $(_this).addClass("submitting");


		
		//mw.image.preload('')
		
		

         mw.dotloader.btnSet(mwd.getElementById('blueBtnLoadingLoginSubmit').getElementsByTagName('input')[0]);

		var form = ('#user_registration_form') ;
		var form_data = mw.form.serialize('#user_registration_form')

		 
		 

        if(form_data.whm_order_domain != undefined){

		

		 var domain = form_data.whm_order_domain;

		  var tld = $('#domain_selector').getDropdownValue()
	
		
		
		
		var cont = domain.contains('microweber.com');
		//var cont = 1;
		 if(!cont){


             mw.cookie.set("whmcartinfo", "", -1, "/", "members.microweber.com");







			 _this.action = 'https://members.microweber.com/cart.php';

             var email = mw.$("#the_email").val();
             var pass = mw.$("#the_pass").val();

             mw.cookie.set("whmcartinfo", "email="+email+"&pass="+pass+"", 1, "/", "members.microweber.com");

			 var html_fields = '<input type="hidden" name="domain" value="register"> <input type="hidden" name="domainoption" value="register">';
 
			 html_fields = html_fields+ '<input type="hidden" name="a" value="add">';
			 html_fields = html_fields+ '<input type="hidden" name="sld" value="">';

			 html_fields = html_fields+ '<input type="hidden" name="billingcycle" value="monthly">';
			 html_fields = html_fields+ '<input type="hidden" name="pid" value="10">'; 
	 		 html_fields = html_fields+ '<input type="hidden" name="skipconfig" value="1">';
			html_fields = html_fields+ '<input type="hidden" name="tld" value="'+tld+'">';

			html_fields = html_fields+ '<input type="hidden" name="domains[]" value="'+domain+'">';
			html_fields = html_fields+ '<input type="hidden" name="domainsregperiod['+domain+']" value="1">';
			$(_this).append(html_fields);
			

			return true;



		 }
		 
		
			
		}
 
//whm_user_register
//user_register

        mw.form.post(mw.$('#user_registration_form'), mw.settings.api_url + 'whm_user_register', function () {
            mw.$('#user_registration_form').removeClass("submitting");
            mw.dotloader.btnUnset(mwd.getElementById('blueBtnLoadingLoginSubmit').getElementsByTagName('input')[0]);
            mw.response('#site-reg-form-holder', this);
            var redir_base = mw.settings.api_url + 'panel_user_link';
            var redir = redir_base + "?goto=clientarea.php?action=products";

			 var redir = mw.settings.site_url + "profile";
			
            if (this.invoiceid !== undefined && this.invoiceid != 0) {
                var intVal = (this.invoiceid);
              
                redir = redir_base + "?goto=creditcard.php?invoiceid=" + intVal;
			
            }
          window.location.href = redir;
		  return false;
        });
        return false;






    });

    mw.$("#user_registration_submit").click(function () {

        if (!$(this).hasClass("disabled")) {
            mw.$('#user_registration_form').trigger("submit")
        }

    });


    var q = mwd.getElementById('domain-search-field'),
        w = mwd.getElementById('the_email'),
        e = mwd.getElementById('the_pass');


    if (q !== null && typeof q.validity !== 'undefined') {
        if (q !== null && w !== null && e !== null) {
            $(mwd.body).bind("keyup mousemove", function () {
                if (q.validity.valid && w.validity.valid && e.validity.valid) {
                    mw.$("#user_registration_submit").removeClass("disabled");
                }
                else {
                    mw.$("#user_registration_submit").addClass("disabled");
                }
            });
        }

    }


});