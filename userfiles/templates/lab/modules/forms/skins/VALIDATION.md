# [Uni-Form Markup](http://sprawsm.com/uni-form/) : Validation documentation


## Initialize the jQuery plugin

The following code will initialize the jQuery Validation plugin with the default options.

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
    <script type="text/javascript" src="../js/uni-form-validation.jquery.js" charset="utf-8"></script>
    <script>
      $(function(){
        $('form.uniForm').uniform();
      });
    </script>

You may use a global object to hold site wide validation settings. To do this, you should
copy the jQuery.fn.uniform.defaults = {} object from the bottom of the validation javascript
file into a new file that you use throughout your site. You may then edit options there
globally, and will make the Uni-Form library easy to update in the future.

You may also initialize Uni-Form Validation with custom settings by passing a settings object
as a parameter when you call uniform(). 

    <script>
      $(function(){
        $('form.uniForm').uniform({
            prevent_submit : true,
            valid_class    : 'okGo'
        });
      });
    </script>

## Uni-Form Settings

* prevent_submit (false)
  Set this to true to prevent the form from submitting if there are outstanding
  errors in the form
* prevent_submit_callback (false)
  Supply a function here and it will be called instead of the internal handler.
  This function can return true to allow the form to proceed with the commit
* ask_on_leave (false)
  Set this to true to have the browser prompt if the visitor has made changes to
  the form, and then initialized a page unload without submitting the form
* on_leave_callback (false)
  Provide a function and it will be called instead of the internal method
* valid_class ('valid')
  CSS class name used for div.holder_class elements that have passed validation
* invalid_class ('invalid')
  CSS class name used for div.holder_class elements that have failed validation
* error_class ('error')
  Please note that both of these are applied by the validation script.
  You may wish to set them separately at the server perhaps.
* focused_class ('focused')
  CSS class name applied to the .holder_class of the current element
* holder_class ('ctrlHolder')
  CSS class name that you have used as the control holder class
* field_selector ('input, textarea, select')
  List of html elements that will be treated with Uni-Form highlighting and 
  validation (if enabled)
* default_value_color ("#AFAFAF")
  HEX color used to display the default data in the background of empty text inputs
  
## Validators

* required
* validateMinLength
* validateMin
* validateMaxLength
* validateMax
* validateSameAs
* validateEmail
* validateUrl
* validateNumber
* validateInteger
* validateAlpha
* validateAlphaNum
* validatePhrase
* validatePhone
* validateDate
* validateCallback

Validators what require a parameter, such as validateMinLength, take that parameter
as a class name following the validator in the format of _val-{value}_. 

## validateCallback

The validateCallback is a special validator. See the demo/callback.html file for example
use. It allows you to define a custom callback to an input without having to add a new
validator type to the library.

