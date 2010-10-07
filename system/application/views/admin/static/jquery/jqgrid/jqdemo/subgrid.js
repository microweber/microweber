jQuery("#list11").jqGrid({
   	url:'server.php?q=1',
	datatype: "xml",
	height: 200,
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
   	pager: jQuery('#pager11'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
	multiselect: false,
	subGrid : true,
	subGridUrl: 'subgrid.php?q=2',
    subGridModel: [{ name  : ['No','Item','Qty','Unit','Line Total'], 
                    width : [55,200,80,80,80] } 
    ],
    caption: "Subgrid Example"
	
}).navGrid('#pager11',{add:false,edit:false,del:false});
