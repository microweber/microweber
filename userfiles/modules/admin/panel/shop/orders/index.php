<?php 

$show = 'list';

if(url_param('show')){
$show = url_param('show');	
}


?>
<h1 class="mw-admin-main-section-title"><span>Orders</span></h1>
<div class="mw-admin-normal-spacer"></div>
<div class="mw-ui-row mw-admin-main-section-inner-panel">
  <div class="mw-ui-col" style="width:33%;"> <h2 class="mw-admin-main-section-inner-panel-title">List of orders</h2> </div>
  <div class="mw-ui-col" style="width:33%;"> 
  
  <div class="mw-admin-main-section-inner-panel-nav">
  <a href="?show=list" <?php if($show == 'list'){ ?> class="active" <?php } ?>>Completed orders</a> 
  <a href="?show=abandoned" <?php if($show == 'abandoned'){ ?> class="active" <?php } ?>>Abandoned carts</a> 
  </div>
  
  </div>
  <div class="mw-ui-col" style="width:33%;">  
  
  
  <div class="pull-right relative">
                    <input type="text" id="mw-search-field-orders" class="mw-ui-searchfield pull-right" placeholder="Search for posts" value="" onkeyup="mw.on.stopWriting(this,function(){mw.url.windowHashParam('search',this.value)})" />
</div>
  
  
  </div>
</div>
<div class="mw-admin-normal-spacer"></div>


<?php if($show == 'list'){ ?>

<module type="admin/panel/shop/orders/list" id="mw-admin-manage-orders-list" />

<?php } ?>
<?php if($show == 'abandoned'){ ?>

<module type="admin/panel/shop/orders/list" order-type='carts' id="mw-admin-manage-orders-list" />

<?php } ?>





<?php only_admin_access(); ?>
<script>
    mw.require('forms.js', true);


   $(mwd).ready(function(){





   });


   mw.on.hashParam('orderstype', function(){
        mw.$("#cartsnav a").removeClass('active');
        mw.$("#cartsnav a[href='#orderstype="+this+"']").addClass('active');
        if(this == 'carts'){
    		mw.$('.mw-admin-order-sort-carts').show();
    		mw.$('.mw-admin-order-sort-completed').hide();
    	} else {
    		mw.$('.mw-admin-order-sort-carts').hide();
    		mw.$('.mw-admin-order-sort-completed').show();
    	}
        mw.$('#mw-admin-manage-orders-list').attr('order-type', this);
        mw.$('#mw-admin-manage-orders-list').removeAttr('keyword');
        mw.$('#mw-admin-manage-orders-list').removeAttr('order');
        mw.reload_module("#mw-admin-manage-orders-list", function(){

        });
   });


ordersSort = function(obj){
    var group = mw.tools.firstParentWithClass(obj.el, 'mw-table-sorting');

    var table = mwd.getElementById(obj.id);

	var parent_mod = mw.tools.firstParentWithClass(table, 'module');

    var others = group.querySelectorAll('.mw-ui-btn'), i=0, len = others.length;
    for( ; i<len; i++ ){
        var curr = others[i];
        if(curr !== obj.el){
           $(curr).removeClass('ASC DESC active');
        }
    }
    obj.el.attributes['data-state'] === undefined ? obj.el.setAttribute('data-state', 0) : '';
    var state = obj.el.attributes['data-state'].nodeValue;
    var tosend = {}
    tosend.type = obj.el.attributes['data-sort-type'].nodeValue;
    if(state === '0'){
        tosend.state = 'ASC';
        obj.el.className = 'mw-ui-btn mw-ui-btn-medium active ASC';
        obj.el.setAttribute('data-state', 'ASC');
    }
    else if(state==='ASC'){
        tosend.state = 'DESC';
        obj.el.className = 'mw-ui-btn mw-ui-btn-medium active DESC';
        obj.el.setAttribute('data-state', 'DESC');
    }
    else if(state==='DESC'){
         tosend.state = 'ASC';
         obj.el.className = 'mw-ui-btn mw-ui-btn-medium active ASC';
         obj.el.setAttribute('data-state', 'ASC');
    }
    else{
       tosend.state = 'ASC';
       obj.el.className = 'mw-ui-btn mw-ui-btn-medium active ASC';
       obj.el.setAttribute('data-state', 'ASC');
    }

	if(parent_mod !== undefined){
		 parent_mod.setAttribute('data-order', tosend.type +' '+ tosend.state);
	     mw.reload_module(parent_mod);
	}
  }

	 mw.on.hashParam('search', function(){


  var field = mwd.getElementById('mw-search-field-orders');
  $(field).addClass('loading')
	
  if(!field.focused){ field.value = this; }

  if(this  != ''){
    $('#mw-admin-manage-orders-list').attr('keyword',this);
	$(field).addClass('active')
  } else {
    $('#mw-admin-manage-orders-list').removeAttr('keyword' );
  }

  $('#mw-admin-manage-orders-list').removeAttr('export_to_excel');


 mw.reload_module('#mw-admin-manage-orders-list', function(){
    mw.$(field).removeClass('loading');
 });


});


</script>
 