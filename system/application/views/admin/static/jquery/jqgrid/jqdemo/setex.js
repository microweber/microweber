jQuery("#list7").jqGrid({
   	url:'server.php?q=2',
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
   	pager: jQuery('#pager7'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"Set Methods Example",
    hidegrid: false,
    height: 210
});
jQuery("#list7").navGrid('#pager7',{edit:false,add:false,del:false,refresh:false,searchtext:"Find"});

jQuery("#s1").click( function() {
	jQuery("#list7").setGridParam({url:"server.php?q=2"}).trigger("reloadGrid")
});
jQuery("#s2").click( function() {
	jQuery("#list7").setGridParam({sortname:"amount",sortorder:"asc"}).trigger("reloadGrid");
});
jQuery("#s3").click( function() {
	var so = jQuery("#list7").getGridParam('sortorder');
	so = so=="asc"?"desc":"asc";
	jQuery("#list7").setGridParam({sortorder:so}).trigger("reloadGrid");
});
jQuery("#s4").click( function() {
	jQuery("#list7").setGridParam({page:2}).trigger("reloadGrid");
});
jQuery("#s5").click( function() {
	jQuery("#list7").setGridParam({rowNum:15}).trigger("reloadGrid");
});
jQuery("#s6").click( function() {
	jQuery("#list7").setGridParam({url:"server.php?q=1",datatype:"xml"}).trigger("reloadGrid");
});
jQuery("#s7").click( function() {
	jQuery("#list7").setCaption("New Caption");
});
jQuery("#s8").click( function() {
	jQuery("#list7").sortGrid("name",false);
});
