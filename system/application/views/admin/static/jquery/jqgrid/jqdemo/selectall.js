jQuery("#selall").jqGrid({
   	url:'server.php?q=1',
	datatype: "xml",
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:55},
   		{name:'invdate',index:'invdate', width:90},
   		{name:'name',index:'name', width:100},
   		{name:'amount',index:'amount', width:80, align:"right"},
   		{name:'tax',index:'tax', width:80, align:"right"},		
   		{name:'total',index:'total', width:80,align:"right"},		
   		{name:'note',index:'note', width:150, sortable:false}		
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: gridimgpath,
   	pager: jQuery('#pselall'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    multiselect: true,
    caption:"onSelectAll Event",
    onSelectAll : function(aSel,selected) {
		if(selected){
			var value =0;
			for(var i=0;i<aSel.length;i++){
				var data = jQuery("#selall").getRowData(aSel[i]);
				value += parseFloat(data.total);
			}
			jQuery("#totamt").html(value.toFixed(2));
		} else {
			jQuery("#totamt").html('0.00');
		}
	},
	onSelectRow : function(rowid, selected){
		var data = jQuery("#selall").getRowData(rowid);		
		var value = parseFloat(jQuery.trim(jQuery("#totamt").html()));
		if(selected) {
			value += parseFloat(data.total);
		} else {
			value -= parseFloat(data.total);
		}
		jQuery("#totamt").html(value.toFixed(2));		
	},
	onPaging: function(b){
		jQuery("#totamt").html('0.00');
	},
	onSortCol: function (a,b){
		jQuery("#totamt").html('0.00');
	}
}).navGrid('#pmethod',{edit:false,add:false,del:false});
