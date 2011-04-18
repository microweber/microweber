<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: archive.js.php 164 2010-05-03 15:06:51Z soeren $
 * @package eXtplorer
 * @copyright soeren 2007-2009
 * This file is dynamically loaded when something is to be archived
 * It handles the callback to control + show the archive progress
 */
 ?>
 function submitArchiveForm( startfrom, msg ) {
	var form = Ext.getCmp("simpleform").getForm();
	if( startfrom == 0 ) {
		Ext.Msg.show({
			title: 'Please wait',
			msg: msg ? msg : '<?php echo ext_Lang::msg( 'creating_archive', true ) ?>',
			progressText: 'Initializing...',
			width:300,
			progress:true,
			closable:false,
		});
	}
	params = getRequestParams();
	params.action = 'archive',
	params.startfrom = startfrom,
	params.confirm = 'true'
	
	form.submit({
		reset: false,
		success: function(form, action) {
			if( !action.result ) return;

			if( action.result.startfrom > 0 ) {
				submitArchiveForm( action.result.startfrom, action.result.message );

				i = action.result.startfrom/action.result.totalitems;
				Ext.Msg.updateProgress(i, action.result.startfrom + " of "+action.result.totalitems + " (" + Math.round(100*i)+'% completed)');

				return
			} else {

				if( form.findField('download').getValue() ) {
					datastore.reload();
					Ext.getCmp("dialog").destroy();
					Ext.Msg.hide();
					location.href = action.result.newlocation;
					
				} else {
					Ext.Msg.alert('<?php echo ext_Lang::msg('success', true) ?>!', action.result.message);
					datastore.reload();
					Ext.Msg.hide();
					Ext.getCmp("dialog").destroy();
				}
				return;
			}
		},
		failure: function(form, action) {
			Ext.Msg.hide();
			if( action.result ) {
				Ext.Msg.alert('<?php echo ext_Lang::err('error', true) ?>', action.result.error);
			} else {
				Ext.Msg.alert('<?php echo ext_Lang::err('error', true) ?>', "An unknown Error occured" );
			}
		},
		scope: form,
		
		params: params
	});
}
