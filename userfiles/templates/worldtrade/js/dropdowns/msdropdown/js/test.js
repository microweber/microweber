//This file is just for demo purpose. 
//no use in your project :)
// please dont use this file in your project
function showValue(arg, arg2) {
	//alert("calling show me: arg1 " + arg + " arg2 " +  arg2);
	var s = (arg2==undefined) ? '' : "<br /><font color='darkgreen'>value:</font> "+ arg2;
	$("#selectedvalue").html("<font color='darkgreen'>label:</font> " + arg +  s);
}
function convertNow(byIds) {
	//MSDropDown.showIconWithTitle(false);
	try {
		if(byIds==undefined) {
			$("body select").msDropDown({iconHolder:'data-icon'});
		} else {
			$(byIds).msDropDown({iconHolder:'data-icon'});
		}
		$("#converta").hide("fast");
		//$("#convertBtn").hide("fast");
	} catch(e) {
		//console.debug(e);
		alert(e);
	}
	$('#info').html('<h2>I would appreciate your <a style="color:chocolate" href="http://www.marghoobsuleman.com/jquery-image-dropdown#comment-controls">feedback.</a></h2>');
}
var counter = 1;
function output(msg, id) {
	if(counter>=100) counter = 1;
	var  old = $("#output").html();
	var sID = (typeof id=="string") ? id : id.id;
	$("#output").html((counter++)+": id= "+ sID +" : " + msg+"<br />"+old);
}
function clearDebugWindow() {
	counter = 1;
	$("#output").html("");
}

function disabledcombo(id, disabled) {
	document.getElementById(id).disabled = disabled;
	//custom function
	if(document.getElementById(id).refresh!=undefined)
			document.getElementById(id).refresh();
}
var cmbOption = new Object();
cmbOption["none"] = new Array("Please select and option");
cmbOption["prestashop"] = new Array("Extra Tab", "Categories Menu", "Add to cart extended", "Image Enlarger");
cmbOption["jquery"] = new Array("Javascript Image dropdown", "Accordion Common", "jQuery Tabs");
cmbOption["component"] = new Array("Magic Text", "Counter Text", "Rounded Text");
   
function populateCombo(val) {
	var targetCombo = 'dynamic';	
	document.getElementById(targetCombo).options.length =0;
	var target_array = 	cmbOption[val];
	for(var i=0;i<target_array.length;i++) {
		document.getElementById(targetCombo).options[i] = new Option(target_array[i].toString(), target_array[i].toString());
		document.getElementById(targetCombo).options[i].title = "icons/enabled.gif";
	}
		document.getElementById(targetCombo).selectedIndex =0;
		if(document.getElementById(targetCombo).refresh!=undefined)
			document.getElementById(targetCombo).refresh();
}

function getSelctedIndexes(targetCombo) {
		var selectedIndex = $("#"+targetCombo+" option:selected");
		for(var i=0;i<selectedIndex.length;i++) {
			output(selectedIndex[i].index, targetCombo);
		}
}
$(document).ready(function(evt) {
						   $(window).bind("scroll",scrollMe);
						   });
function scrollMe() {
	var position = $("#debugwindow").position();
	var windowPos = $(window).scrollTop();
	if(windowPos<45) {
		windowPos = 45;
	}
	//console.debug("windowPos "+windowPos);
	$("#debugwindow").animate({top:(windowPos)}, {queue:false, duration:1000});
}