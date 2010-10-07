jQuery("#listdt").jqGrid({        
   	//url:'server.php?q=2',
	datatype : 	function  (pdata) {
		$.ajax({
			url:'server.php?q=2',
			data:pdata,
			dataType:"json",
			complete: function(jsondata,stat){
				if(stat=="success") {
					var thegrid = jQuery("#listdt")[0];
					thegrid.addJSONData(eval("("+jsondata.responseText+")"))
				}
			}
		});
	},
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:55},
   		{name:'invdate',index:'invdate', width:90},
   		{name:'name',index:'name asc, invdate', width:100},
   		{name:'amount',index:'amount', width:80, align:"right", editable:true,editrules:{number:true,minValue:100,maxValue:350}},
   		{name:'tax',index:'tax', width:80, align:"right",editable:true,edittype:"select",editoptions:{value:"IN:InTime;TN:TNT;AR:ARAMEX"}},		
   		{name:'total',index:'total', width:80,align:"right",editable: true,edittype:"checkbox",editoptions: {value:"Yes:No"} },		
   		{name:'note',index:'note', width:150, sortable:false}		
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: gridimgpath,
   	pager: jQuery('#pagerdt'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"Data type as function Example",
    cellEdit: true
}).navGrid('#pagerdt',{edit:false,add:false,del:false});
