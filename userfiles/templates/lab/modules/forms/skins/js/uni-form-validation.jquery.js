/**
 * Uni-Form jQuery Plugin with Validation
 *
 * Provides form actions for use with the Uni-Form markup style
 * This version adds additional support for client side validation
 *
 * Author: Ilija Studen for the purposes of Uni-Form
 * 
 * Modified by Aris Karageorgos to use the parents function
 * 
 * Modified by Toni Karlheinz to support input fields' text
 * coloring and removal of their initial values on focus
 * 
 * Modified by Jason Brumwell for optimization, addition
 * of valid and invalid states and default data attribues
 * 
 * Modified by LearningStation to add support for client
 * side validation routines. The validation routines based on an
 * open source library of unknown authorship.
 *
 * @see http://sprawsm.com/uni-form/
 * @license MIT http://www.opensource.org/licenses/mit-license.php
 */
jQuery.fn.uniform = function(extended_settings) {
    
    /**
     * Self reference for closures
     *
     * @var object
     */
    var self = this;
    
    /**
     * Object extending the defaults object
     *
     * @var object
     */
    var settings = jQuery.extend(
        jQuery.fn.uniform.defaults,
        extended_settings
    );
    
    /**
     * Language abstration string
     * 
     * to extend, use a script tag to include a file from the localization folder
     *
     * @var object
     */
    var i18n_strings = jQuery.fn.uniform.language;

    /**
     * Supported validators
     *
     * @var Object
     */
    this.validators = {
    
        /**
         * Get the value for a validator that takes parameters
         *
         * @param string name
         * @param string all classes on element
         *
         * @return mixed || null
         */
        get_val : function(name, classes, default_value) {
            var value = default_value;
            classes = classes.split(' ');
            for(var i = 0; i < classes.length; i++) {
                if(classes[i] == name) {
                    if((classes[i + 1] != 'undefined') && ('val-' === classes[i + 1].substr(0,4))) {
                        value = parseInt(classes[i + 1].substr(4),10);
                        return value;
                    }
                }
            }
            return value;
        },
        
        /**
         * Value of field is not empty, whitespace will be counted as empty
         *
         * @param jQuery field
         * @param string caption
         */
        required : function(field, caption) {
            if(field.is(':radio')) {
                var name = field.attr('name');
                if($("input[name=" + name + "]:checked").length) {
                    return true;
                }
                return i18n('req_radio', caption);
            }
            if(field.is(':checkbox')) {
                var name = field.attr('name');
                if(field.is(":checked")) {
                    return true;
                }
                return i18n('req_checkbox', caption);
            }
            if(jQuery.trim(field.val()) == '') {
                return i18n('required', caption);
            }
            return true;
        },
    
        /**
         * Value is shorter than allowed
         *
         * @param jQuery field
         * @param sting caption
         */
        validateMinLength : function(field, caption) {
            var min_length = this.get_val('validateMinLength', field.attr('class'), 0);

            if((min_length > 0) && (field.val().length < min_length)) {
                return i18n('minlength', caption, min_length);
            }
            return true;
        },
        
        /**
         * Value is less than min
         *
         * @param jQuery field
         * @param sting caption
         */
        validateMin : function(field, caption) {
            var min_val = this.get_val('validateMin', field.attr('class'), 0);
      
            if((parseInt(field.val(),10) < min_val)) {
                return i18n('min', caption, min_val);
            }
            return true;
        },
    
        /**
         * Value is longer than allowed
         *
         * @param jQuery field
         * @param string caption
         */
        validateMaxLength : function(field, caption) {
            var max_length = this.get_val('validateMaxLength', field.attr('class'), 0);
      
            if((max_length > 0) && (field.val().length > max_length)) {
                return i18n('maxlength', caption, max_length);
            }
            return true;
        },
        
        /**
         * Value is greater than max
         *
         * @param jQuery field
         * @param sting caption
         */
        validateMax : function(field, caption) {
            var max_val = this.get_val('validateMax', field.attr('class'), 0);
    
            if((parseInt(field.val(),10) > max_val)) {
                return i18n('max', caption, max_val);
            }
            return true;
        },
    
        /**
         * Element has same value as that of the target Element
         *
         * This does not use the val-{name} format, and instead
         * is only the name of the element
         *
         * class="validateSameAs field_id"
         *
         * @param jQuery field
         * @param string caption
         */
        validateSameAs : function(field, caption) {
            var classes = field.attr('class').split(' ');
            var target_field_name = '';
      
            for(var i = 0; i < classes.length; i++) {
                if(classes[i] == 'validateSameAs') {
                    if(classes[i + 1] != 'undefined') {
                        target_field_name = classes[i + 1];
                        break;
                    }
                }
            }
            
            if(target_field_name) {
                var target_field = jQuery('input[name="' + target_field_name + '"]');
                if(target_field.length > 0) {
                    if(target_field.val() != field.val()) {
                        var target_field_caption = target_field.closest('div.'+settings.holder_class).find('label').text().replace('*','');
                        return i18n('same_as', caption, target_field_caption);
                    }
                }
            }
      
            return true;
        },
    
        /**
         * Valid email address
         *
         * @param jQuery field
         * @param string caption
         */
        validateEmail : function(field, caption) {
            if(field.val().match(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/)) {
                return true;
            } else {
                return i18n('email', caption);
            }
        },
    
        /**
         * Valid URL (http://,https://,ftp://)
         *
         * @param jQuery field
         * @param string caption
         */
        validateUrl : function(field, caption) {
            if(field.val().match(/^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i)) {
                return true;
            }
            return i18n('url', caption);
        },
    
        /**
         * Number is only valid value (integers and floats)
         *
         * @param jQuery field
         * @param string caption
         */
        validateNumber : function(field, caption) {
            if(field.val().match(/(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/) || field.val() == '') {
                return true;
            }
            return i18n('number', caption);
        },
    
        /**
         * Whole numbers are allowed
         *
         * @param jQuery field
         * @param string caption
         */
        validateInteger : function(field, caption) {
            if(field.val().match(/(^-?\d\d*$)/) || field.val() == '') {
                return true;
            }
            return i18n('integer', caption);
        },
    
        /**
         * Letters only
         *
         * @param jQuery field
         * @param string caption
         */
        validateAlpha : function(field, caption) {
            if(field.val().match(/^[a-zA-Z]+$/)) {
                return true;
            }
            return i18n('alpha', caption);
        },
    
        /**
         * Letters and numbers
         *
         * @param jQuery field
         * @param string caption
         */
        validateAlphaNum : function(field, caption) {
            if(field.val().match(/\W/)) {
                return i18n('alphanum', caption);
            }
            return true;
        },
    
        /**
         * Simple phrases
         *
         * @param jQuery field
         * @param string caption
         */
        validatePhrase : function(field, caption) {
            if((field.val() == '') || field.val().match(/^[\w\d\.\-_\(\)\*'# :,]+$/i)) {
                return true;
            }
            return i18n('phrase', caption);
        },
        
        /**
         * Phone number
         *
         * @param jQuery field
         * @param string caption
         */
        validatePhone : function(field, caption) {
            phoneNumber = /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/;
            if(phoneNumber.test(field.val())) {
                return true;
            }
            return i18n('phone', caption);
        },
        
        /**
         * Date in MM/DD/YYYY format
         *
         * @param jQuery field
         * @param string caption
         */
        validateDate : function(field, caption) {
            if(field.val().match('(1[0-9]|[1-9])/([1-3][0-9]|[1-9])/((19|20)[0-9][0-9]|[0-9][0-9])')) {
                return true;
            }
            return i18n('date', caption);
        },
    
        /**
         * Callback validator
         *
         * Lets you define your own validators. Usage:
         *
         * <input name="myinput" class="validateCallback my_callback" />
         *
         * This will result in UniForm searching for window.my_callback funciton and
         * executing it with field and caption arguments. Sample implementation:
         *
         * window.my_callback = function(field, caption) {
         *   if(field.val() == '34') {
         *     return true;
         *   } else {
         *     return caption + ' value should be "34"';
         *   }
         * }
         *
         * @param jQuery field
         * @param caption
         */
        validateCallback : function(field, caption) {
            var classes = field.attr('class').split(' ');
            var callback_function = '';
      
            for(var i = 0; i < classes.length; i++) {
                if(classes[i] == 'validateCallback') {
                    if(classes[i + 1] != 'undefined') {
                        callback_function = classes[i + 1];
                        break;
                    }
                }
            }
      
            if(window[callback_function] != 'undefined' && (typeof window[callback_function] == 'function')) {
                return window[callback_function](field, caption);
            }
      
            return i18n('callback', caption, callback_function);
        }
  
    };

    /** 
     * Simple replacement for i18n + sprintf
     *
     * @param string language for for the local i18n object
     * @param mixed substitution vars
     *
     * @return internationalized string
     */
    var i18n = function(lang_key) {
        var lang_string = i18n_strings[lang_key];
        var bits = lang_string.split('%');
        var out = bits[0];
        var re = /^([ds])(.*)$/;
        for (var i=1; i<bits.length; i++) {
            p = re.exec(bits[i]);
            if (!p || arguments[i] == null) continue;
            if (p[1] == 'd') {
                out += parseInt(arguments[i], 10);
            } else if (p[1] == 's') {
                out += arguments[i];
            }
            out += p[2];
        }
        return out;
    };

    /** 
     * Uni-Form form validation error
     *
     * @param string title of the form error
     * @param array  list of error messages to show
     *
     * @return false
     */
    var showFormError = function(form, title, messages) {
      if($('#errorMsg').length) {
        $('#errorMsg').remove();
      }
      $message =
          $('<div />')
              .attr('id','errorMsg')
              .html("<h3>" + title + "</h3>");
      if(messages.length) {
          $message.append($('<ol />'));
          for(m in messages) {
              $('ol', $message).append(
                  $('<li />').text(messages[m])
              );
          }
      }
      form.prepend($message);
      $('html, body').animate({
          scrollTop: form.offset().top
      }, 500);
      $('#errorMsg').slideDown();
      return false;
    };
    
    var showFormSuccess = function(form, title) {
      if($('#okMsg').length) {
        $('#okMsg').remove();
      }
      $message =
          $('<div />')
              .attr('id','okMsg')
              .html("<h3>" + title + "</h3>");
      form.prepend($message);
      $('html, body').animate({
          scrollTop: form.offset().top
      }, 500);
      $('#okMsg').slideDown();
      return false;
    };

    return this.each(function() {
        var form = jQuery(this);
        
        /**
         * Set the results of form validation on the form element
         *
         * @param object $input jQuery form element
         * @param bool   valid  true if the form value passed all validation
         * @param string text   validation error message to display
         *
         * @return null
         */
        var validate = function($input,valid,text) {
            var $p = $input.closest('div.' + settings.holder_class)
                           .andSelf()
                           .toggleClass(settings.invalid_class, !valid)
                           .toggleClass(settings.error_class, !valid)
                           .toggleClass(settings.valid_class, valid)
                           .find('p.formHint');
    
            if (! valid && ! $p.data('info-text')) {
                $p.data('info-text', $p.html());
            }
            else if (valid) {
                text = $p.data('info-text');
            }
    
            if (text) {
                $p.html(text);
            }
        };
        
        /**
         * Select form fields and attach the higlighter functionality
         *
         */
        form.find(settings.field_selector).each(function(){
            var $input = $(this),
                value = $input.val();

            $input.data('default-color',$input.css('color'));

            if (value === $input.data('default-value') || ! value) {
                $input.not('select').css("color", settings.default_value_color);
                $input.val($input.data('default-value'));
            }
        });

        /**
         * If we've set ask_on_leave we'll register a handler here
         *
         * We need to seriaze the form data, wait for a beforeunload, 
         * then serialize and compare for changes
         *
         * If they changed things, and haven't submitted, we'll let them
         * know about it
         * 
         */
        if(settings.ask_on_leave || form.hasClass('askOnLeave')) {
            var initial_values = form.serialize();
            $(window).bind("beforeunload", function(e) {
                if((initial_values != form.serialize()) 
                    && (settings.ask_on_leave || form.hasClass('askOnLeave'))
                ) {
                    return ($.isFunction(settings.on_leave_callback))
                        ? settings.on_leave_callback(form)
                        : confirm(i18n('on_leave'));
                }
            });
        }

        /** 
         * Handle the submission of the form
         *
         * Tasks
         *  * Remove any default values from the form
         *  * If prevent_submit is set true, return false if
         *    there are outstanding errors in the form
         *
         * Todo
         *  * it would be novel to use prevent_submit to disable
         *    the submit button in the blur handler
         *
         * @return bool
         */
        form.submit(function(){
            // in the case of a previously failed submit, we'll remove our marker
            form.removeClass('failedSubmit');
            
            // remove the default values from the val() where they were being displayed
            form.find(settings.field_selector).each(function(){
                if($(this).val() === $(this).data('default-value')) { $(this).val(""); }
            });

            // traverse and revalidate making sure that we haven't missed any fields
            // perhaps if a field was filled in before uniform was initialized
            // or if blur failed to fire correctly
            if(settings.prevent_submit || form.hasClass('preventSubmit')) {
              // use blur to run the validators on each field
              form.find(settings.field_selector).each(function(){
                $(this).blur();
              });
            
              if (form
                    .find('.' + settings.invalid_class)
                    .add('.' + settings.error_class).length
              ) {
                form.addClass('failedSubmit');
                return ($.isFunction(settings.prevent_submit_callback))
                    ? settings.prevent_submit_callback(form)
                    : showFormError(form, i18n('submit_msg'), [i18n('submit_help')]);
              }
              
              settings.ask_on_leave = false;
              form.removeClass('askOnLeave');
              return true;
            }
            
            // qUnit needs to run this function, and still prevent the submit
            if(form.parents('#qunit-fixture').length) {
              return false;
            }
          
            settings.ask_on_leave = false;
            form.removeClass('askOnLeave');
            return true;
        });
        
        /**
         * Set the form focus class
         * 
         * Remove any classes other than the focus class and hide the default label text
         *
         */
        form.delegate(settings.field_selector, 'focus', function() {
            form.find('.' + settings.focused_class).removeClass(settings.focused_class);

            var $input = $(this);

            $input.parents().filter('.'+settings.holder_class+':first').addClass(settings.focused_class);
            
            if($input.val() === $input.data('default-value')){
                $input.val("");
            }

            $input.not('select').css('color',$input.data('default-color'));
        });

        /**
         * Validate a form field on the blur event
         * 
         * Search the classnames on the element for the names of 
         * validators, and run them as we find them
         *
         * If the validators fail, we trigger either 'success' or 'error' events
         *
         */
        form.delegate(settings.field_selector, 'blur', function() {
            var $input = $(this);
            var label  = $(this)
                .closest('div.' + settings.holder_class)
                .find('label').text().replace('*','');

            // remove focus from form element
            form.find('.' + settings.focused_class).removeClass(settings.focused_class);
            
            // (if empty or equal to default value) AND not required
            if(($input.val() === "" || $input.val() === $input.data('default-value'))
                && !$input.hasClass('required')
            ){
                $input.not('select').css("color",settings.default_value_color);
                $input.val($input.data('default-value'));
                return;
            }

            // run the validation and if they all pass, we mark the color and move on
            var has_validation = false;
            for(validator in self.validators) {
                if($input.hasClass(validator)){
                    has_validation = true;
                    var validation_result = self.validators[validator]($input, label);
                    if(typeof(validation_result) == 'string') {
                        $input.trigger('error', validation_result);
                        return;
                    }
                }
            }
            
            // if it had validation and we didn't return above,
            // then all validation passed
            if (has_validation) {
                $input.trigger('success');
            }
            
            // return the color to the default
            $input.css('color', $input.data('default-color'));
            return;
        });

        /**
         * Handle a validation error in the form element
         * 
         * This will set the field to have the error marker
         * and update the warning text
         *
         * @param event  e
         * @param string validation message
         */
        form.delegate(settings.field_selector,'error',function(e,text) {
            validate($(this), false, text);
        });

        /**
         * Handle a succesful validation in the form element
         * 
         * Remove any error messages and set the validation 
         * marker to be success
         *
         * @param event  e
         * @param string unused
         */
        form.delegate(settings.field_selector,'success',function(e,text) {
            validate($(this), true);
        });
    });
};

/**
 * Internationalized language strings for validation messages
 */
jQuery.fn.uniform.language = {
    required      : '%s is required',
    req_radio     : 'Please make a selection',
    req_checkbox  : 'You must select this checkbox to continue',
    minlength     : '%s should be at least %d characters long',
    min           : '%s should be greater than or equal to %d',
    maxlength     : '%s should not be longer than %d characters',
    max           : '%s should be less than or equal to %d',
    same_as       : '%s is expected to be same as %s',
    email         : '%s is not a valid email address',
    url           : '%s is not a valid URL',
    number        : '%s needs to be a number',
    integer       : '%s needs to be a whole number',
    alpha         : '%s should contain only letters (without special characters or numbers)',
    alphanum      : '%s should contain only numbers and letters (without special characters)',
    phrase        : '%s should contain only alphabetic characters, numbers, spaces, and the following: . , - _ () * # :',
    phone         : '%s should be a phone number',
    date          : '%s should be a date (mm/dd/yyyy)',
    callback      : 'Failed to validate %s field. Validator function (%s) is not defined!',
    on_leave      : 'Are you sure you want to leave this page without saving this form?',
    submit_msg    : 'Sorry, this form needs corrections.',
    submit_help   : 'Please see the items marked below.',
    submit_success: 'Thank you, this form has been sent.'
};

/**
 * See the validation.md file for more information about these options
 */
jQuery.fn.uniform.defaults = {
    prevent_submit          : false,
    prevent_submit_callback : false,
    ask_on_leave            : false,
    on_leave_callback       : false,
    valid_class             : 'valid',
    invalid_class           : 'invalid',
    error_class             : 'error',
    focused_class           : 'focused',
    holder_class            : 'ctrlHolder',
    field_selector          : 'input, textarea, select',
    default_value_color     : "#AFAFAF"
};
