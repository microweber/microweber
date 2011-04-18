h3. Changelog

h4. 1.7.2

* Submit on change if input type select and no submit button defined ("gregpyp":http://github.com/gregpyp)

h4. 1.7.1

* Namespace default event as click.editable ("Zangetsu":http://github.com/Zangetsu)
* Trim whitespace when determining the selected value of pulldown ("binarylogic":http://github.com/binarylogic)
* Make default settings publicly available ("lawrencepit":http://github.com/lawrencepit)
* Allow ajax calls other than 'html', e.g. json and script calls. ("lawrencepit":http://github.com/lawrencepit)
* Do not follow links if they are editable ("Darwin":http://github.com/darwin)
* make JSLInt happy ("olleolleolle":http://github.com/olleolleolle)

h4. 1.7.0

* Full control over jQuery AJAX options for those who want to tinker.
* Fix problem with IE and placeholder with HTML tags.
* Add $.editable('disable'), $.editable('enable') and $.editable('destroy')
* Add onedit, onsubmit, onreset and onerror hooks. 
* Allow passing select options as JavaScript object.
* Fix IE throwing error with textareas when width or height was set to 'none'.

h4. 1.6.2

* Fix problems when xhtml is served application/xhtml+xml.

h4. 1.6.1

* Submit method can now be POST (default) or PUT.
* Fix form being submitted twice in some cases.

h4. 1.6.0

* Onblur parameter can now be a function.
* Support for any arbitary event for triggering Jeditable
* Submitting of form will be canceled if submit() method of custom input returns false. 
* Custom inputs now have access to reset() method. 
