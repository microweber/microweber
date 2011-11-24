/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.form.VTypes=function(){var C=/^[a-zA-Z_]+$/;var D=/^[a-zA-Z0-9_]+$/;var B=/^([\w]+)(.[\w]+)*@([\w-]+\.){1,5}([A-Za-z]){2,4}$/;var A=/(((https?)|(ftp)):\/\/([\-\w]+\.)+\w{2,3}(\/[%\-\w]+(\.\w{2,})?)*(([\w\-\.\?\\\/+@&#;`~=%!]*)(\.\w{2,})?)*\/?)/i;return{"email":function(E){return B.test(E)},"emailText":"This field should be an e-mail address in the format \"user@domain.com\"","emailMask":/[a-z0-9_\.\-@]/i,"url":function(E){return A.test(E)},"urlText":"This field should be a URL in the format \"http:/"+"/www.domain.com\"","alpha":function(E){return C.test(E)},"alphaText":"This field should only contain letters and _","alphaMask":/[a-z_]/i,"alphanum":function(E){return D.test(E)},"alphanumText":"This field should only contain letters, numbers and _","alphanumMask":/[a-z0-9_]/i}}();