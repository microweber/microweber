var carbon = {};

carbon = function ()
{
	var pub = {};
	var self = {};
	
	pub.init = function ()
	{
		$('#dataTable').tablesorter ({ headers: { 3: {sorter: false} } });
		$('#gallery a').lightBox ();
		$('select, input:checkbox, input:radio, input:file').uniform();
		$('.bargraph').visualize({ 
			type: 'area',
			width: '600',
			height: '200',
			colors: ['#EDC240','#0066A4','#555','#777','#999','#bbb','#ccc','#eee'],
			appendTitle: false
		});
	}
	
	
	
	return pub;
	
}();