/**
 * @name NiceJForms
 * @description This a jQuery equivalent for Niceforms1. All the forms are styled with beautiful images as backgrounds and stuff. Enjoy them!
 * @param Hash hash A hash of parameters
 * @option Integer border border width
 * @option String loaderSRC path to loading image
 * @option String closeHTML path to close overlay image
 * @option Float overlayOpacity opacity for overlay
 * @option String textImage when a galalry it is build then the iteration is displayed
 * @option String textImageFrom when a galalry it is build then the iteration is displayed
 * @option Integer fadeDuration fade duration in miliseconds
 *
 * @type jQuery
 * @cat Plugins/Interface/Forms
 * @author Lucian Lature
 */

jQuery.preloadImages = function()
{
	var imgs = new Array();
	for(var i = 0; i<arguments.length; i++)
	{
		imgs[i] = jQuery("<img>").attr("src", arguments[i]);
	}
	
	return imgs;
}

jQuery.NiceJForms = {
	options : {
		selectRightSideWidth     : 21,
		selectLeftSideWidth      : 8,
		selectAreaHeight 	     : 21,
		selectAreaOptionsOverlap : 2,
		imagesPath               : "images/"
		// other options here
	},
	
	selectText     : 'please select',
	preloads       : new Array(),
	inputs         : new Array(),
	labels         : new Array(),
	textareas      : new Array(),
	selects        : new Array(),
	radios         : new Array(),
	checkboxes     : new Array(),
	texts          : new Array(),
	buttons        : new Array(),
	radioLabels    : new Array(),
	checkboxLabels : new Array(),
	hasImages      : true,
	
	keyPressed : function(event)
	{
		var pressedKey = event.charCode || event.keyCode || -1;
		
		switch (pressedKey)
		{
			case 40: //down
			var fieldId = this.parentNode.parentNode.id.replace(/sarea/g, "");
			var linkNo = 0;
			for(var q = 0; q < selects[fieldId].options.length; q++) {if(selects[fieldId].options[q].selected) {linkNo = q;}}
			++linkNo;
			if(linkNo >= selects[fieldId].options.length) {linkNo = 0;}
			selectMe(selects[fieldId].id, linkNo, fieldId);
			break;
		
		case 38: //up
			var fieldId = this.parentNode.parentNode.id.replace(/sarea/g, "");
			var linkNo = 0;
			for(var q = 0; q < selects[fieldId].options.length; q++) {if(selects[fieldId].options[q].selected) {linkNo = q;}}
			--linkNo;
			if(linkNo < 0) {linkNo = selects[fieldId].options.length - 1;}
			selectMe(selects[fieldId].id, linkNo, fieldId);
			break;
		default:
			break;
		}
	},
	
	build : function(options)
	{
		if (options)
			jQuery.extend(jQuery.NiceJForms.options, options);	
			
		if (window.event) {
			jQuery('body',document).bind('keyup', jQuery.NiceJForms.keyPressed);
		} else {
			jQuery(document).bind('keyup', jQuery.NiceJForms.keyPressed);
		}
			
		// test if images are disabled or not
		var testImg = document.createElement('img');
		$(testImg).attr("src", jQuery.NiceJForms.options.imagesPath + "blank.gif");
		jQuery('body').append(testImg);

		if(testImg.complete)
		{
			if(testImg.offsetWidth == '1') {jQuery.NiceJForms.hasImages = true;}
			else {jQuery.NiceJForms.hasImages = false;}
		}

		$(testImg).remove();
			
		if(jQuery.NiceJForms.hasImages)
		{
			$('form.niceform').each( function()
				{
					el 				= jQuery(this);
					jQuery.NiceJForms.preloadImages();
					jQuery.NiceJForms.getElements(el);
					jQuery.NiceJForms.replaceRadios();
					jQuery.NiceJForms.replaceCheckboxes();
					jQuery.NiceJForms.replaceSelects();
					
					if (!$.browser.safari) {
						jQuery.NiceJForms.replaceTexts();
						jQuery.NiceJForms.replaceTextareas();
						jQuery.NiceJForms.buttonHovers();
					}
				}
			);
		}	
	},
	
	preloadImages: function()
	{
		jQuery.NiceJForms.preloads = $.preloadImages(jQuery.NiceJForms.options.imagesPath + "button_left_xon.gif", jQuery.NiceJForms.options.imagesPath + "button_right_xon.gif", 
		jQuery.NiceJForms.options.imagesPath + "input_left_xon.gif", jQuery.NiceJForms.options.imagesPath + "input_right_xon.gif",
		jQuery.NiceJForms.options.imagesPath + "txtarea_bl_xon.gif", jQuery.NiceJForms.options.imagesPath + "txtarea_br_xon.gif", 
		jQuery.NiceJForms.options.imagesPath + "txtarea_cntr_xon.gif", jQuery.NiceJForms.options.imagesPath + "txtarea_l_xon.gif", jQuery.NiceJForms.options.imagesPath + "txtarea_tl_xon.gif", jQuery.NiceJForms.options.imagesPath + "txtarea_tr_xon.gif");
	},
	
	getElements: function(elm)
	{
		el = elm ? jQuery(elm) : jQuery(this);
		
		var r = 0; var c = 0; var t = 0; var rl = 0; var cl = 0; var tl = 0; var b = 0;
		//check this out
		
		jQuery.NiceJForms.inputs = $('input', el);
		jQuery.NiceJForms.labels = $('label', el);
		jQuery.NiceJForms.textareas = $('textarea', el);
		jQuery.NiceJForms.selects = $('select', el);
		jQuery.NiceJForms.radios = $('input[@type=radio]', el);
		jQuery.NiceJForms.checkboxes = $('input[@type=checkbox]', el);
		jQuery.NiceJForms.texts = $('input[@type=text]', el).add($('input[@type=password]', el));		
		jQuery.NiceJForms.buttons = $('input[@type=submit]', el).add($('input[@type=button]', el));
		
		jQuery.NiceJForms.labels.each(function(i){
			labelFor = $(jQuery.NiceJForms.labels[i]).attr("for");
			jQuery.NiceJForms.radios.each(function(q){
				if(labelFor == $(jQuery.NiceJForms.radios[q]).attr("id"))
				{
					if(jQuery.NiceJForms.radios[q].checked)
					{
						$(jQuery.NiceJForms.labels[i]).removeClass().addClass("chosen");	
					}
					
					jQuery.NiceJForms.radioLabels[rl] = jQuery.NiceJForms.labels[i];
					++rl;
				}
			})
			
			jQuery.NiceJForms.checkboxes.each(function(x){
				
				if(labelFor == $(this).attr("id"))
				{
					if(this.checked)
					{
						$(jQuery.NiceJForms.labels[i]).removeClass().addClass("chosen");	
					}
					jQuery.NiceJForms.checkboxLabels[cl] = jQuery.NiceJForms.labels[i];
					++cl;
				}
			})
		});
	},
	
	replaceRadios: function()
	{
		var self = this;
		
		jQuery.NiceJForms.radios.each(function(q){
		
			//alert(q);
			$(this).removeClass().addClass('outtaHere'); //.hide(); //.className = "outtaHere";
			
			var radioArea = document.createElement('div');
			//console.info($(radioArea));
			if(this.checked) {$(radioArea).removeClass().addClass("radioAreaChecked");} else {$(radioArea).removeClass().addClass("radioArea");};
			
			radioPos = jQuery.iUtil.getPosition(this);
			
			jQuery(radioArea)
				.attr({id: 'myRadio'+q})
				.css({left: radioPos.x + 'px', top: radioPos.y + 'px', margin : '1px'})
				.bind('click', {who: q}, function(e){self.rechangeRadios(e)})
				.insertBefore($(this));
			
			$(jQuery.NiceJForms.radioLabels[q]).bind('click', {who: q}, function(e){self.rechangeRadios(e)});
			
			if (!$.browser.msie) {
				$(this).bind('focus', function(){self.focusRadios(q)}).bind('blur', function() {self.blurRadios(q)});
			}
			
			$(this).bind('click', function(e){self.radioEvent(e)});
		});
		
		return true;
	},
	
	changeRadios: function(who) {
		
		var self = this;
		
		if(jQuery.NiceJForms.radios[who].checked) {
		
			jQuery.NiceJForms.radios.each(function(q){
				if($(this).attr("name") == $(jQuery.NiceJForms.radios[who]).attr("name"))
				{
					this.checked = false;
					$(jQuery.NiceJForms.radioLabels[q]).removeClass();	
				}
			});
			jQuery.NiceJForms.radios[who].checked = true;
			$(jQuery.NiceJForms.radioLabels[who]).addClass("chosen");
			
			self.checkRadios(who);
		}
	},
	
	rechangeRadios:function(e) 
	{
		who = e.data.who;
		
		if(!jQuery.NiceJForms.radios[who].checked) {
			for(var q = 0; q < jQuery.NiceJForms.radios.length; q++) 
			{
				if(jQuery.NiceJForms.radios[q].name == jQuery.NiceJForms.radios[who].name) 
				{
					jQuery.NiceJForms.radios[q].checked = false; 
					//console.info(q);
					jQuery.NiceJForms.radioLabels[q].className = "";
				}
			}
			$(jQuery.NiceJForms.radios[who]).attr('checked', true); 
			jQuery.NiceJForms.radioLabels[who].className = "chosen";
			jQuery.NiceJForms.checkRadios(who);
		}
	},
	
	checkRadios: function(who) {
		$('div').each(function(q){
			if($(this).is(".radioAreaChecked") && $(this).next().attr("name") == $(jQuery.NiceJForms.radios[who]).attr("name")) {$(this).removeClass().addClass("radioArea");}
		});
		$('#myRadio' + who).toggleClass("radioAreaChecked");
	},
	
	focusRadios: function(who) {
		$('#myRadio' + who).css({border: '1px dotted #333', margin: '0'}); return false;
	},
	
	blurRadios:function(who) {
		$('#myRadio' + who).css({border: 'none', margin: '1px'}); return false;
	},
	
	radioEvent: function(e) {
		var self = this;
		if (!e) var e = window.event;
		if(e.type == "click") {for (var q = 0; q < jQuery.NiceJForms.radios.length; q++) {if(this == jQuery.NiceJForms.radios[q]) {self.changeRadios(q); break;}}}
	},
	
replaceCheckboxes: function () 
	{
		var self = this;
		
		jQuery.NiceJForms.checkboxes.each(function(q){
			//move the checkboxes out of the way
			$(jQuery.NiceJForms.checkboxes[q]).removeClass().addClass('outtaHere');
			//create div
			var checkboxArea = document.createElement('div');
			
			//console.info($(radioArea));
			if(jQuery.NiceJForms.checkboxes[q].checked) {$(checkboxArea).removeClass().addClass("checkboxAreaChecked");} else {$(checkboxArea).removeClass().addClass("checkboxArea");};
			
			checkboxPos = jQuery.iUtil.getPosition(jQuery.NiceJForms.checkboxes[q]);
			
			jQuery(checkboxArea)
				.attr({id: 'myCheckbox' + q})
				.css({
				left: checkboxPos.x + 'px', 
				top: checkboxPos.y + 'px',
				margin : '1px'
			})
			.bind('click', {who: q}, function(e){self.rechangeCheckboxes(e)})
			.insertBefore($(jQuery.NiceJForms.checkboxes[q]));
			
			if(!$.browser.safari)
			{
				$(jQuery.NiceJForms.checkboxLabels[q]).bind('click', {who:q}, function(e){self.changeCheckboxes(e)})
			}
			else {
				$(jQuery.NiceJForms.checkboxLabels[q]).bind('click', {who:q}, function(e){self.rechangeCheckboxes(e)})
			}
			
			if(!$.browser.msie)
			{
				$(jQuery.NiceJForms.checkboxes[q]).bind('focus', {who:q}, function(e){self.focusCheckboxes(e)});
				$(jQuery.NiceJForms.checkboxes[q]).bind('blur', {who:q}, function(e){self.blurCheckboxes(e)});
			}	
			
			//$(jQuery.NiceJForms.checkboxes[q]).keydown(checkEvent);
		});
		return true;
	},

	rechangeCheckboxes: function(e)
	{
		who = e.data.who;
		var tester = false;
		
		if($(jQuery.NiceJForms.checkboxLabels[who]).is(".chosen")) {
			tester = false;
			$(jQuery.NiceJForms.checkboxLabels[who]).removeClass();
		}
		else if(jQuery.NiceJForms.checkboxLabels[who].className == "") {
			tester = true;
			$(jQuery.NiceJForms.checkboxLabels[who]).addClass("chosen");
		}
		jQuery.NiceJForms.checkboxes[who].checked = tester;
		jQuery.NiceJForms.checkCheckboxes(who, tester);
	},

	checkCheckboxes: function(who, action)
	{
		var what = $('#myCheckbox' + who);
		if(action == true) {$(what).removeClass().addClass("checkboxAreaChecked");}
		if(action == false) {$(what).removeClass().addClass("checkboxArea");}
	},

	focusCheckboxes: function(who) 
	{
		var what = $('#myCheckbox' + who);
		$(what).css(
					{
						border : "1px dotted #333", 
						margin : "0"
					});	
		return false;
	},

	changeCheckboxes: function(e) {
		who = e.data.who;
		//console.log('changeCheckboxes who is ' + who);
		if($(jQuery.NiceJForms.checkboxLabels[who]).is(".chosen")) {
			jQuery.NiceJForms.checkboxes[who].checked = true;
			$(jQuery.NiceJForms.checkboxLabels[who]).removeClass();
			jQuery.NiceJForms.checkCheckboxes(who, false);
		}
		else if(jQuery.NiceJForms.checkboxLabels[who].className == "") {
			jQuery.NiceJForms.checkboxes[who].checked = false;
			$(jQuery.NiceJForms.checkboxLabels[who]).toggleClass("chosen");
			jQuery.NiceJForms.checkCheckboxes(who, true);
		}
	},

	blurCheckboxes: function(who) 
	{
		var what = $('#myCheckbox' + who);
		$(what).css(
					{
						border : 'none', 
						margin : '1px'
					});	
		return false;
	},
	
	replaceSelects: function()
	{
		var self = this;
		
		jQuery.NiceJForms.selects.each(function(q){
			//create and build div structure
			var selectArea = document.createElement('div');
			var left = document.createElement('div');
			var right = document.createElement('div');
			var center = document.createElement('div');
			var button = document.createElement('a');
			var text = document.createTextNode(jQuery.NiceJForms.selectText);
			var selectWidth = parseInt(this.className.replace(/width_/g, ""));
			
			//console.log('selectWidth is ' + selectWidth);
			
			jQuery(center)
				.attr({id:'mySelectText'+q})
				.css({width: selectWidth - 10 + 'px'});
			jQuery(selectArea)
				.attr({id:'sarea'+q})
				.css({
					width: selectWidth + jQuery.NiceJForms.options.selectRightSideWidth + jQuery.NiceJForms.options.selectLeftSideWidth + 'px'
				})
				.addClass("selectArea");
				
			jQuery(button)
				.css({
				width      : selectWidth + jQuery.NiceJForms.options.selectRightSideWidth + jQuery.NiceJForms.options.selectLeftSideWidth + 'px',
				marginLeft : - selectWidth - jQuery.NiceJForms.options.selectLeftSideWidth + 'px',
				cursor: 'pointer'
				})
				.addClass("selectButton")
				.bind('click', {who:q}, function(e){self.showOptions(e)})
				.keydown(jQuery.NiceJForms.keyPressed);
			
			jQuery(left).addClass("left");	
			jQuery(right).addClass("right").append(button);	
			jQuery(center).addClass("center").append(text);	
			
			jQuery(selectArea).append(left).append(right).append(center).insertBefore(this);
			//hide the select field
			$(this).hide();
			//insert select div
			//build & place options div
			var optionsDiv = document.createElement('div');
			selectAreaPos = jQuery.iUtil.getPosition(selectArea);
			
			jQuery(optionsDiv)
				.attr({id:"optionsDiv" + q})
				.css({
					width : selectWidth + 1 + 'px', 
					left  : selectAreaPos.x + 'px', 
					top   : selectAreaPos.y + jQuery.NiceJForms.options.selectAreaHeight - jQuery.NiceJForms.options.selectAreaOptionsOverlap + 'px'
				})
				.addClass("optionsDivInvisible");
			
			//get select's options and add to options div
			$(jQuery.NiceJForms.selects[q]).children().each(function(w){
				var optionHolder = document.createElement('p');
				var optionLink = document.createElement('a');
				var optionTxt = document.createTextNode(jQuery.NiceJForms.selects[q].options[w].text);
				
				jQuery(optionLink)
					.attr({href:'#'})
					.css({cursor:'pointer'})
					.append(optionTxt)
					.bind('click', {who: q, id:jQuery.NiceJForms.selects[q].id, option:w, select:q}, function(e){self.showOptions(e);self.selectMe(jQuery.NiceJForms.selects[q].id, w, q)});
				
				jQuery(optionHolder).append(optionLink);
				jQuery(optionsDiv).append(optionHolder);//.append(optionLink);
				
				//check for pre-selected items
				if(jQuery.NiceJForms.selects[q].options[w].selected) {self.selectMe($(jQuery.NiceJForms.selects[q]).attr("id"), w, q);}
			});
			
			jQuery('body').append(optionsDiv);
		});
	},

	selectMe: function(selectFieldId, linkNo, selectNo) {
		selectField = $('#' + selectFieldId);
		sFoptions = selectField.children();
		
		selectField.children().each(function(k){
			//console.info(this);
			if(k == linkNo) {sFoptions[k].selected="selected";}
			else {sFoptions[k].selected = "";}
		});
		
		textVar = $("#mySelectText" + selectNo);
		var newText = document.createTextNode($(sFoptions[linkNo]).text());
		textVar.empty().append(newText);
	}, 

	showOptions: function(e) {
		var self = this;
		$("#optionsDiv"+e.data.who).toggleClass("optionsDivVisible").toggleClass("optionsDivInvisible").mouseout(function(e){self.hideOptions(e)});
	},
	
	hideOptions: function(e) {
		if (!e) var e = window.event;
		var reltg = (e.relatedTarget) ? e.relatedTarget : e.toElement;
		if(((reltg.nodeName != 'A') && (reltg.nodeName != 'DIV')) || ((reltg.nodeName == 'A') && (reltg.className=="selectButton") && (reltg.nodeName != 'DIV'))) {this.className = "optionsDivInvisible";};
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();
	},
	
	replaceTexts: function() {
		jQuery.NiceJForms.texts.each(function(q){
			$(jQuery.NiceJForms.texts[q]).css({width:this.size * 10 + 'px'});
			//console.info($(jQuery.NiceJForms.texts[q]));
			//felicia email
			var txtLeft = new Image();
			jQuery(txtLeft)
				.attr({src:jQuery.NiceJForms.options.imagesPath + "input_left.gif"})
				.addClass("inputCorner");
			
			var txtRight = new Image();
			jQuery(txtRight)
				.attr({src:jQuery.NiceJForms.options.imagesPath + "input_right.gif"})
				.addClass("inputCorner");
			
			$(jQuery.NiceJForms.texts[q]).before(txtLeft).after(txtRight).addClass("textinput");
			
			//create hovers
			$(jQuery.NiceJForms.texts[q]).focus(function(){$(this).addClass("textinputHovered");$(this).prev().attr('src', jQuery.NiceJForms.options.imagesPath + "input_left_xon.gif");$(this).next().attr('src', jQuery.NiceJForms.options.imagesPath + "input_right_xon.gif");});
			
			$(jQuery.NiceJForms.texts[q]).blur(function() {$(this).removeClass().addClass("textinput");$(this).prev().attr('src', jQuery.NiceJForms.options.imagesPath + "input_left.gif");$(this).next().attr('src', jQuery.NiceJForms.options.imagesPath + "input_right.gif");});
		});
	},
	
	replaceTextareas: function() {
		jQuery.NiceJForms.textareas.each(function(q){
			
			var where = $(this).parent();
			var where2 = $(this).prev();
			
			$(this).css({width: $(this).attr("cols") * 10 + 'px', height: $(this).attr("rows") * 10 + 'px'});
			//create divs
			var container = document.createElement('div');
			jQuery(container)
				.css({width: jQuery.NiceJForms.textareas[q].cols * 10 + 20 + 'px', height: jQuery.NiceJForms.textareas[q].rows * 10 + 20 + 'px'})
				.addClass("txtarea");
			
			var topRight = document.createElement('div');
			jQuery(topRight).addClass("tr");
			
			var topLeft = new Image();
			jQuery(topLeft).attr({src: jQuery.NiceJForms.options.imagesPath + 'txtarea_tl.gif'}).addClass("txt_corner");
			
			var centerRight = document.createElement('div');
			jQuery(centerRight).addClass("cntr");
			var centerLeft = document.createElement('div');
			jQuery(centerLeft).addClass("cntr_l");
			
			if(!$.browser.msie) {jQuery(centerLeft).height(jQuery.NiceJForms.textareas[q].rows * 10 + 10 + 'px')}
			else {jQuery(centerLeft).height(jQuery.NiceJForms.textareas[q].rows * 10 + 12 + 'px')};
			
			var bottomRight = document.createElement('div');
			jQuery(bottomRight).addClass("br");
			var bottomLeft = new Image();
			jQuery(bottomLeft).attr({src: jQuery.NiceJForms.options.imagesPath + 'txtarea_bl.gif'}).addClass('txt_corner');
			
			//assemble divs
			jQuery(topRight).append(topLeft);
			jQuery(centerRight).append(centerLeft).append(jQuery.NiceJForms.textareas[q]);
			jQuery(bottomRight).append(bottomLeft);
			jQuery(container).append(topRight).append(centerRight).append(bottomRight);
			
			jQuery(where2).before(container);
			
			//create hovers
			$(jQuery.NiceJForms.textareas[q]).focus(function(){$(this).prev().removeClass().addClass("cntr_l_xon"); $(this).parent().removeClass().addClass("cntr_xon"); $(this).parent().prev().removeClass().addClass("tr_xon"); $(this).parent().prev().children(".txt_corner").attr('src', jQuery.NiceJForms.options.imagesPath + "txtarea_tl_xon.gif"); $(this).parent().next().removeClass().addClass("br_xon"); $(this).parent().next().children(".txt_corner").attr('src', jQuery.NiceJForms.options.imagesPath + "txtarea_bl_xon.gif")});
			$(jQuery.NiceJForms.textareas[q]).blur(function(){$(this).prev().removeClass().addClass("cntr_l"); $(this).parent().removeClass().addClass("cntr"); $(this).parent().prev().removeClass().addClass("tr"); $(this).parent().prev().children(".txt_corner").attr('src', jQuery.NiceJForms.options.imagesPath + "txtarea_tl.gif"); $(this).parent().next().removeClass().addClass("br"); $(this).parent().next().children(".txt_corner").attr('src', jQuery.NiceJForms.options.imagesPath + "txtarea_bl.gif")});
		});
	},
	
	buttonHovers: function() {
		jQuery.NiceJForms.buttons.each(function(i){
			$(this).addClass("buttonSubmit");
			var buttonLeft = document.createElement('img');
			jQuery(buttonLeft).attr({src: jQuery.NiceJForms.options.imagesPath + "button_left.gif"}).addClass("buttonImg");
			
			$(this).before(buttonLeft);
			
			var buttonRight = document.createElement('img');
			jQuery(buttonRight).attr({src: jQuery.NiceJForms.options.imagesPath + "button_right.gif"}).addClass("buttonImg");
			
			if($(this).next()) {$(this).after(buttonRight)}
			else {$(this).parent().append(buttonRight)};
			
			$(this).hover(
				function(){$(this).attr("class", $(this).attr("class") + "Hovered"); $(this).prev().attr("src", jQuery.NiceJForms.options.imagesPath + "button_left_xon.gif"); $(this).next().attr("src", jQuery.NiceJForms.options.imagesPath + "button_right_xon.gif")},
				function(){$(this).attr("class", $(this).attr("class").replace(/Hovered/g, "")); $(this).prev().attr("src", jQuery.NiceJForms.options.imagesPath + "button_left.gif"); $(this).next().attr("src", jQuery.NiceJForms.options.imagesPath + "button_right.gif")}
			);
		});
	}
}