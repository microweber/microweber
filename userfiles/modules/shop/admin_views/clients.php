

<script type="text/javascript">
function clientorderHandleParam(paramvalue){
    if(paramvalue!=false){
        mwd.getElementById('mw-clientorder').setAttribute('data-order-id', paramvalue);
        mw.load_module('shop/orders/client_inner','#mw-clientorder', function(){
            Rotator.go(1)
        });
    }
    else{
        mw.reload_module('#mw-clients-orders');
        Rotator.go(0);
    }
}

$(document).ready(function(){
  Rotator = mwd.getElementById('clients-rotator');
  mw.admin.simpleRotator(Rotator);
  var ord = mw.url.getHashParams(location.hash).clientorder;
  if(typeof ord != 'undefined'){
      clientorderHandleParam(ord)
  }

  mw.on.hashParam("clientorder", function(){
      clientorderHandleParam(this)
  });
});


</script>
<div class="admin-section-container"><div class="mw-simple-rotator">
    <div class="mw-simple-rotator-container" id="clients-rotator">
        <module id="mw-clients-orders" type="shop/orders/clients" />
        <div id="mw-clientorder"></div>
    </div>
</div></div>