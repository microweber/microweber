module("Github cases");

/**
 * Case 1
 * 
 * Prevent submit fails if the blur handler hasn't been called on data
 * 
 * @link https://github.com/LearningStation/uni-form/issues/issue/1
 */
test("Case 1 : Prevent submit fails for existing data", function() {

  $form = jQuery('#qunit-form');
  jQuery('#email', $form).val('invalid@example');
  $form.uniform({
      prevent_submit : true
  });

  $form.trigger('submit');
  
  equals(
    $form.hasClass('failedSubmit'),
    true,
    "Form has failedSubmit class after submit without blur on invalid data"
  );

});

/**
 * Case 2
 * 
 * Feature request for required selection on radio button
 * when form inits with no selection
 * 
 * @link https://github.com/LearningStation/uni-form/issues/issue/2
 */
test("Case 2 : Required validation for radio button", function() {
  var hasError = false;
  $form = jQuery('#qunit-form');
  $form.uniform({
    invalid_class           : 'invalid',
    error_class             : 'error',
    prevent_submit          : true,
    prevent_submit_callback : function($submit_form) {
      hasError = 
          $('input[name="color"]:radio:first', $submit_form)
              .parents('div.ctrlHolder')
              .hasClass('error');
      return false;
    }
  });
  $form.trigger('submit');
  
  equals(
    hasError,
    true,
    "Radio group has invalid class after submit"
  );
  
  $('input[name="color"]:radio:first', $form).attr('checked', true);
  $form.trigger('submit');
  
  equals(
    hasError,
    false,
    "Radio group validated after selection and second submit"
  );
  
});

/**
 * Case 3
 * 
 * data-default-value gets submitted
 * 
 * @link https://github.com/LearningStation/uni-form/issues/issue/3
 */
test("Case 3 : data-default-value gets submitted", function() {

  $form = jQuery('#qunit-form');
  $form.uniform();

  equals(
    $('input[name="email"]', $form).val(),
    "Your email address",
    "Value of email field is same as prompt"
  );
  
  $form.trigger('submit');

  equals(
    $('input[name="email"]', $form).val(),
    "",
    "Value of email field is empty after submit"
  );

});

/**
 * Case 4
 * 
 * Feature request for required selection on checkbox
 * 
 * @link https://github.com/LearningStation/uni-form/issues/issue/4
 */
test("Case 4 : Required validation for checkbox", function() {
  var hasError = false;
  $form = jQuery('#qunit-form');
  $form.uniform({
    invalid_class           : 'invalid',
    error_class             : 'error',
    prevent_submit          : true,
    prevent_submit_callback : function($submit_form) {
      hasError = 
          $('input[name="agreement"]:checkbox', $submit_form)
              .parents('div.ctrlHolder')
              .hasClass('error');
      return false;
    }
  });
  $form.trigger('submit');
  
  equals(
    hasError,
    true,
    "Checkbox has invalid class after submit"
  );
  
  $('input[name="agreement"]:checkbox', $form).attr('checked', true);
  $form.trigger('submit');
  
  equals(
    hasError,
    false,
    "Checkbox validated after selection and second submit"
  );
  
});
