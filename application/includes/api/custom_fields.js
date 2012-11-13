mw.custom_fields = {
  add:function(el){
    var parent = $(mw.tools.firstParentWithClass(el, 'mw-custom-field-form-controls'));

    var clone = parent.clone(true);
    parent.after(clone);
    clone.find("input").val("");

  },
  remove:function(el){
    var q = "Are you sure you want to remove this field?";

    //mw.tools.confirm(q, function(){
        $(mw.tools.firstParentWithClass(el, 'mw-custom-field-form-controls')).remove();

    //});
  },
  sort:function(group){
    var group = mwd.getElementById(group);
    $(group).sortable({
        handle:'.iMove',
        containment: "parent",
        axis:'y',
        items:".mw-custom-field-form-controls"
    });
  },
  refreshSort:function(){

  }
}