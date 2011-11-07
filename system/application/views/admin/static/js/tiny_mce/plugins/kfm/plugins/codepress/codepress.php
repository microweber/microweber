<?php
require_once('../../initialise.php');
if(!isset($_GET['id'])) die ('No file id specified');
$f=kfmFile::getInstance($_GET['id']);
if(!$f)die('File could not be loaded');
switch($f->getExtension()){
	case 'asp':
		$lang='asp';
		break;
	case 'autoit':
		$lang='autoit';
		break;
	case 'css':
		$lang='css';
		break;
	case 'csharp':
		$lang='csharp';
		break;
	case 'html':
	case 'tpl':
	case 'htm':
		$lang='html';
		break;
	case 'java':
	case 'j':
		$lang='java';
		break;
	case 'js':
		$lang='javascript';
		break;
	case 'perl':
		$lang='perl';
		break;
	case 'php':
	case 'php3':
	case 'php4':
		$lang='php';
		break;
	case 'ruby':
	case 'rb':
		$lang='ruby';
		break;
	case 'sql':
		$lang='sql';
		break;
	case 'txt':
		$lang='text';
		break;
	case 'vbscript':
	case 'vba':
		$lang='vbscript';
		break;
	case 'xsl':
	case 'xml':
		$lang='xsl';
		break;
	default:
		$lang='generic';
		break;
}
//print $kfm->doctype;
?>
<html>
<head>
<title>Codepress Editor</title>
<script src="codepress-0.9.6/codepress.js" type="text/javascript"></script>
<style type="text/css">
textarea{
	width:100%;
	height:100%;
	margin:0;
}
body{
	background-color:#ddf;
	margin:0;
	padding:0;
}
</style>
<script type="text/javascript">
</script>
</head>
<body>
<textarea id="editor_area" class="codepress <?php echo $lang;if(!$f->writable)echo ' readonly-on';?>"><?php echo $f->getContent();?></textarea>
</body>
</html>
