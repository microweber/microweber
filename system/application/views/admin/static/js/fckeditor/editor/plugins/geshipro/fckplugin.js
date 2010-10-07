/* 
FCKCommands.RegisterCommand(commandName, command)
       commandName - Command name, referenced by the Toolbar, etc...
       command - Command object (must provide an Execute() function).
*/

var GeshiproCodeCommand=function(){
        //create our own command, we dont want to use the FCKDialogCommand because it uses the default fck layout and not our own
};

GeshiproCodeCommand.prototype.Execute=function(){
}

GeshiproCodeCommand.GetState=function() {
        return FCK_TRISTATE_OFF; //we dont want the button to be toggled
}

GeshiproCodeCommand.Execute=function() {
        //open a popup window when the button is clicked
        window.open(FCKConfig.PluginsPath + 'geshipro/geshipro.php', 'Geshipro', 'width=500,height=400,scrollbars=no,scrolling=no,location=no,toolbar=no');
}


// Register the related commands.
FCKCommands.RegisterCommand(
   'GeSHiPro',
    new FCKDialogCommand(
		FCKLang['GeshiproTitle'],
  		FCKLang['GeshiproTitle'],
        FCKConfig.PluginsPath + 'geshipro/geshipro.php', 600, 420));
        
var btnGeSHiPro = new FCKToolbarButton('GeSHiPro', FCKLang['GeshiproTitle'])
btnGeSHiPro.IconPath = FCKConfig.PluginsPath + 'geshipro/images/geshipro.gif' ;

// 'My_Find' is the name used in the Toolbar config.
FCKToolbarItems.RegisterItem( 'GeSHiPro', btnGeSHiPro ) ;