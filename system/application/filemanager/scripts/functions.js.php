<?php
// ensure this file is being included by a parent file
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
/**
 * @version $Id: functions.js.php 165 2010-05-26 21:22:17Z soeren $
 * @package eXtplorer
 * @copyright soeren 2007-2009
 * @author The eXtplorer project (http://sourceforge.net/projects/extplorer)
 * @author The	The QuiX project (http://quixplorer.sourceforge.net)
 * @license
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * Alternatively, the contents of this file may be used under the terms
 * of the GNU General Public License Version 2 or later (the "GPL"), in
 * which case the provisions of the GPL are applicable instead of
 * those above. If you wish to allow use of your version of this file only
 * under the terms of the GPL and not to allow others to use
 * your version of this file under the MPL, indicate your decision by
 * deleting  the provisions above and replace  them with the notice and
 * other provisions required by the GPL.  If you do not delete
 * the provisions above, a recipient may use your version of this file
 * under either the MPL or the GPL."
 *
*/
/**
 * Layout and Application Logic Functions based on ExtJS
 */
?>
Ext.BLANK_IMAGE_URL = '<?php echo _EXT_URL ?>/scripts/extjs3/resources/images/default/s.gif';

	/**
	* This function is for changing into a specified directory
	* It updates the tree, the grid and the ContentPanel title
	*/
    function chDir( directory, loadGridOnly ) {
   		
    	if( datastore.directory.replace( /\//g, '' ) == directory.replace( /\//g, '' )
    		&& datastore.getTotalCount() > 0 && directory != '') {
    		// Prevent double loading
    		return;
    	}
    	datastore.directory = directory;
    	var conn = datastore.proxy.getConnection();
    	if( directory == '' || conn && !conn.isLoading()) {
    		datastore.load({params:{start:0, limit:150, dir: directory, option:'com_extplorer', action:'getdircontents', sendWhat: datastore.sendWhat }});
    	}
		Ext.Ajax.request({
			url: '<?php echo basename( $GLOBALS['script_name']) ?>',
			params: { action:'chdir_event', dir: directory, option: 'com_extplorer' },
			callback: function(options, success, response ) {
				if( success ) {
					checkLoggedOut( response ); // Check if current user is logged off. If yes, Joomla! sends a document.location redirect, which will be eval'd here
					var result = Ext.decode( response.responseText );						
					document.title = 'eXtplorer - ' + datastore.directory;
					Ext.get('bookmark_container').update( result.bookmarks );
				}
			}
		});

	    if( !loadGridOnly ) {
			expandTreeToDir( null, directory );
    	}
    }
	
	function expandTreeToDir( node, dir ) {
		dir = dir ? dir : new String('<?php echo str_replace("'", "\'", extGetParam( $_SESSION,'ext_'.$GLOBALS['file_mode'].'dir', '' )) ?>');
		var dirs = dir.split('/');
		if( dirs[0] == '') { dirs.shift(); }
		if( dirs.length > 0 ) {
			node = Ext.getCmp("dirTree").getNodeById( '_RRR_'+ dirs[0] );
			if( !node ) return;
			if( node.isExpanded() ) {
				expandNode( node, dir );
				return;
			}
			node.on('load', function() { expandNode( node, dir ) } );
			node.expand();
		}
	}
	function expandNode( node, dir ) {
		var fulldirpath, dirpath;
	
		var dirs = dir.split('/');
		if( dirs[0] == '') { dirs.shift(); }
		if( dirs.length > 0 ) {
			fulldirpath = '';
			for( i=0; i < dirs.length; i++ ) {
				fulldirpath += '_RRR_'+ dirs[i];
			}
			if( node.id.substr( 0, 5 ) != '_RRR_' ) {
				fulldirpath = fulldirpath.substr( 5 );
			}
		
			if( node.id != fulldirpath ) {
				dirpath = '';
		
				var nodedirs = node.id.split('_RRR_');
				if( nodedirs[0] == '' ) nodedirs.shift();
				for( i=0; i < dirs.length; i++ ) {
					if( nodedirs[i] ) {
						dirpath += '_RRR_'+ dirs[i];
					} else {
						dirpath += '_RRR_'+ dirs[i];
						//dirpath = dirpath.substr( 5 );
						var nextnode = Ext.getCmp("dirTree").getNodeById( dirpath );
						if( !nextnode ) { return; }
						if( nextnode.isExpanded() ) { expandNode( nextnode, dir ); return;}
						nextnode.on( 'load', function() { expandNode( nextnode, dir ) } );	

						nextnode.expand();
						break;
					}
				}
			}
			else {
				node.select();
			}
			
		}
	}
    function handleNodeClick( sm, node ) {
    	if( node && node.id ) {
    		chDir( node.id.replace( /_RRR_/g, '/' ) );
    	}
    } 
    function checkLoggedOut( response ) {
    	var re = /(?:<script([^>]*)?>)((\n|\r|.)*?)(?:<\/script>)/ig;
    
		var match;
    	while(match = re.exec(response.responseText)){
            if(match[2] && match[2].length > 0){
               eval(match[2]);
            }
        }
	}
function showLoadingIndicator( el, replaceContent ) {
	if( !el ) return;
	var loadingimg = 'components/com_extplorer/images/_indicator.gif';
	var imgtag = '<' + 'img src="'+ loadingimg + '" alt="Loading..." border="0" name="Loading" align="absmiddle" />';

	if( replaceContent ) {
		el.innerHTML = imgtag;
	}
	else {
		el.innerHTML += imgtag;
	}
}
function getURLParam( strParamName, myWindow){
	if( !myWindow ){
		myWindow = window;
	}
  var strReturn = "";
  var strHref = myWindow.location.href;
  if ( strHref.indexOf("?") > -1 ){
    var strQueryString = strHref.substr(strHref.indexOf("?")).toLowerCase();
    var aQueryString = strQueryString.split("&");
    for ( var iParam = 0; iParam < aQueryString.length; iParam++ ){
      if ( aQueryString[iParam].indexOf(strParamName + "=") > -1 ){
        var aParam = aQueryString[iParam].split("=");
        strReturn = aParam[1];
        break;
      }
    }
  }
  return strReturn;
}

function openActionDialog( caller, action ) {
	var dialog;
	var selectedRows = ext_itemgrid.getSelectionModel().getSelections();
	if( selectedRows.length < 1 ) {
		var selectedNode = Ext.getCmp("dirTree").getSelectionModel().getSelectedNode();
		if( selectedNode ) {
			selectedRows = Array( Ext.getCmp("dirTree").getSelectionModel().getSelectedNode().id.replace( /_RRR_/g, '/' ) );
		}
	}
	var dontNeedSelection = { mkitem:1, get_about:1, ftp_authentication:1, upload:1, search:1, admin:1, ssh2_authentication: 1, extplorer_authentication: 1 };
	if( dontNeedSelection[action] == null  && selectedRows.length < 1 ) {
		Ext.Msg.alert( '<?php echo ext_Lang::err('error', true )."','".ext_Lang::err('miscselitems', true ) ?>');
		return false;
	}

	switch( action ) {
		case 'admin':
		case 'archive':
		case 'chmod':
		case 'copy':
		case 'edit':
		case 'extplorer_authentication':
		case 'ftp_authentication':
		case 'ssh2_authentication':
		case 'get_about':
		case 'mkitem':
		case 'move':
		case 'rename':
		case 'search':
		case 'upload':
		case 'view':
		case 'diff':
		case 'move':
			requestParams = getRequestParams();
			requestParams.action = action;
			if( action != "edit" ) {
            	dialog = new Ext.Window( {
            		id: "dialog",
                    autoCreate: true,
                    modal:true,
                    width:600,
                    height:400,
                    shadow:true,
                    minWidth:300,
                    minHeight:200,
                    proxyDrag: true,
                    resizable: true,
                    renderTo: Ext.getBody(),
                    keys: {
					    key: 27,
					    fn  : function(){
	                        dialog.hide();
	                    }
					},
                    //animateTarget: typeof caller.getEl == 'function' ? caller.getEl() : caller,
					title: '<?php echo ext_Lang::msg('dialog_title', true ) ?>'
                   
            	});			
			}
			Ext.Ajax.request( { url: '<?php echo basename($GLOBALS['script_name']) ?>',
								params: Ext.urlEncode( requestParams ),
								scripts: true,
								callback: function(oElement, bSuccess, oResponse) {
											if( !bSuccess ) {
												msgbox = Ext.Msg.alert( "Ajax communication failure!");
												msgbox.setIcon( Ext.MessageBox.ERROR );
											}
											if( oResponse && oResponse.responseText ) {
												
												//Ext.Msg.alert("Debug", oResponse.responseText );
												try{ json = Ext.decode( oResponse.responseText );
													if( json.error && typeof json.error != 'xml' ) {
														Ext.Msg.alert( "<?php echo ext_Lang::err('error', true ) ?>", json.error );
														dialog.destroy();
														return false;
													}
												} catch(e) {
													msgbox = Ext.Msg.alert( "<?php echo ext_Lang::err('error', true ) ?>", "JSON Decode Error: " + e.message );
													msgbox.setIcon( Ext.MessageBox.ERROR );
													return false; 
												}
												if( action == "edit" ) {
													Ext.getCmp("mainpanel").add(json);
													Ext.getCmp("mainpanel").activate(json.id);
												}
												else {
													// we expect the returned JSON to be an object that
													// contains an "Ext.Component" or derivative in xtype notation
													// so we can simply add it to the Window
													dialog.add(json);
													if( json.dialogtitle ) {
														// if the component delivers a title for our
														// dialog we can set the title of the window
														dialog.setTitle(json.dialogtitle);
													}

													try {
														// recalculate layout
														dialog.doLayout();
														// recalculate dimensions, based on those of the newly added child component
														firstComponent = dialog.getComponent(0);
														newWidth = firstComponent.getWidth() + dialog.getFrameWidth();
														newHeight = firstComponent.getHeight() + dialog.getFrameHeight();
														dialog.setSize( newWidth, newHeight );
														
													} catch(e) {}
													//alert( "Before: Dialog.width: " + dialog.getWidth() + ", Client Width: "+ Ext.getBody().getWidth());
													if( dialog.getWidth() >= Ext.getBody().getWidth() ) {
														dialog.setWidth( Ext.getBody().getWidth() * 0.8 );
													}
													//alert( "After: Dialog.width: " + dialog.getWidth() + ", Client Width: "+ Ext.getBody().getWidth());
													if( dialog.getHeight() >= Ext.getBody().getHeight() ) {
														dialog.setHeight( Ext.getBody().getHeight() * 0.7 );
													} else if( dialog.getHeight() < Ext.getBody().getHeight() * 0.3 ) {
														dialog.setHeight( Ext.getBody().getHeight() * 0.5 );
													}

													// recalculate Window size
													dialog.syncSize();
													// center the window
													dialog.center();
												}
											} else if( !response || !oResponse.responseText) {
												msgbox = Ext.Msg.alert( "<?php echo ext_Lang::err('error', true ) ?>", "Received an empty response");
												msgbox.setIcon( Ext.MessageBox.ERROR );

											}
										}
							});
            
			if( action != "edit" ) {
            	dialog.on( 'hide', function() { dialog.destroy(true); } );
            	dialog.show();
            }
            break;

		case 'delete':
			var num = selectedRows.length;
			Ext.Msg.confirm('<?php echo ext_Lang::msg('dellink', true ) ?>?', String.format("<?php echo ext_Lang::err('miscdelitems', true ) ?>", num ), deleteFiles);
			break;
		case 'extract':
			Ext.Msg.confirm('<?php echo ext_Lang::msg('extractlink', true ) ?>?', "<?php echo ext_Lang::msg('extract_warning', true ) ?>", extractArchive);
			break;
		case 'download':
			document.location = '<?php echo basename($GLOBALS['script_name']) ?>?option=com_extplorer&action=download&item='+ encodeURIComponent(ext_itemgrid.getSelectionModel().getSelected().get('name')) + '&dir=' + encodeURIComponent( datastore.directory );
			break;
	}
}
function handleCallback(requestParams, node) {
	var conn = new Ext.data.Connection();

	conn.request({
		url: '<?php echo basename($GLOBALS['script_name']) ?>',
		params: requestParams,
		callback: function(options, success, response ) {
			if( success ) {
				json = Ext.decode( response.responseText );
				if( json.success ) {
					statusBarMessage( json.message, false, true );
					try {
						if( dropEvent) {
							dropEvent.target.parentNode.reload();
							dropEvent = null;
						}
						if( node ) {
							if( options.params.action == 'delete' || options.params.action == 'rename' ) {
								node.parentNode.select();
							}
							node.parentNode.reload();
						} else {
							datastore.reload();
						}
					} catch(e) { datastore.reload(); }
				} else {
					Ext.Msg.alert( 'Failure', json.error );
				}
			}
			else {
				Ext.Msg.alert( 'Error', 'Failed to connect to the server.');
			}

		}
	});
}
function getRequestParams() {
	var selitems, dir, node;
	var selectedRows = ext_itemgrid.getSelectionModel().getSelections();
	if( selectedRows.length < 1 ) {
		node = Ext.getCmp("dirTree").getSelectionModel().getSelectedNode();
		if( node ) {
			var dir = Ext.getCmp("dirTree").getSelectionModel().getSelectedNode().id.replace( /_RRR_/g, '/' );
			var lastSlash = dir.lastIndexOf( '/' );
			if( lastSlash > 0 ) {
				selitems = Array( dir.substring(lastSlash+1) );
			} else {
				selitems = Array( dir );
			}
		} else {
			selitems = {};
		}
		dir = datastore.directory.substring( 0, datastore.directory.lastIndexOf('/'));
	}
	else {
		selitems = Array(selectedRows.length);

		if( selectedRows.length > 0 ) {
			for( i=0; i < selectedRows.length;i++) {
				selitems[i] = selectedRows[i].get('name');
			}
		}
		dir = datastore.directory;
	}
	//Ext.Msg.alert("Debug", dir );
	var requestParams = {
		option: 'com_extplorer',
		dir: dir,
		item: selitems.length > 0 ? selitems[0]:'',
		'selitems[]': selitems
	};
	return requestParams;
}
/**
* Function for actions, which don't require a form like download, extraction, deletion etc.
*/
function deleteFiles(btn) {
	if( btn != 'yes') {
		return;
	}
	requestParams = getRequestParams();
	requestParams.action = 'delete';
	handleCallback(requestParams);
}
function extractArchive(btn) {
	if( btn != 'yes') {
		return;
	}
	requestParams = getRequestParams();
	requestParams.action = 'extract';
	handleCallback(requestParams);
}
function deleteDir( btn, node ) {
	if( btn != 'yes') {
		return;
	}
	requestParams = getRequestParams();
	requestParams.dir = datastore.directory.substring( 0, datastore.directory.lastIndexOf('/'));
	requestParams.selitems = Array( node.id.replace( /_RRR_/g, '/' ) );
	requestParams.action = 'delete';
	handleCallback(requestParams, node);
}

Ext.msgBoxSlider = function(){
    var msgCt;

    function createBox(t, s){
        return ['<div class="msg">',
                '<div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>',
                '<div class="x-box-ml"><div class="x-box-mr"><div id="x-box-mc-inner" class="x-box-mc"><h3>', t, '</h3>', s, '</div></div></div>',
                '<div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>',
                '</div>'].join('');
    }
    return {
        msg : function(title, format){
            if(!msgCt){
                msgCt = Ext.DomHelper.insertFirst(document.body, {id:'msg-div'}, true);
            }
            msgCt.alignTo(document, 't-t');
            var s = String.format.apply(String, Array.prototype.slice.call(arguments, 1));
            var m = Ext.DomHelper.append(msgCt, {html:createBox(title, s)}, true);
            m.setWidth(400 );
            m.position(null, 5000 );
           m.alignTo(document, 't-t');
           Ext.get('x-box-mc-inner' ).setStyle('background-image', 'url("<?php echo _EXT_URL ?>/images/_accept.png")');
           Ext.get('x-box-mc-inner' ).setStyle('background-position', '5px 10px');
           Ext.get('x-box-mc-inner' ).setStyle('background-repeat', 'no-repeat');
           Ext.get('x-box-mc-inner' ).setStyle('padding-left', '35px');
            m.slideIn('t').pause(3).ghost("t", {remove:true});
        }
    };
}();


function statusBarMessage( msg, isLoading, success ) {
	var statusBar = Ext.getCmp('statusPanel');
	if( !statusBar ) return;
	if( isLoading ) {
		statusBar.showBusy();
	}
	else {
		statusBar.setStatus("Done.");
	}
	if( success ) {
		statusBar.setStatus({
		    text: '<?php echo ext_Lang::msg('success', true ) ?>: ' + msg,
		    iconCls: 'success',
		    clear: true
		});
		Ext.msgBoxSlider.msg('<?php echo ext_Lang::msg('success', true ) ?>', msg );
	} else if( success != null ) {
		statusBar.setStatus({
		    text: '<?php echo ext_Lang::err('error', true ) ?>: ' + msg,
		    iconCls: 'error',
		    clear: true
		});
		
	}
	

}

function selectFile( dir, file ) {
	chDir( dir );
	var conn = datastore.proxy.getConnection();
   	if( conn.isLoading() ) {
   		setTimeout( "selectFile(\"" + dir + "\", \""+ file + "\")", 1000 );
   	}
	idx  = datastore.find( "name", file );
	if( idx >= 0 ) {
		ext_itemgrid.getSelectionModel().selectRow( idx );
	}
}

/**
*	Debug Function, that works like print_r for Objects in Javascript
*/
function var_dump(obj) {
	var vartext = "";
	for (var prop in obj) {
		if( isNaN( prop.toString() )) {
			vartext += "\t->"+prop+" = "+ eval( "obj."+prop.toString()) +"\n";
		}
    }
   	if(typeof obj == "object") {
    	return "Type: "+typeof(obj)+((obj.constructor) ? "\nConstructor: "+obj.constructor : "") + "\n" + vartext;
   	} else {
      	return "Type: "+typeof(obj)+"\n" + vartext;
	}
}//end function var_dump

//http://www.bazon.net/mishoo/home.epl?NEWS_ID=1345
function doGetCaretPosition (textarea) {

	var txt = textarea.value;
	var len = txt.length;
	var erg = txt.split("\n");
	var pos = -1;
	if(typeof textarea.selectionStart != "undefined") { // FOR MOZILLA
		pos = textarea.selectionStart;
	}
	else if(typeof document.selection != "undefined") { // FOR MSIE
		range_sel = document.selection.createRange();
		range_obj = textarea.createTextRange();
		range_obj.moveToBookmark(range_sel.getBookmark());
		range_obj.moveEnd('character',textarea.value.length);
		pos = len - range_obj.text.length;
	}
	if(pos != -1) {
		var ind = 0;
		for(;erg.length;ind++) {
			len = erg[ind].length + 1;
			if(pos < len)
			break;
			pos -= len;
		}
		ind++; pos++;
		return [ind, pos]; // ind = LINE, pos = COLUMN

	}
}
/**
* This function allows us to change the position of the caret
* (cursor) in the textarea
* Various workarounds for IE, Firefox and Opera are included
* Firefox doesn't count empty lines, IE does...
*/
function setCaretPosition( textarea, linenum ) {
	if (isNaN(linenum)) {
		updatePosition( textarea );
		return;
	}
	var txt = textarea.value;
	var len = txt.length;
	var erg = txt.split("\n");

	var ind = 0;
	var pos = 0;
	var nonempty = -1;
	var empty = -1;
	for(;ind < linenum;ind++) {
		if( !erg[ind] && pos < len ) { empty++; pos++; continue; }
		else if( !erg[ind] ) break;
		pos += erg[ind].length;
		nonempty++;
	}
	try {
		pos -= erg[ind-1].length;
	} catch(e) {}

	textarea.focus();

	if(textarea.setSelectionRange)
	{
		pos += nonempty;
		textarea.setSelectionRange(pos,pos);
	}
	else if (textarea.createTextRange) {
		pos -= empty;
		var range = textarea.createTextRange();
		range.collapse(true);
		range.moveEnd('character', pos);
		range.moveStart('character', pos);

		range.select();
	}
}
/**
* Updates the Position Indicator fields
*/
function updatePosition(textBox) {
	var posArray = doGetCaretPosition(textBox);
	if( posArray[0] ) {
	    Ext.fly( 'txtLine' ).set( { value: posArray[0] } );
	}
	if( posArray[1] ) {
	    Ext.fly( 'txtColumn' ).set( { value: posArray[1] } );
	}
}