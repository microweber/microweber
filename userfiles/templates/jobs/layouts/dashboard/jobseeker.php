<?  if($user_id == false){
	$user_id = user_id();
}
?>
<? $form_values = get_user($user_id);
//p($form_values);
 ?>

<div class="row-fluid">
  <div class="span6">
    <div class="role1">1. <span class="role">Upload your CV </span> </div>
    <mw module="content/custom_field"	name="cv" user_id="<? print user_id(); ?>" type="file" edit="true" autosave="true" />
  </div>
  <div class="span4 offset2">
    <div class="pwd_changed2">Password has been changed </div>
  </div>
</div>
<div class="row-fluid">
<div class="span6">
  <div class="role1">1. <span class="role">Upload your Picure </span> </div>
</div>
<div class="span4 offset2">
  <div class="cv_pic"><img src="<? print TEMPLATE_URL ?>images/job_seaker1.jpg" alt="jobseaker"></div>
  <div class="delete_pic_but">
    <input type="image" src="<? print TEMPLATE_URL ?>images/delete_pic_but.jpg">
  </div>
</div>
<form action="#" method="post" enctype="multipart/form-data" id="edit-profile-form" class="form validate edit-profile-form">
  <input type="hidden" name="id" value="<? print $form_values['id']; ?>" />
  <!-- <span id="first-fields-error">You must fill out your: username, first name, last name and email.</span>-->
  <div class="role1">
    <div class="control-group">
      <label class="control-label control-label-big">Undergraduate and Year graduated</label>
      <div class="controls">
        <label> School Name:
          <input type="text" name="custom_field_school_name"  value="<? print $form_values['custom_fields']['school_name'] ?>"  >
        </label>
      </div>
      <div class="controls">
        <label> Year Graduated:
          <input type="text" name="custom_field_school_year"  value="<? print $form_values['custom_fields']['school_year'] ?>"  >
        </label>
      </div>
    </div>
    
    
    
    
    
    
    
    
    
    
    
    <div class="control-group">
      <label class="control-label control-label-big">Undergraduate International and Year graduated</label>
      <div class="controls">
        <label> School Name:
          <input type="text" name="custom_field_school_name_international"  value="<? print $form_values['custom_fields']['school_name_international'] ?>"  >
        </label>
      </div>
      <div class="controls">
        <label> Year Graduated:
          <input type="text" name="custom_field_school_year_international"  value="<? print $form_values['custom_fields']['school_year_international'] ?>"  >
        </label>
      </div>
    </div>
    
    
    
        <div class="control-group">
      <label class="control-label control-label-big">Medical School and Year Graduated</label>
      <div class="controls">
        <label> School Name:
          <input type="text" name="custom_field_school_name_medical"  value="<? print $form_values['custom_fields']['school_name_medical'] ?>"  >
        </label>
      </div>
      <div class="controls">
        <label> Year Graduated:
          <input type="text" name="custom_field_school_year_medical"  value="<? print $form_values['custom_fields']['school_year_medical'] ?>"  >
        </label>
      </div>
    </div>
    
    
    
        
        <div class="control-group">
      <label class="control-label control-label-big">Residency and Year graduate</label>
      <div class="controls">
        <label> Hospital or Facility Name:
          <input type="text" name="custom_field_residency"  value="<? print $form_values['custom_fields']['residency'] ?>"  >
        </label>
      </div>
      <div class="controls">
        <label> Resident from:
          <input type="text" name="custom_field_residency_from"  value="<? print $form_values['custom_fields']['residency_from'] ?>"  >
        </label>
      </div>
    </div>
    
    
    
    
            <div class="control-group">
      <label class="control-label control-label-big">Certifications and Year graduated	 </label>
      <div class="controls">
        <label> Hospital or Facility Name:
          <input type="text" name="custom_field_certifications"  value="<? print $form_values['custom_fields']['certifications'] ?>"  >
        </label>
      </div>
      <div class="controls">
        <label> Year certificatified:
          <input type="text" name="custom_field_certifications_year"  value="<? print $form_values['custom_fields']['certifications_year'] ?>"  >
        </label>
      </div>
    </div>
    
    
    
              <div class="control-group">
      <label class="control-label control-label-big">Fellowship and Year graduated	</label>
      <div class="controls">
        <label> Hospital or Facility Name:
          <input type="text" name="custom_field_fellowship"  value="<? print $form_values['custom_fields']['fellowship '] ?>"  >
        </label>
      </div>
      <div class="controls">
        <label> Year certificatified:
          <input type="text" name="custom_field_fellowship_year"  value="<? print $form_values['custom_fields']['fellowship_year'] ?>"  >
        </label>
      </div>
    </div>
    
    
    
    
    
    
             <div class="control-group">
      <label class="control-label control-label-big">NPI</label>
      <div class="controls">
        <label> NPI: 
          <input type="text" name="custom_field_npi"  value="<? print $form_values['custom_fields']['npi '] ?>"  >
        </label>
      </div>
      
    </div>
    
    
    
    
    
      
             <div class="control-group">
      <label class="control-label control-label-big">Contact info</label>
      <div class="controls">
        <label> Address: 
          <input type="text" name="custom_field_address"  value="<? print $form_values['custom_fields']['address2'] ?>"  >
        </label>
      </div>
      
      
      <div class="controls">
        <label> Address 2: 
          <input type="text" name="custom_field_address2"  value="<? print $form_values['custom_fields']['address2'] ?>"  >
        </label>
      </div>
      
      
         <div class="controls">
        <label> City: 
          <input type="text" name="custom_field_city"  value="<? print $form_values['custom_fields']['city'] ?>"  >
        </label>
      </div>
      
      
      
        <div class="controls">
        <label> State: 
          <input type="text" name="custom_field_state"  value="<? print $form_values['custom_fields']['state'] ?>"  >
        </label>
      </div>
      
       
      
           
        <div class="controls">
        <label> Zip: 
          <input type="text" name="custom_field_zip"  value="<? print $form_values['custom_fields']['zip'] ?>"  >
        </label>
      </div>
      
        
       
       
      
       
          
        <div class="controls">
        <label> Phone: 
          <input type="text" name="custom_field_phone"  value="<? print $form_values['custom_fields']['phone'] ?>"  >
        </label>
      </div>
      
      
      
                
        <div class="controls">
        <label> Phone 2: 
          <input type="text" name="custom_field_phone2"  value="<? print $form_values['custom_fields']['phone2'] ?>"  >
        </label>
      </div>
      
      
      
      
         <div class="controls">
        <label>email: 
          <input type="text" name="email"  value="<? print $form_values['email'] ?>"  >
        </label>
      </div>
      
      
      
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
  
  </div>
  <span class="field"> <a href="javascript:if($('#edit-profile-form')){save_user()};" ><img src="<? print TEMPLATE_URL ?>images/pwd_change_save_but_45.jpg" /></a> </span>
</form>
