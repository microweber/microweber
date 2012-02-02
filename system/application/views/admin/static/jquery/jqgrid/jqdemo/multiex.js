jQuery("#list9").jqGrid({
   	url:'server.php?q=2&nd='+new Date().getTime(),
	datatype: "json",
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
   	pager: jQuery('#pager9'),
   	sortname: 'id',
	recordpos: 'left',
    viewrecords: true,
    sortorder: "desc",
	multiselect: true,
	caption: "Multi Select Example"
}).navGrid('#pager9',{add:false,del:false,edit:false,position:'right'});
jQuery("#m1").click( function() {
	var s;
	s = jQuery("#list9").getGridParam('selarrrow');
	alert(s);
});
jQuery("#m1s").click( function() {
	jQuery("#list9").setSelection("13");
});
