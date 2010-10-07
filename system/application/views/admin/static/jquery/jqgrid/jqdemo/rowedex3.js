var lastsel;
jQuery("#rowed3").jqGrid({
   	url:'server.php?q=2',
	datatype: "json",
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:55},
   		{name:'invdate',index:'invdate', width:90, editable:true},
   		{name:'name',index:'name', width:100,editable:true},
   		{name:'amount',index:'amount', width:80, align:"right",editable:true},
   		{name:'tax',index:'tax', width:80, align:"right",editable:true},		
   		{name:'total',index:'total', width:80,align:"right",editable:true},		
   		{name:'note',index:'note', width:150, sortable:false,editable:true}		
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: gridimgpath,
   	pager: jQuery('#prowed3'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
	onSelectRow: function(id){
		if(id && id!==lastsel){
			jQuery('#rowed3').restoreRow(lastsel);
			jQuery('#rowed3').editRow(id,true);
			lastsel=id;
		}
	},
	editurl: "server.php",
	caption: "Using events example"
}).navGrid("#prowed3",{edit:false,add:false,del:false});
