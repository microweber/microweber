/*
+-----------------------------------------------------------------------+
| Copyright (c) 2006-2007 Mika Tuupola, Dylan Verheul                   |
| All rights reserved.                                                  |  
|                                                                       |
| Redistribution and use in source and binary forms, with or without    |
| modification, are permitted provided that the following conditions    |
| are met:                                                              |
|                                                                       | 
| o Redistributions of source code must retain the above copyright      |
|   notice, this list of conditions and the following disclaimer.       |
| o Redistributions in binary form must reproduce the above copyright   |
|   notice, this list of conditions and the following disclaimer in the |
|   documentation and/or other materials provided with the distribution.|
|                                                                       |
| THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS   |
| "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT     |
| LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR |
| A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT  |
| OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, |
| SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT      |
| LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, |
| DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY |
| THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT   |
| (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE |
| OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.  |
|                                                                       |
+-----------------------------------------------------------------------+
*/

/* $Id: jquery.jeditable.js 235 2007-09-25 08:34:09Z tuupola $ */

/**
  * jQuery inplace editor plugin (version 1.4.2)
  *
  * Based on editable by Dylan Verheul <dylan@dyve.net>
  * http://www.dyve.net/jquery/?editable
  *
  * @name  jEditable
  * @type  jQuery
  * @param String  target             POST URL or function name to send edited content
  * @param Hash    options            additional options 
  * @param String  options[name]      POST parameter name of edited content
  * @param String  options[id]        POST parameter name of edited div id
  * @param Hash    options[submitdata] Extra parameters to send when submitting edited content.
  * @param String  options[type]      text, textarea or select
  * @param Integer options[rows]      number of rows if using textarea
  * @param Integer options[cols]      number of columns if using textarea
  * @param Mixed   options[height]    'auto', 'none' or height in pixels
  * @param Mixed   options[width]     'auto', 'none' or width in pixels 
  * @param String  options[loadurl]   URL to fetch external content before editing
  * @param String  options[loadtype]  Request type for load url. Should be GET or POST.
  * @param String  options[loadtext]  Text to display while loading external content.
  * @param Hash    options[loaddata]  Extra parameters to pass when fetching content before editing.
  * @param String  options[data]      Or content given as paramameter.
  * @param String  options[indicator] indicator html to show when saving
  * @param String  options[tooltip]   optional tooltip text via title attribute
  * @param String  options[event]     jQuery event such as 'click' of 'dblclick'
  * @param String  options[onblur]    'cancel', 'submit' or 'ignore'
  * @param String  options[submit]    submit button value, empty means no button
  * @param String  options[cancel]    cancel button value, empty means no button
  * @param String  options[cssclass]  CSS class to apply to input form. 'inherit' to copy from parent.
  * @param String  options[style]     Style to apply to input form 'inherit' to copy from parent.
  * @param String  options[select]    true or false, when true text is highlighted
  *             
  */

jQuery.fn.editable = function(target, options, callback) {

    /* prevent elem has no properties error */
    if (this.length === 0) { 
        return(this); 
    }
    
    var settings = {
        target     : target,
        name       : 'value',
        id         : 'id',
        type       : 'text',
        width      : 'auto',
        height     : 'auto',
        event      : 'click',
        onblur     : 'cancel',
        loadtype   : 'GET',
        loadtext   : 'Loading...',
        loaddata   : {},
        submitdata : {}
    };
        
    if(options) {
        jQuery.extend(settings, options);
    }
    
    /* setup some functions */
    var plugin   = jQuery.editable.types[settings.type].plugin || function() { };
    var submit   = jQuery.editable.types[settings.type].submit || function() { };
    var buttons  = jQuery.editable.types[settings.type].buttons 
                || jQuery.editable.types['defaults'].buttons;
    var content  = jQuery.editable.types[settings.type].content 
                || jQuery.editable.types['defaults'].content;
    var element  = jQuery.editable.types[settings.type].element 
                || jQuery.editable.types['defaults'].element;

    callback = callback || function() { };
          
    jQuery(this).attr('title', settings.tooltip);

    /* temporary fix for auto width and height */
    settings.autowidth  = 'auto' == settings.width;
    settings.autoheight = 'auto' == settings.height;
                
    jQuery(this)[settings.event](function(e) {

        /* save this to self because this changes when scope changes */
        var self = this;

        /* prevent throwing an exeption if edit field is clicked again */
        if (self.editing) {
            return;
        }

        /* figure out how wide and tall we are */
        if (settings.width != 'none') {
            settings.width = 
               settings.autowidth ? jQuery(self).width()  : settings.width;
        }
        if (settings.height != 'none') {
            settings.height = 
                settings.autoheight ? jQuery(self).height() : settings.height;
        }
                
        self.editing    = true;
        self.revert     = jQuery(self).html();
        self.innerHTML  = '';

        /* create the form object */
        var f = document.createElement('form');
        
        /* apply css or style or both */
        if (settings.cssclass) {
            if ('inherit' == settings.cssclass) {
                jQuery(f).attr('class', jQuery(self).attr('class'));
            } else {
                jQuery(f).attr('class', settings.cssclass);
            }
        }
        
        if (settings.style) {
            if ('inherit' == settings.style) {
                jQuery(f).attr('style', jQuery(self).attr('style'));
                /* IE needs the second line or display wont be inherited */
                jQuery(f).css('display', jQuery(self).css('display'));                
            } else {
                jQuery(f).attr('style', settings.style);
            }
        }
        
        /*  Add main input element to form and store it in i. */
        var i = element.apply(f, [settings, self]);

        /* maintain bc with 1.1.1 and earlier versions */        
        if (settings.getload) {
            settings.loadurl    = settings.getload;
            settings.loadtype = 'GET';
        } else if (settings.postload) {
            settings.loadurl    = settings.postload;
            settings.loadtype = 'POST';
        }

        /* set input content via POST, GET, given data or existing value */
        if (settings.loadurl) {
            var t = setTimeout(function() {
                i.disabled = true;
                content.apply(f, [settings.loadtext, settings, self]);
            }, 100);
                
            var loaddata = {};
            loaddata[settings.id] = self.id;
            if (jQuery.isFunction(settings.loaddata)) {
                jQuery.extend(loaddata, settings.loaddata.apply(self, [self.revert, settings]));
            } else {
                jQuery.extend(loaddata, settings.loaddata);
            }
            jQuery.ajax({
               type : settings.loadtype,
               url  : settings.loadurl,
               data : loaddata,
               success: function(string) {
               	  window.clearTimeout(t);                
                  content.apply(f, [string, settings, self]);
                  i.disabled = false;
               }
            });
        } else if (settings.data) {
            var str = settings.data;
            if (jQuery.isFunction(settings.data)) {
                var str = settings.data.apply(self, [self.revert, settings]);
            }
            content.apply(f, [str, settings, self]);
        } else { 
            content.apply(f, [self.revert, settings, self]);
        }

        i.name  = settings.name;
        
        /* add buttons to the form */
        buttons.apply(f, [settings, self]);

        /* add created form to self */
        self.appendChild(f);
        
        /* highlight input contents when requested */
        if (settings.select) {
            i.select();
        }
         
        /* attach 3rd party plugin if requested */
        plugin.apply(f, [settings, self]);            

        /* focus to first visible form element */
        jQuery(":input:visible:enabled:first", f).focus();
        
        /* discard changes if pressing esc */
        jQuery(i).keydown(function(e) {
            if (e.keyCode == 27) {
                e.preventDefault();
                reset();
            }
        });

        /* discard, submit or nothing with changes when clicking outside */
        /* do nothing is usable when navigating with tab */
        var t;
        if ('cancel' == settings.onblur) {
            jQuery(i).blur(function(e) {
                t = setTimeout(reset, 500);
            });
        } else if ('submit' == settings.onblur) {
            jQuery(i).blur(function(e) {
                jQuery(f).submit();
            });
        } else {
            jQuery(i).blur(function(e) {
              /* TODO: maybe something here */
            });
        }

        jQuery(f).submit(function(e) {

            if (t) { 
                clearTimeout(t);
            }

            /* do no submit */
            e.preventDefault(); 
            
            /* if this input type has a call before submit hook, call it */
            submit.apply(f, [settings, self]);            

            /* check if given target is function */
            if (jQuery.isFunction(settings.target)) {
                var str = settings.target.apply(self, [jQuery(i).val(), settings]);
                self.innerHTML = str;
                self.editing = false;
                callback.apply(self, [self.innerHTML, settings]);
            } else {
                /* add edited content and id of edited element to POST */
                var submitdata = {};
                submitdata[i.name] = jQuery(i).val();
                submitdata[settings.id] = self.id;
                /* add extra data to be POST:ed */
                if (jQuery.isFunction(settings.submitdata)) {
                    jQuery.extend(submitdata, settings.submitdata.apply(self, [self.revert, settings]));
                } else {
                    jQuery.extend(submitdata, settings.submitdata);
                }          

                /* show the saving indicator */
                jQuery(self).html(settings.indicator);
                jQuery.post(settings.target, submitdata, function(str) {
                    self.innerHTML = str;
                    self.editing = false;
                    callback.apply(self, [self.innerHTML, settings]);
                });
            }
                        
            return false;
        });

        function reset() {
            self.innerHTML = self.revert;
            self.editing   = false;
        }

    });
    
    return(this);
};

/**
  *
  */
 
jQuery.editable = {
    types: {
        defaults: {
            element : function(settings, original) {
                var input = jQuery('<input type="hidden">');                
                jQuery(this).append(input);
                return(input);
            },
            content : function(string, settings, original) {
                jQuery(':input:first', this).val(string);
            },
            buttons : function(settings, original) {
                if (settings.submit) {
                    var submit = jQuery('<input type="submit">');
                    submit.val(settings.submit);
                    jQuery(this).append(submit);
                }
                if (settings.cancel) {
                    var cancel = jQuery('<input type="button">');
                    cancel.val(settings.cancel);
                    jQuery(this).append(cancel);

                    jQuery(cancel).click(function() {
                        jQuery(original).html(original.revert);
                        original.editing = false;
                    });
                }
            }
        },
        text: {
            element : function(settings, original) {
                var input = jQuery('<input>');
                if (settings.width  != 'none') { input.width(settings.width);  }
                if (settings.height != 'none') { input.height(settings.height); }
                /* https://bugzilla.mozilla.org/show_bug.cgi?id=236791 */
                //input[0].setAttribute('autocomplete','off');
                input.attr('autocomplete','off');
                jQuery(this).append(input);
                return(input);
            }
        },
        textarea: {
            element : function(settings, original) {
                var textarea = jQuery('<textarea>');
                if (settings.rows) {
                    textarea.attr('rows', settings.rows);
                } else {
                    textarea.height(settings.height);
                }
                if (settings.cols) {
                    textarea.attr('cols', settings.cols);
                } else {
                    textarea.width(settings.width);
                }
                jQuery(this).append(textarea);
                return(textarea);
            }
        },
        select: {
            element : function(settings, original) {
                var select = jQuery('<select>');
                jQuery(this).append(select);
                return(select);
            },
            content : function(string, settings, original) {
                /* IE borks if we do not store select in separate variable. */
                var select = jQuery('select', this);
                if (String == string.constructor) { 	 
                    eval ("var json = " + string);
                    for (var key in json) {
                        if ('selected' == key) {
                            continue;
                        } 
                        var option = $('<option>').val(key).append(json[key]);
                        select.append(option); 	 
                    }
                    /* TODO: leave only this to content so IE works too! */
/*
                    select.children().each(function() {
                        if (jQuery(this).val() == json['selected']) {
                            jQuery(this).attr('selected', 'selected');
                        };
            		});
            		*/
            		//setTimeout(function() { jQuery.editable.types.select.iefix(select, json['selected']) }, 1000);
                }
                jQuery.editable.types.select.iefix(select, json['selected']);
            },
            iefix : function(select, which) {
                console.log(this);
                console.log(select);
                select.children().each(function() {
                    if (jQuery(this).val() == which) {
                        jQuery(this).attr('selected', 'selected');
                    };                    
                });
            }
        }
    },
    
    /* Add new input type */
    addInputType: function(name, input) {
        jQuery.editable.types[name] = input;
    }
};
