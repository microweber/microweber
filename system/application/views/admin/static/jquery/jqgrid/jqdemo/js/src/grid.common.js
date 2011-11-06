/*
 * jqGrid common function
 * Tony Tomov tony@trirand.com
 * http://trirand.com/blog/ 
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
*/ 
// Modal functions
var showModal = function(h) {
	h.w.show();
};
var closeModal = function(h) {
	h.w.hide().attr("aria-hidden","true");
	if(h.o) { h.o.remove(); }
};
var createModal = function(aIDs, content, p, insertSelector, posSelector, appendsel) {
	var mw  = document.createElement('div');
	mw.className= "ui-widget ui-widget-content ui-corner-all ui-jqdialog";
	mw.id = aIDs.themodal;
	var mh = document.createElement('div');
	mh.className = "ui-jqdialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix";
	mh.id = aIDs.modalhead;
	jQuery(mh).append("<span class='ui-jqdialog-title'>"+p.caption+"</span>");
	var ahr= jQuery("<a href='javascript:void(0)' class='ui-jqdialog-titlebar-close ui-corner-all'></a>")
	.hover(function(){ahr.addClass('ui-state-hover');},
		   function(){ahr.removeClass('ui-state-hover');})
	.append("<span class='ui-icon ui-icon-closethick'></span>");
	jQuery(mh).append(ahr);
	var mc = document.createElement('div');
	jQuery(mc).addClass("ui-jqdialog-content ui-widget-content").attr("id",aIDs.modalcontent);
	jQuery(mc).append(content);
	mw.appendChild(mc);
	jQuery(mw).prepend(mh);
	if(appendsel===true) { jQuery('body').append(mw); } //append as first child in body -for alert dialog
	else {jQuery(mw).insertBefore(insertSelector);}
	if(typeof p.jqModal === 'undefined') {p.jqModal = true;} // internal use
	if ( jQuery.fn.jqm && p.jqModal === true) {
		if(p.left ==0 && p.top==0) {
			var pos = [];
			pos = findPos(posSelector);
			p.left = pos[0] + 4;
			p.top = pos[1] + 4;
		}
	}
	jQuery("a.ui-jqdialog-titlebar-close",mh).click(function(e){
		var oncm = jQuery("#"+aIDs.themodal).data("onClose") || p.onClose;
		var gboxclose = jQuery("#"+aIDs.themodal).data("gbox") || p.gbox;
		hideModal("#"+aIDs.themodal,{gb:gboxclose,jqm:p.jqModal,onClose:oncm});
		return false;
	});
	if (p.width == 0 || !p.width) {p.width = 300;}
	if(p.height==0 || !p.height) {p.height =200;}
	if(!p.zIndex) {p.zIndex = 950;}
	jQuery(mw).css({
		top: p.top+"px",
		left: p.left+"px",
		width: isNaN(p.width) ? "auto": p.width+"px",
		height:isNaN(p.height) ? "auto" : p.height + "px",
		zIndex:p.zIndex,
		overflow: 'hidden'
	})
	.attr({tabIndex: "-1","role":"dialog","aria-labelledby":aIDs.modalhead,"aria-hidden":"true"});
	if(typeof p.drag == 'undefined') { p.drag=true;}
	if(typeof p.resize == 'undefined') {p.resize=true;}
	if (p.drag) {
		jQuery(mh).css('cursor','move');
		if(jQuery.fn.jqDrag) {
			jQuery(mw).jqDrag(mh);
		} else {
			try {
				jQuery(mw).draggable({handle: jQuery("#"+mh.id)});
			} catch (e) {}
		}
	}
	if(p.resize) {
		if(jQuery.fn.jqResize) {
			jQuery(mw).append("<div class='jqResize ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se ui-icon-grip-diagonal-se'></div>");
			jQuery("#"+aIDs.themodal).jqResize(".jqResize",aIDs.scrollelm ? "#"+aIDs.scrollelm : false);
		} else {
			try {
				jQuery(mw).resizable({handles: 'se',alsoResize: aIDs.scrollelm ? "#"+aIDs.scrollelm : false});
			} catch (e) {}
		}
	}
	if(p.closeOnEscape === true){
		jQuery(mw).keydown( function( e ) {
			if( e.which == 27 ) {
				var cone = jQuery("#"+aIDs.themodal).data("onClose") || p.onClose;
				hideModal(this,{gb:p.gbox,jqm:p.jqModal,onClose: cone});
			}
		});
	}
};
var viewModal = function (selector,o){
	o = jQuery.extend({
		toTop: true,
		overlay: 10,
		modal: false,
		onShow: showModal,
		onHide: closeModal,
		gbox: '',
		jqm : true,
		jqM : true
	}, o || {});
	if (jQuery.fn.jqm && o.jqm == true) {
		if(o.jqM) jQuery(selector).attr("aria-hidden","false").jqm(o).jqmShow();
		else jQuery(selector).attr("aria-hidden","false").jqmShow();
	} else {
		if(o.gbox != '') {
			jQuery(".jqgrid-overlay:first",o.gbox).show();
			jQuery(selector).data("gbox",o.gbox);
		}
		jQuery(selector).show().attr("aria-hidden","false");
		try{jQuery(':input:visible',selector)[0].focus();}catch(_){}
	}
	return false;
};
var hideModal = function (selector,o) {
	o = jQuery.extend({jqm : true, gb :''}, o || {});
    if(o.onClose) {
		var oncret =  o.onClose(selector);
		if (typeof oncret == 'boolean'  && !oncret ) return;
    }	
	if (jQuery.fn.jqm && o.jqm === true) {
		jQuery(selector).attr("aria-hidden","true").jqmHide();
	} else {
		if(o.gb != '') {
			try {jQuery(".jqgrid-overlay:first",o.gb).hide();} catch (e){}
		}
		jQuery(selector).hide().attr("aria-hidden","true");
	}
};

function info_dialog(caption, content,c_b) {
	var cnt = "<div id='info_id'>";
	cnt += "<div align='center'><br />"+content+"<br /><br />";
	cnt += "<input type='button' size='10' id='closedialog' class='jqmClose EditButton' value='"+c_b+"' />";
	cnt += "</div></div>";
	createModal({
		themodal:'info_dialog',
		modalhead:'info_head',
		modalcontent:'info_content'},
		cnt,
		{ width:290,
		height:120,
		drag: false,
		resize: false,
		caption:"<b>"+caption+"</b>",
		left:250,
		top:170,
		closeOnEscape : true },
		'','',true
	);
	jQuery("#closedialog", "#info_id").addClass('ui-state-default ui-corner-all').height(21)
		.css({padding:" .2em .5em", cursor: 'pointer'})
		.hover(
			function(){jQuery(this).addClass('ui-state-hover');}, 
			function(){jQuery(this).removeClass('ui-state-hover');}
	);
	if(jQuery.fn.jqm) {;}
	else {
		jQuery("#closedialog", "#info_id").click(function(e){
			hideModal("#info_dialog");
			return false;
		});
	}
	viewModal("#info_dialog",{
		onHide: function(h) {
			h.w.hide().remove();
			if(h.o) { h.o.remove(); }
		},
		modal :true
	});
}
//Helper functions
function findPos(obj) {
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		do {
			curleft += obj.offsetLeft;
			curtop += obj.offsetTop; 
		} while (obj = obj.offsetParent);
		//do not change obj == obj.offsetParent 
	}
	return [curleft,curtop];
}
function isArray(obj) {
	if (obj.constructor.toString().indexOf("Array") == -1) {
		return false;
	} else {
		return true;
	}
}
// Form Functions
function createEl(eltype,options,vl) {
	var elem = "";
	if(options.defaultValue) delete options['defaultValue'];
	function bindEv (el, opt) {
		if(jQuery.isFunction(opt.dataInit)) {
			// datepicker fix 
			el.id = opt.id;
			opt.dataInit(el);
			delete opt['id'];
			delete opt['dataInit'];
		}
		if(opt.dataEvents) {
		    jQuery.each(opt.dataEvents, function() {
		        if (this.data != null)
			        jQuery(el).bind(this.type, this.data, this.fn);
		        else
		            jQuery(el).bind(this.type, this.fn);
		    });
			delete opt['dataEvents'];
		}
		return opt;
	}
	switch (eltype)
	{
		case "textarea" :
				elem = document.createElement("textarea");
				if(!options.cols) {jQuery(elem).css("width","98%");}
				if(vl=='&nbsp;' || vl=='&#160;' || (vl.length==1 && vl.charCodeAt(0)==160)) {vl="";}
				elem.value = vl;
				options = bindEv(elem,options);
				jQuery(elem).attr(options);
				break;
		case "checkbox" : //what code for simple checkbox
			elem = document.createElement("input");
			elem.type = "checkbox";
			if( !options.value ) {
				var vl1 = vl.toLowerCase();
				if(vl1.search(/(false|0|no|off|undefined)/i)<0 && vl1!=="") {
					elem.checked=true;
					elem.defaultChecked=true;
					elem.value = vl;
				} else {
					elem.value = "on";
				}
				jQuery(elem).attr("offval","off");
			} else {
				var cbval = options.value.split(":");
				if(vl === cbval[0]) {
					elem.checked=true;
					elem.defaultChecked=true;
				}
				elem.value = cbval[0];
				jQuery(elem).attr("offval",cbval[1]);
				try {delete options['value'];} catch (e){}
			}
			options = bindEv(elem,options);
			jQuery(elem).attr(options);
			break;
		case "select" :
			elem = document.createElement("select");
			var msl = options.multiple===true ? true : false;
			if(options.dataUrl != null) {
				jQuery.get(options.dataUrl,{_nsd : (new Date().getTime())},function(data){
					try {delete options['dataUrl'];delete options['value'];} catch (e){}
					var a = jQuery(data).html();
					jQuery(elem).append(a);
					options = bindEv(elem,options);
					if(typeof options.size === 'undefined') { options.size =  msl ? 3 : 1;}
					jQuery(elem).attr(options);
					setTimeout(function(){
						jQuery("option",elem).each(function(i){
							if(jQuery(this).html()==vl) {
								this.selected= "selected";
								return false;
							}
						});
					},0);
				},'html');
			} else if(options.value) {
				var ovm = [], i;
				if(msl) {
					ovm = vl.split(",");
					ovm = jQuery.map(ovm,function(n){return jQuery.trim(n)});
					if(typeof options.size === 'undefined') {options.size = 3;}
				} else {
					options.size = 1;
				}
				if(typeof options.value === 'string') {
					var so = options.value.split(";"),sv, ov;
					try {delete options['value'];} catch (e){}
					options = bindEv(elem,options);
					jQuery(elem).attr(options);
					for(i=0; i<so.length;i++){
						sv = so[i].split(":");
						ov = document.createElement("option");
						ov.value = sv[0]; ov.innerHTML = sv[1];
						if (!msl &&  sv[1]==vl) ov.selected ="selected";
						if (msl && jQuery.inArray(jQuery.trim(sv[1]), ovm)>-1) {ov.selected ="selected";}
						elem.appendChild(ov);
					}
				} else if (typeof options.value === 'object') {
					var oSv = options.value;
					try {delete options['value'];} catch (e){}
					options = bindEv(elem,options);
					jQuery(elem).attr(options);
					i=0;
					for ( var key in oSv) {
						i++;
						ov = document.createElement("option");
						ov.value = key; ov.innerHTML = oSv[key];
						if (!msl &&  oSv[key]==vl) ov.selected ="selected";
						if (msl && jQuery.inArray(jQuery.trim(oSv[key]),ovm)>-1) ov.selected ="selected";
						elem.appendChild(ov);
					}
				}
			}
			break;
		case "text" :
		case "password" :
		case "button" :
			elem = document.createElement("input");
			elem.type = eltype;
			elem.value = jQuery.jgrid.htmlDecode(vl);
			options = bindEv(elem,options);
			if(!options.size) {
				jQuery(elem).css({width:"98%"});
			}
			jQuery(elem).attr(options);
			break;
		case "image" :
		case "file" :
			elem = document.createElement("input");
			elem.type = eltype;
			options = bindEv(elem,options);
			jQuery(elem).attr(options);
			break;
	}
	return elem;
}
function checkValues(val, valref,g) {
	var edtrul,i, nm;
	if(typeof(valref)=='string'){
		for( i =0, len=g.p.colModel.length;i<len; i++){
			if(g.p.colModel[i].name==valref) {
				edtrul = g.p.colModel[i].editrules;
				valref = i;
				try { nm = g.p.colModel[i].formoptions.label; } catch (e) {}
				break;
			}
		}
	} else if(valref >=0) {
		var edtrul = g.p.colModel[valref].editrules;
	}
	if(edtrul) {
		if(!nm) nm = g.p.colNames[valref];
		if(edtrul.required === true) {
			if( val.match(/^s+$/) || val == "" )  return [false,nm+": "+jQuery.jgrid.edit.msg.required,""];
		}
		// force required
		var rqfield = edtrul.required === false ? false : true;
		if(edtrul.number === true) {
			if( !(rqfield === false && isEmpty(val)) ) {
				if(isNaN(val)) return [false,nm+": "+jQuery.jgrid.edit.msg.number,""];
			}
		}
		if(typeof edtrul.minValue != 'undefined' && !isNaN(edtrul.minValue)) {
			if (parseFloat(val) < parseFloat(edtrul.minValue) ) return [false,nm+": "+jQuery.jgrid.edit.msg.minValue+" "+edtrul.minValue,""];
		}
		if(typeof edtrul.maxValue != 'undefined' && !isNaN(edtrul.maxValue)) {
			if (parseFloat(val) > parseFloat(edtrul.maxValue) ) return [false,nm+": "+jQuery.jgrid.edit.msg.maxValue+" "+edtrul.maxValue,""];
		}
		var filter;
		if(edtrul.email === true) {
			if( !(rqfield === false && isEmpty(val)) ) {
			// taken from jquery Validate plugin
				filter = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
				if(!filter.test(val)) {return [false,nm+": "+jQuery.jgrid.edit.msg.email,""];}
			}
		}
		if(edtrul.integer === true) {
			if( !(rqfield === false && isEmpty(val)) ) {
				if(isNaN(val)) return [false,nm+": "+jQuery.jgrid.edit.msg.integer,""];
				if ((val % 1 != 0) || (val.indexOf('.') != -1)) return [false,nm+": "+jQuery.jgrid.edit.msg.integer,""];
			}
		}
		if(edtrul.date === true) {
			if( !(rqfield === false && isEmpty(val)) ) {
				var dft = g.p.colModel[valref].datefmt || "Y-m-d";
				if(!checkDate (dft, val)) return [false,nm+": "+jQuery.jgrid.edit.msg.date+" - "+dft,""];
			}
		}
        if(edtrul.url === true) {
            if( !(rqfield === false && isEmpty(val)) ) {
                filter = /^(((https?)|(ftp)):\/\/([\-\w]+\.)+\w{2,3}(\/[%\-\w]+(\.\w{2,})?)*(([\w\-\.\?\\/+@&#;`~=%!]*)(\.\w{2,})?)*\/?)/i;
                if(!filter.test(val)) {return [false,nm+": "+jQuery.jgrid.edit.msg.url,""];}
            }
        }
	}
	return [true,"",""];
}
// Date Validation Javascript
function checkDate (format, date) {
	var tsp = {}, sep;
	format = format.toLowerCase();
	//we search for /,-,. for the date separator
	if(format.indexOf("/") != -1) {
		sep = "/";
	} else if(format.indexOf("-") != -1) {
		sep = "-";
	} else if(format.indexOf(".") != -1) {
		sep = ".";
	} else {
		sep = "/";
	}
	format = format.split(sep);
	date = date.split(sep);
	if (date.length != 3) return false;
	var j=-1,yln, dln=-1, mln=-1;
	for(var i=0;i<format.length;i++){
		var dv =  isNaN(date[i]) ? 0 : parseInt(date[i],10); 
		tsp[format[i]] = dv;
		yln = format[i];
		if(yln.indexOf("y") != -1) { j=i; }
		if(yln.indexOf("m") != -1) {mln=i}
		if(yln.indexOf("d") != -1) {dln=i}
	}
	if (format[j] == "y" || format[j] == "yyyy") {
		yln=4;
	} else if(format[j] =="yy"){
		yln = 2;
	} else {
		yln = -1;
	}
	var daysInMonth = DaysArray(12);
	var strDate;
	if (j === -1) {
		return false;
	} else {
		strDate = tsp[format[j]].toString();
		if(yln == 2 && strDate.length == 1) {yln = 1;}
		if (strDate.length != yln || tsp[format[j]]==0 ){
			return false;
		}
	}
	if(mln === -1) {
		return false;
	} else {
		strDate = tsp[format[mln]].toString();
		if (strDate.length<1 || tsp[format[mln]]<1 || tsp[format[mln]]>12){
			return false;
		}
	}
	if(dln === -1) {
		return false;
	} else {
		strDate = tsp[format[dln]].toString();
		if (strDate.length<1 || tsp[format[dln]]<1 || tsp[format[dln]]>31 || (tsp[format[mln]]==2 && tsp[format[dln]]>daysInFebruary(tsp[format[j]])) || tsp[format[dln]] > daysInMonth[tsp[format[mln]]]){
			return false;
		}
	}
	return true;
}
function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31;
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30;}
		if (i==2) {this[i] = 29;}
	} 
	return this;
}

function isEmpty(val)
{
	if (val.match(/^s+$/) || val == "")	{
		return true;
	} else {
		return false;
	} 
}
