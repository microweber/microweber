var FCKorig = window.parent.InnerDialogLoaded();
var FCK	= FCKorig.FCK ;
var FCKLang	= FCKorig.FCKLang;
var FCKConfig = FCKorig.FCKConfig;

window.addEvent('domready', function () {
	FCKorig.FCKLanguageManager.TranslatePage (document) ;
	window.parent.SetOkButton (true) ;
});

//#### Dialog Tabs
var tabs = ['SourceCode', 'Options'];

// Set the dialog tabs.
tabs.each(function(tab){
	var lang = 'Geshipro' + tab;
	window.parent.AddTab(tab, FCKLang[lang]);
})
	
// Function called when a dialog tag is selected.
function OnDialogTabChange(tabCode) {
	tabs.each(function(tab){
		$('div' + tab).setStyle('display', (tabCode == tab ? 'block' : 'none'));
	});
}

function Ok() {
	// hier de highlighted content in de editor zetten.
	
	$('wait').setStyle('display', 'block');
	
	myAjax = new Ajax(FCKConfig.PluginsPath + 'geshipro/geshi/output.php', {
		method: 'post',
		data: $('f1'),
		onComplete: function(responseText) {
			ajaxResponse (responseText);
		}
	}).request();
}

function ajaxResponse(responseText) {
	FCK.InsertHtml(responseText);
	window.parent.CloseDialog();
}