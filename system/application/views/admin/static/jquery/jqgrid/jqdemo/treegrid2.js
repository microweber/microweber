jQuery("#treegrid2").jqGrid({
    treeGrid: true,
	treeGridModel : 'adjacency',
	ExpandColumn : 'name',
   	url: 'server.php?q=tree3',
	datatype: "xml",
	mtype: "POST",
   	colNames:["id","Account","Acc Num", "Debit", "Credit","Balance"],
   	colModel:[
   		{name:'id',index:'id', width:1,hidden:true,key:true},
   		{name:'name',index:'name', width:180},
   		{name:'num',index:'acc_num', width:80, align:"center"},
   		{name:'debit',index:'debit', width:80, align:"right"},		
   		{name:'credit',index:'credit', width:80,align:"right"},		
   		{name:'balance',index:'balance', width:80,align:"right"}		
   	],
	height:'auto',
	pager : jQuery("#ptreegrid2"),
   	imgpath: gridimgpath,
	caption: "Treegrid example"
});
