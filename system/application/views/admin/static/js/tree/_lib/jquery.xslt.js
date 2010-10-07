/**
 * xslTransform
 * Tools for XSLT transformations; jQuery wrapper for Sarissa <http://sarissa.sourceforge.net/>.
 * See jQuery.fn.log below for documentation on $.log().
 * See jQuery.fn.getTransform below for documention on the $.getTransform().
 * See var DEBUG below for turning debugging/logging on and off.
 *
 * @version   20071203
 * @since     2006-07-05
 * @copyright Copyright (c) 2006 Glyphix Studio, Inc. http://www.glyphix.com
 * @author    Brad Brizendine <brizbane@gmail.com>, Matt Antone <antone@glyphix.com>
 * @license   MIT http://www.opensource.org/licenses/mit-license.php
 * @requires  >= jQuery 1.0.3			http://jquery.com/
 * @requires  jquery.debug.js			http://jquery.glyphix.com/
 * @requires  >= sarissa.js 0.9.7.6		http://sarissa.sourceforge.net/
 *
 * @example
 * var r = xslTransform.transform('path-to-xsl.xsl','path-to-xml.xml');
 * @desc Perform a transformation and place the results in var r
 *
 * @example
 * var r = xslTransform.transform('path-to-xsl.xsl','path-to-xml.xml');
 * var str = xslTransform.serialize( r );
 * @desc Perform a transformation, then turn the result into a string
 *
 * @example
 * var doc = xslTransform.load('path-to-xml.xml');
 * @desc Load an xml file and return a parsed xml object
 *
 * @example
 * var xml = '<xmldoc><foo>bar</foo></xmldoc>';
 * var doc = xslTransform.load(xml);
 * @desc Load an xml string and return a parsed xml object
 */
var xslTransform = {
	init: function() { },
	XMLSerializer: new XMLSerializer(),
	serialize: function(data) {
		if(typeof(data) == 'string') return data;
		return this.XMLSerializer.serializeToString(data);
	},
	load: function(xml, meth, dat) {
		var r;
		if(typeof(xml) == 'object') { return xml; }
		if(xml.substring(0,1) == '<') { r = this.loadString( xml ); }
		else { r = this.loadFile(xml, meth, dat); }

		if(r) {
			r.setProperty('SelectionNamespaces', 'xmlns:xsl="http://www.w3.org/1999/XSL/Transform"');
			r.setProperty('SelectionLanguage', 'XPath');
			return r;
		}
		else return false;
	},
	loadString: function(str) {
		var p = new DOMParser();
		var xml = p.parseFromString(str, 'text/xml');
		if(!xml) return false;
		return xml;
	},
	loadFile: function(url, meth, dat){
		if(!url) return false;
		var doc;
		this.xhrsuccess = function(data,str) {
			doc = data;
			return true;
		};
		this.xhrerror = function(xhr,err){
			doc = null;
			return false;
		};

		if(!meth)	meth = "GET";
		if(!dat)	dat = {};
		jQuery.ajax({
			type:		meth,
			url:		url,
			data:		dat,
			async:		false,
			success:	this.xhrsuccess,
			error:		this.xhrerror
		});
		if(!doc) return false;
		if(doc.length == 0) return false;
		return doc;
	},

	transform: function(xsl, xml, options) {
		var log = { 'xsl':xsl, 'xml':xml, 'options':options };
		options = options || {};

		var xml = { 'request':xml, 'doc':this.load(xml, options.meth, options.dat) };
		if(xml.doc == false) return false;

		var result = { 'xsl':this.load(xsl, options.meth, options.dat) };
		if(result.xsl == false) return false;

		result.json = false;
		if(options.json && xml.doc) {
			result.json = xml.doc.selectSingleNode(options.json.toString());
		}

		var processor = new XSLTProcessor();
		processor.importStylesheet( result.xsl );
		if(options.params && processor) {
			for(key in options.params) {
				processor.setParameter(null, key.toString(), options.params[key].toString());
			}
		}

		result.doc = processor.transformToDocument( xml.doc );
		var errorTxt = Sarissa.getParseErrorText(result.doc);
		if(errorTxt != Sarissa.PARSED_OK) return false;
		result.string = this.serialize( result.doc );
		result.scripts = jQuery('script',result.doc).text();
		return result;
	}
};


xslTransform.init();
jQuery.fn.getTransform = function(xsl, xml, options){
	var settings = {
		append:		false,
		params:		{},
		xpath:		'',
		eval:		true,
		callback:	'',
		error:		'',
		json:		false,
		meth:		"GET",
		dat:		{ }
	};
	jQuery.extend(settings, options);
	if(!xsl || !xml) { return; }

	var trans = xslTransform.transform(xsl, xml, settings);
	if(trans == false) {
		if(settings.error && jQuery.isFunction(settings.error)) {
			settings.error.apply(window, [ ]);
		}
		return;
	}

	return this.each( function(){
		var re = trans.string.match(/<\?xml.*?\?>/);
		if(re) { trans.string = trans.string.replace(re, ''); }
		try {
			if(settings.append)			jQuery(this).append(trans.string);
			else if(settings.repl)		jQuery(this).replaceWith(trans.string);
			else						jQuery(this).html(trans.string);
		} catch(e) {
			jQuery(this)[0].innerHTML = trans.string;
		}

		if(settings.eval && trans.scripts) {
			if(trans.scripts.length > 0) {
				eval.call(window, trans.scripts);
			}
		}

		if(settings.callback && jQuery.isFunction(settings.callback)) {
			var json = false;
			if(settings.json && trans.json) eval("json = " + trans.json.firstChild.data);
			settings.callback.apply(window, [trans.string, json ]);
		}

	});

};