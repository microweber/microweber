jQuery("#celltbl").jqGrid({
   	url:'server.php?q=2',
	datatype: "json",
   	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
   	colModel:[
   		{name:'id',index:'id', width:55},
   		{name:'invdate',index:'invdate', width:90,editable:true},
   		{name:'name',index:'name asc, invdate', width:100},
   		{name:'amount',index:'amount', width:80, align:"right",editable:true,editrules:{number:true}},
   		{name:'tax',index:'tax', width:80, align:"right",editable:true,editrules:{number:true}},		
   		{name:'total',index:'total', width:80,align:"right"},		
   		{name:'note',index:'note', width:150, sortable:false}		
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	imgpath: gridimgpath,
   	pager: jQuery('#pcelltbl'),
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"Cell Edit Example",
	forceFit : true,
	cellEdit: true,
	cellsubmit: 'clientArray',
	afterEditCell: function (id,name,val,iRow,iCol){
		if(name=='invdate') {
			jQuery("#"+iRow+"_invdate","#celltbl").datepicker({dateFormat:"yy-mm-dd"});
		}
	},
	afterSaveCell : function(rowid,name,val,iRow,iCol) {
		if(name == 'amount') {
			var taxval = jQuery("#celltbl").getCell(rowid,iCol+1);
			jQuery("#celltbl").setRowData(rowid,{total:parseFloat(val)+parseFloat(taxval)});
		}
		if(name == 'tax') {
			var amtval = jQuery("#celltbl").getCell(rowid,iCol-1);
			jQuery("#celltbl").setRowData(rowid,{total:parseFloat(val)+parseFloat(amtval)});
		}
	}
}).navGrid('#pgwidth',{edit:false,add:false,del:false});
