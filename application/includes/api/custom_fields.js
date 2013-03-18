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

  edit: function($selector, $id, callback, event){

    if(!!event){
        var curr = event.target;

        if(mw.tools.hasClass(curr.className, 'mw-ui-btnclose')){
            return false;
        }

        var curr = $(curr).hasClass('mw-ui-btn') ? curr : mw.tools.firstParentWithClass(curr, 'mw-ui-btn');

        if(mw.tools.hasClass(curr.className, 'ui-sortable-helper')){
          return false;
        }
        if(mw.tools.hasClass(curr.className, 'mw-ui-btn-blue')){
            return false;
        }

        else if(mw.tools.hasClass(curr.className, 'mw-ui-btn-small')){
            $(curr.parentNode.querySelectorAll('a')).removeClass('mw-ui-btn-blue');
            $(curr).addClass('mw-ui-btn-blue')
        }
      }

      var data = {};
      data.settings = 'y';
      data.field_id = $id;

      var holder = mw.$("#custom-field-editor");

      $(".custom_fields_selector:visible").slideUp('fast');

      holder.show();


      if(!!event){
        if(curr != undefined){
              mw.$("#which_field").html(curr.textContent);
              }

        holder.find('.custom-field-edit-title').html('<span class="'+curr.querySelector('span').className+'"></span><strong>' + curr.textContent + '</strong>');
      }

      mw.$($selector).load(mw.settings.api_html+ 'make_custom_field',data , function(){
        mw.is.func(callback) ? callback.call(this) : '';
      });

     mw.$("#smart_field_opener").hide();

  },


   _create: function($selector, $type, $copy, $for_table, $for_id, callback, event){
      var copy_str = '';
      if($copy !== undefined && $copy !== false){
        var copy_str = '/copy_from:'+ $copy;
      }
        mw.$($selector).load(mw.settings.api_html+'make_custom_field/settings:y/basic:y/for_module_id:'+ $for_id + '/for:'+ $for_table +'/custom_field_type:'+$type + copy_str , function(){

        mw.is.func(callback) ? callback.call($type) : '';
      });
  },
  create:function(obj){
    return mw.custom_fields._create(obj.selector, obj.type, obj.copy, obj.table, obj.id, obj.onCreate, obj.event);
  },
  copy_field_by_id: function($copy, $for_table, $for_id, callback){
      var data = {};
      data.copy_from =$copy;
      data.save_on_copy =1;
      data.to_table =$for_table;
      data.to_table_id =$for_id;


      $.post(mw.settings.api_html+'make_custom_field' , data , function(){
		  mw.reload_module('custom_fields/list');
		  mw.is.func(callback) ? callback.call($type) : '';
      });
  },

  sort:function(group){
    var group = mwd.getElementById(group);

    if(group.querySelectorAll('.mw-custom-field-form-controls').length>0){
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
              __save();
            }
        });
    }



  },
  autoSaveOnWriting:function(el, id){  return false;
     mw.on.stopWriting(el, function(){
         mw.custom_fields.save(id, function(){
            if(typeof __sort_fields === 'function'){
                 __sort_fields();
               }
          });
     });
  }
}






mw.custom_fields.serialize = function(id){
      var el = mw.$(id);
      fields = "input[type='text'], input[type='email'], input[type='number'], input[type='password'], input[type='hidden'], textarea, select, input[type='checkbox']:checked, input[type='radio']:checked";
      var data = {};
      data.options = {};
      $(fields, el).not(':disabled').each(function(){
          var el = this, _el = $(el);
          var val = _el.val();
          var name = el.name;
          if(name.contains("[")){
              if(name.contains('[]')){
                var _name = name.replace(/[\[\]']+/g, '');
                if(name.indexOf('option')==0){
                  try{data.options.push(val)}
                  catch(e){data.options = [val]}
                }
                else{
                  try{data[_name].push(val)}
                  catch(e){data[_name] = [val]}
                }
              }
              else{
                if(name.indexOf('option')==0){
                  var name = name.slice(name.indexOf("[")+1, name.indexOf("]"));
                  try{data.options[name].push(val)}
                  catch(e){data.options[name] = [val]}
                }
                else{
                  var arr_name =  name.slice(0, name.indexOf("["));
                  var key = name.slice(name.indexOf("[")+1, name.indexOf("]"));
                  if(typeof data[arr_name] == 'object'){
                    try{data[arr_name][key].push(val)}
                     catch(e){data[arr_name][key] = [val]}
                  }
                  else{
                    data[arr_name] = {}
                    data[arr_name][key] = [val]
                  }
                }
              }
          }
          else{
             data[name] = val;
          }
      });
      if(mw.tools.isEmptyObject(data.options)){
        data.options = '';
      }
      mw.log(data)
      return data;
}


mw.custom_fields.save = function(id, callback){
    var obj = mw.custom_fields.serialize(id);
    $.post(mw.settings.api_url+'save_custom_field', obj, function(data) {
         var $cfadm_reload = false;
         if(obj.cf_id === undefined){
      //      mw.reload_module('.edit [data-parent-module="custom_fields"]');
         }
         mw.$(".mw-live-edit [data-type='custom_fields']").each(function(){
         if(!mw.tools.hasParentsWithClass(this, 'mw_modal') && !mw.tools.hasParentsWithClass(this, 'is_admin')){
               //mw.reload_module(this);
           } else {
			  var $cfadm_reload = true;
		   }
        });

	  mw.requestToReload("custom_fields/list");


    	mw.reload_module('custom_fields/list', function(){
         //   if(!!callback) callback.call(data);
           // $(window).trigger('customFieldSaved', [id, data]);
    	});
    });
}

mw.custom_fields.del = function(id, toremove){
    var q = "Are you sure you want to delete this?";
    mw.tools.confirm(q, function(){
      var obj = {
        id:id
      }
      $.post(mw.settings.api_url+"remove_field",  obj, function(data){
         mw.reload_module('custom_fields/list', function(){
            if(typeof __sort_fields === 'function'){
                 __sort_fields();
               }
            if(!!toremove){
              $(toremove).remove();
            }
            mw.$("#custom-field-editor").removeClass('mw-custom-field-created').hide();
            $(window).trigger('customFieldSaved', id);

         });
      });
    });

}




__save = function(){
             mw.custom_fields.save(__save__global_id, function(){
               if(mw.$("#custom-field-editor").hasClass('mw-custom-field-created')){

                    mw.custom_fields.edit('.mw-admin-custom-field-edit-item', this, function(){
                        __sort_fields();
                    });
                    mw.$("#custom-field-editor").removeClass('mw-custom-field-created')
               }
               else{
                  __sort_fields();
               }
             });
         }




  $(document).ready(function(){
    $(window).bind('customFieldSaved', function(a,b,c){
        $("a#custom-field-"+c).addClass('mw-ui-btn-blue');
    });
  });
























