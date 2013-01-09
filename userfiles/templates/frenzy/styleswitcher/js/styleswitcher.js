$(document).ready(function(){

	$('#switcher-toggle').click(function(){
		if($(this).css('left') == '185px') {
			$(this).css('left', '0px');
			$(this).next().hide();
		}
		else {
			$(this).css('left','185px');
			$(this).next().show();
		}
	});

	$('#bonfire-switcher a.css').click(function() {
		loadCss(this);
		return false;
	});

	$('#bonfire-switcher a.img').click(function(){
		loadImg(this);
		return false;
	});

	$('#bonfire-switcher a.bg').click(function(){
		loadBg(this);
		return false;
	});
});


function loadCss(obj) {
	$.get(obj.href, function(data){
		$('#'+obj.name).attr('href','css/' + data + '.css');
	});
}

function loadImg(obj) {
	$.get( obj.href, function(data) {
		$('#'+obj.name).attr('src', assetPath + data);
		$('#'+obj.name).attr('alt', 'Background');
	});
}

function loadBg(obj) {
	$.get( obj.href, function(data) {
		$('body').css('background-image', "url("+ assetPath +"pattern/" +data +")");
	});
}