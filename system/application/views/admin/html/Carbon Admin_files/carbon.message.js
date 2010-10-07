carbon.message = function ()
{
	var pub = {};
	var self = {};
	
	pub.init = function ()
	{
		$('.message span').live ( 'click', self.closeMessage );
		
		$('.message').each ( function () 
		{			
			if ( $(this).hasClass ('message-closable') )
			{
				$(this).append ('<span>X</span>');
			}	
		});
	}
	
	self.closeMessage = function ()
	{
		$(this).parent ().slideUp ();	
		return false;	
	}
	
	return pub;	
}();