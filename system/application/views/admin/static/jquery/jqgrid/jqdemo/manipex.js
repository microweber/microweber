jQuery("#list5").jqGrid({        
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
   	pager: jQuery('#pager5'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"Simple data manipulation",
    editurl:"someurl.php"
}).navGrid("#pager5",{edit:false,add:false,del:false});
jQuery("#a1").click( function(){
	var id = jQuery("#list5").getGridParam('selrow');
	if (id)	{
		var ret = jQuery("#list5").getRowData(id);
		alert("id="+ret.id+" invdate="+ret.invdate+"...");
	} else { alert("Please select row");}
});
jQuery("#a2").click( function(){
	var su=jQuery("#list5").delRowData(12);
	if(su) alert("Succes. Write custom code to delete row from server"); else alert("Allready deleted or not in list");
});
jQuery("#a3").click( function(){
	var su=jQuery("#list5").setRowData(11,{amount:"333.00",tax:"33.00",total:"366.00",note:"<img src='images/user1.gif'/>"});
	if(su) alert("Succes. Write custom code to update row in server"); else alert("Can not update");
});
jQuery("#a4").click( function(){
	var datarow = {id:"99",invdate:"2007-09-01",name:"test3",note:"note3",amount:"400.00",tax:"30.00",total:"430.00"};
	var su=jQuery("#list5").addRowData(99,datarow);
	if(su) alert("Succes. Write custom code to add data in server"); else alert("Can not update");
});
