jQuery("#treegrid2").jqGrid({
   	url: 'server.php?q=tree2',
	treedatatype: "xml",
	mtype: "POST",
   	colNames:["id","Account","Acc Num", "Debit", "Credit","Balance","Enabled"],
   	colModel:[
   		{name:'id',index:'id', width:1,hidden:true,key:true},
   		{name:'name1',index:'name', width:180},
   		{name:'num',index:'acc_num', width:80, align:"center"},
   		{name:'debit',index:'debit', width:80, align:"right"},		
   		{name:'credit',index:'credit', width:80,align:"right"},		
   		{name:'balance',index:'balance', width:80,align:"right"},
		{name:'enbl', index:'enbl', width: 60, align:'center', formatter:'checkbox', editoptions:{value:'1:0'}, formatoptions:{disabled:false}}
   	],
	height:'auto',
	pager : "#ptreegrid2",
    treeGrid: true,
	ExpandColumn : 'name1',
	caption: "Treegrid example"
});

var ci,rowid,ptr,td;
$('#treegrid2').contextMenu('myMenu1', {
      bindings: {
        'mchild': function(t) {
        	if(ptr && rowid && ci >=1) {
        		var gcn = $("#treegrid2").getFullTreeNode(ptr);
				$(gcn).each(function(i,v){
					$("td:eq("+ci+")",this).each(function(){
						if(!$("input[type='checkbox']",this).attr("checked")) {
							$(this).toggleClass("changed");
							$("input[type='checkbox']",this).attr("defaultChecked",true).attr("checked","checked");
						}
					});
				});
				ptr = rowid=ci=null;
        	}
        },
        'umchild': function(t) {
        	if(ptr && rowid && ci >=1) {
        		var gcn = $("#treegrid2").getFullTreeNode(ptr);
				$(gcn).each(function(){
					$("td:eq("+ci+")",this).each(function(){
						if($("input[type='checkbox']",this).attr("checked")) {
							$(this).toggleClass("changed");
							$("input[type='checkbox']",this).removeAttr("checked").attr("defaultChecked","");
						}
					});
				});
				ptr = rowid=ci=null;
        	}
        }
      },
      onContextMenu: function(e, menu) {
		td = e.target || e.srcElement;
		ptr = $(td).parents("tr.jqgrow")[0];
		ci = !$(td).is('td') ? $(td).parents("td:first")[0].cellIndex : td.cellIndex;
		if($.browser.msie) {
			ci = $.jgrid.getAbsoluteIndex(ptr,ci);
		}
		if( ci >=1 ) {
			rowid = ptr.id;
			$('#treegrid2').setSelection(rowid,false);
			return true;
		} else {
		//alert(ptr.id+" : "+ptr.rowIndex+" : "+ci);
			return false;
		}
	  }
});
$("#jqContextMenu").addClass("ui-widget ui-widget-content").css("font-size","12px");
	