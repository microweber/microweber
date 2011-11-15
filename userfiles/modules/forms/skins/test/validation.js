// http://docs.jquery.com/Qunit

/**
 * Short accessor to a input element
 * 
 * Note that IE can't do attr('type','text')
 *
 * @param string type  input type enumerated [checkbox, radio, text, password]
 * @param string value text value of input
 *
 * @return obj jQuery object
 */
var getInput = function(type, value) {
  return $('<input type="' + type + '" />').val(value);
}

/**
 * Test our validators
 *
 * Validators return:
 *  bool true for sucess
 *  string error message for failure
 * 
 * So, this function tests for true/string, and makes the output
 * in the unit test a little more clear
 *
 * @param mixed  a       result
 * @param bool   b       expected pass/fail
 * @param string message unit test message
 * 
 * return bool result of assertion
 */
var validationTest = function(a, b, message) {
  if (b === true) {
    return ok((a === b), message);
  } 
  return ok((typeof a == "string"), message);
}


module("Test fixtures");

test("Test data is correctly setup", function() {
  ok(jQuery().uniform, "Plugin script not loaded/registered");
  ok(jQuery('#qunit-form').length, "Sample form present");
});

var validators;
module("Validation unit tests", {
  setup: function() {
    validators = jQuery().uniform().validators;
  }
});

test("Required test", function() {
  var $input = getInput('text','');

  validationTest(
    validators.required($input.val('non empty text')),
    true,
    'Required value detected'
  );
  
  validationTest(
    validators.required($input.val('')),
    false,
    "Error message for empty input"
  );
  
});

test("Minlength test", function() {
  var $input = getInput('text','');
  $input
    .addClass('validateMinLength')
    .addClass('val-8');
  
  validationTest(
    validators.validateMinLength($input.val('non empty text')), // 14 char
    true,
    'Minimum length passed with 14 char input'
  );
  
  validationTest(
    validators.validateMinLength($input.val('Short')),
    false,
    "Error message set for short input"
  );

  validationTest(
    validators.validateMinLength($input.val('')),
    false,
    "Error message set for empty input"
  );
  
});


test("Maxlength test", function() {
  var $input = getInput('text','');
  $input
    .addClass('validateMaxLength')
    .addClass('val-8');
  
  validationTest(
    validators.validateMaxLength($input.val('non empty text')), // 14 char
    false,
    'maximum length error with 14 char input'
  );
  
  validationTest(
    validators.validateMaxLength($input.val('Short')),
    true,
    "Passed short input"
  );

  validationTest(
    validators.validateMaxLength($input.val('')),
    true,
    "Passed empty input"
  );
  
});

test("Min test", function() {
  var $input = getInput('text','');
  $input
    .addClass('validateMin')
    .addClass('val-8');
  
  validationTest(
    validators.validateMin($input.val('6')),
    false,
    'Value less than min'
  );

  validationTest(
    validators.validateMin($input.val('8')),
    true,
    'Value equal to min'
  );

  validationTest(
    validators.validateMin($input.val('10')),
    true,
    "Value greater than min"
  );
  
});

test("Max test", function() {
  var $input = getInput('text','');
  $input
    .addClass('validateMax')
    .addClass('val-8');
  
  validationTest(
    validators.validateMax($input.val('6')),
    true,
    'Value less than max'
  );

  validationTest(
    validators.validateMax($input.val('8')),
    true,
    'Value equal to max'
  );

  validationTest(
    validators.validateMax($input.val('10')),
    false,
    "Value greater than max"
  );
  
});

test("SameAs test", function() {
  var $inputA = getInput('text','Same Value').attr('name','inputA');
  var $inputB = getInput('text','Same Value').attr('name','inputB').addClass('validateSameAs').addClass('inputA');
  
  var $form = $('#qunit-fixture').append($inputA).append($inputB);
  
  validationTest(
    validators.validateSameAs($inputB),
    true,
    'Same Values'
  );
  
  $inputA.val('Different Value');
  validationTest(
    validators.validateSameAs($inputB),
    false,
    "Different values"
  );
  
});

test("Email address test", function() {
  var $input = getInput('text','');
  
  var addresses = {
    'spam@example.com'        : true,
    'spam@example.co.uk'      : true,
    'spam.spam@example.co.uk' : true,
    'spam@.com'               : false,
    'spam@com'                : false,
    'spam.com'                : false
  };
  
  for (address in addresses) {
    var explanation = (addresses[address]) ? ' passes' : ' fails'
    validationTest(
      validators.validateEmail($input.val(address)),
      addresses[address],
      address + explanation
    );
  }
  
});

test("Url test", function() {
  var $input = getInput('text','');
  
  var addresses = {
    'http://www.example.com/test/url'  : true,
    'http://www.example.com/test.html' : true,
    'ftp://www.example.com'            : true,
    'htp://www.example.com'            : false,
    'http://www example.com'           : false
  };
  
  for (address in addresses) {
    var explanation = (addresses[address]) ? ' passes' : ' fails'
    validationTest(
      validators.validateUrl($input.val(address)),
      addresses[address],
      address + explanation
    );
  }
  
});

test("Number test", function() {
  var $input = getInput('text','');
  
  validationTest(
    validators.validateNumber($input.val('6')),
    true,
    'Interger value'
  );

  validationTest(
    validators.validateNumber($input.val('8.345')),
    true,
    'Float value'
  );

  validationTest(
    validators.validateNumber($input.val('Notanumber')),
    false,
    "Text"
  );

  validationTest(
    validators.validateNumber($input.val('#$')),
    false,
    "Special characters"
  );
  
});

test("Alpha test", function() {
  var $input = getInput('text','');
  validationTest(
    validators.validateAlpha($input.val('6')),
    false,
    'Integer value'
  );

  validationTest(
    validators.validateAlpha($input.val('8.345')),
    false,
    'Float value'
  );

  validationTest(
    validators.validateAlpha($input.val('Text with spaces')),
    false,
    "Text with spaces"
  );

  validationTest(
    validators.validateAlpha($input.val('Word')),
    true,
    "Single word"
  );

  validationTest(
    validators.validateAlpha($input.val('Word345')),
    false,
    "Single alphanum"
  );
  
});

test("Alphanum test", function() {
  var $input = getInput('text','');
  validationTest(
    validators.validateAlphaNum($input.val('6')),
    true,
    'Integer value'
  );

  // questionable, because "." is not in \W
  validationTest(
    validators.validateAlphaNum($input.val('8.345')),
    false,
    'Float value'
  );

  validationTest(
    validators.validateAlphaNum($input.val('Text with spaces')),
    false,
    "Text with spaces"
  );

  validationTest(
    validators.validateAlphaNum($input.val('Word')),
    true,
    "Single word"
  );

  validationTest(
    validators.validateAlphaNum($input.val('Word345')),
    true,
    "Single alphanum"
  );
  
});

test("Phrase test", function() {
  var $input = getInput('text','');

  validationTest(
    validators.validatePhrase($input.val('Text with spaces')),
    true,
    "Text with spaces"
  );

  validationTest(
    validators.validatePhrase($input.val('Word')),
    true,
    "Single word"
  );

  validationTest(
    validators.validatePhrase($input.val('Word345')),
    true,
    "Single alphanum"
  );
  
});

test("Phone test", function() {
  var $input = getInput('text','');

  var numbers = {
    '(308)-135-7895'  : true,
    '(123) 456-7890'  : true,
    '123-345-6789'    : true,
    '123 456-7890'  : true,
    '456-7890'        : false,
    '23456789'        : false
  };
  
  for (number in numbers) {
    var explanation = (numbers[number]) ? ' passes' : ' fails'
    validationTest(
      validators.validatePhone($input.val(number)),
      numbers[number],
      number + explanation
    );
  }
  
});

test("Date test", function() {
  var $input = getInput('text','');

  var dates = {
    '1/1/11'     : true,
    '1/1/2011'   : true,
    '1/1/2011'   : true,
    '11/1/2011/' : true,
    '16/40/2011' : false
  };
  
  for (date in dates) {
    var explanation = (dates[date]) ? ' passes' : ' fails'
    validationTest(
      validators.validateDate($input.val(date)),
      dates[date],
      date + explanation
    );
  }
  
});


