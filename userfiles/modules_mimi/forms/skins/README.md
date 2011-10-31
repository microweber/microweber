# [Uni-Form Markup](http://sprawsm.com/uni-form/) : Making forms as simple as 1,2,3

## Announcements:

* __Please note that the jQuery plugins no longer automatically initialize.__
  You must init them yourself with the code found in the section below 
  titled "How to use?"
  

## Copyright (c) 2010, Dragan Babic
   
   Permission is hereby granted, free of charge, to any person
   obtaining a copy of this software and associated documentation
   files (the "Software"), to deal in the Software without
   restriction, including without limitation the rights to use,
   copy, modify, merge, publish, distribute, sublicense, and/or sell
   copies of the Software, and to permit persons to whom the
   Software is furnished to do so, subject to the following
   conditions:
   
   The above copyright notice and this permission notice shall be
   included in all copies or substantial portions of the Software.
   
   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
   EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
   OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
   NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
   HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
   WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
   FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
   OTHER DEALINGS IN THE SOFTWARE.


## About Uni–Form 

Uni-Form is a framework that standardizes form markup and styles it with CSS 
giving you two most widely used layout options to choose from. Anyone can get nice 
looking, well structured, highly customizable, accessible and usable forms. To put 
it simply: it makes a developer's life a lot easier. 

* [Uni-Form Homepage](http://sprawsm.com/uni-form/)
* [Support at Get Satisfaction](http://getsatisfaction.com/uni-form)
* [GitHub repository]()

## How to Use? 

First thing you need to do is to link up the necessary files: 

1.  Link to the main CSS file
    
        <link href="path/to/file/uni-form.css" media="all" rel="stylesheet"/>
    
1.  Link to the Uni–Form style CSS file
    
        <link href="path/to/file/default.uni-form.css" media="all" rel="stylesheet"/>
    
1.  Optionally you'll want to link up jQuery and Uni–Form jQuery files if you'd 
    like Uni–Form to highlight the form rows on focus (it's a usability aid): 
      
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
        <script type="text/javascript" src="path/to/file/uni-form.jquery.js"></script>
    
1.  You may also want to try out the version of the Uni–Form jQuery plugin that
    supports client side validation, in case replace the regular plugin this this:
    
        <script type="text/javascript" src="path/to/file/uni-form-validation.jquery.js"></script>

1. Please note that this plugin no longer automatically initialize the Uni–Form plugin.
   You must do this yourself, by adding this snippet after you have included
   both jQuery and the plugin you have chosen:
   
       <script type="text/javascript">
        $(function(){
          $('form.uniForm').uniform();
        });
       </script>


Now that you're all set up, all you need to do is add form fields that are formatted
with Uni–Form markup so the CSS and JavaScript will get the “hooks” they need. These
chunks of code are called “units” and all available units can be found within the 
file called fauxform.html that is included in this package. 

Feel free to extend Uni–Form with units of your own and share. 


## Styles 

As of v1.4 Uni–Form supports styles. These are separate CSS files that contain the
presentation aspect of your form (considering that uni-form.css) contains the 
layout. Style CSS files should be used to control how your form looks, spacing… 

Sharing styles is encouraged, and by default Uni–Form is shipped with three: 

 * Default
 * Blue 
 * Dark 
    
Consider these a starting point for making your own. 

## Options and Layout Control 

Uni–Form by default has two form layouts: default and inline. This is controlled 
by adding (or removing) a CSS class .inlineLabels to the fieldset element. 

There is another option in regards to the layout and it concerns what is referred 
to as "multifields". These are fields that contain multiple inputs per unit and 
are usually used for checkboxes and radio buttons. Each layout supports an 
alternate multifield layout. This is achieved by adding (or removing) a CSS class
.alternate to the ul element. 


## Events

Triggering an error event on the form fields will apply the error
class to the controller and overwrite the supplied description of that
controller with the error text, an example would be:

    $(selector).trigger('error',['an error occured']);

Subsequent calls to success on the form field will remove the error
and replace the error text with the originally supplied description,
an example:

    $(selector).trigger('success');

----------------------------------------------------------------------------------

## Form Validation

Uni–Form can be used with the included uni-form-validation.js file for client
side validation. This is accomplished by using class names on the form elements
to trigger validation rules on blur(). It must be noted that these validation rules
should be used to supplement a server side solution.

Required element, cannot be empty:

    <input type="text" class="textInput required" />

Integer with value greater than or equal to 8:

    <input type="text" class="textInput validateInteger validateMin val-8" />

### Available validators:

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



## Give respect and get it back.
