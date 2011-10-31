/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.data.Connection
 * @extends Ext.util.Observable
 * <p>The class encapsulates a connection to the page's originating domain, allowing requests to be made
 * either to a configured URL, or to a URL specified at request time.</p>
 * <p>Requests made by this class are asynchronous, and will return immediately. No data from
 * the server will be available to the statement immediately following the {@link #request} call.
 * To process returned data, use a {@link #request-option-success callback} in the request options object,
 * or an {@link #requestcomplete event listener}.</p>
 * <p>{@link #request-option-isUpload File uploads} are not performed using normal "Ajax" techniques, that
 * is they are <b>not</b> performed using XMLHttpRequests. Instead the form is submitted in the standard
 * manner with the DOM <tt>&lt;form></tt> element temporarily modified to have its
 * <a href="http://www.w3.org/TR/REC-html40/present/frames.html#adef-target">target</a> set to refer
 * to a dynamically generated, hidden <tt>&lt;iframe></tt> which is inserted into the document
 * but removed after the return data has been gathered.</p>
 * <p>The server response is parsed by the browser to create the document for the IFRAME. If the
 * server is using JSON to send the return object, then the
 * <a href="http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.17">Content-Type</a> header
 * must be set to "text/html" in order to tell the browser to insert the text unchanged into the document body.</p>
 * <p>The response text is retrieved from the document, and a fake XMLHttpRequest object
 * is created containing a <tt>responseText</tt> property in order to conform to the
 * requirements of event handlers and callbacks.</p>
 * <p>Be aware that file upload packets are sent with the content type <a href="http://www.faqs.org/rfcs/rfc2388.html">multipart/form</a>
 * and some server technologies (notably JEE) may require some custom processing in order to
 * retrieve parameter names and parameter values from the packet content.</p>
 * @constructor
 * @param {Object} config a configuration object.
 */
Ext.data.Connection = function(config){
    Ext.apply(this, config);
    this.addEvents(
        /**
         * @event beforerequest
         * Fires before a network request is made to retrieve a data object.
         * @param {Connection} conn This Connection object.
         * @param {Object} options The options config object passed to the {@link #request} method.
         */
        "beforerequest",
        /**
         * @event requestcomplete
         * Fires if the request was successfully completed.
         * @param {Connection} conn This Connection object.
         * @param {Object} response The XHR object containing the response data.
         * See <a href="http://www.w3.org/TR/XMLHttpRequest/">The XMLHttpRequest Object</a>
         * for details.
         * @param {Object} options The options config object passed to the {@link #request} method.
         */
        "requestcomplete",
        /**
         * @event requestexception
         * Fires if an error HTTP status was returned from the server.
         * See <a href="http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html">HTTP Status Code Definitions</a>
         * for details of HTTP status codes.
         * @param {Connection} conn This Connection object.
         * @param {Object} response The XHR object containing the response data.
         * See <a href="http://www.w3.org/TR/XMLHttpRequest/">The XMLHttpRequest Object</a>
         * for details.
         * @param {Object} options The options config object passed to the {@link #request} method.
         */
        "requestexception"
    );
    Ext.data.Connection.superclass.constructor.call(this);
};

Ext.extend(Ext.data.Connection, Ext.util.Observable, {
    /**
     * @cfg {String} url (Optional) The default URL to be used for requests to the server. (defaults to undefined)
     */
    /**
     * @cfg {Object} extraParams (Optional) An object containing properties which are used as
     * extra parameters to each request made by this object. (defaults to undefined)
     */
    /**
     * @cfg {Object} defaultHeaders (Optional) An object containing request headers which are added
     *  to each request made by this object. (defaults to undefined)
     */
    /**
     * @cfg {String} method (Optional) The default HTTP method to be used for requests.
     * (defaults to undefined; if not set, but {@link #request} params are present, POST will be used;
     * otherwise, GET will be used.)
     */
    /**
     * @cfg {Number} timeout (Optional) The timeout in milliseconds to be used for requests. (defaults to 30000)
     */
    timeout : 30000,
    /**
     * @cfg {Boolean} autoAbort (Optional) Whether this request should abort any pending requests. (defaults to false)
     * @type Boolean
     */
    autoAbort:false,

    /**
     * @cfg {Boolean} disableCaching (Optional) True to add a unique cache-buster param to GET requests. (defaults to true)
     * @type Boolean
     */
    disableCaching: true,
    
    /**
     * @cfg {String} disableCachingParam (Optional) Change the parameter which is sent went disabling caching
     * through a cache buster. Defaults to '_dc'
     * @type String
     */
    disableCachingParam: '_dc',
    

    /**
     * <p>Sends an HTTP request to a remote server.</p>
     * <p><b>Important:</b> Ajax server requests are asynchronous, and this call will
     * return before the response has been received. Process any returned data
     * in a callback function.</p>
     * <p>To execute a callback function in the correct scope, use the <tt>scope</tt> option.</p>
     * @param {Object} options An object which may contain the following properties:<ul>
     * <li><b>url</b> : String/Function (Optional)<div class="sub-desc">The URL to
     * which to send the request, or a function to call which returns a URL string. The scope of the
     * function is specified by the <tt>scope</tt> option. Defaults to configured URL.</div></li>
     * <li><b>params</b> : Object/String/Function (Optional)<div class="sub-desc">
     * An object containing properties which are used as parameters to the
     * request, a url encoded string or a function to call to get either. The scope of the function
     * is specified by the <tt>scope</tt> option.</div></li>
     * <li><b>method</b> : String (Optional)<div class="sub-desc">The HTTP method to use
     * for the request. Defaults to the configured method, or if no method was configured,
     * "GET" if no parameters are being sent, and "POST" if parameters are being sent.  Note that
     * the method name is case-sensitive and should be all caps.</div></li>
     * <li><b>callback</b> : Function (Optional)<div class="sub-desc">The
     * function to be called upon receipt of the HTTP response. The callback is
     * called regardless of success or failure and is passed the following
     * parameters:<ul>
     * <li><b>options</b> : Object<div class="sub-desc">The parameter to the request call.</div></li>
     * <li><b>success</b> : Boolean<div class="sub-desc">True if the request succeeded.</div></li>
     * <li><b>response</b> : Object<div class="sub-desc">The XMLHttpRequest object containing the response data. 
     * See <a href="http://www.w3.org/TR/XMLHttpRequest/">http://www.w3.org/TR/XMLHttpRequest/</a> for details about 
     * accessing elements of the response.</div></li>
     * </ul></div></li>
     * <a id="request-option-success"></a><li><b>success</b> : Function (Optional)<div class="sub-desc">The function
     * to be called upon success of the request. The callback is passed the following
     * parameters:<ul>
     * <li><b>response</b> : Object<div class="sub-desc">The XMLHttpRequest object containing the response data.</div></li>
     * <li><b>options</b> : Object<div class="sub-desc">The parameter to the request call.</div></li>
     * </ul></div></li>
     * <li><b>failure</b> : Function (Optional)<div class="sub-desc">The function
     * to be called upon failure of the request. The callback is passed the
     * following parameters:<ul>
     * <li><b>response</b> : Object<div class="sub-desc">The XMLHttpRequest object containing the response data.</div></li>
     * <li><b>options</b> : Object<div class="sub-desc">The parameter to the request call.</div></li>
     * </ul></div></li>
     * <li><b>scope</b> : Object (Optional)<div class="sub-desc">The scope in
     * which to execute the callbacks: The "this" object for the callback function. If the <tt>url</tt>, or <tt>params</tt> options were
     * specified as functions from which to draw values, then this also serves as the scope for those function calls.
     * Defaults to the browser window.</div></li>
     * <li><b>form</b> : Element/HTMLElement/String (Optional)<div class="sub-desc">The <tt>&lt;form&gt;</tt>
     * Element or the id of the <tt>&lt;form&gt;</tt> to pull parameters from.</div></li>
     * <a id="request-option-isUpload"></a><li><b>isUpload</b> : Boolean (Optional)<div class="sub-desc">True if the form object is a
     * file upload (will usually be automatically detected).
     * <p>File uploads are not performed using normal "Ajax" techniques, that is they are <b>not</b>
     * performed using XMLHttpRequests. Instead the form is submitted in the standard manner with the
     * DOM <tt>&lt;form></tt> element temporarily modified to have its
     * <a href="http://www.w3.org/TR/REC-html40/present/frames.html#adef-target">target</a> set to refer
     * to a dynamically generated, hidden <tt>&lt;iframe></tt> which is inserted into the document
     * but removed after the return data has been gathered.</p>
     * <p>The server response is parsed by the browser to create the document for the IFRAME. If the
     * server is using JSON to send the return object, then the
     * <a href="http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.17">Content-Type</a> header
     * must be set to "text/html" in order to tell the browser to insert the text unchanged into the document body.</p>
     * <p>The response text is retrieved from the document, and a fake XMLHttpRequest object
     * is created containing a <tt>responseText</tt> property in order to conform to the
     * requirements of event handlers and callbacks.</p>
     * <p>Be aware that file upload packets are sent with the content type <a href="http://www.faqs.org/rfcs/rfc2388.html">multipart/form</a>
     * and some server technologies (notably JEE) may require some custom processing in order to
     * retrieve parameter names and parameter values from the packet content.</p>
     * </div></li>
     * <li><b>headers</b> : Object (Optional)<div class="sub-desc">Request
     * headers to set for the request.</div></li>
     * <li><b>xmlData</b> : Object (Optional)<div class="sub-desc">XML document
     * to use for the post. Note: This will be used instead of params for the post
     * data. Any params will be appended to the URL.</div></li>
     * <li><b>jsonData</b> : Object/String (Optional)<div class="sub-desc">JSON
     * data to use as the post. Note: This will be used instead of params for the post
     * data. Any params will be appended to the URL.</div></li>
     * <li><b>disableCaching</b> : Boolean (Optional)<div class="sub-desc">True
     * to add a unique cache-buster param to GET requests.</div></li>
     * </ul></p>
     * <p>The options object may also contain any other property which might be needed to perform
     * postprocessing in a callback because it is passed to callback functions.</p>
     * @return {Number} transactionId The id of the server transaction. This may be used
     * to cancel the request.
     */
    request : function(o){
        if(this.fireEvent("beforerequest", this, o) !== false){
            var p = o.params;

            if(typeof p == "function"){
                p = p.call(o.scope||window, o);
            }
            if(typeof p == "object"){
                p = Ext.urlEncode(p);
            }
            if(this.extraParams){
                var extras = Ext.urlEncode(this.extraParams);
                p = p ? (p + '&' + extras) : extras;
            }

            var url = o.url || this.url;
            if(typeof url == 'function'){
                url = url.call(o.scope||window, o);
            }

            if(o.form){
                var form = Ext.getDom(o.form);
                url = url || form.action;

                var enctype = form.getAttribute("enctype");
                if(o.isUpload || (enctype && enctype.toLowerCase() == 'multipart/form-data')){
                    return this.doFormUpload(o, p, url);
                }
                var f = Ext.lib.Ajax.serializeForm(form);
                p = p ? (p + '&' + f) : f;
            }

            var hs = o.headers;
            if(this.defaultHeaders){
                hs = Ext.apply(hs || {}, this.defaultHeaders);
                if(!o.headers){
                    o.headers = hs;
                }
            }

            var cb = {
                success: this.handleResponse,
                failure: this.handleFailure,
                scope: this,
                argument: {options: o},
                timeout : o.timeout || this.timeout
            };

            var method = o.method||this.method||((p || o.xmlData || o.jsonData) ? "POST" : "GET");

            if(method == 'GET' && (this.disableCaching && o.disableCaching !== false) || o.disableCaching === true){
                var dcp = o.disableCachingParam || this.disableCachingParam;
                url += (url.indexOf('?') != -1 ? '&' : '?') + dcp + '=' + (new Date().getTime());
            }

            if(typeof o.autoAbort == 'boolean'){ // options gets top priority
                if(o.autoAbort){
                    this.abort();
                }
            }else if(this.autoAbort !== false){
                this.abort();
            }
            if((method == 'GET' || o.xmlData || o.jsonData) && p){
                url += (url.indexOf('?') != -1 ? '&' : '?') + p;
                p = '';
            }
            this.transId = Ext.lib.Ajax.request(method, url, cb, p, o);
            return this.transId;
        }else{
            Ext.callback(o.callback, o.scope, [o, null, null]);
            return null;
        }
    },

    /**
     * Determine whether this object has a request outstanding.
     * @param {Number} transactionId (Optional) defaults to the last transaction
     * @return {Boolean} True if there is an outstanding request.
     */
    isLoading : function(transId){
        if(transId){
            return Ext.lib.Ajax.isCallInProgress(transId);
        }else{
            return this.transId ? true : false;
        }
    },

    /**
     * Aborts any outstanding request.
     * @param {Number} transactionId (Optional) defaults to the last transaction
     */
    abort : function(transId){
        if(transId || this.isLoading()){
            Ext.lib.Ajax.abort(transId || this.transId);
        }
    },

    // private
    handleResponse : function(response){
        this.transId = false;
        var options = response.argument.options;
        response.argument = options ? options.argument : null;
        this.fireEvent("requestcomplete", this, response, options);
        Ext.callback(options.success, options.scope, [response, options]);
        Ext.callback(options.callback, options.scope, [options, true, response]);
    },

    // private
    handleFailure : function(response, e){
        this.transId = false;
        var options = response.argument.options;
        response.argument = options ? options.argument : null;
        this.fireEvent("requestexception", this, response, options, e);
        Ext.callback(options.failure, options.scope, [response, options]);
        Ext.callback(options.callback, options.scope, [options, false, response]);
    },

    // private
    doFormUpload : function(o, ps, url){
        var id = Ext.id();
        var frame = document.createElement('iframe');
        frame.id = id;
        frame.name = id;
        frame.className = 'x-hidden';
        if(Ext.isIE){
            frame.src = Ext.SSL_SECURE_URL;
        }
        document.body.appendChild(frame);

        if(Ext.isIE){
           document.frames[id].name = id;
        }

        var form = Ext.getDom(o.form);
        form.target = id;
        form.method = 'POST';
        form.enctype = form.encoding = 'multipart/form-data';
        if(url){
            form.action = url;
        }

        var hiddens, hd;
        if(ps){ // add dynamic params
            hiddens = [];
            ps = Ext.urlDecode(ps, false);
            for(var k in ps){
                if(ps.hasOwnProperty(k)){
                    hd = document.createElement('input');
                    hd.type = 'hidden';
                    hd.name = k;
                    hd.value = ps[k];
                    form.appendChild(hd);
                    hiddens.push(hd);
                }
            }
        }

        function cb(){
            var r = {  // bogus response object
                responseText : '',
                responseXML : null
            };

            r.argument = o ? o.argument : null;

            try { //
                var doc;
                if(Ext.isIE){
                    doc = frame.contentWindow.document;
                }else {
                    doc = (frame.contentDocument || window.frames[id].document);
                }
                if(doc && doc.body){
                    r.responseText = doc.body.innerHTML;
                }
                if(doc && doc.XMLDocument){
                    r.responseXML = doc.XMLDocument;
                }else {
                    r.responseXML = doc;
                }
            }
            catch(e) {
                // ignore
            }

            Ext.EventManager.removeListener(frame, 'load', cb, this);

            this.fireEvent("requestcomplete", this, r, o);

            Ext.callback(o.success, o.scope, [r, o]);
            Ext.callback(o.callback, o.scope, [o, true, r]);

            setTimeout(function(){Ext.removeNode(frame);}, 100);
        }

        Ext.EventManager.on(frame, 'load', cb, this);
        form.submit();

        if(hiddens){ // remove dynamic params
            for(var i = 0, len = hiddens.length; i < len; i++){
                Ext.removeNode(hiddens[i]);
            }
        }
    }
});

/**
 * @class Ext.Ajax
 * @extends Ext.data.Connection
 * Global Ajax request class.  Provides a simple way to make Ajax requests with maximum flexibility.  Example usage:
 * <pre><code>
// Basic request
Ext.Ajax.request({
   url: 'foo.php',
   success: someFn,
   failure: otherFn,
   headers: {
       'my-header': 'foo'
   },
   params: { foo: 'bar' }
});

// Simple ajax form submission
Ext.Ajax.request({
    form: 'some-form',
    params: 'foo=bar'
});

// Default headers to pass in every request
Ext.Ajax.defaultHeaders = {
    'Powered-By': 'Ext'
};

// Global Ajax events can be handled on every request!
Ext.Ajax.on('beforerequest', this.showSpinner, this);
</code></pre>
 * @singleton
 */
Ext.Ajax = new Ext.data.Connection({
    /**
     * @cfg {String} url @hide
     */
    /**
     * @cfg {Object} extraParams @hide
     */
    /**
     * @cfg {Object} defaultHeaders @hide
     */
    /**
     * @cfg {String} method (Optional) @hide
     */
    /**
     * @cfg {Number} timeout (Optional) @hide
     */
    /**
     * @cfg {Boolean} autoAbort (Optional) @hide
     */

    /**
     * @cfg {Boolean} disableCaching (Optional) @hide
     */

    /**
     * @property  disableCaching
     * True to add a unique cache-buster param to GET requests. (defaults to true)
     * @type Boolean
     */
    /**
     * @property  url
     * The default URL to be used for requests to the server. (defaults to undefined)
     * @type String
     */
    /**
     * @property  extraParams
     * An object containing properties which are used as
     * extra parameters to each request made by this object. (defaults to undefined)
     * @type Object
     */
    /**
     * @property  defaultHeaders
     * An object containing request headers which are added to each request made by this object. (defaults to undefined)
     * @type Object
     */
    /**
     * @property  method
     * The default HTTP method to be used for requests. Note that this is case-sensitive and should be all caps (defaults
     * to undefined; if not set but parms are present will use "POST," otherwise "GET.")
     * @type String
     */
    /**
     * @property  timeout
     * The timeout in milliseconds to be used for requests. (defaults to 30000)
     * @type Number
     */

    /**
     * @property  autoAbort
     * Whether a new request should abort any pending requests. (defaults to false)
     * @type Boolean
     */
    autoAbort : false,

    /**
     * Serialize the passed form into a url encoded string
     * @param {String/HTMLElement} form
     * @return {String}
     */
    serializeForm : function(form){
        return Ext.lib.Ajax.serializeForm(form);
    }
});