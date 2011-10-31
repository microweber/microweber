<link href="<? print $config['url_to_module'] ?>skins/css/uni-form.css" media="screen" rel="stylesheet"/>
    <link href="<? print $config['url_to_module'] ?>skins/css/default.uni-form.css" id="formStyle"  rel="stylesheet"/>
 <!--     <link href="<? print $config['url_to_module'] ?>skins/demos/css/demo.css" rel="stylesheet"/>-->
    
    <script type="text/javascript" src="<? print $config['url_to_module'] ?>skins/js/uni-form.jquery.js"></script>
    <script type="text/javascript">
      $(function(){
        
        // init Uni-Form
        $('form.uniForm', '.edit').uniform();
        
         
         
      });
    </script>
    
    

<script type="text/javascript">
                $(document).ready(function(){
                  $("#contact_form<? print  $params['module_id'] ?>").validate(function(){

                    var contacts = {
                        name:$("input[name='from_name']").val(),
                        email:$("input[name='from_email']").val(),
                        phone:$("input[name='from_phone']").val(),
                        message:$("textarea[name='message']").val()
                    }
                    $.post("<? print $config['url_to_module'] ?>contact_form_sender.php", contacts, function(){
                        Modal.box("<h2 style='padding:20px;text-align:center'>Your message has been Sent</h2>", 400, 100);
                        Modal.overlay();
                    });


                  })
                });
            </script>
<div class="contact_fasdasdorm"> 




 
  <?php $form_btn_text  = option_get('form_btn_text', $params['module_id']);
  
  if(trim($form_btn_text) == ''){
	$form_btn_text = 'Send';  
	  
  }
  ?>
  
  <form method="post" action="" id="contact_form<? print  $params['module_id'] ?>" class="uniForm"> 
  
  <div class="uniForm_header">
        <h2><?php print option_get('form_title', $params['module_id']) ?></h2>
        <p><?php print option_get('form_description', $params['module_id']) ?></p>
      </div>
      
      
      
      
          
    <input  type="hidden"   name="to_emails" value="<? print base64_encode($params['email']); ?>"  />
    
    
      
         
      <fieldset class="inlineLabels">
 
        
         
        
        <div class="ctrlHolder">
          <label for=""><em>*</em> Your name</label>
           <input class="required" type="text" default="Name" name="from_name" data-default-value="Your name"   />
           
                 <p class="formHint">Your name</p>
        </div>
        
        
           <div class="ctrlHolder">
          <label for=""><em>*</em> Your Email</label>
           <input class="required" type="text" default="Email" name="from_email" data-default-value="Your email"   />
           
                 <p class="formHint">Your Email</p>
        </div>
        
         
         
         
          <div class="ctrlHolder">
       <label for=""><em>*</em> Your message</label>
          <textarea name="message" id="" rows="25" cols="25"></textarea>
          <p class="formHint">Your message here.</p>
        </div>
        
        
        
        
        <?
		$cf_post_config = array();
				$cf_post_config['post_id'] = $params['module_id'];
			  $cf_post_config =  CI::model('core')->getCustomFieldsConfig($cf_post_config);
	//	p( $cf_post_config );
		?>
        
        <? foreach($cf_post_config as $k => $cf): ?>
        
        
          <div class="ctrlHolder">
       <label for=""><? print $cf['name'] ?></label>
           <microweber module="content/custom_field"  name="custom_field_<? print $cf['name'] ?>" cf_id="<? print $cf['id'] ?>" module_id="<? print $params['module_id'].'_cf_'.$cf['id'] ?>">

          <p class="formHint"><? print $cf['help'] ?></p>
        </div>
        
        
        
        
        <? endforeach; ?>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
      </fieldset>
      
      
      <input type="hidden" value=""  class="x" />
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
  
    <a href="#" class="submit form_submit_button"><? print $form_btn_text; ?></a>
  </form>
</div>
