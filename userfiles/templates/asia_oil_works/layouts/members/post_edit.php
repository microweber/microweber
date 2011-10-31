<? $edit = url_param('edit'); ?>
<? if(intval($edit) != 0): ?>
<? $form_values=get_post($edit); 
//p($form_values);
 $selected_categories = get_categories_for_post($edit);
 
 
 
 
?>
<? endif; ?>
<script>
 function handle_content_save(){
	 
	mw.content.save('#post_form', process_post_save);  
 }
 
 
 
  function process_post_save(msg){
	// alert(msg);
	  
	 if(msg.error){
	 $errors = 'Error' + '\n';
		
			var err = msg.error;
			
			$.each(err, function(key, value) {
			$errors = $errors+ '\n' + value;
			});
			
			alert($errors);
			
	
	 } else {
		 //window.location.reload()
		 
		 
	 }
	  if(msg.id){
		 new Notification('<strong>You</strong> just saved <strong>your changes</strong>', 'saved');
		  
		  window.location = "<? print site_url('members/view:posts'); ?>"
		  
	  }
	 
	 
 }
 

</script>

<h2>Please edit your job ad</h2>
<form onsubmit="handle_content_save(); return false;" id="post_form">
  <input name="id"     value="<? print $form_values['id'];  ?>" type="hidden"  />
  <div>
    <section>
      <label> Job ad title * </label>
      <div> Please enter the title of the job ad.<br />
        <br />
        <input name="content_title" class="required"   value="<? print $form_values['content_title'];  ?>" type="text"  />
      </div>
    </section>
    <section>
      <label for="post">Job ad text *</label>
      <div> Please enter the text of your job ad.<br />
        <br />
        <textarea id="post" name="content_body" class="required"><? print $form_values['content_body'];  ?></textarea>
      </div>
    </section>
    <section>
      <label for="post">Specialization </label>
      <div> Please enter more info about the knowledge requred by the candidates.<br />
        <br />
        <textarea id="post" name="custom_field_specialization" ><? print $form_values['custom_fields']['specialization'];  ?></textarea>
      </div>
    </section>
    <section>
      <label for="post">Job duties </label>
      <div> Please enter more info about the job duties.<br />
        <br />
        <textarea id="post" name="custom_field_duties" ><? print $form_values['custom_fields']['duties'];  ?></textarea>
      </div>
    </section>
    <section>
      <label>Location </label>
      <div> Please enter the location of the job, such as country and city.<br />
        <br />
        <input name="custom_field_location"    value="<? print $form_values['custom_fields']['location'];  ?>" type="text"  />
      </div>
    </section>
    <section>
      <label>Start date of job </label>
      <div> Please enter the date of which the canditates can start working.<br />
        <br />
        <input name="custom_field_start_date"    value="<? print $form_values['custom_fields']['start_date'];  ?>" type="text"  />
      </div>
    </section>
    <section>
      <label>Empoyment type </label>
      <div>
        <select name="custom_field_employment_type" >
          <option value="contract" <? if( $form_values['custom_fields']['employment_type'] == 'contract'):  ?> selected="selected"   <? endif; ?> >Contract</option>
          <option value="permanent" <? if( $form_values['custom_fields']['employment_type'] == 'permanent'):  ?> selected="selected"   <? endif; ?> >Permanent</option>
          <option value="duration" <? if( $form_values['custom_fields']['employment_type'] == 'duration'):  ?> selected="selected"   <? endif; ?> >Duration</option>
        </select>
      </div>
    </section>
    <section>
      <label>Salary range</label>
      <div> Please enter the sallary range of the job.<br />
        <br />
        <input name="custom_field_salary_range"    value="<? print $form_values['custom_fields']['salary_range'];  ?>" type="text"  />
      </div>
    </section>
    <section>
      <label>Check boxes</label>
      <div>
        <div class="no_ul_list_type">
          <? 
  
   $jobs_page = get_page('search_jobs');
  //  p( $selected_categories);
 //  p($jobs_page );
   ?>
          <input type='checkbox' name='categories[]' checked="checked"  class="hidden"  value='<? print $jobs_page['content_subtype_value'] ?>' />
          <? //print $jobs_page['content_subtype_value'] ?>
          <? $par = array();
		
		$par['link'] = "
		 <input type='checkbox' name='categories[]'  {active_code} id='{id}' value='{id}' />
          <label for='{id}'>{taxonomy_value} </label>
		 ";
		 
		 $par['for_page'] = $jobs_page['id'];
		 if(!empty($selected_categories)){
			 
			 		 $par['actve_ids'] = $selected_categories;
					 
					  $par['active_code'] = " checked='checked' ";

		 }
		 
		 
		 
		 
	//	 p($par);
		 
		
		?>
          <? category_tree($par); ?>
        </div>
        <div class="clear"></div>
      </div>
    </section>
  </div>
  <div class="clear"></div>
  <h2>Contact info</h2>
  <section>
    <label>Contact name</label>
    <div> Please enter name of the point of contact person.<br />
      <br />
      <input name="custom_field_name"    value="<? print $form_values['custom_fields']['name'];  ?>" type="text"  />
    </div>
  </section>
  <section>
    <label>Contact phone</label>
    <div> Please enter phone on which the candidates can contact you.<br />
      <br />
      <input name="custom_field_phone"    value="<? print $form_values['custom_fields']['phone'];  ?>" type="text"  />
    </div>
  </section>
  <section>
    <label>Contact email</label>
    <div> Please enter the email on which the candidates can contact you.<br />
      <br />
      <input name="custom_field_email"    value="<? print $form_values['custom_fields']['email'];  ?>" type="text"  />
    </div>
  </section>
  <div class="clear"></div>
  <h2>Job ad expiration</h2>
  <section>
    <label>Expiration date</label>
    <div>
      <? if(strval($form_values['expires_on']) == "" or strval($form_values['expires_on']) == "0000-00-00 00:00:00"): ?>
      Select the date you wish this job ad to expire.<br />
      <br />
      <select name="expires_on">
        <option value="<? print date("Y-m-d H:i:s", strtotime("+2 weeks")) ?>">After 2 weeks (<? print date("M, d", strtotime("+2 weeks")) ?>)</option>
        <option value="<? print date("Y-m-d H:i:s", strtotime("+4 weeks")) ?>">After 4 weeks (<? print date("M, d", strtotime("+4 weeks")) ?>)</option>
        <option value="<? print date("Y-m-d H:i:s", strtotime("+6 weeks")) ?>">After 6 weeks (<? print date("M, d", strtotime("+6 weeks")) ?>)</option>
      </select>
      <? else: ?>
      This job ad expires on <? print date("M, d", strtotime($form_values['expires_on'])) ?>.<br />
      <br />
      <input name="expires_on"    value="<? print $form_values['expires_on'];  ?>" type="hidden"  />
      <? endif; ?>
    </div>
  </section>
  <div class="clear"></div>
  <br />
  <br />
  <p>
    <input type="submit" class="button primary submit" value="Save" />
  </p>
</form>
<?  




//p($form_values); ?>
