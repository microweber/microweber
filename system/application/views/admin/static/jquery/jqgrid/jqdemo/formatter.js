jQuery("#frmgrid").jqGrid({
   	url:'editing.php?q=1',
	datatype: "xml",
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Closed','Ship via','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:55,formatter: 'integer'},
   		{name:'invdate',index:'invdate', width:80,formatter:'date'},
   		{name:'name',index:'name', width:90, formatter: 'link'},
   		{name:'amount',index:'amount', width:60, align:"right",formatter:'currency'},
   		{name:'tax',index:'tax', width:60, align:"right",formatter:'currency'},
   		{name:'total',index:'total', width:60,align:"right",formatter:'currency'},
		{name:'closed',index:'closed',width:55,align:'center',formatter:'checkbox'},
		{name:'ship_via',index:'ship_via',width:70},
   		{name:'note',index:'note', width:100, sortable:false}		
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: gridimgpath,
   	pager: jQuery('#pfrmgrid'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"Formatter Example",
	height:210
});