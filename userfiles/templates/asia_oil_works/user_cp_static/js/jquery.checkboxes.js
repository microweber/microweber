/* ------------------------------------------------------------------------
	prettyCheckboxes
	
	Developped By: Stephane Caron (http://www.no-margin-for-errors.com)
	Inspired By: All the non user friendly custom checkboxes solutions ;)
	Version: 1.1
	
	Copyright: Feel free to redistribute the script/modify it, as
			   long as you leave my infos at the top.
------------------------------------------------------------------------- */
	
jQuery.fn.prettyCheckboxes=function(a){a=jQuery.extend({checkboxWidth:17,checkboxHeight:17,className:"prettyCheckbox",display:"list"},a);$(this).each(function(){$label=$('label[for="'+$(this).attr("id")+'"]');$label.prepend("<span class='holderWrap'><span class='holder'></span></span>");if($(this).is(":checked")){$label.addClass("checked")}$label.addClass(a.className).addClass($(this).attr("type")).addClass(a.display);$label.find("span.holderWrap").width(a.checkboxWidth).height(a.checkboxHeight);$label.find("span.holder").width(a.checkboxWidth);$(this).addClass("hiddenCheckbox");$label.bind("click",function(){$("input#"+$(this).attr("for")).triggerHandler("click");if($("input#"+$(this).attr("for")).is(":checkbox")){$(this).toggleClass("checked");$("input#"+$(this).attr("for")).checked=true;$(this).find("span.holder").css("top",0)}else{$toCheck=$("input#"+$(this).attr("for"));$('input[name="'+$toCheck.attr("name")+'"]').each(function(){$('label[for="'+$(this).attr("id")+'"]').removeClass("checked")});$(this).addClass("checked");$toCheck.checked=true}});$("input#"+$label.attr("for")).bind("keypress",function(b){if(b.keyCode==32){if($.browser.msie){$('label[for="'+$(this).attr("id")+'"]').toggleClass("checked")}else{$(this).trigger("click")}return false}})})};checkAllPrettyCheckboxes=function(b,a){if($(b).is(":checked")){$(a).find("input[type=checkbox]:not(:checked)").each(function(){$('label[for="'+$(this).attr("id")+'"]').trigger("click");if($.browser.msie){$(this).attr("checked","checked")}else{$(this).trigger("click")}})}else{$(a).find("input[type=checkbox]:checked").each(function(){$('label[for="'+$(this).attr("id")+'"]').trigger("click");if($.browser.msie){$(this).attr("checked","")}else{$(this).trigger("click")}})}};