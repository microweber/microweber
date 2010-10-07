$("#cnvxml").click(function(){
	$("#xmlist1").jqGridImport({impurl:"testxml.xml"});
	$(this).hide();
});