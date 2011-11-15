/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * Copyright (c) 2006-2007, TIBCO Software Inc.
 * Use, modification, and distribution subject to terms of license.
 * 
 * TIBCO(R) PageBus 1.2.0
 */

/*******************************************************************************
 *
 * Contains an implementation of the OpenAjax Hub
 * 
 * Copyright 2006-2007 OpenAjax Alliance
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not 
 * use this file except in compliance with the License. You may obtain a copy 
 * of the License at http://www.apache.org/licenses/LICENSE-2.0 . Unless 
 * required by applicable law or agreed to in writing, software distributed 
 * under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR 
 * CONDITIONS OF ANY KIND, either express or implied. See the License for the 
 * specific language governing permissions and limitations under the License.
 *
 ******************************************************************************/

// prevent re-definition of the OpenAjax object
if(!window["OpenAjax"]){
	OpenAjax = new function() {
		var t = true;
		var f = false;
		var g = window;
		var libs;
		var ooh = "org.openajax.hub.";

		var h = {};
		this.hub = h;
		h.implementer = "http://tibco.com";
		h.implVersion = "0.6";
		h.specVersion = "0.6";
		h.implExtraData = {};
		var libs = {};
		h.libraries = libs;

		h.registerLibrary = function(prefix, nsURL, version, extra){
			libs[prefix] = {
				prefix: prefix,
				namespaceURI: nsURL,
				version: version,
				extraData: extra 
			};
			this.publish(ooh+"registerLibrary", libs[prefix]);
		}
		h.unregisterLibrary = function(prefix){
			this.publish(ooh+"unregisterLibrary", libs[prefix]);
			delete libs[prefix];
		}

		h._subscriptions = { c:{}, s:[] };
		h._cleanup = [];
		h._subIndex = 0;
		h._pubDepth = 0;

		h.subscribe = function(name, callback, scope, subscriberData, filter)			
		{
			if(!scope){
				scope = window;
			}
			var handle = name + "." + this._subIndex;
			var sub = { scope: scope, cb: callback, fcb: filter, data: subscriberData, sid: this._subIndex++, hdl: handle };
			var path = name.split(".");
	 		this._subscribe(this._subscriptions, path, 0, sub);
			return handle;
		}

		h.publish = function(name, message)		
		{
			path = name.split(".");
			this._pubDepth++;
			this._publish(this._subscriptions, path, 0, name, message);
			this._pubDepth--;				
			if((this._cleanup.length > 0) && (this._pubDepth == 0)) {
				for(var i = 0; i < this._cleanup.length; i++) 
					this.unsubscribe(this._cleanup[i].hdl);
				delete(this._cleanup);
				this._cleanup = [];
			}
		}

		h.unsubscribe = function(sub) 
		{
			var path = sub.split(".");
			var sid = path.pop();
			this._unsubscribe(this._subscriptions, path, 0, sid);
		}
		
		h._subscribe = function(tree, path, index, sub) 
		{
			var token = path[index];
			if(index == path.length) 	
				tree["."].push(sub);
			else { 
				if(!tree[token]) {
					tree[token] = { ".": [] }; 
					this._subscribe(tree[token], path, index + 1, sub);
				}
				else 
					this._subscribe(tree[token], path, index + 1, sub);
			}
		}

		h._publish = function(tree, path, index, name, msg) {
			if(typeof tree != "undefined") {
				var node;
				if(index == path.length) {
					node = tree;
				} else {
					this._publish(tree[path[index]], path, index + 1, name, msg);
					this._publish(tree["*"], path, index + 1, name, msg);			
					node = tree["**"];
				}
				if(typeof node != "undefined") {
					var callbacks = node["."];
					var max = callbacks.length;
					for(var i = 0; i < max; i++) {
						if(callbacks[i].cb) {
							var sc = callbacks[i].scope;
							var cb = callbacks[i].cb;
							var fcb = callbacks[i].fcb;
							var d = callbacks[i].data;
							if(typeof cb == "string"){
								// get a function object
								cb = sc[cb];
							}
							if(typeof fcb == "string"){
								// get a function object
								fcb = sc[fcb];
							}
							try {
								if((!fcb) || 
							   		(fcb.call(sc, name, msg, d))) {
										cb.call(sc, name, msg, d);
								}
							}
							catch(err) {
								if(err.message == "PageBus.StackOverflow")
									throw err;
								h.publish("com.tibco.pagebus.error.callbackError", { name: escape(name), error: escape(err.message) });
							}
						}
					}
				}
			}
		}
			
		h._unsubscribe = function(tree, path, index, sid) {
			if(typeof tree != "undefined") {
				if(index < path.length) {
					var childNode = tree[path[index]];
					this._unsubscribe(childNode, path, index + 1, sid);
					if(childNode["."].length == 0) {
						for(var x in childNode) 
					 		return;		
						delete tree[path[index]];	
					}
					return;
				}
				else {
					var callbacks = tree["."];
					var max = callbacks.length;
					for(var i = 0; i < max; i++) 
						if(sid == callbacks[i].sid) {
							if(this._pubDepth > 0) {
								callbacks[i].cb = null;	
								this._cleanup.push(callbacks[i]);						
							}
							else
								callbacks.splice(i, 1);
							return; 	
						}
				}
			}
		}
	};
	// Register the OpenAjax Hub itself as a library.
	OpenAjax.hub.registerLibrary("OpenAjax", "http://openajax.org/hub", "0.6", {});
}

if(!window["PageBus"]) {
PageBus = new function() {
	var version = "1.2.0";
	var D = 0;  
	var Q = []; 
	var Reg = {}; 
	var RClean = []; 
	var RD = 0; 

	_throw = function(n) { 
		throw new Error("PageBus." + n); 
	}
	
	_badName = function(n) { 
		_throw("BadName"); 
	}
	
	_fix = function(p) {
		if(typeof p == "undefined")
			return null;
		return p;
	}

	_valPub = function(name) {
		if((name == null) || (name.indexOf("*") != -1) || (name.indexOf("..") != -1) || 
			(name.charAt(0) == ".") || (name.charAt(name.length-1) == ".")) 
			_badName();
	}
	
	_valSub = function(name) {
		var path = name.split(".");
		var len = path.length;
		for(var i = 0; i < len; i++) {
			if((path[i] == "") ||
			  ((path[i].indexOf("*") != -1) && (path[i] != "*") && (path[i] != "**")))
				_badName();
			if((path[i] == "**") && (i < len - 1))
				_badName();
		}
		return path;
	}
	
	this.subscribe = function(name, scope, callback, subscriberData, filter)			
	{
		filter = _fix(filter);
		subscriberData = _fix(subscriberData);
		var path = _valSub(name);
	 	return OpenAjax.hub.subscribe(name, callback, scope, subscriberData, filter);
	}
	
	this.publish = function (name, message) {	
		_valPub(name);
		if(D > 20) 
			_throw("StackOverflow");
		Q.push({ n: name, m: message, d: (D + 1) });
		if(D == 0) {
			while(Q.length > 0) {
				var qitem = Q.shift();
				var path = qitem.n.split(".");
				try {
					D = qitem.d;
					OpenAjax.hub.publish(qitem.n, qitem.m);
					D = 0;
				}
				catch(err) {
					D = 0;
					throw(err);
				}
			}
		}
	}
	
	this.unsubscribe = function(sub) {
		try {
			OpenAjax.hub.unsubscribe(sub);
		}
		catch(err) {
			_throw("BadParameter");
		}
	}

	this.store = function(name, msg, props) {

		_store = function(tree, path, index, name, msg) {
			var tok = path[index];
			var len = path.length;
			if(typeof tree[tok]== "undefined")
				tree[tok] = {};
			var n = tree[tok];
			if(index == len - 1) {
				if(typeof n["."] != "undefined") {
					if(RD == 0) 
						delete n["."];
					else {
						n["."].v = null;
						RClean.push(n["."]);
					}
				}
				if(msg != null) 
					n["."] = { n: name, v: msg };
			}
			else {
				_store(n, path, index+1, name, msg);
				if(msg == null) {
					for(var x in n[path[index+1]]) 
	 					return;		
					if(RD == 0) 
						delete n[path[index+1]];
					else {
						RClean.push(n[path[index+1]]);
						n[path[index+1]] = null;
					}
				}
			}
		}
	
		_valPub(name);
		var path = name.split(".");
		_store(Reg, path, 0, name, msg);
		if(!props || !props.quiet)
			PageBus.publish(name, msg);
	}
	
	this.query = function(name, scope, cb, data, fcb) {

		_query = function(tree, path, idx, rSub) {
	
			function _doRCB(node, rSub) {
				var z = rSub.z;
				var cb = rSub.c;
				var d = rSub.d;
				var fcb = rSub.f;
	
				var n = node["."];
				if(!n || !n.v) 
					return true;
				if((fcb == null) || fcb.call(z, n.n, n.v, d)) 
					return cb.call(z, n.n, n.v, d);
				return true;
			}
		
			var len = path.length;
			var tok = path[idx];
			var last = (idx == len - 1)
			if(tok == "**") {
				for(tok in tree) {
					if(tok != ".") {
						if (!_doRCB(tree[tok], rSub))
							return false;
						if(!_query(tree[tok], path, idx, rSub))
							return false;
					}
				}
			}
			else if(tok == "*") {
				for(tok in tree) {
					if(tok != ".") {
						if(last) { 
							if(!_doRCB(tree[tok], rSub))
								return false;
						}
						else
							if(!_query(tree[tok], path, idx+1, rSub))
								return false;
					}
				}
			}
			else if(typeof tree[tok] != "undefined") {
				if(last) 
					return _doRCB(tree[tok], rSub);
				else
					return _query(tree[tok], path, idx+1, rSub);
			}
			return true;
		}

		if(scope == null)
			scope = window;
		var path = _valSub(name);
		var len = path.length;
		var res;
		try {
			RD++;
			var rSub = { z: scope, c: cb, d: data, f: fcb };
			res = _query(Reg, path, 0, rSub);
			RD--;	
		}
		catch(err) {
			RD--;	
			throw err;
		}
		if(RD == 0) {
			while(RClean.length > 0) {	
				var p = RClean.pop();
				delete p;
			}
		}
		if(!res)
			return;
		var subj = "com.tibco.pagebus.query.done";
		if((fcb == null) || fcb.call(scope, subj, null, data))
			cb.call(scope, subj, null, data);
	}

};
OpenAjax.hub.registerLibrary("PageBus", "http://tibco.com/PageBus", "1.2.0", {});
}

