<script type="text/javascript">

  $(document).ready(function(){
     $("#the_contact_form").submit({
      

       
contact = $(this).serialize();
       $.post("<? print TEMPLATE_URL ?>mailsender.php", contact, function(){
$("#the_contact_form").html("<br><br><h2>Your request has been sent</h2><br><br><br>");

 

       });

        return false; 
       } 
  });

  </script>

<form  id="the_contact_form" class="form-horizontal well">
  <h2>Mock Test Entry Form</h2>
  <fieldset>
    <div class="control-group">
      <label for="name_of_child" class="control-label">Name of Child</label>
      <div class="controls">
        <input type="text"  id="name_of_child"  name="name_of_child" class="input-xlarge">
        <!--                  <p class="help-block">In addition to freeform text, any HTML5 text-based input appears like so.</p>
--> </div>
    </div>
    <? if( $large_form == true): ?>
    <div class="control-group">
      <label for="input01" class="control-label">Age of Child</label>
      <div class="controls">
        <input type="text"   name="name_of_child" class="input-xlarge">
        <!--                  <p class="help-block">In addition to freeform text, any HTML5 text-based input appears like so.</p>
--> </div>
    </div>
    <div class="control-group">
      <label for="number_of_exams" class="control-label">Number of exams to attend</label>
      <div class="controls">
        <? $ex = url_param('number_of_exams'); ?>
        <select id="number_of_exams" name="number_of_exams">
          <option <? if($ex == "2_exams"): ?>  selected <? endif; ?> value="2_exams">2 exams</option>
          <option <? if($ex == "3_exams"): ?>  selected <? endif; ?> value="3_exams">3 exams</option>
          <option <? if($ex == "4_exams"): ?>  selected <? endif; ?> value="4_exams">4 exams</option>
          <option <? if($ex == "5_and_more_exams"): ?>  selected <? endif; ?> value="5_and_more_exams">5+ exams</option>
        </select>
      </div>
    </div>
    <div class="control-group">
      <label for="exam_dates" class="control-label">Please indicate the date which exams are required</label>
      <div class="controls">
        <label class="checkbox">
          <input type="checkbox" value="30 May 2012" name="exam_dates">
          30 May 2012 </label>
        <label class="checkbox">
          <input type="checkbox" value="07 July 2012" name="exam_dates">
          07 July 2012 </label>
        <label class="checkbox">
          <input type="checkbox" value="14 July 2012" name="exam_dates">
          14 July 2012 </label>
        <label class="checkbox">
          <input type="checkbox" value="21 July 2012" name="exam_dates">
          21 July 2012 </label>
        <label class="checkbox">
          <input type="checkbox" value="28 July 2012" name="exam_dates">
          28 July 2012 </label>
        <label class="checkbox">
          <input type="checkbox" value="04 August 2012" name="exam_dates">
          04 August 2012 </label>
        <label class="checkbox">
          <input type="checkbox" value="11 August 2012" name="exam_dates">
          11 August 2012 </label>
        <label class="checkbox">
          <input type="checkbox" value="18 August 2012" name="exam_dates">
          18 August 2012 </label>
        <label class="checkbox">
          <input type="checkbox" value="25 August 2012" name="exam_dates">
          25 August 2012 </label>
        <label class="checkbox">
          <input type="checkbox" value="01 September 2012" name="exam_dates">
          01 September 2012 </label>
        <p class="help-block"><strong>Note:</strong> Choose the dates you want to attend to exams.</p>
      </div>
    </div>
    <div class="control-group">
      <label for="optionsCheckboxList" class="control-label">Apply for the scholarship?</label>
      <div class="controls">
        <label class="checkbox">
          <input type="checkbox" value="yes" name="apply_for_the_scholarship">
          Do you want your child to apply for the scholarship? </label>
        <p class="help-block">Only applicable if booking all 4 exams for all 10 sessions plus extra exams to be sat at a cost of £50</p>
      </div>
    </div>
    <? endif; ?>
    <div class="control-group">
      <label for="name" class="control-label">Your name</label>
      <div class="controls">
        <input type="text"   name="name" id="name" class="input-xlarge">
        <!--                  <p class="help-block">In addition to freeform text, any HTML5 text-based input appears like so.</p>
--> </div>
    </div>
    <div class="control-group">
      <label for="email" class="control-label">Your email</label>
      <div class="controls">
        <input type="text"   name="email"  id="email" class="input-xlarge">
        <!--                  <p class="help-block">In addition to freeform text, any HTML5 text-based input appears like so.</p>
--> </div>
    </div>
    <div class="control-group">
      <label for="phone" class="control-label">Your phone</label>
      <div class="controls">
        <input type="text"   name="phone" id="phone" class="input-xlarge">
        <!--                  <p class="help-block">In addition to freeform text, any HTML5 text-based input appears like so.</p>
--> </div>
    </div>
    <p class="landing-actions"> <a href="javascript:;" class="btn btn-large btn-inverse">Apply Now!</a> <a href="<? print page_link(4841) ?>"  class="btn btn-large">See pricing</a> </p>
  </fieldset>
</form>
