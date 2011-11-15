/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.data.XmlReader
 * @extends Ext.data.DataReader
 * Data reader class to create an Array of {@link Ext.data.Record} objects from an XML document
 * based on mappings in a provided {@link Ext.data.Record} constructor.<br><br>
 * <p>
 * <em>Note that in order for the browser to parse a returned XML document, the Content-Type
 * header in the HTTP response must be set to "text/xml" or "application/xml".</em>
 * <p>
 * Example code:
 * <pre><code>
var Employee = Ext.data.Record.create([
   {name: 'name', mapping: 'name'},     // "mapping" property not needed if it's the same as "name"
   {name: 'occupation'}                 // This field will use "occupation" as the mapping.
]);
var myReader = new Ext.data.XmlReader({
   totalRecords: "results", // The element which contains the total dataset size (optional)
   record: "row",           // The repeated element which contains row information
   id: "id"                 // The element within the row that provides an ID for the record (optional)
}, Employee);
</code></pre>
 * <p>
 * This would consume an XML file like this:
 * <pre><code>
&lt;?xml version="1.0" encoding="UTF-8"?>
&lt;dataset>
 &lt;results>2&lt;/results>
 &lt;row>
   &lt;id>1&lt;/id>
   &lt;name>Bill&lt;/name>
   &lt;occupation>Gardener&lt;/occupation>
 &lt;/row>
 &lt;row>
   &lt;id>2&lt;/id>
   &lt;name>Ben&lt;/name>
   &lt;occupation>Horticulturalist&lt;/occupation>
 &lt;/row>
&lt;/dataset>
</code></pre>
 * @cfg {String} totalRecords The DomQuery path from which to retrieve the total number of records
 * in the dataset. This is only needed if the whole dataset is not passed in one go, but is being
 * paged from the remote server.
 * @cfg {String} record The DomQuery path to the repeated element which contains record information.
 * @cfg {String} success The DomQuery path to the success attribute used by forms.
 * @cfg {String} id The DomQuery path relative from the record element to the element that contains
 * a record identifier value.
 * @constructor
 * Create a new XmlReader.
 * @param {Object} meta Metadata configuration options
 * @param {Object} recordType Either an Array of field definition objects as passed to
 * {@link Ext.data.Record#create}, or a Record constructor object created using {@link Ext.data.Record#create}.
 */
Ext.data.XmlReader = function(meta, recordType){
    meta = meta || {};
    Ext.data.XmlReader.superclass.constructor.call(this, meta, recordType || meta.fields);
};
Ext.extend(Ext.data.XmlReader, Ext.data.DataReader, {
    /**
     * This method is only used by a DataProxy which has retrieved data from a remote server.
	 * @param {Object} response The XHR object which contains the parsed XML document.  The response is expected
	 * to contain a property called <tt>responseXML</tt> which refers to an XML document object.
     * @return {Object} records A data block which is used by an {@link Ext.data.Store} as
     * a cache of Ext.data.Records.
     */
    read : function(response){
        var doc = response.responseXML;
        if(!doc) {
            throw {message: "XmlReader.read: XML Document not available"};
        }
        return this.readRecords(doc);
    },

    /**
     * Create a data block containing Ext.data.Records from an XML document.
	 * @param {Object} doc A parsed XML document.
     * @return {Object} records A data block which is used by an {@link Ext.data.Store} as
     * a cache of Ext.data.Records.
     */
    readRecords : function(doc){
        /**
         * After any data loads/reads, the raw XML Document is available for further custom processing.
         * @type XMLDocument
         */
        this.xmlData = doc;
        var root = doc.documentElement || doc;
    	var q = Ext.DomQuery;
    	var recordType = this.recordType, fields = recordType.prototype.fields;
    	var sid = this.meta.id;
    	var totalRecords = 0, success = true;
    	if(this.meta.totalRecords){
    	    totalRecords = q.selectNumber(this.meta.totalRecords, root, 0);
    	}

        if(this.meta.success){
            var sv = q.selectValue(this.meta.success, root, true);
            success = sv !== false && sv !== 'false';
    	}
    	var records = [];
    	var ns = q.select(this.meta.record, root);
        for(var i = 0, len = ns.length; i < len; i++) {
	        var n = ns[i];
	        var values = {};
	        var id = sid ? q.selectValue(sid, n) : undefined;
	        for(var j = 0, jlen = fields.length; j < jlen; j++){
	            var f = fields.items[j];
                var v = q.selectValue(f.mapping || f.name, n, f.defaultValue);
	            v = f.convert(v, n);
	            values[f.name] = v;
	        }
	        var record = new recordType(values, id);
	        record.node = n;
	        records[records.length] = record;
	    }

	    return {
	        success : success,
	        records : records,
	        totalRecords : totalRecords || records.length
	    };
    }
});