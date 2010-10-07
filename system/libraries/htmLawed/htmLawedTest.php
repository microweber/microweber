<?php

/*
htmLawedTest.php, 23 April 2009
htmLawed 1.1.8, 23 April 2009
Copyright Santosh Patnaik
GPL v3 license
A PHP Labware internal utility - http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed

Test htmLawed; user provides text input; input and processed input are shown as highlighted code and rendered HTML; also shown are execution time and peak memory usage
*/

// config
$_errs = 0; // display PHP errors
$_limit = 8000; // input character limit

// more config
$_hlimit = 1000; // input character limit for showing hexdumps
$_hilite = 1; // 0 turns off slow Javascript-based code-highlighting, e.g., if $_limit is high
$_w3c_validate = 1; // 1 to show buttons to send input/output to w3c validator
$_sid = 'sid'; // session name; alphanum.
$_slife = 30; // session life in min.

// errors
error_reporting(E_ALL | (defined('E_STRICT') ? E_STRICT : 1));
ini_set('display_errors', $_errs);

// session
session_name($_sid);
session_cache_limiter('private');
session_cache_expire($_slife);
ini_set('session.gc_maxlifetime', $_slife * 60);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_lifetime', 0);
session_start();
if(!isset($_SESSION['token'])){
 $_SESSION['token'] = md5(uniqid(rand(), 1));
}

// slashes
if(get_magic_quotes_gpc()){
 foreach($_POST as $k => $v){
  $_POST[$k] = stripslashes($v);
 }
 ini_set('magic_quotes_gpc', 0);
}
set_magic_quotes_runtime(0);

$_POST['enc'] = (isset($_POST['enc']) and preg_match('`^[-\w]+$`', $_POST['enc'])) ? $_POST['enc'] : 'utf-8';

// token for anti-CSRF
if(count($_POST)){
 if((empty($_GET['pre']) and ((!empty($_POST['token']) and !empty($_SESSION['token']) and $_POST['token'] != $_SESSION['token']) or empty($_POST[$_sid]) or $_POST[$_sid] != session_id() or empty($_COOKIE[$_sid]) or $_COOKIE[$_sid] != session_id())) or ($_POST[$_sid] != session_id())){
  $_POST = array('enc'=>'utf-8');
 }
}
if(empty($_GET['pre'])){
 $_SESSION['token'] = md5(uniqid(rand(), 1));
 $token = $_SESSION['token'];
 session_regenerate_id(1);
}

// compress
if(function_exists('gzencode') && isset($_SERVER['HTTP_ACCEPT_ENCODING']) && preg_match('`gzip|deflate`i', $_SERVER['HTTP_ACCEPT_ENCODING']) && !ini_get('zlib.output_compression')){
 ob_start('ob_gzhandler');
}

// HTM for unprocessed
if(isset($_POST['inputH'])){
 echo '<html><head><title>htmLawed test: HTML view of unprocessed input</title></head><body style="margin:0; padding: 0;"><p style="background-color: black; color: white; padding: 2px;">&nbsp; Rendering of unprocessed input without an HTML doctype or charset declaration &nbsp; &nbsp; <small><a style="color: white; text-decoration: none;" href="1" onclick="javascript:window.close(this); return false;">close window</a> | <a style="color: white; text-decoration: none;" href="htmLawedTest.php" onclick="javascript: window.open(\'htmLawedTest.php\', \'hlmain\'); window.close(this); return false;">htmLawed test page</a></small></p><div>', $_POST['inputH'], '</div></body></html>';
 exit;
}

// main
$_POST['text'] = isset($_POST['text']) ? $_POST['text'] : 'text to process; < '. $_limit. ' characters'. ($_hlimit ? ' (for binary hexdump view, < '. $_hlimit. ')' : '');
$do = (!empty($_POST[$_sid]) && isset($_POST['text'][0]) && !isset($_POST['text'][$_limit])) ? 1 : 0;
$limit_exceeded = isset($_POST['text'][$_limit]) ? 1 : 0;
$pre_mem = memory_get_usage();
$validation = (!empty($_POST[$_sid]) and isset($_POST['w3c_validate'][0])) ? 1 : 0;
include './htmLawed.php';

function format($t){
 $t = "\n". str_replace(array("\t", "\r\n", "\r", '&', '<', '>', "\n"), array('    ', "\n", "\n", '&amp;', '&lt;', '&gt;', "<span class=\"newline\">&#172;</span><br />\n"), $t);
 return str_replace(array('<br />', "\n ", '  '), array("\n<br />\n", "\n&nbsp;", ' &nbsp;'), $t);
}

function hexdump($d){
// Mainly by Aidan Lister <aidan@php.net>, Peter Waller <iridum@php.net>
 $hexi = '';
 $ascii = '';
 ob_start();
 echo '<pre>';
 $offset = 0;
 $len = strlen($d);
 for($i=$j=0; $i<$len; $i++)
 {
  // Convert to hexidecimal
  $hexi .= sprintf("%02X ", ord($d[$i]));
  // Replace non-viewable bytes with '.'
  if(ord($d[$i]) >= 32){
   $ascii .= htmlspecialchars($d[$i]);
  }else{
   $ascii .= '.';
  } 
  // Add extra column spacing
  if($j == 7){
   $hexi .= ' ';
   $ascii .= '  ';
  }
  // Add row
  if(++$j == 16 || $i == $len-1){
   // Join the hexi / ascii output
   echo sprintf("%04X   %-49s   %s", $offset, $hexi, $ascii);   
   // Reset vars
   $hexi = $ascii = '';
   $offset += 16;
   $j = 0;  
   // Add newline   
   if ($i !== $len-1){
    echo "\n";
   }
  }
 }
 echo '</pre>';
 $o = ob_get_contents();
 ob_end_clean();
 return $o;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo htmlspecialchars($_POST['enc']); ?>" />
<meta name="description" content="htmLawed <?php echo hl_version();?> test page" />
<style type="text/css"><!--/*--><![CDATA[/*><!--*/
a, a.resizer{text-decoration:none;}
a:hover, a.resizer:hover{color:red;}
a.resizer{color:green; float:right;}
body{background-color:#efefef;}
body, button, div, html, input, p{font-size:13px; font-family:'Lucida grande', Verdana, Arial, Helvetica, sans-serif;}
button, input{font-size: 85%;}
div.help{border-top: 1px dotted gray; margin-top: 15px; padding-top: 15px; color:#999999;}
#inputC, #inputD, #inputF, #inputR, #outputD, #outputF, #outputH, #outputR, #settingF{display:block;}
#inputC, #settingF{background-color:white; border:1px gray solid; padding:3px;}
#inputC li{margin: 0; padding: 0;}
#inputC ul{margin: 0; padding: 0; margin-left: 14px;}
#inputC input{margin: 0; margin-left: 2px; margin-right: 2px; padding: 1px; vertical-align: middle;}
#inputD{overflow:auto; background-color:#ffff99; border:1px #cc9966 solid; padding:3px;}
#inputR{overflow:auto; background-color:#ffffcc; border:1px #ffcc99 solid; padding:3px;}
#inputC, #settingF, #inputD, #inputR, #outputD, #outputR, textarea{font-size:100%; font-family:'Bitstream vera sans mono', 'courier new', 'courier', monospace;}
#outputD{overflow:auto; background-color: #99ffcc; border:1px #66cc99 solid; padding:3px;} 
#outputH{overflow:auto; background-color:white; padding:3px; border:1px #dcdcdc solid;} 
#outputR{overflow:auto; background-color: #ccffcc; border:1px #99cc99 solid; padding:3px;} 
span.cmtcdata{color: orange;}
span.ctag{color:red;}
span.ent{border-bottom:1px dotted #999999;}
span.etag{color:purple;}
span.help{color:#999999;}
span.newline{color:#dcdcdc;}
span.notice{color:green;}
span.otag{color:blue;}
#topmost{margin:auto; width:98%;}
/*]]>*/--></style>
<script type="text/javascript"><!--//--><![CDATA[//><!-- 
window.name = 'hlmain';
function hl(i){
 <?php if(!$_hilite){echo 'return;'; }?>
 var e = document.getElementById(i);
 if(!e){return;}
 run(e, '</[a-z1-6]+>', 'ctag');
 run(e, '<[a-z]+(?:[^>]*)/>', 'etag');
 run(e, '<[a-z1-6]+(?:[^>]*)>', 'otag');
 run(e, '&[#a-z0-9]+;', 'ent');
 run(e, '<!(?:(?:--(?:.|\n)*?--)|(?:\\[CDATA\\[(?:.|\n)*?\\]\\]))>', 'cmtcdata');
}
function sndProc(){
 var f = document.getElementById('testform');
 if(!f){return;}
 var e = document.createElement('input');
 e.type = 'hidden';
 e.name = '<?php echo htmlspecialchars($_sid); ?>';
 e.id = '<?php echo htmlspecialchars($_sid); ?>';
 e.value = readCookie('<?php echo htmlspecialchars($_sid); ?>');
 f.appendChild(e);
 f.submit();
}
function readCookie(n){
 var ne = n + '=';
 var ca = document.cookie.split(';');
 for(var i=0;i < ca.length;i++){
  var c = ca[i];
  while(c.charAt(0)==' '){
   c = c.substring(1,c.length);
  }
  if(c.indexOf(ne) == 0){
   return c.substring(ne.length,c.length);
  }
 }
 return null;
}
function run(e, q, c){
 var q = new RegExp(q);
 if(e.firstChild == null){
  var m = q.exec(e.data);
  if(m){
   var v = m[0];
   var k2 = e.splitText(m.index);
   var k3 = k2.splitText(v.length);
   var s = e.ownerDocument.createElement('span');
   e.parentNode.replaceChild(s, k2);
   s.className = c; s.appendChild(k2);
  }
 }
 for(var k = e.firstChild; k != null; k = k.nextSibling){
  if(k.nodeType == 3){     
   var m = q.exec(k.data);
   if(m){
    var v = m[0];
    var k2 = k.splitText(m.index);
    var k3 = k2.splitText(v.length);
    var s = k.ownerDocument.createElement('span');
    k.parentNode.replaceChild(s, k2);
    s.className = c; s.appendChild(k2);
   }
  }
  else if(c == 'ent' && k.nodeType == 1){
   var d = k.firstChild;
   if(d){
    var m = q.exec(d.data);
    if(m){
     var v = m[0];
     var d2 = d.splitText(m.index);
     var d3 = d2.splitText(v.length);
     var s = d.ownerDocument.createElement('span');
     d.parentNode.replaceChild(s, d2);
     s.className = c; s.appendChild(d2);
    }
   }
  }
 }
}
function toggle(i){  
 var e = document.getElementById(i);  
 if(!e){return;}
 if(e.style){
  var a = e.style.display;   
  if(a == 'block'){e.style.display = 'none'; return;}
  if(a == 'none'){e.style.display = 'block';}
  else{e.style.display = 'none';}
  return;   
 }
 var a = e.visibility;
 if(a == 'hidden'){e.visibility = 'show'; return;}
 if(a == 'show'){e.visibility = 'hidden';}
}
function sndUnproc(){
 var i = document.getElementById('text');
 if(!i){return;}
 i = i.value;
 i = i.replace(/>/g, '&gt;');
 i = i.replace(/</g, '&lt;');
 i = i.replace(/"/g, '&quot;');
 var w = window.open('htmLawedTest.php?pre=1', 'hlprehtm');
 var f = document.createElement('form');
 f.enctype = 'application/x-www-form-urlencoded';
 f.method = 'post';
 f.acceptCharset = '<?php echo htmlspecialchars($_POST['enc']); ?>';
 if(f.style){f.style.display = 'none';}
 else{f.visibility = 'hidden';}
 f.innerHTML = '<p style="display:none;"><input style="display:none;" type="hidden" name="token" id="token" value="<?php echo $token; ?>" /><input style="display:none;" type="hidden" name="<?php echo htmlspecialchars($_sid); ?>" id="<?php echo htmlspecialchars($_sid); ?>" value="' + readCookie('<?php echo htmlspecialchars($_sid); ?>') + '" /><input style="display:none;" type="hidden" name="inputH" id="inputH" value="'+ i+ '" /></p>';
 f.action = 'htmLawedTest.php?pre=1';
 f.target = 'hlprehtm';
 f.method = 'post';
 var b = document.getElementsByTagName('body')[0];
 b.appendChild(f);
 f.submit();
 w.focus;
}
function sndValidn(id, type){
 var i = document.getElementById(id);
 if(!i){return;}
 i = i.value;
 i = i.replace(/>/g, '&gt;');
 i = i.replace(/</g, '&lt;');
 i = i.replace(/"/g, '&quot;');
 var w = window.open('http://validator.w3.org/check', 'validate'+id+type);
 var f = document.createElement('form');
 f.enctype = 'application/x-www-form-urlencoded';
 f.method = 'post';
 f.acceptCharset = '<?php echo htmlspecialchars($_POST['enc']); ?>';
 if(f.style){f.style.display = 'none';}
 else{f.visibility = 'hidden';}
 f.innerHTML = '<p style="display:none;"><input style="display:none;" type="hidden" name="fragment" id="fragment" value="'+ i+ '" /><input style="display:none;" type="hidden" name="prefill" id="prefill" value="1" /><input style="display:none;" type="hidden" name="prefill_doctype" id="prefill_doctype" value="'+ type+ '" /><input style="display:none;" type="hidden" name="group" id="group" value="1" /><input type="hidden" name="ss" id="ss" value="1" /></p>';
 f.action = 'http://validator.w3.org/check';
 f.target = 'validate'+id+type;
 var b = document.getElementsByTagName('body')[0];
 b.appendChild(f);
 f.submit();
 w.focus;
}
tRs = {
 formEl: null,
 resizeClass: 'textarea',
 adEv: function(t,ev,fn){
  if(typeof document.addEventListener != 'undefined'){
   t.addEventListener(ev,fn,false);
  }else{
   t.attachEvent('on' + ev, fn);
  }
 },
 rmEv: function(t,ev,fn){
  if(typeof document.removeEventListener != 'undefined'){
   t.removeEventListener(ev,fn,false);
  }else
  {
   t.detachEvent('on' + ev, fn);
  }
 },
 adBtn: function(){
  var textareas = document.getElementsByTagName('textarea');
  for(var i = 0; i < textareas.length; i++){	
   var txtclass=textareas[i].className;
   if(txtclass.substring(0,tRs.resizeClass.length)==tRs.resizeClass ||
   txtclass.substring(txtclass.length -tRs.resizeClass.length)==tRs.resizeClass){
    var a = document.createElement('a');
    a.appendChild(document.createTextNode("\u2195"));
    a.style.cursor = 'n-resize';
    a.className= 'resizer';
    a.title = 'click-drag to resize'
    tRs.adEv(a, 'mousedown', tRs.initResize);
    textareas[i].parentNode.appendChild(a);
   }	
  }
 },
 initResize: function(event){
  if(typeof event == 'undefined'){
   event = window.event;
  }
  if(event.srcElement){
   var target = event.srcElement.previousSibling;
  }else{
   var target = event.target.previousSibling;
  }
  if(target.nodeName.toLowerCase() == 'textarea' || (target.nodeName.toLowerCase() == 'input' && target.type == 'text')){
   tRs.formEl = target;
   tRs.formEl.startHeight = tRs.formEl.clientHeight;
   tRs.formEl.startY = event.clientY;
   tRs.adEv(document, 'mousemove', tRs.resize);
   tRs.adEv(document, 'mouseup', tRs.stopResize);
   tRs.formEl.parentNode.style.cursor = 'n-resize';
   tRs.formEl.style.cursor = 'n-resize';
   try{
    event.preventDefault();
   }catch(e){
   }
  }
 },
 resize: function(event){
  if(typeof event == 'undefined'){
   event = window.event;
  }
	if(tRs.formEl.nodeName.toLowerCase() == 'textarea'){
   tRs.formEl.style.height = event.clientY - tRs.formEl.startY + tRs.formEl.startHeight + 'px';
  }
 },
 stopResize: function(event){
  tRs.rmEv(document, 'mousedown', tRs.initResize);
  tRs.rmEv(document, 'mousemove', tRs.resize);
  tRs.formEl.style.cursor = 'text';
  tRs.formEl.parentNode.style.cursor = 'auto';
  return false;
 }
};
tRs.adEv(window, 'load', tRs.adBtn);
//--><!]]></script>
<title>htmLawed (<?php echo hl_version();?>) test</title>
</head>
<body>
<div id="topmost">

<h5 style="float: left; display: inline; margin-top: 0; margin-bottom: 5px;"><a href="http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/index.php" title="htmLawed home">HTM<big><big>L</big></big>AWED</a> <?php echo hl_version();?> <a href="htmLawedTest.php" title="test home">TEST</a></h5>
<span style="float: right;" class="help"><a href="htmLawed_README.htm"><span class="notice">htm</span></a> / <a href="htmLawed_README.txt"><span class="notice">txt</span></a> documentation</span><br style="clear:both;" />

<a href="htmLawedTest.php" title="[toggle visibility] type or copy-paste" onclick="javascript:toggle('inputF'); return false;"><span class="notice">Input &raquo;</span> <span class="help" title="limit lower with multibyte characters<?php echo (($_hlimit < $_limit && $_hlimit)? '; limit is '. $_hlimit. ' for viewing binaries' : ''); ?>"><small>(max. <?php echo htmlspecialchars($_limit);?> chars)</small></span></a>

<form id="testform" name="testform" action="htmLawedTest.php" method="post" accept-charset="<?php echo htmlspecialchars($_POST['enc']); ?>" style="padding:0; margin: 0; display:inline;">

<div id="inputF" style="display: block;">

<input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
<div><textarea id="text" class="textarea" name="text" rows="5" cols="100" style="width: 100%;"><?php echo htmlspecialchars($_POST['text']);?></textarea></div>
<input type="submit" id="submitF" name="submitF" value="Process" style="float:left;" title="filter using htmLawed" onclick="javascript: sndProc(); return false;" onkeypress="javascript: sndProc(); return false;" />

<?php
if($do){
 if($validation){
  echo '<input type="hidden" value="1" name="w3c_validate" id="w3c_validate" />';
 }
?>
 
<button type="button" title="rendered as web-page without a doctype or charset declaration" style="float: right;" onclick="javascript: sndUnproc(); return false;" onkeypress="javascript: sndUnproc(); return false;">View unprocessed</button>
<button type="button" onclick="javascript:document.getElementById('text').focus();document.getElementById('text').select()" title="select all to copy" style="float:right;">Select all</button>

<?php
if($_w3c_validate && $validation){
?>
  
<button type="button" title="HTML 4.01 W3C online validation" style="float: right;" onclick="javascript: sndValidn('text', 'html401'); return false;" onkeypress="javascript: sndValidn('text', 'html401'); return false;">Check HTML</button>
<button type="button" title="XHTML 1.1 W3C online validation" style="float: right;" onclick="javascript: sndValidn('text', 'xhtml110'); return false;" onkeypress="javascript: sndValidn('text', 'xhtml110'); return false;">Check XHTML</button>
  
<?php
 }
}
else{
 if($_w3c_validate){
  echo '<span style="float: right;" class="help" title="for direct submission of input or output code to W3C validator for (X)HTML validation"><span style="font-size: 85%;">&nbsp;Validator tools: </span><input type="checkbox" value="1" name="w3c_validate" id="w3c_validate" style="vertical-align: middle;"', ($validation ? ' checked="checked"' : ''), ' /></span>';
 }
}
?>

<span style="float:right;" class="help"><span style="font-size: 85%;">Encoding: </span><input type="text" size="8" id="enc" name="enc" style="vertical-align: middle;" value="<?php echo htmlspecialchars($_POST['enc']); ?>" title="IANA-recognized name of the input character-set; can be multiple ;- or space-separated values; may not work in some browsers" /></span>

</div>
<br style="clear:both;" />

<?php
if($limit_exceeded){
 echo '<br /><strong>Input text is too long!</strong><br />';
}
?>

<br />

<a href="htmLawedTest.php" title="[toggle visibility] htmLawed configuration" onclick="javascript:toggle('inputC'); return false;"><span class="notice">Settings &raquo;</span></a>

<div id="inputC" style="display: none;">
<table summary="none">
<tr>
<td><span class="help" title="$config argument">Config:</span></td>
<td><ul>
 
<?php
$cfg = array(
'abs_url'=>array('3', '0', 'absolute/relative URL conversion', '-1'),
'and_mark'=>array('2', '0', 'mark original <em>&amp;</em> chars', '0', 'd'=>1), // 'd' to disable
'anti_link_spam'=>array('1', '0', 'modify <em>href</em> values as an anti-link spam measure', '0', array(array('30', '1', '', 'regex for extra <em>rel</em>'), array('30', '2', '', 'regex for no <em>href</em>'))),
'anti_mail_spam'=>array('1', '0', 'replace <em>@</em> in <em>mailto:</em> URLs', '0', '8', 'NO@SPAM', 'replacement'),
'balance'=>array('2', '1', 'fix nestings and balance tags', '0'),
'base_url'=>array('', '', 'base URL', '25'),
'cdata'=>array('4', 'nil', 'allow <em>CDATA</em> sections', 'nil'),
'clean_ms_char'=>array('3', '0', 'replace bad characters introduced by Microsoft apps. like <em>Word</em>', '0'),
'comment'=>array('4', 'nil', 'allow HTML comments', 'nil'),
'css_expression'=>array('2', 'nil', 'allow dynamic expressions in CSS style properties', 'nil'),
'deny_attribute'=>array('1', '0', 'denied attributes', '0', '50', '', 'these'),
'elements'=>array('', '', 'allowed elements', '50'),
'hexdec_entity'=>array('3', '1', 'convert hexadecimal numeric entities to decimal ones, or vice versa', '0'),
'hook'=>array('', '', 'name of hook function', '25'),
'hook_tag'=>array('', '', 'name of custom function to further check attribute values', '25'),
'keep_bad'=>array('7', '6', 'keep, or remove <em>bad</em> tag content', '0'),
'lc_std_val'=>array('2', '1', 'lower-case std. attribute values like <em>radio</em>', '0'),
'make_tag_strict'=>array('3', 'nil', 'transform deprecated elements', 'nil'),
'named_entity'=>array('2', '1', 'allow named entities, or convert numeric ones', '0'),
'no_deprecated_attr'=>array('3', '1', 'allow deprecated attributes, or transform them', '0'),
'parent'=>array('', 'div', 'name of parent element', '25'),
'safe'=>array('2', '0', 'for most <em>safe</em> HTML', '0'),
'schemes'=>array('', 'href: aim, feed, file, ftp, gopher, http, https, irc, mailto, news, nntp, sftp, ssh, telnet; *:file, http, https', 'allowed URL protocols', '50'),
'show_setting'=>array('', 'htmLawed_setting', 'variable name to record <em>finalized</em> htmLawed settings', '25', 'd'=>1),
'style_pass'=>array('2', 'nil', 'do not look at <em>style</em> attribute values', 'nil'),
'tidy'=>array('3', '0', 'beautify/compact', '-1', '8', '1t1', 'format'),
'unique_ids'=>array('2', '1', 'unique <em>id</em> values', '0', '8', 'my_', 'prefix'),
'valid_xhtml'=>array('2', 'nil', 'auto-set various parameters for most valid XHTML', 'nil'),
'xml:lang'=>array('3', 'nil', 'auto-add <em>xml:lang</em> attribute', '0'),
);
foreach($cfg as $k=>$v){
 echo '<li>', $k, ': ';
 if(!empty($v[0])){ // input radio
  $j = $v[3];
  for($i = $j-1; ++$i < $v[0]+$v[3];++$j){
   echo '<input type="radio" name="h', $k, '" value="', $i, '"', (!isset($_POST['h'. $k]) ? ($v[1] == $i ? ' checked="checked"' : '') : ($_POST['h'. $k] == $i ? ' checked="checked"' : '')), (isset($v['d']) ? ' disabled="disabled"' : ''), ' />', $i, ' ';
  }
  if($v[1] == 'nil'){
   echo '<input type="radio" name="h', $k, '" value="nil"', ((!isset($_POST['h'. $k]) or $_POST['h'. $k] == 'nil') ? ' checked="checked"' : ''), (isset($v['d']) ? ' disabled="disabled"' : ''), ' />not set ';
  }
  if(!empty($v[4])){ // + input text box
   echo '<input type="radio" name="h', $k, '" value="', $j, '"', (((isset($_POST['h'. $k]) && $_POST['h'. $k] == $j) or (!isset($_POST['h'. $k]) && $j == $v[1])) ? ' checked="checked"' : ''), (isset($v['d']) ? ' disabled="disabled"' : ''), ' />';
   if(!is_array($v[4])){
    echo $v[6], ': <input type="text" size="', $v[4], '" name="h', $k. $j, '" value="', htmlspecialchars(isset($_POST['h'. $k. $j][0]) ? $_POST['h'. $k. $j] : $v[5]), '"', (isset($v['d']) ? ' disabled="disabled"' : ''), ' />';
   }
   else{
    foreach($v[4] as $z){
     echo ' ', $z[3], ': <input type="text" size="', $z[0], '" name="h', $k. $j. $z[1], '" value="', htmlspecialchars(isset($_POST['h'. $k. $j. $z[1]][0]) ? $_POST['h'. $k. $j. $z[1]] : $z[2]), '"', (isset($v['d']) ? ' disabled="disabled"' : ''), ' />';
    }    
   }
  }
 }
 elseif(ctype_digit($v[3])){ // input text
  echo '<input type="text" size="', $v[3], '" name="h', $k, '" value="', htmlspecialchars(isset($_POST['h'. $k][0]) ? $_POST['h'. $k] : $v[1]), '"', (isset($v['d']) ? ' disabled="disabled"' : ''), ' />';  
 }
 else{} // text-area
 echo ' <span class="help">', $v[2], '</span></li>';
}
echo '</ul></td></tr><tr><td><span style="vertical-align: top;" class="help" title="$spec argument: element-specific attribute rules">Spec:</span></td><td><textarea name="spec" id="spec" cols="70" rows="3" style="width:80%;">', htmlspecialchars((isset($_POST['spec']) ? $_POST['spec'] : '')), '</textarea></td></tr></table>';
?>

</div>
</form>

<?php
if($do){
 $cfg = array();
 foreach($_POST as $k=>$v){
  if($k[0] == 'h' && $v != 'nil'){
   $cfg[substr($k, 1)] = $v;
  }
 }

 if($cfg['anti_link_spam'] && (!empty($cfg['anti_link_spam11']) or !empty($cfg['anti_link_spam12']))){
  $cfg['anti_link_spam'] = array($cfg['anti_link_spam11'], $cfg['anti_link_spam12']);
 }
 unset($cfg['anti_link_spam11'], $cfg['anti_link_spam12']);
 if($cfg['anti_mail_spam'] == 1){
  $cfg['anti_mail_spam'] = isset($cfg['anti_mail_spam1'][0]) ? $cfg['anti_mail_spam1'] : 0;
 }
 unset($cfg['anti_mail_spam11']);
 if($cfg['deny_attribute'] == 1){
  $cfg['deny_attribute'] = isset($cfg['deny_attribute1'][0]) ? $cfg['deny_attribute1'] : 0;
 }
 unset($cfg['deny_attribute1']);
 if($cfg['tidy'] == 2){
  $cfg['tidy'] = isset($cfg['tidy2'][0]) ? $cfg['tidy2'] : 0;
 }
 unset($cfg['tidy2']);
 if($cfg['unique_ids'] == 2){
  $cfg['unique_ids'] = isset($cfg['unique_ids2'][0]) ? $cfg['unique_ids2'] : 1;
 }
 unset($cfg['unique_ids2']);
 unset($cfg['and_mark']); // disabling and_mark

 $cfg['show_setting'] = 'hlcfg';
 $st = microtime(); 
 $out = htmLawed($_POST['text'], $cfg, str_replace(array('$', '{'), '', $_POST['spec']));
 $et = microtime();
 echo '<br /><a href="htmLawedTest.php" title="[toggle visibility] syntax-highlighted" onclick="javascript:toggle(\'inputR\'); return false;"><span class="notice">Input code &raquo;</span></a> <span class="help" title="tags estimated as half of total &gt; and &lt; chars; values may be inaccurate for non-ASCII text"><small><big>', strlen($_POST['text']), '</big> chars, ~<big>', round((substr_count($_POST['text'], '>') + substr_count($_POST['text'], '<'))/2), '</big> tags</small>&nbsp;</span><div id="inputR" style="display: none;">', format($_POST['text']), '</div><script type="text/javascript">hl(\'inputR\');</script>', (!isset($_POST['text'][$_hlimit]) ? ' <a href="htmLawedTest.php" title="[toggle visibility] hexdump; non-viewable characters like line-returns are shown as dots" onclick="javascript:toggle(\'inputD\'); return false;"><span class="notice">Input binary &raquo;&nbsp;</span></a><div id="inputD" style="display: none;">'. hexdump($_POST['text']). '</div>' : ''), ' <a href="htmLawedTest.php" title="[toggle visibility] finalized settings as interpreted by htmLawed; for developers" onclick="javascript:toggle(\'settingF\'); return false;"><span class="notice">Finalized settings &raquo;&nbsp;</span></a> <div id="settingF" style="display: none;">', str_replace(array('    ', "\t", '  '), array('  ', '&nbsp;  ', '&nbsp; '), nl2br(htmlspecialchars(print_r($GLOBALS['hlcfg']['config'], true)))), '</div><script type="text/javascript">hl(\'settingF\');</script>', '<br /><a href="htmLawedTest.php" title="[toggle visibility] suitable for copy-paste" onclick="javascript:toggle(\'outputF\'); return false;"><span class="notice">Output &raquo;</span></a> <span class="help" title="approx., server-specific value excluding the \'include()\' call"><small>htmLawed processing time <big>', number_format(((substr($et,0,9)) + (substr($et,-10)) - (substr($st,0,9)) - (substr($st,-10))),4), '</big> s</small></span>', (($mem = memory_get_peak_usage()) !== false ? '<span class="help"><small>, peak memory usage <big>'. round(($mem-$pre_mem)/1048576, 2). '</big> <small>MB</small>' : ''), '</small></span><div id="outputF"  style="display: block;"><div><textarea id="text2" class="textarea" name="text2" rows="5" cols="100" style="width: 100%;">', htmlspecialchars($out), '</textarea></div><button type="button" onclick="javascript:document.getElementById(\'text2\').focus();document.getElementById(\'text2\').select()" title="select all to copy" style="float:right;">Select all</button>';
 if($_w3c_validate && $validation)
 {
?>
  
<button type="button" title="HTML 4.01 W3C online validation" style="float: right;" onclick="javascript: sndValidn('text2', 'html401'); return false;" onkeypress="javascript: sndValidn('text2', 'html401'); return false;">Check HTML</button>
<button type="button" title="XHTML 1.1 W3C online validation" style="float: right;" onclick="javascript: sndValidn('text2', 'xhtml110'); return false;" onkeypress="javascript: sndValidn('text2', 'xhtml110'); return false;">Check XHTML</button>
  
<?php
 }
 echo '</div><br /><a href="htmLawedTest.php" title="[toggle visibility] syntax-highlighted" onclick="javascript:toggle(\'outputR\'); return false;"><span class="notice">Output code &raquo;</span></a><div id="outputR" style="display: block;">', format($out), '</div><script type="text/javascript">hl(\'outputR\');</script>', (!isset($_POST['text'][$_hlimit]) ? '<br /><a href="htmLawedTest.php" title="[toggle visibility] hexdump; non-viewable characters like line-returns are shown as dots" onclick="javascript:toggle(\'outputD\'); return false;"><span class="notice">Output binary &raquo;</span></a><div id="outputD" style="display: none;">'. hexdump($out). '</div>' : ''), '<br /><a href="htmLawedTest.php" title="[toggle visibility] XHTML 1 Transitional doctype" onclick="javascript:toggle(\'outputH\'); return false;"><span class="notice">Output rendered &raquo;</span></a><div id="outputH" style="display: block;">', $out, '</div>';
}
else{
?>

<br />

<div class="help">Use with a Javascript- and cookie-enabled, relatively new version of a common browser.

<?php echo (file_exists('./htmLawed_TESTCASE.txt') ? '<br /><br />You can use text from <a href="htmLawed_TESTCASE.txt"><span class="notice">this collection of test-cases</span></a> in the input. Set the character encoding of the browser to Unicode/utf-8 before copying.' : ''); ?>

<br /><br />For more about the anti-XSS capability of htmLawed, see <a href="http://www.bioinformatics.org/phplabware/internal_utilities/htmLawed/rsnake/RSnakeXSSTest.htm"><span class="notice">this page</span></a>.
<br /><br /><em>Submitted input will also be HTML-rendered (XHTML 1) after htmLawed-filtering.</em>
<br /><br />Change <em>Encoding</em> to reflect the character encoding of the input text. Even then, it may not work or some characters may not display properly because of variable browser support and because of the form interface. Developers can write some PHP code to capture the filtered input to a file if this is important.
<br /><br />Refer to the htmLawed documentation (<a href="htmLawed_README.htm"><span class="notice">htm</span></a>/<a href="htmLawed_README.txt"><span class="notice">txt</span></a>) for details about <em>Settings</em>, and htmLawed's behavior and limitations.
<br /><br />For <em>Settings</em>, incorrectly-specified values like regular expressions are silently ignored. One or more settings form-fields may have been disabled. Some characters are not allowed in the <em>Spec</em> field.
<br /><br />Hovering the mouse over some of the text can provide additional information in some browsers.

<?php
if($_w3c_validate){
?>

<br /><br />Because of character-encoding issues, the W3C validator (anyway not perfect) may reject validation requests or invalidate otherwise-valid code, esp. if text was copy-pasted in the input box. Local applications like the <em>HTML Validator</em> Firefox browser add-on may be useful in such cases.

<?php
}
?>

</div>

<?php
}
?>

</div>
</body>
</html>