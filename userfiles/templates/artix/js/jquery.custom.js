$(document).ready(function() {

/***************************************************
		FORM VALIDATION JAVASCRIPT
***************************************************/
$(document).ready(function() {
	$('form.form-horizontal').submit(function() {
		$('form.form-horizontal .error').remove();
		var hasError = false;
		$('.requiredField').each(function() {
			if(jQuery.trim($(this).val()) == '') {
            	var labelText = $(this).prev('label').text();
            	$(this).parent().append('<span class="error">Please enter your '+labelText+'</span>');
            	$(this).addClass('inputError');
            	hasError = true;
            } else if($(this).hasClass('email')) {
            	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            	if(!emailReg.test(jQuery.trim($(this).val()))) {
            		var labelText = $(this).prev('label').text();
            		$(this).parent().append('<span class="error">Please a valid '+labelText+'</span>');
            		$(this).addClass('inputError');
            		hasError = true;
            	}
            }
		});
		if(!hasError) {
			$('form.form-horizontal input.submit').fadeOut('normal', function() {
				$(this).parent().append('');
			});
			var formInput = $(this).serialize();
			$.post($(this).attr('action'),formInput, function(data){
				$('form.form-horizontal').slideUp("fast", function() {
					$(this).before('<p class="success">Thank you!<br/>Your email was sent successfully.<br/>We will contact you as soon as possible.</p>');
				});
			});
		}

		return false;
	});
});

/***************************************************
		SCROLL TO
***************************************************/
	$('.brand a, .main-nav a, .scroll a, #to-top').live('click', function() {
		var thehref = $(this).attr('href');
			$('.in').collapse('hide');	// closes the bootstrap menu
		if ( thehref == '#intro' ) {
			$.scrollTo( 0, 800 );
		} else {
			$.scrollTo( thehref, 800, {offset:-95} );
		}
		return false;
	});
/**
 * Copyright (c) 2007-2012 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * @author Ariel Flesler
 * @version 1.4.3.1
 */
;(function($){var h=$.scrollTo=function(a,b,c){$(window).scrollTo(a,b,c)};h.defaults={axis:'xy',duration:parseFloat($.fn.jquery)>=1.3?0:1,limit:true};h.window=function(a){return $(window)._scrollable()};$.fn._scrollable=function(){return this.map(function(){var a=this,isWin=!a.nodeName||$.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!isWin)return a;var b=(a.contentWindow||a).document||a.ownerDocument||a;return/webkit/i.test(navigator.userAgent)||b.compatMode=='BackCompat'?b.body:b.documentElement})};$.fn.scrollTo=function(e,f,g){if(typeof f=='object'){g=f;f=0}if(typeof g=='function')g={onAfter:g};if(e=='max')e=9e9;g=$.extend({},h.defaults,g);f=f||g.duration;g.queue=g.queue&&g.axis.length>1;if(g.queue)f/=2;g.offset=both(g.offset);g.over=both(g.over);return this._scrollable().each(function(){if(e==null)return;var d=this,$elem=$(d),targ=e,toff,attr={},win=$elem.is('html,body');switch(typeof targ){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(targ)){targ=both(targ);break}targ=$(targ,this);if(!targ.length)return;case'object':if(targ.is||targ.style)toff=(targ=$(targ)).offset()}$.each(g.axis.split(''),function(i,a){var b=a=='x'?'Left':'Top',pos=b.toLowerCase(),key='scroll'+b,old=d[key],max=h.max(d,a);if(toff){attr[key]=toff[pos]+(win?0:old-$elem.offset()[pos]);if(g.margin){attr[key]-=parseInt(targ.css('margin'+b))||0;attr[key]-=parseInt(targ.css('border'+b+'Width'))||0}attr[key]+=g.offset[pos]||0;if(g.over[pos])attr[key]+=targ[a=='x'?'width':'height']()*g.over[pos]}else{var c=targ[pos];attr[key]=c.slice&&c.slice(-1)=='%'?parseFloat(c)/100*max:c}if(g.limit&&/^\d+$/.test(attr[key]))attr[key]=attr[key]<=0?0:Math.min(attr[key],max);if(!i&&g.queue){if(old!=attr[key])animate(g.onAfterFirst);delete attr[key]}});animate(g.onAfter);function animate(a){$elem.animate(attr,f,g.easing,a&&function(){a.call(this,e,g)})}}).end()};h.max=function(a,b){var c=b=='x'?'Width':'Height',scroll='scroll'+c;if(!$(a).is('html,body'))return a[scroll]-$(a)[c.toLowerCase()]();var d='client'+c,html=a.ownerDocument.documentElement,body=a.ownerDocument.body;return Math.max(html[scroll],body[scroll])-Math.min(html[d],body[d])};function both(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);

/***************************************************
		GOOGLE MAP
***************************************************/			
/*! jquery-ui-map rc1 | Johan Säll Larsson */
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?"":e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)d[e(c)]=k[c]||e(c);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1;};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p;}('(3(d){d.a=3(a,b){j c=a.v(".")[0],a=a.v(".")[1];d[c]=d[c]||{};d[c][a]=3(a,b){I.O&&2.1i(a,b)};d[c][a].K=d.n({1s:c,1u:a},b);d.N[a]=3(b){j g="1p"===1k b,f=L.K.X.W(I,1),i=2;l(g&&"1j"===b.1l(0,1))6 i;2.18(3(){j h=d.1b(2,a);h||(h=d.1b(2,a,k d[c][a](b,2)));g&&(i=h[b].14(h,f))});6 i}};d.a("1J.1G",{u:{1A:"1x",1y:5},1B:3(a,b){6 b?(2.u[a]=b,2.4("9").x(a,b),2):2.u[a]},1i:3(a,b){2.E=b;a=a||{};m.n(2.u,a,{1h:2.w(a.1h)});2.1g();2.1f&&2.1f()},1g:3(){j a=2;2.o={9:k 8.7.1D(a.E,a.u),M:[],p:[],q:[]};8.7.G.1C(a.o.9,"1F",3(){d(a.E).19("1E",a.o.9)});a.C(a.u.1t,a.o.9)},Z:3(a){j b=2.4("12",k 8.7.1z);b.n(2.w(a));2.4("9").1M(b);6 2},1L:3(a){j b=2.4("9").1O();6 b?b.1N(a.Y()):!1},1K:3(a,b){2.4("9").1H[b].J(2.F(a));6 2},1I:3(a,b){a.9=2.4("9");a.13=2.w(a.13);j c=k(a.1n||8.7.1o)(a),e=2.4("M");c.16?e[c.16]=c:e.J(c);c.12&&2.Z(c.Y());2.C(b,a.9,c);6 d(c)},z:3(a){2.B(2.4(a));2.x(a,[]);6 2},B:3(a){y(j b Q a)a.11(b)&&(a[b]r 8.7.17?(8.7.G.1v(a[b]),a[b].A&&a[b].A(t)):a[b]r L&&2.B(a[b]),a[b]=t)},1w:3(a,b,c){a=2.4(a);b.s=d.1m(b.s)?b.s:[b.s];y(j e Q a)l(a.11(e)){j g=!1,f;y(f Q b.s)l(-1<d.1r(b.s[f],a[e][b.1q]))g=!0;10 l(b.V&&"1P"===b.V){g=!1;2c}c(a[e],g)}6 2},4:3(a,b){j c=2.o;l(!c[a]){l(-1<a.2e(">")){y(j e=a.T(/ /g,"").v(">"),d=0;d<e.O;d++){l(!c[e[d]])l(b)c[e[d]]=d+1<e.O?[]:b;10 6 t;c=c[e[d]]}6 c}b&&!c[a]&&2.x(a,b)}6 c[a]},2g:3(a,b,c){j d=2.4("H",a.2f||k 8.7.2i);d.R(a);d.2h(2.4("9"),2.F(b));2.C(c,d);6 2},2b:3(){t!=2.4("H")&&2.4("H").2a();6 2},x:3(a,b){2.o[a]=b;6 2},2d:3(){j a=2.4("9"),b=a.2o();d(a).1e("2q");a.2p(b);6 2},2k:3(){2.z("M").z("q").z("p").B(2.o);m.2n(2.E,2.1W)},C:3(a){a&&d.1X(a)&&a.14(2,L.K.X.W(I,1))},w:3(a){l(!a)6 k 8.7.P(0,0);l(a r 8.7.P)6 a;a=a.T(/ /g,"").v(",");6 k 8.7.P(a[0],a[1])},F:3(a){6!a?t:a r m?a[0]:a r 1Q?a:d("#"+a)[0]},1S:3(a,b,c){j d=2,g=2.4("q > U",k 8.7.U),f=2.4("q > S",k 8.7.S);b&&f.R(b);g.1U(a,3(a,b){"1T"===b?(f.26(a),f.A(d.4("9"))):f.A(t);c(a,b)})},27:3(a,b){2.4("9").29(2.4("q > 1d",k 8.7.1d(2.F(a),b)))},28:3(a,b){2.4("q > 1a",k 8.7.1a).21(a,b)},20:3(a,b){j c=k 8.7[a](m.n({9:2.4("9")},b));2.4("p > "+a,[]).J(c);6 d(c)},22:3(a,b){(!b?2.4("p > D",k 8.7.D):2.4("p > D",k 8.7.D(b,a))).R(m.n({9:2.4("9")},a))},23:3(a,b,c){2.4("p > "+a,k 8.7.1Y(b,m.n({9:2.4("9")},c)))}});m.N.n({1e:3(a){8.7.G.19(2[0],a);6 2},15:3(a,b,c){8.7&&2[0]r 8.7.17?8.7.G.24(2[0],a,b):c?2.1c(a,b,c):2.1c(a,b);6 2}});m.18("25 1R 1Z 1V 2m 2l 2j".v(" "),3(a,b){m.N[b]=3(a,d){6 2.15(b,a,d)}})})(m);',62,151,'||this|function|get||return|maps|google|map||||||||||var|new|if|jQuery|extend|instance|overlays|services|instanceof|value|null|options|split|_latLng|set|for|clear|setMap|_c|_call|FusionTablesLayer|el|_unwrap|event|iw|arguments|push|prototype|Array|markers|fn|length|LatLng|in|setOptions|DirectionsRenderer|replace|DirectionsService|operator|call|slice|getPosition|addBounds|else|hasOwnProperty|bounds|position|apply|addEventListener|id|MVCObject|each|trigger|Geocoder|data|bind|StreetViewPanorama|triggerEvent|_init|_create|center|_setup|_|typeof|substring|isArray|marker|Marker|string|property|inArray|namespace|callback|pluginName|clearInstanceListeners|find|roadmap|zoom|LatLngBounds|mapTypeId|option|addListenerOnce|Map|init|bounds_changed|gmap|controls|addMarker|ui|addControl|inViewport|fitBounds|contains|getBounds|AND|Object|rightclick|displayDirections|OK|route|mouseover|name|isFunction|KmlLayer|dblclick|addShape|geocode|loadFusion|loadKML|addListener|click|setDirections|displayStreetView|search|setStreetView|close|closeInfoWindow|break|refresh|indexOf|infoWindow|openInfoWindow|open|InfoWindow|dragend|destroy|drag|mouseout|removeData|getCenter|setCenter|resize'.split('|'),0,{}))

/***************************************************
		GALLERY QUICKSAND
***************************************************/
var $filterType = $('#filterOptions li.active a').attr('class');
var $holder = $('ul.holder');
var $data = $holder.clone();

$('#filterOptions li a').click(function(e) {
	
	$('#filterOptions li').removeClass('active');
	
	var $filterType = $(this).attr('class');
	$(this).parent().addClass('active');
	
	if ($filterType == 'all') {
		var $filteredData = $data.find('li');
	} 
	else {
		var $filteredData = $data.find('li[data-type~=' + $filterType + ']');
	}
	
	// call quicksand
	$holder.quicksand($filteredData, {
		duration: 800,
		easing: 'easeInOutQuad'
		},
		function() {
			callprettyPhoto();
			galleryHover();
	});
	return false;
});

/***************************************************
		TOOLTIP
***************************************************/
$("[rel=tooltip]").tooltip();
$("[rel=popover]").hover(function(){
	$(this).popover('toggle');
	});
	
/***************************************************
		BACK TO TOP
***************************************************/
	$('#to-top').hide();
	
	var offset = $(document).scrollTop();
	var offsetBottom = offset + ($(window).height() - 70);
		
	$('#to-top').css({
		'top':offsetBottom
	});
	if(offset > 10){
		$('#to-top').fadeIn(900);	
	}
	$(window).scroll(function(){
		
		var offset = $(document).scrollTop();
			offsetBottom = offset + ($(window).height() - 70);
		
		if(offset > 1){
			$('#to-top').fadeIn(900);	
		}
		else{
			$('#to-top').fadeOut(900);	
		}
			
		$('#to-top').animate({
			top:offsetBottom
		},{duration:500,queue:false});
	});
	
/***************************************************
		GALLERY SETTINGS
***************************************************/
$(document).ready(function() {
	$(".various").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
});
$(".fancybox-effects").fancybox({
				padding: 0,
				closeClick : true,
				helpers : {
					speedOut : 0
				}
			});
});

