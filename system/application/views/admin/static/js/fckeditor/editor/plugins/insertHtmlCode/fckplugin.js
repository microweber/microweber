/*
 * File Name: fckplugin.js
 * Plugin to launch the Insert Code dialog in FCKeditor
 */

// Register the related command.
FCKCommands.RegisterCommand( 'insertHtmlCode', new FCKDialogCommand( 'InsertHtmlCode', 'InsertHtmlCode', FCKPlugins.Items['insertHtmlCode'].Path + 'fck_insertHtmlCode.html', 415, 300 ) ) ;

// Create the "insertHtmlCode" toolbar button.
var oinsertHtmlCodeItem = new FCKToolbarButton( 'insertHtmlCode', 'InsertHtmlCode', 'InsertHtmlCode', null, null, false, true) ;
oinsertHtmlCodeItem.IconPath = FCKPlugins.Items['insertHtmlCode'].Path + 'insertHtmlCode.gif' ;


FCKToolbarItems.RegisterItem( 'insertHtmlCode', oinsertHtmlCodeItem ) ;

