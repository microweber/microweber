<?php
/*-- ***** BEGIN LICENSE BLOCK *****
  -   Version: MPL 1.1/GPL 2.0/LGPL 2.1
  -
  - The contents of this file are subject to the Mozilla Public License Version
  - 1.1 (the "License"); you may not use this file except in compliance with
  - the License. You may obtain a copy of the License at
  - http://www.mozilla.org/MPL/
  - 
  - Software distributed under the License is distributed on an "AS IS" basis,
  - WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
  - for the specific language governing rights and limitations under the
  - License.
  -
  - The Original Code is AIE (Ajax Image Editor).
  -
  - The Initial Developer of the Original Code is
  - Julian Stricker.
  - Portions created by the Initial Developer are Copyright (C) 2006
  - the Initial Developer. All Rights Reserved.
  -
  - Contributor(s):
  -
  - Alternatively, the contents of this file may be used under the terms of
  - either the GNU General Public License Version 2 or later (the "GPL"), or
  - the GNU Lesser General Public License Version 2.1 or later (the "LGPL"),
  - in which case the provisions of the GPL or the LGPL are applicable instead
  - of those above. If you wish to allow use of your version of this file only
  - under the terms of either the GPL or the LGPL, and not to allow others to
  - use your version of this file under the terms of the MPL, indicate your
  - decision by deleting the provisions above and replace them with the notice
  - and other provisions required by the GPL or the LGPL. If you do not delete
  - the provisions above, a recipient may use your version of this file under
  - the terms of any one of the MPL, the GPL or the LGPL.
  - 
  - ***** END LICENSE BLOCK ***** */
header("Content-Type: text/html; charset=utf-8");
include_once( 'config.inc.php' );
include_once( 'includes/'.$language.'.lang.php' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AIE (Ajax Image Editor) - <?php echo ($_GET["img"]); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
body {
  font-family:tahoma,arial,verdana,sans-serif;
  font-size:11px;
  font-size-adjust:none;
  font-style:normal;
  font-variant:normal;

}
.iframeborder {
	border-width: 1px;
	border-style: solid;
	border-top-color: #7a7a7a;
	border-right-color: #d2d2d2;
	border-bottom-color: #d2d2d2;
	border-left-color: #7a7a7a;
	background-color: #666666;
	background-image:url(img/schachbrettmuster.gif) !important;
}

.tabiconresize{
  background: url(img/resize.png) 0 3px no-repeat !important;
}
.tabiconcrop{
  background: url(img/crop.png) 0 3px no-repeat !important;
}
.tabiconrotate{
  background: url(img/rotate.png) 0 3px no-repeat !important;
}
.tabiconfilter{
  background: url(img/filter.png) 0 3px no-repeat !important;
}
.tabicontext{
  background: url(img/text.png) 0 3px no-repeat !important;
}
.tabiconwatermark{
  background: url(img/watermark.png) 0 3px no-repeat !important;
}


</style>

	<link rel="stylesheet" type="text/css" href="<?php echo $ext_dir; ?>resources/css/ext-all.css" />
  <link id="theme" href="<?php echo $ext_dir; ?>resources/css/xtheme-black.css" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="jsdynlib.js"></script>
 	<script type="text/javascript" src="<?php echo $ext_dir; ?>adapter/ext/ext-base.js"></script>
  <script type="text/javascript" src="<?php echo $ext_dir; ?>ext-all.js"></script>
  <?php
//temporäre bilddatei generieren:
$dateiendung = substr( $_GET["img"], strrpos( $_GET["img"], '.' ) );
$imagetempname = md5( time().$_GET["img"] ).$dateiendung;
if ( @copy( $web_root_dir.$_GET["img"], $server_temp_dir.$imagetempname ) ) {
}
?>


<script language="javascript1.2" type="text/javascript">

//This function is called after a image was saved.
//Change this function for your needs:

function imagesaved(){
  //window.parent.imagesaved();
  window.opener.imagesaved();
  self.close();
}


var historypos=0;
var historyfunc=Array('Start');

function historyback(){
  if (historypos>0){
    historypos--;
    if (document.getElementById('bildframe').contentDocument) {
      document.getElementById('bildframe').contentDocument.ladehistorybild(historypos);
    }else{
      document.getElementById('bildframe').contentWindow.document.ladehistorybild(historypos);
    }
    vorschaustart()
    activateforwardbutton();    
  }
  if (historypos==0) {
    deactivatebackbutton();
  }else{
    activatebackbutton();
  }
}
function historyforward(){
  if (historypos<historyfunc.length-1){
    historypos++;
    if (document.getElementById('bildframe').contentDocument) {
      document.getElementById('bildframe').contentDocument.ladehistorybild(historypos);
    }else{
      document.getElementById('bildframe').contentWindow.document.ladehistorybild(historypos);
    }
    vorschaustart()
    activatebackbutton(); 
    
  }
  if (historypos==historyfunc.length-1){
    deactivateforwardbutton();
  }else{
    activateforwardbutton();
  }
}

function deactivatebackbutton(){
  historybackbutton.disable();
  historybackbutton.setText('Back');
}
function activatebackbutton(){
  historybackbutton.enable();
  historybackbutton.setText('Back ('+historyfunc[historypos]+')');
}
function deactivateforwardbutton(){
  historyforwardbutton.disable();
  historyforwardbutton.setText('Forward');
}
function activateforwardbutton(){
  historyforwardbutton.enable();
  historyforwardbutton.setText('Forward ('+historyfunc[historypos+1]+')');
}


function showabout( bild ) {
	Ext.MessageBox.show({
           title: 'AIE (Ajax Image Editor)',
           msg: '<div style="width:100%; text-align:center"><img src="img/aielogo.png" alt="Logo AIE (Ajax Image Editor)" /><br/><b>Version 0.6</b></div><p align="justify">Copyright © 2006-2008 Julian Stricker. AIE (Ajax Image Editor) comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under certain conditions; Removing the link to this notice or obstructing the appearance is prohibited by law.</p><p>Creator: Julian Stricker</p><p><a href="http://www.ajax-image-editor.com" target=\"_blank\">http://www.ajax-image-editor.com</a></p>',
           buttons: Ext.MessageBox.OK,
           width: 320,
           src: 'aieabout.html'
       });
	
}



function istgeladen() {
	bildpixelx = window.frames.bildframe.bildpixelx;
	bildpixely = window.frames.bildframe.bildpixely;
	vorschaustart();
  var jetzt = new Date();
	gebid( "vorschauganzesbild" ).src='bildserver.php?img=<?php echo $imagetempname; ?>&'+jetzt.getTime();;
	gebid( "resize_width" ).value = bildframe.bildpixelx;
  gebid( "resize_height" ).value = bildframe.bildpixely;
  viewport.doLayout();
}
var iframedoc="";


var rotateslider;

//init ext:
Ext.onReady(function(){
    var thecookie = new Ext.state.CookieProvider({
      expires: new Date(new Date().getTime()+(1000*60*60*24*1)), //30 days
      domain: "www.ajax-image-editor.com"
    });
    //Ext.state.Manager.setProvider(thecookie);
    var theme = thecookie.get('exttheme') || '<?php echo $default_theme; ?>';
    Ext.util.CSS.swapStyleSheet("theme","<?php echo $ext_dir; ?>resources/css/xtheme-"+theme+".css")
    
    function saveimage(btn){
      if (btn!='no'){
        saveimagestore.load(); 
      }
    }
    
    var saveimagestore = new Ext.data.JsonStore({
      fields: ['status'],
      url: 'saveimage.php?tempname=<?php echo $imagetempname; ?>&newname=<?php echo $_GET["img"]; ?>',
      listeners: {              
          	load: {
          		fn: function(){
          		  /*if (document.getElementById('bildframe').contentDocument){
                  document.getElementById('bildframe').contentDocument.ladebild('<?php echo $imagetempname; ?>');
                }else{
                  document.getElementById('bildframe').contentWindow.document.ladebild('<?php echo $imagetempname; ?>');
                }
                vorschaustart()*/
                imagesaved();
                
          		}
          	}
          }
    });
    
    historybackbutton= new Ext.Button({
      text:'Undo',
      disabled: true,
      id:'historybackbutton',
      tooltip:'Go back in History',
      handler: function() { 
        historyback();
      }
    });
    historyforwardbutton= new Ext.Button({
      text:'Redo',
      id:'historyforwardbutton',
       tooltip:'Go forward in History',
      disabled: true,
      handler: function() { 
        historyforward();
      }
    });
    
    
    
    
    
  

            
    //Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    //if (Ext.isIE) {zoomheight=24 }else{ zoomheight=48 }
    zoomheight=48
    viewport = new Ext.Viewport({
        layout:'border',
        items:[{
          region:'north',
          contentEl: 'zoom-area',
          title: '<?php echo $_GET["img"]; ?>',
          height: zoomheight
          

        },{
          region:'center',
          contentEl: 'bildframe',
          split:true,
          height: 48
          

        },{
          region:'south',
          contentEl: 'tools-tabs',
          title: 'Tools',
          split:true,
          collapsible: true,
          autoHeight: true,
          collapsed: false,
          //floatable: false,
          //minHeight: 100,
          //height: 200
          bbar: new Ext.StatusBar({
            defaultText: '&copy; 2006-2008 Julian Stricker',
            id: 'basic-statusbar',
  	        items: ['-','History:',historybackbutton,historyforwardbutton,'-',{
  	          text:'<?php echo $wl["default"]["save"]; ?>',
              handler: function() { 
                Ext.MessageBox.confirm('Save', 'Are you sure you want to save changes?', saveimage);
              }
  	        }]
          })

          
        }]
        

      });
    
    
    
    var zoomslider=new Ext.Slider({
        renderTo: 'zoom-slider',
        width: 200,
        minValue: 10,
        maxValue: 500,
        value: 100,
        listeners: {              
        	change: function(){
              window.frames.bildframe.setzezoom( this.getValue() );
		          window.frames.bildframe.beimscrollen();
		          document.getElementById('zoom-value').innerHTML= this.getValue()+"%";
		          
        		
        	},
        	drag: function(){
              window.frames.bildframe.setzezoom( this.getValue() );
		          window.frames.bildframe.beimscrollen();
		          document.getElementById('zoom-value').innerHTML= this.getValue()+"%";
        		
        	}
        }
        
    });
    
    
    Ext.ux.SliderTip = Ext.extend(Ext.Tip, {
        minWidth: 10,
        offsets : [0, -10],
        init : function(slider){
            slider.on('dragstart', this.onSlide, this);
            slider.on('drag', this.onSlide, this);
            slider.on('dragend', this.hide, this);
            slider.on('destroy', this.destroy, this);
        },
    
        onSlide : function(slider){
            this.show();
            this.body.update(this.getText(slider));
            this.doAutoWidth();
            this.el.alignTo(slider.thumb, 'b-t?', this.offsets);
        },
    
        getText : function(slider){
            return slider.getValue();
        }
    });
    
    var rotateslidertip = new Ext.ux.SliderTip({
        getText: function(slider){
            return String.format('<b>{0}°</b>', slider.getValue()-180);
        }
    });

    
         
    
    //resizereiter zusammenstellen:
    var resizereiter_width = new Ext.form.NumberField({
      allowBlank:false,
      applyTo:'resize_width'
    });
    var resizereiter_height = new Ext.form.NumberField({
      allowBlank:false,
      applyTo:'resize_height'
    });
    var resizereiter_proportionen = new Ext.form.Checkbox({
      allowBlank:false,
      applyTo:'resize_proportionen',
      boxLabel: '<?php echo $wl["default"]["proportions"]; ?>'
      
    });
    var resizereiter_proportionen = new Ext.form.Checkbox({
      applyTo:'resize_proportionen'
    });  
    
    var resizereiter_button = new Ext.Button({
      text:'<?php echo $wl["default"]["apply"]; ?>',
      renderTo: 'resize_form',
      handler: function() { return doresize(); }
    });
    
    //cropreiter zusammenstellen:
    var cropreiter_width = new Ext.form.NumberField({
      allowBlank:false,
      applyTo:'crop_width'
    });
    
    var cropreiter_height = new Ext.form.NumberField({
      allowBlank:false,
      applyTo:'crop_height'
    });
      
    var cropreiter_button = new Ext.Button({
      text:'<?php echo $wl["default"]["apply"]; ?>',
      renderTo: 'crop_form',
      handler: function() { return docrop(); }
    });    
    
    
    //rotatereiter zusammenstellen:
    rotateslider = new Ext.Slider({
        renderTo: 'rotateslider',
        width: 180,
        minValue: 0,
        maxValue: 360,
        value: 180,
        increment: 90,
        plugins: rotateslidertip
    });  

    var rotatereiter_button = new Ext.Button({
      text:'<?php echo $wl["default"]["apply"]; ?>',
      renderTo: 'rotate_form',
      handler: function() { return dorotate(); }
    });
     
    //filterreiter zusammenstellen:
    filterparam1slider=new Ext.Slider({
        
        renderTo: 'filter_param1',
        width: 253,
        value:50,
        minValue: 0,
        maxValue: 100,

        listeners: {              
        	change: function(){              
		          document.getElementById('filter_param1_wert').value= this.getValue()*2-100;	   
              filterparam1 = this.getValue()*2-100;
        	},
        	drag: function(){
              document.getElementById('filter_param1_wert').value= this.getValue()*2-100;  
              filterparam1 = this.getValue()*2-100;   
        	},
        	dragend: function(){
              document.getElementById('filter_param1_wert').value= this.getValue()*2-100;  
              filterparam1 = this.getValue()*2-100;   
              vorschaustart();   		
        	}
        }
        
    });
    
    filterparam2slider=new Ext.Slider({        
        renderTo: 'filter_param2',
        width: 253,
        value:50,
        minValue: 0,
        maxValue: 100,

        listeners: {              
        	change: function(){              
		          document.getElementById('filter_param2_wert').value= this.getValue()*2-100;	   
              filterparam2 = this.getValue()*2-100;
        	},
        	drag: function(){
              document.getElementById('filter_param2_wert').value= this.getValue()*2-100;  
              filterparam2 = this.getValue()*2-100;   
        	},
        	dragend: function(){
              document.getElementById('filter_param2_wert').value= this.getValue()*2-100;  
              filterparam2 = this.getValue()*2-100;   
              vorschaustart();   		
        	}
        }
        
    });
    
    filterparam3slider=new Ext.Slider({        
        renderTo: 'filter_param3',
        width: 253,
        value:50,
        minValue: 0,
        maxValue: 100,

        listeners: {              
        	change: function(){              
		          document.getElementById('filter_param3_wert').value= this.getValue()*2-100;	   
              filterparam3 = this.getValue()*2-100;
        	},
        	drag: function(){
              document.getElementById('filter_param3_wert').value= this.getValue()*2-100;  
              filterparam3 = this.getValue()*2-100;   
        	},
        	dragend: function(){
              document.getElementById('filter_param3_wert').value= this.getValue()*2-100;  
              filterparam3 = this.getValue()*2-100;   
              vorschaustart();   		
        	}
        }
        
    });
    
    var filterreiter_button = new Ext.Button({
      text:'<?php echo $wl["default"]["apply"]; ?>',
      renderTo: 'filter_form',
      handler: function() { return dofilter(); }
    });
    
    //fontreiter zusammenstellen:    
    
    var colormenu = new Ext.menu.Menu({
      id: 'colorMenu',
      defaultAlign: 'c',
      items: [
              new Ext.menu.ColorItem({selectHandler:function(cp, color){
                  setzefarbe( color );
                  aktualisiereschrift();
              }})
      ]
    });
    Ext.get('farbfeld').on('click',function(e)
      {
        
        colormenu.showAt(e.getXY());
        return false;
      }
    ); 
    
    
    
    
    
    var fontstore = new Ext.data.JsonStore({
      url: 'fontlist.php',
      root: 'fonts',
      fields: ['font', 'name'],
      listeners: {              
          	load: {
          		fn: function(){
                
          		}
          	}
          }
    });
    
    fontstore.load();
        
    fontcombo = new Ext.form.ComboBox({
	    store: fontstore,
	    typeAhead: true,
	    displayField:'name',
      listeners: {              
      	select: function(){
          aktualisiereschrift();          		
      	}
      },
	    tpl : '<tpl for="." ><div class="x-combo-list-item"><img src="fontpreview.php?font={font}&amp;fontstring='+document.getElementById("text_dertext").value+'" alt="{name}" /></div></tpl>',
	    triggerAction: 'all',
	    emptyText:'Select a font...',
	    selectOnFocus:true,
	    applyTo: 'fontselectorinp'
  	});
    var text_dertextfield = new Ext.form.TextField({
      allowBlank:false,
      applyTo:'text_dertext',
      listeners: {              
      	change: function(){
          aktualisiereschrift();          		
      	}
      }
    });
    var text_fontsizefield = new Ext.form.TextField({
      allowBlank:false,
      applyTo:'fontsize',
      listeners: {              
      	change: function(){
          aktualisiereschrift();          		
      	}
      }
    });
    var textreiter_button = new Ext.Button({
      text:'<?php echo $wl["default"]["apply"]; ?>',
      renderTo: 'text_form',
      handler: function() { return dotext(); }
    });
    
    //watermarkreiter zusammenstellen
    
    var watermarkstore = new Ext.data.JsonStore({
      url: 'watermarklist.php',
      root: 'watermarks',
      fields: ['watermark', 'name'],
      listeners: {              
          	load: {
          		fn: function(){
                
          		}
          	}
          }
    });
    
    watermarkstore.load();
        
    watermarkcombo = new Ext.form.ComboBox({
	    store: watermarkstore,
	    typeAhead: true,
	    displayField:'name',
	    valueField:'watermark',
      listeners: {              
      	select: function(){
          updatewatermark();          		
      	}
      },
	    tpl : '<tpl for="." ><div class="x-combo-list-item"><img src="getthumbnail.php?dat=images/{watermark}&maxx=80&maxy=40" alt="{name}" />{name}</div></tpl>',
	    triggerAction: 'all',
	    emptyText:'Select a watermark...',
	    selectOnFocus:true,
	    applyTo: 'watermarkselectorinp'
  	});
    
    
    watermarkalphaslider=new Ext.Slider({
        
        renderTo: 'watermarkalphainp',
        width: 200,
        value:100,
        minValue: 0,
        maxValue: 100,

        listeners: {              
        	change: function(){              
		          document.getElementById('watermarkalpha_wert').value= this.getValue();  
		          updatewatermark(); 		
        	},
        	drag: function(){
              document.getElementById('watermarkalpha_wert').value= this.getValue();  
        	},
        	dragend: function(){
        	    document.getElementById('watermarkalpha_wert').value= this.getValue();  
              updatewatermark(); 		
        	}
        }
        
    });
    
    
    
    
    
    var watermarkreiter_button = new Ext.Button({
      text:'<?php echo $wl["default"]["apply"]; ?>',
      renderTo: 'watermark_form',
      handler: function() { return dowatermark(); }
    });
    
    
    
    
    
    //reiter zusammenstellen:
    
    
    var reiternames=Array('resize','crop','rotate','filter','text','mark');
    for(var i=0; i<reiternames.length; i++){
      document.getElementById(reiternames[i]+"reiter").className="x-hide-display";
    
    }
    
    
    
    var tabs = new Ext.TabPanel({
        renderTo: 'tools-tabs',
        //width:100,
        activeTab: 0,
        frame:true,
        defaults:{autoHeight: true},
        autoScroll:true,
        enableTabScroll: true,
        layoutOnTabChange: true,
        autoShow:false,
        items:[
            {contentEl:'resizereiter', frame:true, title: '<?php echo $wl["default"]["change size"]; ?>', autoHeight:true, iconCls: 'tabiconresize', listeners: {activate: activateresize}},

            {contentEl:'cropreiter', frame:true, title: '<?php echo $wl["default"]["cut"]; ?>', autoHeight:true, iconCls: 'tabiconcrop', listeners: {activate: activatecrop}},
            {contentEl:'rotatereiter', frame:true, title: '<?php echo $wl["default"]["rotate"]; ?>', autoHeight:true, iconCls: 'tabiconrotate', listeners: {activate: activaterotate}},
            {contentEl:'filterreiter', frame:true, title: '<?php echo $wl["default"]["filter"]; ?>', autoHeight:true, iconCls: 'tabiconfilter', listeners: {activate: activatefilter}},
            {contentEl:'textreiter', frame:true, title: '<?php echo $wl["default"]["text"]; ?>', autoHeight:true, iconCls: 'tabicontext', listeners: {activate: activatetext}},
            {contentEl:'markreiter', frame:true, title: '<?php echo $wl["default"]["watermark"]; ?>', autoHeight:true, iconCls: 'tabiconwatermark', listeners: {activate: activatewatermark}}
        ]
    });
    
    //alert(document.getElementById('bildframe').tagName);
    

    
    
    //document.getElementById('bildframe').src="bild.html";
    initbildframecheck()
    

       
});

function initbildframecheck(){
  var erf=false;
  if (typeof iframeistgeladen == 'undefined'){
    window.setTimeout("initbildframecheck()",100); 
  }else{
    if (document.getElementById('bildframe').contentDocument) { 
      if (typeof (document.getElementById('bildframe').contentDocument.ladebildfunc) == 'function'){
        document.getElementById('bildframe').contentDocument.ladebildfunc('<?php echo $imagetempname; ?>') 
        erf=true;
      }
    }else{ 
      if (typeof (document.getElementById('bildframe').contentWindow.document.ladebildfunc) == 'function'){
        document.getElementById('bildframe').contentWindow.document.ladebildfunc('<?php echo $imagetempname; ?>') 
        erf=true;
      }
    }
    if (erf==false) window.setTimeout("initbildframecheck()",100); 
  }

}

function activateresize(){
  gebid( "resize_ppwidth" ).value = "pixel";
  gebid( "resize_ppheight" ).value = "pixel";
  if (typeof window.frames.bildframe.setzewerkzeug != 'undefined') window.frames.bildframe.setzewerkzeug( 'resize' );
  if (typeof window.frames.bildframe.setzewerkzeug != 'undefined') window.frames.bildframe.hideauswahlrechteck();
  //window.frames.bildframe.hideschriftlayer();
  viewport.syncSize();
}
function activatecrop(){
  
  window.frames.bildframe.setzewerkzeug( 'crop' );
  window.frames.bildframe.welchefunktion='crop';
  window.frames.bildframe.hideschriftlayer();
  viewport.syncSize();
}
function activaterotate(){
  
  window.frames.bildframe.setzewerkzeug( 'rotate' );
  window.frames.bildframe.welchefunktion='rotate';
  window.frames.bildframe.hideauswahlrechteck();
  window.frames.bildframe.hideschriftlayer();
  viewport.syncSize();
}
function activatefilter(){
  //filterparam1slider.setValue=100;
  window.frames.bildframe.setzewerkzeug( 'filter' );
  window.frames.bildframe.welchefunktion='filter';
  window.frames.bildframe.hideauswahlrechteck();
  window.frames.bildframe.hideschriftlayer();
  filteraendern()
  vorschaustart();
	viewport.syncSize();
}
function activatetext(){
  window.frames.bildframe.setzewerkzeug( 'text' );
  window.frames.bildframe.welchefunktion='text';
  window.frames.bildframe.hideauswahlrechteck();
  //window.frames.bildframe.fontbildstart()
	viewport.syncSize();

	
}
function activatewatermark(){
  window.frames.bildframe.setzewerkzeug( 'watermark' );
  window.frames.bildframe.welchefunktion='watermark';
  window.frames.bildframe.hideauswahlrechteck();
  window.frames.bildframe.hideschriftlayer();
	viewport.syncSize();
}



function resize_setzewerte( wer ) {
	if ( gebid( "resize_proportionen" ).checked == true ) {
		bildpixelx = window.frames.bildframe.bildpixelx;
		bildpixely = window.frames.bildframe.bildpixely;
		if ( wer.id == "resize_width" ) {
			if ( gebid( "resize_ppwidth" ).value == "pixel" ) {
				var prozent = parseInt( gebid( "resize_width" ).value ) / ( bildpixelx / 100 );
			} else {
				var prozent = parseInt( gebid( "resize_width" ).value );
			}
			if ( gebid( "resize_ppheight" ).value == "pixel" ) {
				gebid( "resize_height" ).value = ( bildpixely / 100 ) * prozent;
			} else {
				gebid( "resize_height" ).value = prozent;
			}
		}
		if ( wer.id == "resize_height" ) {
			if ( gebid( "resize_ppheight" ).value == "pixel" ) {
				var prozent = parseInt( gebid( "resize_height" ).value ) / ( bildpixely / 100 );
			} else {
				var prozent = parseInt( gebid( "resize_height" ).value );
			}
			if ( gebid( "resize_ppwidth" ).value == "pixel" ) {
				gebid( "resize_width" ).value = ( bildpixelx / 100 ) * prozent;
			} else {
				gebid( "resize_width" ).value = prozent;
			}
		}
	}
}

function resize_changemasseinheit( wer ) {
	bildpixelx = window.frames.bildframe.bildpixelx;
	bildpixely = window.frames.bildframe.bildpixely;
	if ( wer.id == "resize_ppwidth" ) {
		if ( wer.value == "prozent" ) {
			var prozent = parseInt( gebid( "resize_width" ).value ) / ( bildpixelx / 100 );
			gebid( "resize_width" ).value = prozent;
		} else {
			var prozent = parseInt( gebid( "resize_width" ).value );
			gebid( "resize_width" ).value = ( bildpixelx / 100 ) * prozent;
		}
	}
	if ( wer.id == "resize_ppheight" ) {
		if ( wer.value == "prozent" ) {
			var prozent = parseInt( gebid( "resize_height" ).value ) / ( bildpixely / 100 );
			gebid( "resize_height" ).value = prozent;
		} else {
			var prozent = parseInt( gebid( "resize_height" ).value );
			gebid( "resize_height" ).value = ( bildpixely / 100 ) * prozent;
		}
	}
}

function doresize() {
	
		if ( gebid( "resize_ppwidth" ).value == "pixel" ) {
			var werw = parseInt( gebid( "resize_width" ).value )
		} else {
			var werw = ( bildpixelx / 100 ) * parseInt( gebid( "resize_width" ).value );
		}
		if ( gebid( "resize_ppheight" ).value == "pixel" ) {
			var werh = parseInt( gebid( "resize_height" ).value )
		} else {
			var werh = ( bildpixely / 100 ) * parseInt( gebid( "resize_height" ).value );
		}
		historypos++;
		historyfunc[historypos]='Resize';
		historyfunc.slice(historypos+1,historyfunc.length-historypos);
		deactivateforwardbutton();
		activatebackbutton();
		window.frames.bildframe.dofunction( historypos, "resize", werw + ", " + werh );
	
	return false;
}


//zuig für cropping:
function setzecropcoords( h1, v1, h2, v2 ) {
	gebid( "crop_width" ).value = Math.round( h2 - h1 );
	gebid( "crop_height" ).value = Math.round( v2 - v1 );
}

function setzecropgroesse() {
	window.frames.bildframe.setzecropgroesse( gebid( "crop_width" ).value, gebid( "crop_height" ).value );
}

function docrop() {
	
	  historypos++;
		historyfunc[historypos]='Crop';
		historyfunc.slice(historypos+1,historyfunc.length-historypos);
		deactivateforwardbutton();
		activatebackbutton();
		window.frames.bildframe.dofunction( historypos, "crop", '' );

	return false;
}

//zuig für rotate:
var rotatelinksrechts = "+";
function dorotate() {
	if ( gebid( "aktiona" ).checked ) {
		var parameter = (rotateslider.getValue()-180);
	} else {
		var parameter = gebid( "rospiegeln" ).value;
	}
	historypos++;
	historyfunc[historypos]='Rotate';
	historyfunc.slice(historypos+1,historyfunc.length-historypos);
	deactivateforwardbutton();
	activatebackbutton();
	window.frames.bildframe.dofunction( historypos, "rotate", parameter );
	return false;
}



//zuig für filter:

bildpixelx = 120;
bildpixely = 120;
vorschaugr = 120;
vorschauposx = 0;
vorschauposy = 0;
var filterparam1 = 0;
var filterparam2 = "x";
var filterparam3 = "x";
ausschnittx = ( bildpixelx / 2 - vorschaugr / 2 );
ausschnitty = ( bildpixely / 2 - vorschaugr / 2 );
vorschaulayerganzesbildleft = vorschauposx - ausschnittx;
vorschaulayerganzesbildtop = vorschauposy - ausschnitty;
bildistgeladen = false;
function vorschaustart() {
  var jetzt = new Date();
	//"&"+jetzt.getTime();
  var bildsrc = 'effektvorschau.php?img=<?php echo $imagetempname; ?>&eff=' + gebid( "welchereff" ).value;
  
	if ( filterparam1 != "x" ) bildsrc+= '&param1=' + filterparam1;
	if ( filterparam2 != "x" ) bildsrc+= '&param2=' + filterparam2;
	if ( filterparam3 != "x" ) bildsrc+= '&param3=' + filterparam3;
	bildsrc+= '&posx=' + ( ausschnittx) + '&posy=' + ( ausschnitty);
	bildsrc+="&"+jetzt.getTime();
  var jetzt = new Date();
	bildsrc+= '&' + jetzt.getTime();

	gebid( "vorschaubild" ).onload = bildistgeladenok;
  gebid( "vorschaubild" ).src = bildsrc;
	
	bildgeladentimeout = window.setTimeout( "bildgeladencheck()", 100 );
	gebid( "vorschaulayerganzesbild" ).style.left = vorschaulayerganzesbildleft + "px";
	gebid( "vorschaulayerganzesbild" ).style.top = vorschaulayerganzesbildtop + "px";
	
}


function vorschauziehenstop() {
	window.clearTimeout( vorschauziehentimeout );
	document.body.parentNode.onmouseup = new Function ( "return true" );
	document.onselectstart = new Function ( "return true" );
	document.onmousedown = reEnable;
	vorschaustart();
}

function vorschauziehen() {
	vorschaulayerganzesbildleft = deltax + Mouse.x;
	vorschaulayerganzesbildtop = deltay + Mouse.y;
	if ( vorschaulayerganzesbildleft >  0) {
		vorschaulayerganzesbildleft = 0;
	} else if ( vorschaulayerganzesbildleft < 120 - bildpixelx ) {
		vorschaulayerganzesbildleft = 120 - bildpixelx;
	}
	if ( vorschaulayerganzesbildtop > 0 ) {
		vorschaulayerganzesbildtop = 0;
	} else if ( vorschaulayerganzesbildtop < 120 - bildpixely ) {
		vorschaulayerganzesbildtop = 120 - bildpixely;
	}
	ausschnittx = vorschauposx - vorschaulayerganzesbildleft// - vorschaugr / 2;
	ausschnitty = vorschauposy - vorschaulayerganzesbildtop// - vorschaugr / 2;
	gebid( "vorschaulayerganzesbild" ).style.left = ( vorschaulayerganzesbildleft ) + "px";
	gebid( "vorschaulayerganzesbild" ).style.top = ( vorschaulayerganzesbildtop ) + "px";
	//gebid( "vorschaulayerganzesbild" ).style.clip = 'rect( ' + ausschnitty + 'px, ' + ( ausschnittx + vorschaugr ) + 'px, ' + ( ausschnitty + vorschaugr ) + 'px, ' + ausschnittx + 'px )';
	vorschauziehentimeout = window.setTimeout( "vorschauziehen()", 50 );
}

function disabletext( e ) {
	return false
}

function reEnable() {
	return true
}

function vorschauziehenstart() {
	//if the browser is IE4+
	document.onselectstart = new Function( "return false" );
	document.ondragstart = new Function( "return false" );
	//if the browser is NS6
	if ( window.sidebar ) {
		document.onmousedown = disabletext
		document.onclick = reEnable
	}
	if ( window.addEventListener ) { // Mozilla, Netscape, Firefox
		document.body.parentNode.addEventListener( 'mouseup', vorschauziehenstop, false );
	} else { // IE
		document.body.parentNode.attachEvent( 'onmouseup', vorschauziehenstop );
		//document.body.parentNode.onmouseup = vorschauziehenstop;
	}
	bildistgeladen = false;
	deltax = vorschaulayerganzesbildleft - Mouse.x;
	deltay = vorschaulayerganzesbildtop - Mouse.y;
	gebid( "vorschaulayer" ).style.left = "-600px";
	gebid( "vorschaulayer" ).style.top = "-600px";
	vorschauziehen();
}


function bildistgeladenok() {
	bildistgeladen = true;
}

function bildgeladencheck() {
	if ( gebid( "vorschaubild" ).complete == true && bildistgeladen ) {
		clearTimeout( bildgeladentimeout );
		gebid( "vorschaulayer" ).style.left = "0px";
		gebid( "vorschaulayer" ).style.top = "0px";
	} else {
		bildgeladentimeout = window.setTimeout( "bildgeladencheck()", 100 );
	}
}



function filteraendern() {
	//removedragger();
	if ( gebid( "welchereff" ).value == "blur" ) {
		gebid( "filter_param1_name" ).innerHTML = "<?php echo $wl["default"]["rate"]; ?>:";
		gebid( "filter_param1_input" ).style.visibility="visible";
		gebid( "filter_param2_name" ).innerHTML = "&nbsp;";
		gebid( "filter_param2_input" ).style.visibility="hidden";
		gebid( "filter_param3_name" ).innerHTML = "&nbsp;";
		gebid( "filter_param3_input" ).style.visibility="hidden";
		filterparam1 = 0;
		filterparam2 = "x";
		filterparam3 = "x";
		filterparam1slider.setValue(50);
		filterparam2slider.setValue(50);
		filterparam3slider.setValue(50);
	} else if ( gebid( "welchereff" ).value == "contrast" ) {
		gebid( "filter_param1_name" ).innerHTML = "<?php echo $wl["default"]["brightness"]; ?>:";
		gebid( "filter_param1_input" ).style.visibility="visible";
		gebid( "filter_param2_name" ).innerHTML = "<?php echo $wl["default"]["contrast"]; ?>:";
		gebid( "filter_param2_input" ).style.visibility="visible";
		gebid( "filter_param3_name" ).innerHTML = "&nbsp;";
		gebid( "filter_param3_input" ).style.visibility="hidden";
		filterparam1 = 0;
		filterparam2 = 0;
		filterparam3 = "x";
		filterparam2slider.on('change', function(){              
      document.getElementById('filter_param2_wert').value= this.getValue();	   
      filterparam2 = this.getValue();
  	});
    filterparam2slider.on('drag', function(){
      document.getElementById('filter_param2_wert').value= this.getValue();  
      filterparam2 = this.getValue();   
  	});
  	filterparam2slider.on('dragend', function(){
      document.getElementById('filter_param2_wert').value= this.getValue();  
      filterparam2 = this.getValue(); 
      vorschaustart();   	  
  	});
  	filterparam1slider.setValue(50);
		filterparam2slider.setValue(0);

		
	} else if ( gebid( "welchereff" ).value == "normalize" ) {
		gebid( "filter_param1_name" ).innerHTML = "&nbsp;";
		gebid( "filter_param1_input" ).style.visibility="hidden";
		gebid( "filter_param2_name" ).innerHTML = "&nbsp;";
		gebid( "filter_param2_input" ).style.visibility="hidden";
		gebid( "filter_param3_name" ).innerHTML = "&nbsp;";
		gebid( "filter_param3_input" ).style.visibility="hidden";
		filterparam1 = "x";
		filterparam2 = "x";
		filterparam3 = "x";
	} else if ( gebid( "welchereff" ).value == "invert" ) {
		gebid( "filter_param1_name" ).innerHTML = "&nbsp;";
		gebid( "filter_param1_input" ).style.visibility="hidden";
		gebid( "filter_param2_name" ).innerHTML = "&nbsp;";
		gebid( "filter_param2_input" ).style.visibility="hidden";
		gebid( "filter_param3_name" ).innerHTML = "&nbsp;";
		gebid( "filter_param3_input" ).style.visibility="hidden";
		filterparam1 = "x";
		filterparam2 = "x";
		filterparam3 = "x";
	} else if ( gebid( "welchereff" ).value == "balance" ) {
		gebid( "filter_param1_name" ).innerHTML = "<?php echo $wl["default"]["cyan red"]; ?>:";
		gebid( "filter_param1_input" ).style.visibility="visible";
		gebid( "filter_param2_name" ).innerHTML = "<?php echo $wl["default"]["magenta green"]; ?>:";
		gebid( "filter_param2_input" ).style.visibility="visible";
		gebid( "filter_param3_name" ).innerHTML = "<?php echo $wl["default"]["yellow blue"]; ?>:";
		gebid( "filter_param3_input" ).style.visibility="visible";
		filterparam1 = 0;
		filterparam2 = 0;
		filterparam3 = 0;
		filterparam2slider.on('change', function(){              
      document.getElementById('filter_param2_wert').value= this.getValue()*2-100;	   
      filterparam2 = this.getValue()*2-100;
  	});
    filterparam2slider.on('drag', function(){
      document.getElementById('filter_param2_wert').value= this.getValue()*2-100;  
      filterparam2 = this.getValue()*2-100;   
  	});
  	filterparam2slider.on('dragend', function(){
      document.getElementById('filter_param2_wert').value= this.getValue()*2-100;  
      filterparam2 = this.getValue()*2-100; 
      vorschaustart();   	  
  	});
  	filterparam1slider.setValue(50);
		filterparam2slider.setValue(50);
		filterparam3slider.setValue(50);
	}
	vorschaustart()
}

function dofilter() {
	
		var parameter = gebid( "welchereff" ).value;
		if ( gebid( "filter_param1_wert" ) ) parameter+= ", " + gebid( "filter_param1_wert" ).value;
		if ( gebid( "filter_param2_wert" ) ) parameter+= ", " + gebid( "filter_param2_wert" ).value;
		if ( gebid( "filter_param3_wert" ) ) parameter+= ", " + gebid( "filter_param3_wert" ).value;
		historypos++;
		historyfunc[historypos]='Filter';
		historyfunc.slice(historypos+1,historyfunc.length-historypos);
		deactivateforwardbutton();
		activatebackbutton();
		window.frames.bildframe.dofunction( historypos, "filter", parameter );
		

	return false;
}



fontfarbe = 'ffffff';
function dotext() {
	
	var parameter = fontcombo.value + ", " + fontfarbe + ", " + gebid( "fontsize" ).value + ", " + gebid( "text_dertext" ).value;
	historypos++;
	historyfunc[historypos]='Text';
	historyfunc.slice(historypos+1,historyfunc.length-historypos);
	deactivateforwardbutton();
	activatebackbutton();
	window.frames.bildframe.dofunction( historypos, "schrift", parameter );
	return false;
}

function dowatermark() {
	
	var parameter = watermarkcombo.getValue()+", "+gebid( "watermarkalpha_wert").value;
	historypos++;
	historyfunc[historypos]='Watermark';
	historyfunc.slice(historypos+1,historyfunc.length-historypos);
	deactivateforwardbutton();
	activatebackbutton();
	window.frames.bildframe.dofunction( historypos, "watermark", parameter );
	return false;
}

function setzefarbe( diefarbe ) {
	fontfarbe = diefarbe;
	if ( diefarbe != "" ) gebid( "farbfeld" ).style.backgroundColor = "#"+diefarbe;
}

function aktualisiereschrift() {

	window.frames.bildframe.zeigeschrift( gebid( "text_dertext" ).value, fontcombo.value, "#"+fontfarbe, gebid( "fontsize" ).value );
}

function updatewatermark() {
	window.frames.bildframe.zeigewatermark( watermarkcombo.getValue(), gebid( "watermarkalpha_wert").value );
}


</script>
</head>

<body onload="iframedoc=document.getElementById('bildframe').contentDocument;">
<div id="zoom-area"><span style="float:left;margin:4px">Zoom:&nbsp;&nbsp;</span> <span id="zoom-slider" style="float:left;"></span><span id="zoom-value" style="float:left;margin:4px">100%</span><span id="aie-logo" style="float:right;margin:1px 4px 0 4px"><a href="#" onclick="showabout()"><img title="AIE (Ajax Image Editor)" alt="AIE (Ajax Image Editor)" src="img/aiesmall.png" /></a></span></div>
<iframe width="100%" height="100%" id="bildframe" name="bildframe" src="bild.html" class="iframeborder" frameborder="0"></iframe>

<div id="tools-tabs">

  
  <div id="resizereiter">
  	<form name="resize_form" id="resize_form" method="post" action="" onsubmit="return doresize();">
  	<table width="100%" cellspacing="5" border="0" cellpadding="3" style="margin:5px;">
  		<tr>
  			<td width="1%"><?php echo $wl["default"]["width"]; ?>:</td>
  			<td width="1%"><input type="text" class="input" name="resize_width" id="resize_width" maxlength="4" onkeyup="resize_setzewerte( this )" style="width:30px;" /></td>
  			<td width="100%" style="text-align:left;">
  				<select name="ppwidth" class="input" id="resize_ppwidth" onchange="resize_changemasseinheit( this )">
  					<option value="pixel" selected="selected">Pixel</option>
  					<option value="prozent"><?php echo $wl["default"]["percent"]; ?></option>
  				</select>
  			</td>
  		</tr>
  		<tr>
  			<td><?php echo $wl["default"]["height"]; ?>:</td>
  			<td><input type="text" class="input" name="resize_height" id="resize_height" maxlength="4" onkeyup="resize_setzewerte( this )" style="width:30px;" /></td>
  			<td style="text-align:left;">
  				<select name="ppheight" class="input" id="resize_ppheight" onchange="resize_changemasseinheit( this )">
  					<option value="pixel" selected="selected">Pixel</option>
  					<option value="prozent"><?php echo $wl["default"]["percent"]; ?></option>
  				</select>
  			</td>
  		</tr>
  		<tr>
  			<td>&nbsp;</td>
  			<td colspan="2" style="text-align:left;"><input name="proportionen" type="checkbox" id="resize_proportionen" value="proportionen" checked="checked" onfocus="if(this.blur)this.blur()" /></td>
  		</tr>
  	</table>
  	</form>
  </div>
												
	<div id="cropreiter">
  	<form name="crop_form" id="crop_form" method="post" action="" onsubmit="return docrop();">
  	<table width="100%" cellspacing="5" border="0" cellpadding="3" style="margin:5px;">
  		<tr>
  			<td width="1%"><?php echo $wl["default"]["width"]; ?>:</td>
  			<td width="1%"><input type="text" class="input" name="crop_width" id="crop_width" maxlength="4" onchange="setzecropgroesse();" style="width:30px;" /></td>
  			<td width="100%" style="text-align:left;">Pixel</td>
  		</tr>
  		<tr>
  			<td><?php echo $wl["default"]["height"]; ?>:</td>
  			<td><input type="text" class="input" name="crop_height" id="crop_height" maxlength="4" onchange="setzecropgroesse();" style="width:30px;" /></td>
  			<td style="text-align:left;">Pixel</td>
  		</tr>
  	</table>
  	</form>
  </div>
												
	<div id="rotatereiter">
		<form name="rotate_form" id="rotate_form" method="post" action="" onsubmit="return dorotate();">
		<table width="380" cellspacing="5" border="0" cellpadding="3" style="margin:5px;">
			<tr>
				<td width="1%"><input name="aktion" type="radio" id="aktiona" value="rotieren" checked="checked" onfocus="if(this.blur)this.blur()" /></td>
				<td width="1%"><label for="aktiona">Rotieren:</label></td>
				<td style="text-align:left;" nowrap="nowrap">
					<label for="aktiona" nowrap="nowrap">
            <span style="float:left;"><img id="rotatel" src="img/rotate.png" class="werkzeug" alt="<?php echo $wl["default"]["to the left"]; ?>" /></span>
            <span style="float:left;"><span id="rotateslider" style="float:left;"></span></span>
            <span style="float:left;"><img id="rotater" src="img/rotater.png" class="werkzeugaktiv" alt="<?php echo $wl["default"]["to the right"]; ?>" /></span>
          </label>
        </td>				
			</tr>
			<tr>
				<td><input name="aktion" id="aktionb" type="radio" value="spiegeln" onfocus="if(this.blur)this.blur()" /></td>
				<td><label for="aktionb"><?php echo $wl["default"]["reflect"]; ?>:</label></td>
				<td style="text-align:left;">
					<label for="aktionb">
					<select name="rospiegeln" class="input" id="rospiegeln" style="width:120px;">
						<option value="flip" selected="selected"><?php echo $wl["default"]["horizontal"]; ?></option>
						<option value="flop"><?php echo $wl["default"]["vertical"]; ?></option>
					</select>
					</label>
				</td>
			</tr>
		</table>
		</form>
	</div>
												
  <div id="filterreiter">
  	<form name="filter_form" id="filter_form" method="post" action="" onsubmit="return dofilter();">
  <table width="100%" cellspacing="5" border="0" cellpadding="3" style="margin:5px;">
  		<tr>
  			<td style="vertical-align:top;">
  				<table width="100%"  border="0" cellspacing="3" cellpadding="3" style="margin:5px;border:0;">
  					<tr>
  						<td><?php echo $wl["default"]["filter"]; ?>:</td>
  						<td style="text-align:left;">
  							<select name="welchereff" class="input" id="welchereff" style="width:300px;" onchange="filteraendern();" >
  								<option value="blur"><?php echo $wl["default"]["blur"]; ?></option>
  								<option value="contrast"><?php echo $wl["default"]["brightness contrast"]; ?></option>
  								<option value="normalize"><?php echo $wl["default"]["correction"]; ?></option>
  								<option value="balance"><?php echo $wl["default"]["balance"]; ?></option>
  								<option value="invert"><?php echo $wl["default"]["invert"]; ?></option>
  							</select>
  						</td>
  					</tr>
  					<tr>
  						<td id="filter_param1_name"><?php echo $wl["default"]["rate"]; ?>:</td>
  						<td id="filter_param1_input" style="text-align:left;"><div id="filter_param1" style="float:left"></div><input name="filter_param1_wert" class="input" type="text" id="filter_param1_wert" value="0" size="4" maxlength="4" style="width:40px; margin-left:5px;" /></td>
  					</tr>
  					<tr>
  						<td id="filter_param2_name">&nbsp;</td>
  						<td id="filter_param2_input" style="text-align:left;"><div id="filter_param2" style="float:left"></div><input name="filter_param2_wert" class="input" type="text" id="filter_param2_wert" value="0" size="4" maxlength="4" style="width:40px; margin-left:5px;" /></td>
  					</tr>
  					<tr>
  						<td id="filter_param3_name">&nbsp;</td>
  						<td id="filter_param3_input" style="text-align:left;"><div id="filter_param3" style="float:left"></div><input name="filter_param3_wert" class="input" type="text" id="filter_param3_wert" value="0" size="4" maxlength="4" style="width:40px; margin-left:5px;" /></td>
  					</tr>
  				</table>  				
  			</td>
  			<td valign="top" width="1%">
  				<strong><?php echo $wl["default"]["preview"]; ?></strong>
  				<div id="vorschaufenster" style="position:relative; width:110px; height:110px; z-index:1; clip:(0,120,120,0);overflow:hidden; cursor:move;" onmousedown="vorschauziehenstart()" >
  					<div id="vorschaulayerganzesbild" style="position:absolute; left:0px; top:0px; width:1px; height:1px; z-index:1; " ><img id="vorschauganzesbild" src="bildserver.php?img=<?php echo $imagetempname; ?>" alt="" /></div>
  					<div id="vorschaulayer" style="position:absolute; left:-600px; top:-600px; width:120px; height:120px; z-index:2; overflow:hidden " ><img id="vorschaubild" src="img/filter.gif" width="120" height="120" alt=""  /></div>
  				</div>
  			</td>
  		</tr>
  	</table>
  	</form>
  </div>
												
	<div id="textreiter">
		
		
		<form name="text_form" id="text_form" method="post" action="" onsubmit="return dotext();">

		<table width="400"  border="0" cellspacing="5" cellpadding="3" style="margin:5px;border:0;">
			<tr>
				<td width="1%"><?php echo $wl["default"]["text"]; ?>:</td>
				<td width="1%" style="text-align:left;"><input type="text" class="input" id="text_dertext" name="text_dertext" value="AIE - Ajax Image Editor" style="width:217px;" /></td>
				<td width="100%">&nbsp;</td>
				<td width="1%" style="text-align:left;"><?php echo $wl["default"]["size"]; ?>:</td>
				<td width="1%" style="text-align:left;"><input type="text" class="input" id="fontsize" maxlength="2" value="14" style="width:15px;" /></td>
			</tr>
			<tr>
				<td><?php echo $wl["default"]["font"]; ?>:</td>
				<td style="text-align:left;"><input type="text" id="fontselectorinp" style="width:200px;" /></td>
				<td>&nbsp;</td>
				<td style="text-align:left;"><?php echo $wl["default"]["color"]; ?>:</td>
				<td style="text-align:left;"><div id="farbfeld" style="background-color:#fff;height:22px;width:22px;border:1px solid #000;">&nbsp;</div></td>
			</tr>
		</table>
		</form>
	</div>
												
	<div id="markreiter">
	<form name="watermark_form" id="watermark_form" method="post" action="">
	<table width="400" border="0" cellspacing="5" cellpadding="3" style="margin:5px;border:0;">
		<tr>
			<td width="1%">Wasserzeichen:</td>
			<td width="1%"><input type="text" id="watermarkselectorinp" style="width:200px;" /></td>
		</tr>
		<tr>
			<td width="1%">Alpha(%):</td>
			<td width="1%"><div id="watermarkalphainp" style="float:left"></div><input name="watermarkalpha_wert" class="input" type="text" id="watermarkalpha_wert" value="100" size="4" maxlength="4" style="width:40px; margin-left:5px;" /></td>
		</tr>
	</table>
	</form>
	</div>
  
  
   

  
  
  
  
  
  </div>
</body>
</html>
