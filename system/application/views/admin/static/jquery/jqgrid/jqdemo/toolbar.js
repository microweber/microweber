jQuery("#toolbar1").jqGrid({
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
   	pager: jQuery('#pgtoolbar1'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"Toolbar Example",
    editurl:"someurl.php",
	toolbar: [true,"top"]
}).navGrid('#pgtoolbar1',{edit:false,add:false,del:false});

$("#t_toolbar1").append("<input type='button' value='Click Me' style='height:20px;font-size:-3'/>");
$("input","#t_toolbar1").click(function(){
	alert("Hi! I'm added button at this toolbar");
});
jQuery("#toolbar2").jqGrid({        
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
   	pager: jQuery('#pgtoolbar2'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"User Data Example",
    editurl:"someurl.php",
	toolbar: [true,"bottom"],
	loadComplete: function() {
		var udata = $("#toolbar2").getUserData();
		$("#t_toolbar2").css("text-align","right").html("Totals Amount:"+udata.tamount+" Tax: "+udata.ttax+" Total: "+udata.ttotal+ "&nbsp;&nbsp;&nbsp;");
	}
}).navGrid('#pgtoolbar2',{edit:false,add:false,del:false});
