This code is based on the work of Michel Staelens (michel.staelens@wanadoo.fr) 
and its license is unknown.

To install it in FCKeditor you should extract the contents of this zip under 
the plugins folder of your installation, and in you fckconfig.js file load the 
plugin:

	FCKConfig.Plugins.Add( 'insertHtmlCode' ) ;

Then you can use it in your toolbar adding the 'insertHtmlCode' item.
Example:

FCKConfig.ToolbarSets["Default"] = [
	['Source','DocProps','-','Save','NewPage','Preview','-','Templates'],
	['Cut','Copy','Paste','PasteText','PasteWord','insertHtmlCode','-','Print','SpellCheck'],
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],
	'/',
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
	['OrderedList','UnorderedList','-','Outdent','Indent'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	['Link','Unlink','Anchor'],
	['Image','Flash','Table','Rule','Smiley','SpecialChar','PageBreak'],
	'/',
	['Style','FontFormat','FontName','FontSize'],
	['TextColor','BGColor'],
	['FitWindow','-','About']
] ;


Then you should add a line in your language file (around line number 99 of the en.js file) to make it look like this:


SpellCheck		: "Check Spelling",
UniversalKeyboard	:	"Universal Keyboard",
PageBreakLbl		:	"Page Break",
PageBreak		: "Insert Page Break",
InsertHtmlCode		: "Insert HTML Code",              //  <---- Add this line
