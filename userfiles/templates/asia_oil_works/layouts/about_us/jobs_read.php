<?   //p($post); ?>
<? //$cf_user = get_custom_fields_for_user($post['edited_by']);

//p( $cf_user);
?>
<? 

//p($post['created_by']);
$author = get_user($post['edited_by']); 

// p( $form_values);
?>
<script>

function process_job_apply(){
	
	$(".post_cv_form_fade_out").fadeOut();
		$("#post_cv_form_ok").fadeIn();
	
	
}


</script>

<div class="">
  <center class="pad2 ishr">
    <? if($author['custom_fields']['picture']): ?>
    <img src="<? print $author['custom_fields']['picture']; ?>" alt="" />
    <? endif; ?>
  </center>
  <div class="pad2"> <span class="jobLabel"> </span>
    <div class="jobinfo">
      <h4>
        <?  print $post['content_title'] ?>
      </h4>
      <br />
      <br />
      <div class="richtext">
        <?  print $post['content_body'] ?>
      </div>
      <br />
    </div>
  </div>
  <div class="c ishr">&nbsp;</div>
  <div class="pad2">
    <ul class="jobinfo_list">
      <li>Employment type: <strong><? print ucfirst($post['custom_fields']['employment_type']); ?> </strong></li>
      <li>Salary range: <strong><? print $post['custom_fields']['salary_range'];  ?></strong></li>
      <li>Job duties: <strong><? print $post['custom_fields']['duties'];  ?></strong></li>
      <li>Specialization: <strong><? print $post['custom_fields']['specialization'];  ?></strong></li>
      <li>Job published on: <strong>
        <?  print $post['created_on'] ?>
        </strong></li>
    </ul>
  </div>
  <div class="c ishr">&nbsp;</div>
  <div class="pad">
    <div id="post_cv_form_ok" style="display:none">
      <h2>Thank you for your application</h2>
      <p>The company will review your application and contact you</p>
    </div>
    <!-- <a href="#" class="btn btn_blue"><span>Apply for this job</span></a>-->
    <div id="application" class="post_cv_form_fade_out">
      <form method="post" action="#" id="post_cv_form" class="pad" >
        <h2>Apply for this job (your CV will be attached automatically)</h2>
        <section>
          <input type="hidden" name="to_table" value="<? print CI::model('core')->securityEncryptString('table_content'); ?>">
          <input type="hidden" name="to_table_id" value="<? print CI::model('core')->securityEncryptString($post['id']); ?>">
          <? if($display): ?>
          <input type="hidden" name="display" value="<?  print $display; ?>">
          <? endif; ?>
          <input type="hidden" name="update_element" value="#<? print $comments_update_element; ?>">
          <input type="hidden" name="hide_element" value="#<? print $form_id; ?>">
          <input type="hidden" name="show_element" value="#<? print $form_id; ?>_success">
          <span>Enter your note for the company </span>
          <textarea name="comment_body" class="required">Hello, I want to apply for this job.</textarea>
          <a class="submit mw_btn_x" href="javascript:mw.comments.post('#post_cv_form', process_job_apply);"><span>Apply for this job</span></a> </section>
      </form>
    </div>
  </div>
  <div class="c ishr">&nbsp;</div>
  <div class="pad2">
    <ul class="jobinfo_list">
      <li>Company Info: <strong>
        <?  print user_name($author['id']); ?>
        </strong></li>
      <li>Contact Person: <strong><? print $post['custom_fields']['name'];  ?></strong></li>
      <li>Contact Phone: <strong><? print $post['custom_fields']['phone'];  ?></strong></li>
      <li>Contact Email: <strong><? print $post['custom_fields']['email'];  ?></strong></li>
    </ul>
  </div>
  <div class="c" style="padding-bottom: 15px;">&nbsp;</div>
  <h2>Candidates for this job</h2>
  <? comments_list($content_id = $post['id'], $display = 'applied_candidates.php', $for = 'post', $display_params = array()); ?>
</div>
