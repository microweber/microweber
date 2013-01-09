
function Pop (a, elID)
{
	var outcome = document.getElementById(elID).style.display =
	document.getElementById(elID).style.display == "none"
	|| document.getElementById(elID).style.display == ""
	? "block" : "none";

	return false;
}

function popitup(url)
{
	var w = 530;
	var h = 400;
	var topPos = 50;
	var leftPos = (screen.width - w) / 2;

	newwindow=window.open(url,'name','left=' + leftPos + ',top=' + topPos + ',height=' + h + ',width=' + w + ',scrollbars=yes');
	if (window.focus) {newwindow.focus()}
	return false;
}

function syncHeights ()
{
	var mxHeight = 0;
	for (var i = 0; i < arguments.length; i ++)
	{
		mxHeight = Math.max(mxHeight, arguments[i].offsetHeight);
	}

	for (var i = 0; i < arguments.length; i ++)
	{
		setHeight(arguments[i], mxHeight);
	}
}

function setHeight(element, height)
{
	document.all
	?	element.style.height = height + "px"
	:	element.style.minHeight = height + "px";
}

function Collection(div_id, images)
{
    this.block_id        = div_id.split('-')[0];
    this.block_div_id    = div_id;
    this.el_image        = document.getElementById('image-collection-' + this.block_div_id);
    this.el_description  = document.getElementById('description-' + this.block_div_id);
    this.el_counter      = document.getElementById('collection-counter-' + this.block_div_id);
    this.images          = images;
    this.current_index   =  0;

    this.getNextImage = function()
    {
        var temp = new Image();
        var img = new Image();

        if(arguments.length)
        {
            switch (arguments[0])
            {
                case 'forward':
                    temp = this.images[this.current_index + 1] || this.images[0];
                    break;

                case 'back':
                    temp = this.images[this.current_index - 1] || this.images[this.images.length - 1];
                    break;
            }
        }
        else
        {
            temp = this.images[current_index + 1] || this.images[current_index];
        }

        img.src = temp.src;
        img.width = temp.width;
        img.height = temp.height;
        img.description = temp.description;
        img.key_index = temp.key_index;

        return img;
    };

    this.next_image      = this.getNextImage('forward');
    this.previous_image  = this.getNextImage('back');

    this.show = function(direction)
    {
        switch(direction)
        {
            case 'next':
                this.el_image.src             = this.next_image.src;
                this.el_image.width           = this.next_image.width;
                this.el_image.height          = this.next_image.height;
                this.current_index            = this.next_image.key_index;
                this.el_description.innerHTML = this.next_image.description;
                this.el_counter.innerHTML = this.current_index + 1 + '/' + this.images.length;
                break;

            case 'previous':
                this.el_image.src             = this.previous_image.src;
                this.el_image.width           = this.previous_image.width;
                this.el_image.height          = this.previous_image.height;
                this.current_index            = this.previous_image.key_index;
                this.el_description.innerHTML = this.previous_image.description;
                this.el_counter.innerHTML = this.current_index + 1 + '/' + this.images.length;
                break;
        }

        this.next_image     = this.getNextImage('forward');
        this.previous_image = this.getNextImage('back');

        return false;
    };

    this.onclick = function()
    {
		var w = 500;
		var h = 500;
		var topPos = (screen.height - h) / 2;
		var leftPos = (screen.width - w) / 2;

		newwindow=window.open(this.images[this.current_index].href,'name','left=' + leftPos + ',top=' + topPos + ',height=' + h + ',width=' + w + ',scrollbars=yes,resizable=yes');
		if (window.focus) {newwindow.focus()}
    	return false;
    }

}

var panes = new Array();

function setupPanes(containerId, defaultTabId) {

  panes[containerId] = new Array();
  var maxHeight = 0; var maxWidth = 0;
  var container = document.getElementById(containerId);
  var paneContainer = container.getElementsByTagName("div")[0];
  var paneList = paneContainer.childNodes;
  for (var i=0; i < paneList.length; i++ ) {
    var pane = paneList[i];
    if (pane.nodeType != 1) continue;
    if (pane.offsetHeight > maxHeight) maxHeight = pane.offsetHeight;
    if (pane.offsetWidth  > maxWidth ) maxWidth  = pane.offsetWidth;
    panes[containerId][pane.id] = pane;
    pane.style.display = "none";
  }
    paneContainer.style.height = maxHeight + "px";
    paneContainer.style.width  = maxWidth + "px";
    document.getElementById(defaultTabId).onclick();
}

function showPane(paneId, activeTab) {
  
    for (var con in panes) {
    activeTab.blur();
    activeTab.className = "tab-active";
    if (panes[con][paneId] != null) { // tab and pane are members of this container
      var pane = document.getElementById(paneId);
      pane.style.display = "block";
      var container = document.getElementById(con);
      var tabs = container.getElementsByTagName("ul")[0];
      var tabList = tabs.getElementsByTagName("a")
      for (var i=0; i<tabList.length; i++ ) {
        var tab = tabList[i];
        if (tab != activeTab) tab.className = "tab-disabled";
      }
      for (var i in panes[con]) {
        var pane = panes[con][i];
        if (pane == undefined) continue;
        if (pane.id == paneId) continue;
        pane.style.display = "none"
      }
    }
  }
  return false;    
}




function Ajax(method,onComplete)
{
	this.requestobj = null;
	this.queryStringSeparator = "?";
	this.argumentSeparator = "&";
	this.method = method;
	this.URLString = '';
	this.failed = false;
	
	if (window.XMLHttpRequest)
	{
          // If IE7, Mozilla, Safari, etc: Use native object
          this.requestobj = new XMLHttpRequest()
	}
	else
	{
		if (window.ActiveXObject)
		{
          // ...otherwise, use the ActiveX control for IE5.x and IE6
          this.requestobj = new ActiveXObject("Microsoft.XMLHTTP");
        }
        else
        {
        	this.failed = true;
        }
	
	}
	
	
	if (this.failed)
	{
		alert('Failed to create AJAX object');
	}
	
	this.request = function(url,params)
	{
		
		this.urlString= url + this.queryStringSeparator + params;
		if (this.method == "GET") {
			totalurlstring = url + this.queryStringSeparator + params;
			this.requestobj.open(this.method, totalurlstring, true);
			
		} else {
			this.requestobj.open(this.method, url, true);
			try {
				this.requestobj.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
			} catch (e) { }
			this.URLString=params;
		}
		this.requestobj.onreadystatechange = onComplete;
		
		this.requestobj.send(this.URLString);
	}
	
	
}
function get_districts(city_id,district,selectID,lang)
{
	//document.getElementById('msgBox').innerHTML='';
	var ajax = new Ajax('GET',onComplete=function()
		{
			switch(ajax.requestobj.readyState)
			{
				case 4:
					xml = ajax.requestobj.responseXML;
					xmlroot = ajax.requestobj.responseXML.documentElement;
					var district_count = xmlroot.getElementsByTagName('district').length-1;
					var select = document.getElementById(selectID);
					select.options.length=0;
					
					/*var op = document.createElement('option');
					op.setAttribute('value',9999);
					var txt = document.createTextNode('Всички');
					op.appendChild(txt);
					select.appendChild(op);*/
						
					for (i=0;i<=district_count;i++)
					{
						var op_txt = xmlroot.getElementsByTagName('district')[i].firstChild.data;
						var op_val = xmlroot.getElementsByTagName('district')[i].getAttribute('value');
						var op = document.createElement('option');
						if (district == op_val)
						{
							op.setAttribute('selected',true);
						}
						//console.log(op_val+':'+xmlroot.getElementsByTagName('district')[i].firstChild.data);
						op.setAttribute('value',op_val);
						var txt = document.createTextNode(op_txt);
						op.appendChild(txt);
						select.appendChild(op);
						select.disabled=false;
					}
				break;
			}
		}
	);
	ajax.request('/ajax_server.php','action=get_districts&city_id='+city_id+'&district='+district+'&lang='+lang);
}

function callme() {
    document.getElementById("ZG1").style.display="none";
    document.getElementById("ZG2").style.visibility="visible";
}

function hideNotStandardBanner(divHide, divShow)
{
	document.getElementById(divHide).style.display="none";
	document.getElementById(divShow).style.display="block";
}

function zagorkaBanner(divHide, divShow){
    document.getElementById(divHide).style.display="none";
    document.getElementById(divShow).style.display="block";
    var flashMovie=document.getElementById("Movie1");
    flashMovie.Play();
}


/*Globul Safe Internet banner Code*/

function getFlashMovieObject(movieName)
{
  if (window.document[movieName]) 
  {
	return window.document[movieName];
  }
  if (navigator.appName.indexOf("Microsoft Internet")==-1)
  {
	if (document.embeds && document.embeds[movieName])
	  return document.embeds[movieName]; 
  }
  else // if (navigator.appName.indexOf("Microsoft Internet")!=-1)
  {
	return document.getElementById(movieName);
  }
}

function Banner(divHide, divShow){
document.getElementById(divHide).style.display="none";
document.getElementById(divShow).style.display="block";
//var flashMovie=getFlashMovieObject("Movie1");
//flashMovie.Play();
}

/*END Globul Safe Internet banner Code*/

/*VW Golf and Jetta code*/
function PlayFlashMovie()
{
	document.getElementById("floating_part").Play();
}
function StopFlashMovie()
{
	document.getElementById("floating_part").Stop();
}
 
function magRemoveFlash(objectName) {
    var object = document.getElementById(objectName) || false;
    if(object) object.parentNode.removeChild(object);
}

function closeFlash(){
	document.getElementById("bigflash").style.display="none";
	StopFlashMovie();
}
function openFlash(){
 	document.getElementById("bigflash").style.display="block";
	PlayFlashMovie();
}
/*END VW Golf and Jetta code*/


function SimpleCarousel(container,options)
{
	this.interval = null;
	this.slideShowPause = options.slideShowTime? options.slideShowTime : 3000;
	this.container = container;
	this.options = options;
	this._slides = null;
	this._slidesArray = new Array();
	this._currentSlide = null;
	this._slideShow = options.slideShow? options.slideShow : false;
	
	if (options.classname)
	{
		this.classname = options.classname; 
	}
	else
	{
		this.classname = 'carousel';
	}
	
	this.nextControl = document.getElementById(options.navigation.next);
	this.prevControl = document.getElementById(options.navigation.prev);
	
	this.callback = options.callback;
	
	
	this.getElementsByClass = function (searchClass,node,tag) {
	    var classElements = new Array();
	    if ( node == null )
	            node = document;
	    if ( tag == null )
	            tag = '*';
	    
	    
	    var els = node.getElementsByTagName(tag);
	    var elsLen = els.length;
	    var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
	    for (i = 0, j = 0; i < elsLen; i++) {
	            if ( pattern.test(els[i].className) ) {
	                    classElements[j] = els[i];
	                    j++;
	            }
	    }
	    return classElements;
	}
	this.stopEvent = function(event)
	{
		event.stopped = true;
		if (event.preventDefault) {
			event.preventDefault();
			event.stopPropagation();
		} else {
			event.returnValue = false;
			event.cancelBubble = true;
		}
	}
	this.attachEvent = function(element, name, observer, useCapture)
	{
		if (element.addEventListener) {
			element.addEventListener(name, observer, useCapture);
		} else if (element.attachEvent) {
			element.attachEvent('on' + name, observer);
		}
	}
	this._getSlides = function()
	{
		if (document.getElementsByClassName)
		{
			var s = document.getElementById(this.container).getElementsByClassName(this.classname)
		}
		else
		{
			var s = this.getElementsByClass(this.classname,document.getElementById(this.container))
		}
		if (s.length)
		{
			this._slides = s;
			for( var i =0 ; i < s.length; i++)
			{
				this._slidesArray[i] = s[i];
			}
			this._currentSlide = 0;
			this._slidesArray[this._currentSlide].style.display = 'block';
		}
	}
	
	this.Next = function()
	{
		this._slidesArray[this._currentSlide].style.display = 'none';
		if (this._slidesArray.length -1 == this._currentSlide)
		{
			var next = 0;
		}
		else
		{
			var next = this._currentSlide + 1;
		}
		
		this._slidesArray[next].style.display = 'block';
		this._currentSlide = next;

		//call Callback
		if(this.callback)
		{
			this.callback.next(this);
		}
		
		this._runSlideShow(this);
	}
	
	this.Prev = function()
	{
		this._slidesArray[this._currentSlide].style.display = 'none';
		if (0 == this._currentSlide)
		{
			var prev = this._slidesArray.length -1 ;
		}
		else
		{
			var prev = this._currentSlide - 1;
		}
		this._slidesArray[prev].style.display = 'block';
		this._currentSlide = prev;

		//Call the callback
		if(this.callback)
		{
			this.callback.prev(this);
		}
		
		
	}
	
	this._setupNav = function(obj)
	{
		if (this.prevControl)
		{
			obj.attachEvent(this.prevControl,'click',function(e){obj.Prev();obj.stopEvent(e);})
		}
		if (this.nextControl)
		{
			obj.attachEvent(this.nextControl,'click',function(e){obj.Next();obj.stopEvent(e);})
		}
	}
	this._runSlideShow = function(obj)
	{
		if (this._slideShow)
		{
			this.interval = window.setTimeout(function (){obj.Next()}, this.slideShowPause);
		}
		
	}
	
	this.stopSlideShow = function()
	{
		window.clearTimeout(this.interval);
		this.interval = null;
	}
	
	this.init = function()
	{
		this._setupNav(this);
		this._getSlides();
		this._runSlideShow(this);
	}
	
}


function toggle(element)
{
	if (element.style.display == 'block')
	{
		element.style.display = 'none';
	}
	else{
		element.style.display = 'block';
	}
}

function Set_Cookie( name, value, expires, path, domain, secure )
{
	// set time, it's in milliseconds
	var today = new Date();
	today.setTime( today.getTime() );
	
	/*
	if the expires variable is set, make the correct
	expires time, the current script below will set
	it for x number of days, to make it for hours,
	delete * 24, for minutes, delete * 60 * 24
	*/
	if ( expires )
	{
	expires = expires * 1000 * 60 * 60 * 24;
	}
	var expires_date = new Date( today.getTime() + (expires) );
	
	document.cookie = name + "=" +escape( value ) +
	( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
	( ( path ) ? ";path=" + path : "" ) +
	( ( domain ) ? ";domain=" + domain : "" ) +
	( ( secure ) ? ";secure" : "" );
}

function WeatherChange(city,block_id, city_code)
{
	var data = weather_data[city]['cc'];
	document.getElementById('JS-CurrentWeatherCity-'+block_id).innerHTML = city;
	document.getElementById('JS-CurrentWeatherLow-'+block_id).innerHTML = data['tmp'];
	//$('JS-CurrentWeatherHi-'+block_id).innerHTML = data['hi'];
	document.getElementById('JS-CurrentWeatherIcon-'+block_id).src = '/img/weather/61x61/'+data['icon']+'.png';
	Set_Cookie('weather_city',city_code,365);
}


function SimpleTabs(options)
{
	this.tabs = options.tabs;
	this.tabNav = options.tabNav
	this.activeTab = 0;
	
	this.init = function()
	{
		//this.tabs[0];
	}
	
	this.toggle = function(index)
	{
		document.getElementById(this.tabNav[this.activeTab]).className = '';
		document.getElementById(this.tabNav[index]).className = 'active';
		
		document.getElementById(this.tabs[this.activeTab]).style.display = 'none';
		document.getElementById(this.tabs[index]).style.display = 'block';
		this.activeTab = index;
	}
}
function getFlashMovie(movieName) {
	var isIE = navigator.appName.indexOf("Microsoft") != -1;
	    return (isIE) ? window[movieName] : document[movieName];
	  }

function collectVars(movie, title, link, link2, vid) {
	//$('JS-title').innerHTML = title;
   getFlashMovie("movie").sendVarsToFlash(movie, title, link, link2, vid);
   
}
function toggleWeatherCity(block_id)
{
	toggle(document.getElementById('JS-weather-cities-'+block_id));
	/*window.setTimeout(function(){
		$('JS-weather-cities-'+block_id).style.display = 'none';
	},2000)*/
}

MenuMore = {
		el : null,
		menu_item : null,
		mouseMoveinterval : null,
		
		init : function()
		{
			YAHOO.util.Event.onAvailable('more', this.onMoreDropDownAvailable, this);
		},
		onMoreDropDownAvailable : function(self)
		{
			self.el = new YAHOO.util.Element('MenuLeftBottom'),
			self.menu_item = new YAHOO.util.Element('more');
			
			YAHOO.util.Event.on('more','click',function(e)
					{
						var current_style = self.el.getStyle('display');
						switch(current_style)
						{
							case 'block':
								self.hideDropDown();
								break;
							case 'none':
								self.showDropDown();
								break;
						}
						
						
						YAHOO.util.Event.stopEvent(e);
						//Attach event listener to Document
						YAHOO.util.Event.on(document,'mousemove',self.documentListener)
					}
			,self);
		},
		hideDropDown : function()
		{
			
			this.menu_item.replaceClass('moreopen','more');
			this.el.setStyle('display' , 'none');
		},
		
		showDropDown : function()
		{
			this.menu_item.replaceClass('more','moreopen');
			this.el.setStyle('display' , 'block');
		},
		
		hideAndStop : function()
		{
			if (MenuMore.mouseMoveinterval)
			{
				YAHOO.util.Event.removeListener(document,'mousemove',this.documentListener);
				MenuMore.hideDropDown();
				
			}
		},
		
		documentListener : function(e)
		{
			var target1 = YAHOO.util.Event.getTarget(e);
			var parent = YAHOO.util.Dom.getAncestorByTagName(target1,'div');
			
			if (!parent || parent.id != 'JS-MenuLeftBottom' && target1.id != 'JS-MenuLeftBottom' && target1.id != 'morelink')
			{
				MenuMore.mouseMoveinterval = window.setTimeout(MenuMore.hideAndStop,1500)
			}
			else
			{
				MenuMore.mouseMoveinterval = null;
			}
		}
}

function SimpleMenu(holder, menu, link)
{
	this.link = link;
	this.el = new YAHOO.util.Element(menu)
	this.holder = new YAHOO.util.Element(holder);
	this.menu_item = null;
	this.mouseMoveinterval = null;
	
	this.init = function()
	{
		YAHOO.util.Event.onAvailable(this.holder, this.onMoreDropDownAvailable, this);
	}
	this.onMoreDropDownAvailable = function(self)
	{
		
		self.menu_item = new YAHOO.util.Element(this.link);
		
		YAHOO.util.Event.on(this.link,'click',function(e)
				{
					var current_style = self.el.getStyle('display');
					switch(current_style)
					{
						case 'block':
							self.hideDropDown();
							break;
						case 'none':
							self.showDropDown();
							break;
					}
					
					
					YAHOO.util.Event.stopEvent(e);
					//Attach event listener to Document
					YAHOO.util.Event.on(document,'mousemove',self.documentListener)
				}
		,self);
	}
	this.hideDropDown = function()
	{
		this.el.setStyle('display' , 'none');
	}
	this.showDropDown = function()
	{
		this.el.setStyle('display' , 'block');
	}
	this.hideAndStop = function()
	{
		if (this.mouseMoveinterval)
		{
			YAHOO.util.Event.removeListener(document,'mousemove',this.documentListener);
			this.hideDropDown();
			
		}
	}
	this.documentListener = function(e)
	{
		var target1 = YAHOO.util.Event.getTarget(e);
		var parent = YAHOO.util.Dom.getAncestorByTagName(target1,'div');
		
		if (!parent || parent.id != this.holder.id && target1.id != id )
		{
			this.mouseMoveinterval = window.setTimeout(MenuMore.hideAndStop,1500)
		}
		else
		{
			this.mouseMoveinterval = null;
		}
	}
}


function loginBoxSlide()
{
	//Uses JQuery
	if($('#Login').is(":hidden"))
	{
		//$('#Login').slideDown();
		$('#Login').animate({
			"height": "toggle", "opacity": "toggle"
		},200);
		$("#LoginEmail").focus();
	}
	else{
		$('#Login').slideUp();
		$('#JS-LoginMsgBox').text('');
	}
}

function Login()
{
	if ($('#LoginEmail').val() && $('#LoginPassword').val())
	{
		$('#JS-LoginMsgBox').hide("slow");
		$.post('/login/',$('#Login').serialize(),
			function(data)
			{
				if (data){
					//Login OK
					$('#Strip').html(data);
					$('#Login').slideUp(100);
				}
				else{
					//Login failed
					$('#JS-LoginMsgBox').text('Въвели сте комбинация от имейл и парола, които не отговарят на нито един от регистрираните профили.');
					$('#JS-LoginMsgBox').show(100);
				}
			}
		);
	}
	else{
		$('#JS-LoginMsgBox').text('Моля попълнете Вашия e-мейл и парола');
	}
}

$(document).ready(function(){
		$("p.material-tags a:last").addClass("last");
  });
  
/* FavoriteIcon v1.1 - 5 May 09 (http://blog.liviuholhos.com/javascript/add-a-favicon-near-external-links-with-jquery)
 * Author : Liviu Holhos (http://www.liviuholhos.com/) */
(function($){
	$.fn.favoriteIcon = function(options) {
		var defaults = {
			iconClass    : 'favoriteIcon',
			insertMethod : 'prependTo',
			iconSearched : 'favicon.ico'
		};
		var options = $.extend(defaults, options);
	
		$(this).filter(function(){
			return this.hostname && this.hostname !== location.hostname;
		}).each(function() {
			var link = jQuery(this);
			var faviconURL = link.attr('href').replace(/^(http:\/\/[^\/]+).*$/, '$1')+'/'+options.iconSearched;
			var faviconIMG = jQuery('<img class="'+options.iconClass+'" src="/img/ico_link.gif" alt="" />')[options.insertMethod](link);
			var extImg = new Image();
			extImg.src = faviconURL;
			if (extImg.complete)
				faviconIMG.attr('src', faviconURL);
			else
				extImg.onload = function() { faviconIMG.attr('src', faviconURL); };
		});
	}
})(jQuery);

$(document).ready(function() {
	 $(".block-links a").favoriteIcon({ iconClass : 'favoriteIcon' });
});


function FBLogin(back_url)
{
	FB.getLoginStatus(function(response) {
		  if (response.session) {
		    // logged in and connected user, someone you know
			  window.location.href = '/fbconnect.php?ref='+escape(window.location.href);
		  } else {
		    // no user session available, someone you dont know
			  FB.login(function(response) {
				  //console.log('A FB.login callback ')
				  //console.log(response)
				  window.location.href = '/fbconnect.php?ref='+back_url
			  }, {perms:''} )
		  }
		});

}
