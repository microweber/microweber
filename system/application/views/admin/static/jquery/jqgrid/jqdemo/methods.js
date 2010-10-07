jQuery("#method").jqGrid({
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
   	pager: jQuery('#pmethod'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"New Methods",
    multiselect: true,
	onPaging : function(but) {
		alert("Button: "+but + " is clicked");
	}
}).navGrid('#pmethod',{edit:false,add:false,del:false});

jQuery("#sm1").click( function() {
	alert($("#method").getGridParam("records"));
});

jQuery("#sm2").click( function() {
	$("#method").setGridParam({
		onSelectRow : function(id) {
			$("#resp").html("I'm row number: "+id +" and setted dynamically").css("color","red");
		}
	});
	alert("Try to select row");
});

jQuery("#sm3").click( function() {
	$("#method").resetSelection();
});
