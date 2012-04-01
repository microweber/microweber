// Register the related commands.
var dialogPath = FCKConfig.PluginsPath + 'flvPlayer/flvPlayer.html';
var flvPlayerDialogCmd = new FCKDialogCommand( FCKLang["DlgFLVPlayerTitle"], FCKLang["DlgFLVPlayerTitle"], dialogPath, 600, 520 );
FCKCommands.RegisterCommand( 'flvPlayer', flvPlayerDialogCmd ) ;

// Create the Flash toolbar button.
var oFlvPlayerItem		= new FCKToolbarButton( 'flvPlayer', FCKLang["DlgFLVPlayerTitle"]) ;
oFlvPlayerItem.IconPath	= FCKPlugins.Items['flvPlayer'].Path + 'flvPlayer.gif' ;

FCKToolbarItems.RegisterItem( 'flvPlayer', oFlvPlayerItem ) ;			
// 'Flash' is the name used in the Toolbar config.

