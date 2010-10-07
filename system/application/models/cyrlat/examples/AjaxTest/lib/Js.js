/**
 * Subsys_JsHttpRequest_Js: JavaScript DHTML data loader.
 * (C) 2005 Dmitry Koterov, http://forum.dklab.ru/users/DmitryKoterov/
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * See http://www.gnu.org/copyleft/lesser.html
 *
 * Do not remove this comment if you want to use script!
 * Не удаляйте данный комментарий, если вы хотите использовать скрипт!
 *
 * This library tries to use XMLHttpRequest (if available), and on 
 * failure - use dynamically created <script> elements. Backend code
 * is the same for both cases.
 *
 * @author Dmitry Koterov 
 * @version 3.25
 */

function Subsys_JsHttpRequest_Js() { this._construct() }
(function() { // to create local-scope variables
    var COUNT       = 0;
    var PENDING     = {};
    var CACHE       = {};

    // Called by server script on data load.
    Subsys_JsHttpRequest_Js.dataReady = function(id, text, js) {
        var undef;
        var th = PENDING[id];
        delete PENDING[id];
        if (th) {
            delete th._xmlReq;
            if (th.caching) CACHE[th.hash] = [text, js];
            th._dataReady(text, js);
        } else if (typeof(th) != typeof(undef)) {
            alert("ScriptLoader: unknown pending id: "+id);
        }
    }
    
    Subsys_JsHttpRequest_Js.prototype = {
        // Standard properties.
        onreadystatechange: null,
        readyState:         0,
        responseText:       null,
        responseXML:        null,
        status:             200,
        statusText:         "OK",
        
        // Additional properties.
        session_name:       "PHPSESSID",  // set to SID cookie or GET parameter name
        responseJS:         null,         // JavaScript response array/hash
        caching:            false,        // need to use caching?

        // Internals.
        _span:              null,
        _id:                null,
        _xmlReq:            null,
        _openArg:           null,
        _reqHeaders:        null,

        abort: function() {
            if (this._xmlReq) return this._xmlReq.abort();
            if (this._span) {
                this.readyState = 0;
                if (this.onreadystatechange) this.onreadystatechange();
                this._cleanupScript();
            }
        },
            
        open: function(method, url, asyncFlag, username, password) {
            this._openArg = {
                'method':    method,
                'url':       url,
                'asyncFlag': asyncFlag,
                'username':  username,
                'password':  password
            };
            this._id = null;
            this._xmlReq = null;
            this._reqHeaders = [];
            return true;
        },
        
        send: function(content) {
            var id = (new Date().getTime()) + "" + COUNT++;
            
            // Build QUERY_STRING from query hash.
            var query = this._hash2query(content);

            // Append SID to original URL now.
            var url = this._openArg.url;
            var sid = this._getSid();
            if (sid) url += (url.indexOf('?')>=0? '&' : '?') + this.session_name + "=" + escape(sid);

            // Solve hash BEFORE appending ID.
            var hash = this.hash = url + '?' + query;
            if (this.caching && CACHE[hash]) {
                var c = CACHE[hash];
                this._dataReady(c[0], c[1]);
                return false;
            }

            // Try to use XMLHttpRequest.
            this._xmlReq = this._obtainXmlReq(id, url);

            // Pass data in URL (GET, HEAD etc.) or in request body (POST)?
            var hasSetHeader = this._xmlReq && (window.ActiveXObject || this._xmlReq.setRequestHeader); 
            var href, body;
            if (this._xmlReq && hasSetHeader && (""+this._openArg.method).toUpperCase() == "POST") {
                // Use POST method. Pass query in request body.
                // Opera 8.01 does not support setRequestHeader, so no POST method.
                this._openArg.method = "POST";
                href = url;
                body = query;
            } else {
                this._openArg.method = "GET";
                href = url + (url.indexOf('?')>=0? '&' : '?') + query;
                body = null;
            }

            // Append ID: a=aaa&b=bbb&<id>
            href = href + (href.indexOf('?')>=0? '&' : '?') + id;

            // Save loading script.
            PENDING[id] = this;

            if (this._xmlReq) {
                // Open request now & send it.
                // In XMLHttpRequest mode request URL MUST be ended with "<id>-xml".
                var a = this._openArg;
                this._xmlReq.open(a.method, href+"-xml", a.asyncFlag, a.username, a.password);
                if (hasSetHeader) {
                    // Pass pending headers.
                    for (var i=0; i<this._reqHeaders.length; i++)
                        this._xmlReq.setRequestHeader(this._reqHeaders[i][0], this._reqHeaders[i][1]);
                    // Set non-default Content-type. We cannot use 
                    // "application/x-www-form-urlencoded" here, because 
                    // in PHP variable HTTP_RAW_POST_DATA is accessible only when 
                    // enctype is not default (e.g., "application/octet-stream" 
                    // is a good start). We parse POST data manually in backend 
                    // library code.
                    this._xmlReq.setRequestHeader('Content-Type', 'application/octet-stream');
                }
                // Send the request.
                return this._xmlReq.send(body);
            } else {
                // Create <script> element and run it.
                this._obtainScript(id, href);
                return true;
            }
        },

        getAllResponseHeaders: function() {
            if (this._xmlReq) return this._xmlReq.getAllResponseHeaders();
            return '';
        },
            
        getResponseHeader: function(label) {
            if (this._xmlReq) return this._xmlReq.getResponseHeader(label);
            return '';
        },

        setRequestHeader: function(label, value) {
            // Collect headers.
            this._reqHeaders[this._reqHeaders.length] = [label, value];
        },


        //
        // Internal functions.
        //

        // Constructor.
        _construct: function() {},

        // Do all work when data is ready.
        _dataReady: function(text, js) { with (this) {
            if (text !== null || js !== null) {
                readyState = 4;
                responseText = responseXML = text;
                responseJS = js;
            } else {
                readyState = 0;
                responseText = responseXML = responseJS = null;
            }
            if (onreadystatechange) onreadystatechange();
            _cleanupScript();
        }},

        // Create new XMLHttpRequest object.
        _obtainXmlReq: function(id, url) {
            // If url.domain specified, cannot use XMLHttpRequest!
            if (url.match(new RegExp('^[a-z]+://', 'i'))) return null;
            
            // Try to use built-in loaders.
            var req = null;
            if (window.XMLHttpRequest) {
                try { req = new XMLHttpRequest() } catch(e) {}
            } else if (window.ActiveXObject) {
                try { req = new ActiveXObject("Microsoft.XMLHTTP") } catch(e) {}
                if (!req) try { req = new ActiveXObject("Msxml2.XMLHTTP") } catch (e) {}
            }
            if (req) {
                var th = this;
                req.onreadystatechange = function() { 
                    if (req.readyState == 4) {
                        // Remove possible junk from response.
                        var responseText = req.responseText;
                        try {
                            // Call associated dataReady().
                            eval(responseText);
                        } catch (e) {
                            Subsys_JsHttpRequest_Js.dataReady(id, "JavaScript code generated by backend is invalid!\n"+responseText, null);
                        }
                    } else {
                        th.readyState = req.readyState;
                        if (th.onreadystatechange) th.onreadystatechange() 
                    }
                };
                this._id = id;
            }
            return req;
        },

        // Create new script element and start loading.
        _obtainScript: function(id, href) { with (document) {
            var span = null;
            // Oh shit! Damned stupid fucked Opera 7.23 does not allow to create SCRIPT 
            // element over createElement (in HEAD or BODY section or in nested SPAN - 
            // no matter): it is created deadly, and does not respons on href assignment.
            // So - always create SPAN.
            span = body.appendChild(createElement("SPAN"));
            span.style.display = 'none';
            span.innerHTML = 'Text for stupid IE.<s'+'cript></' + 'script>';
            setTimeout(function() {
                var s = span.getElementsByTagName("script")[0];
                s.language = "JavaScript";
                if (s.setAttribute) s.setAttribute('src', href); else s.src = href;
            }, 10);
            this._id = id;
            this._span = span;
        }},

        // Remove last used script element (clean memory).
        _cleanupScript: function() {
            var span = this._span;
            if (span) {
                this._span = null;
                setTimeout(function() {
                    // without setTimeout - crash in IE 5.0!
                    span.parentNode.removeChild(span);
                }, 50);
            }
            return false;
        },

        // Convert hash to QUERY_STRING.
        _hash2query: function(content, prefix) {
            if (prefix == null) prefix = "";
            var query = [];
            if (content instanceof Object) {
                for (var k in content) {
                    var v = content[k];
                    if (v.constructor.prototype[k]) continue;
                    var curPrefix = prefix? prefix+'['+escape(k)+']' : escape(k);
                    if (v instanceof Object)
                        query[query.length] = this._hash2query(v, curPrefix);
                    else
                        query[query.length] = curPrefix + "=" + escape(v);
                }
            } else {
                query = [content];
            }
            return query.join('&');
        },

        // Return value of SID based on QUERY_STRING or cookie
        // (PHP compatible sessions).
        _getSid: function() {
            var m = document.location.search.match(new RegExp('[&?]'+this.session_name+'=([^&?]*)'));
            var sid = null;
            if (m) {
                sid = m[1];
            } else {
                var m = document.cookie.match(new RegExp(s='(;|^)\\s*'+this.session_name+'=([^;]*)'));
                if (m) sid = m[2];
            }
            return sid;
        }
    }
})();
