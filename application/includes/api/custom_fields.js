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
        handle:'.custom-fields-handle-field',
        placeholder:'custom-fields-placeholder',
        //containment: "parent",
        axis:'y',
        items:".mw-custom-field-form-controls",
        start:function(a,ui){
            $(ui.placeholder).height($(ui.item).outerHeight())
        },
        scroll:false,
        update:function(){
          mw.custom_fields.save(this.parentNode.id);
        }
    });
  },
  autoSaveOnWriting:function(el, id){
     mw.on.stopWriting(el, function(){
         mw.custom_fields.save(id);
     });
  }
}