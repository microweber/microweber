/*!
* Aloha Editor
* Author & Copyright (c) 2011 Gentics Software GmbH
* aloha-sales@gentics.com
* Licensed unter the terms of http://www.aloha-editor.com/license.html
*/

/**
 * Language Repository provides a set of language codes and images
 */
define(
[
 'aloha/jquery',
 'aloha/repository',
 'i18n!aloha/nls/i18n'
],
function(jQuery, Repository, i18nCore) {
	
	
	var $ = jQuery,
		GENTICS = window.GENTICS,
		Aloha = window.Aloha;

	/**
	 * Register the WaiLang Plugin as Aloha.Plugin
	 */
	return new (Aloha.AbstractRepository.extend({
		_constructor: function(){
			this._super('wai-languages');
		},

	
		/**
		 * Set of language codes
		 */
		languageCodes: [],
		
		/**
		 * Initialize WAI Languages, load the language file and prepare the data.
		 */
		init: function() {
		
			var that = this;
			// Load the language codes
			jQuery.ajax({
				url: Aloha.getPluginUrl('wai-lang') + '/lib/language-codes.json',
				dataType: 'json',
				success: jQuery.proxy(that.storeLanguageCodes,that),
				error: that.errorHandler
			});
			
		    // repository name
		    this.repositoryName = 'WaiLanguages';
		},
		
	
		markObject: function (obj, item)  {
			//copied from wai-lang-plugin makeVisible to avoid a circular dependency
			jQuery(obj).addClass('wai-lang');
		},
		
		
		/**
		 * This method will invoked if a error occurres while loading data via ajax
		 */
		errorHandler: function(text, error) {
			//TODO log error here
		},
		
		/**
		 * Stores the retrieved language code data in this object
		 */
		storeLanguageCodes: function(data) {
			var that = this;

			// Transform loaded json into a set of repository documents
			jQuery.each(data, function(key, value) {

				var e = value;
				// Set the id for the element
			   	e.id = key;
								
			   	// Set the repositoryId for that element 
			   	e.repositoryId = that.repositoryId;
			   	e.type = "language";
			   	e.url = "img/flags/" + e.id + ".png";
			   	//e.renditions.url = "img/flags/" + e.id + ".png";
			   	//e.renditions.kind.thumbnail = true; 
			  	that.languageCodes.push( new Aloha.RepositoryDocument(e) );
			});
			
		},
		
		/**
		 * Searches a repository for object items matching query if objectTypeFilter.
		 * If none found it returns null.
		 */
		query: function( p, callback) {
			// Not supported; filter, orderBy, maxItems, skipcount, renditionFilter
			var that = this;
			var query = new RegExp("^" + p.queryString, 'i'), i, d = [], matchesName, matchesType, currentElement;

			for (i = 0; i < this.languageCodes.length; ++i) {
				currentElement = this.languageCodes[i];
				matchesName = ( !p.queryString || currentElement.name.match(query)  || currentElement.nativeName.match(query));
				matchesType = ( !p.objectTypeFilter || ( !p.objectTypeFilter.length ) || jQuery.inArray(currentElement.type, p.objectTypeFilter) > -1);

				if (matchesName && matchesType) {
					d.push(currentElement);
				}
			}
			
			callback.call( this, d);
		}
	
}))();
	
});