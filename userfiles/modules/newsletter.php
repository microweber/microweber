
<style type="text/css">


.mw-newsletter-default{
    margin: 12px 0;
    /*max-width:316px;*/
    border: 1px solid #D7D7D7;
    border-top-color: #C5C5C5;
    background: #EEEEEE;
    font-family: Verdana, sans-serif;
    border-radius:12px;
}

.mw-newsletter-default input{
  outline: none;
  box-shadow:none;
}


.mw-newsletter-content{
  padding: 12px;
  overflow: hidden;
  zoom:1;
}

.mw-newsletter-default h3{
  font-size:13px;
  line-height: 16px;
  font-weight: bold;
  padding-bottom: 5px;
}

.mw-newsletter-default p{
  font-size: 11px;
  line-height: 12px;
  padding-bottom: 10px;
  color: #5D5D5D;
}
.mw-newsletter-default .mw-field{
  font-size: 11px;
  color: #1B74AD;
  padding: 0 16px;
  background: white;
  box-shadow:inset 0px 1px 3px #ADADAD;
  border-radius:3px;
  margin-bottom: 4px;
  position: relative;
}

.mw-newsletter-default input[type='text']{
  background: none;
  padding-top: 15px;
  padding-bottom: 15px;
  width: 100%;
  border: none;
  color: #1B74AD;
  position: relative;
  z-index: 1;
  box-sahdow:none;
}

.mw-newsletter-default label{
    position: absolute;
    z-index: 0;
    top: 13px;
    left: 17px;
    cursor: text;
    color: #1B74AD;
}

.mw-newsletter-default label.mw-focused{
  opacity:.5;
}

.mw-newsletter-default input[type='submit']{
  cursor: pointer;
  padding: 10px 20px;
  margin-top: 8px;
  font-size: 12px;
  color:#616161;
  text-transform: uppercase;
  border-style: solid;
  border-width: 1px;
  border-top-color: #e6e6e6;
  border-left-color: #c6c6c6;
  border-right-color: #c6c6c6;
  border-bottom-color: #b2b2b2;
  background: url(<? print INCLUDES_URL ?>img/grad.php?top=ffffff&bot=e9e9e9);
  border-radius:3px;
}
.mw-newsletter-default input[type='submit']:hover,
.mw-newsletter-default input[type='submit']:focus{
  background: url(<? print INCLUDES_URL ?>img/grad.php?top=e9e9e9&bot=ffffff);
}
.mw-newsletter-default input[type='submit']:active{
  box-shadow:inset 0 0 4px #5D5D5D;
}

</style>

<script type="text/javascript">

/*
$(".mw-newsletter-default input[type='text']").focus(function(){
  var val = $(this).val();
  var label = this.parentNode.getElementsByTagName('label')[0];
  if(val==""){
    label.className = 'mw-focused';
  }
});
$(".mw-newsletter-default input[type='text']").blur(function(){
  var val = $(this).val();
  var label = this.parentNode.getElementsByTagName('label')[0];
  if(val==""){
    label.className = '';
  }
});

$(".mw-newsletter-default input[type='text']").keydown(function(){
  var val = $(this).val();
  var label = this.parentNode.getElementsByTagName('label')[0];
  if(val==""){
    label.className = 'semi_hidden';
  }
});
*/

</script>


<div class="mw-reset mw-newsletter mw-newsletter-default">
  <div class="mw-newsletter-content">
  
  <? //  d($params);?>
  
<div class="edit" field="form_title"  rel="module" data-option_group="<? print $params['id'] ?>" data-module="<? print $params['type'] ?>">
 <h3>Subscribe for our Newsletter</h3>
</div>
  
<div class="edit" field="form_sub_title"  rel="module" data-option_group="<? print $params['id'] ?>" data-module="<? print $params['type'] ?>">
 <p>To subscribe for out newsletter please fill the form and click subscribe button!</p>
</div>
<div>
<p>NOT editable     </p>
<p>NOT editable     </p>
<p>NOT editable     </p>
<p>NOT editable     </p>
<p>NOT editable     </p>
<p>NOT editable     </p>
</div>
  
      
     
      <form method="post" action="#">
          <div class="mw-field">
            <label for="mw-newsletter-name"><?php _e("Your Name"); ?></label>
            <input tabindex="1" autocomplete="off" type="text" name="name" id="mw-newsletter-name" value="" />
          </div>
          <div class="mw-field">
             <label for="mw-newsletter-email"><?php _e("Your E-mail"); ?></label>
             <input tabindex="2" autocomplete="off" type="text" name="email" id="mw-newsletter-email" value="" />
          </div>
          <input tabindex="3" class="right" type="submit" value="<?php _e("Subscribe me Now"); ?>" />
      </form>
  </div>
</div>


