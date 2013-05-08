<?php  
 


$source_code =  get_option('source_code', $params['id']) ;

$source_code_language =  get_option('source_code_language', $params['id']) ;



if(strval($source_code_language) == ''){
	$source_code_language = 'php';
}

if(strval($source_code) == ''){
	$source_code = '// source code here';
}
$source_code = html_entity_decode($source_code);
//p($config);
 
$source_code_id = md5($source_code );
 
?>
<script>

mw.require("<?php print $config['url_to_module']; ?>luminous-v0.7.0/style/luminous.css", true);
mw.require("<?php print $config['url_to_module']; ?>luminous-v0.7.0/style/github.css", true);


</script>
<style>
/* --- syntax highlighting code - GeSHi --- */
.sdsdsmw-code-hl pre {
	margin: 0 1em !important;
	overflow: auto;
	background-color: white;
	border: 1px dotted #ccc;
	 
}
<?php 

require_once($config['path_to_module'].'luminous-v0.7.0'.DS . 'luminous.php');
 

 



//div.mw-code-hl pre ol li {
//	margin-bottom: 0;				/* override .entry-content ol li */
//	font-family:   Monaco, monospace;
//	/*outline: 1px dotted red;*/	/* DEBUG */
//}
//div.mw-code-hl pre ol li.li2 {
//	background-color: #ffd;			/* gentle zebra stripes */
//}
//div.mw-code-hl pre ol li div {
//	margin: 0;						/* override .entry-content div */
//}
//div.mw-code-hl .br0	{ color: #6C6; }
//div.mw-code-hl .co0	{ color: #808080; font-style: italic; }	/* comment */
//div.mw-code-hl .co1	{ color: #808080; font-style: italic; }
//div.mw-code-hl .co2	{ color: #808080; font-style: italic; }
//div.mw-code-hl .coMULTI	{ color: #808080; font-style: italic; } /* multi-line comment */
//div.mw-code-hl .es0	{ color: #009; font-weight: bold; }
//div.mw-code-hl .kw1	{ color: #b1b100; }	/* keyword */
//div.mw-code-hl .kw2	{ color: #000; font-weight: bold; }
//div.mw-code-hl .kw3	{ color: #006; }
//div.mw-code-hl .kw4	{ color: #933; }
//div.mw-code-hl .kw5	{ color: #00F; }
//div.mw-code-hl .me0	{ color: #060; }
//div.mw-code-hl .nu0	{ color: #C6C; }	/* number */
//div.mw-code-hl .re0	{ color: #00F; }
//div.mw-code-hl .re1	{ color: #00F; }
//div.mw-code-hl .re2	{ color: #00F; }
//div.mw-code-hl .re4	{ color: #099; }
//div.mw-code-hl .sc0	{ color: #0BD; }
//div.mw-code-hl .sc1	{ color: #DB0; }
//div.mw-code-hl .sc2	{ color: #090; }
//div.mw-code-hl .st0	{ color: #F00; }
//div.mw-code-hl .sy0	{ color: #F00; }	/* symbol */
?>
</style>
<div class="mw-code-hl">
 <?php echo luminous::highlight($source_code_language,$source_code); ?>
</div>
