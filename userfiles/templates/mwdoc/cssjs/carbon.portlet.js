
carbon.portlet = function ()
{
	var pub = {};
	var self = {};
	
	pub.init = function ()
	{
		$('.portlet-closable .portlet-header').live ( 'click', self.togglePortlet );
		$('.portlet-tab-nav a').live ( 'click' , self.selectTabContent );
		
		$('.portlet-closable .portlet-header').each ( function () 
		{			
			$(this).append ('<span class="portlet-toggle-icon"></span>');
		});		
		
		$('.portlet-tab-nav li').each ( function () 
		{			
			if ( $(this).hasClass ('portlet-tab-nav-active') )
			{
				var id = $(this).find ('a').attr ('href');
				$(this).parents ('.portlet').find ( id ).show ().addClass ('portlet-tab-content-active');
			}			
		});
	}
	
	self.selectTabContent = function ()
	{
		$(this).parents ('ul').find ('li').removeClass ('portlet-tab-nav-active');
		$(this).parents ('li').addClass ('portlet-tab-nav-active');
		var $portlet = $(this).parents ('.portlet');
		$portlet.find ('.portlet-tab-content').hide ();
		$portlet.find ('#' + $(this).attr ('href') ).show ();
		return false;
	}
	
	self.togglePortlet = function ()
	{
		var $this = $(this);	
		var $portlet = $this.parent ();
		var $content = $portlet.find ('.portlet-content');
		
		var browser = $.browser.msie + $.browser.version;
		if ( browser == 'true7.0' )
			$content.toggle ();
		else
			$content.slideToggle ();
		
		$portlet.toggleClass ('portlet-state-closed');
		return false;
	}

	
	return pub;
	
}();