<?php
/** @version $Id: mimes.php 173 2010-06-18 09:46:45Z soeren $ */
/** ensure this file is being included by a parent file */
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) die( 'Restricted access' );
//------------------------------------------------------------------------------
// editable files:
$GLOBALS["editable_ext"]=
	"\.asm$|\.rc$|\.hh$|\.hxx$|\.odl$|\.idl$|\.rc2$|\.dlg$"
	."|\.php$|\.php3$|\.php5$|\.phtml$|\.inc$|\.sql$|\.csv$"
	."|\.vb$|\.vbs$|\.bas$|\.frm$|\.cls$|\.ctl$|\.rb$|\.htm$|\.html$|\.shtml$|\.dhtml$|\.xml$"
	."|\.js$|\.css$|\.cgi$|\.cpp$|\.c$|\.cc$|\.cxx$|\.hpp$|\.h$|\.lua$"
	."|\.pas$|\.p$|\.pl$|\.java$|\.py$|\.sh$|\.bat$|\.tcl$|\.tk$"
	."|\.txt$|\.ini$|\.conf$|\.properties$|\.htaccess$|\.htpasswd$";

//------------------------------------------------------------------------------
// image files:
$GLOBALS["images_ext"]="\.png$|\.bmp$|\.jpg$|\.jpeg$|\.gif$|\.tif$|\.ico$";
//------------------------------------------------------------------------------
// mime types: (description,image,extension)
$GLOBALS["super_mimes"]=array(
	// dir, exe, file
	"dir"	=> array(extGetParam( $GLOBALS["mimes"], 'dir', 'Dir' ),"extension/folder.png"),
	"exe"	=> array(extGetParam( $GLOBALS["mimes"], 'exe', 'exe' ),"extension/exe.png","\.exe$|\.com$|\.bin$"),
	"file"	=> array(extGetParam( $GLOBALS["mimes"], 'file', 'file' ),"extension/document.png")
);
$GLOBALS["used_mime_types"]=array(
	// text
	"text"	=> array(extGetParam( $GLOBALS["mimes"], 'text', 'Text' ),  "extension/txt.png",   "\.txt$"),

	// programming
	"php"	=> array(extGetParam( $GLOBALS["mimes"], 'php', 'php' ),   "extension/php.png",   "\.php$"),
	"php3"	=> array(extGetParam( $GLOBALS["mimes"], 'php3', 'php3' ),  "extension/php3.png",  "\.php3$"),
	"php4"	=> array(extGetParam( $GLOBALS["mimes"], 'php4', 'php4' ),  "extension/php4.png",  "\.php4$"),
	"php5"	=> array(extGetParam( $GLOBALS["mimes"], 'php5', 'php5' ),  "extension/php5.png",  "\.php5$"),
	"phtml"	=> array(extGetParam( $GLOBALS["mimes"], 'phtml', 'phtml' ), "extension/phtml.png", "\.phtml$"),
	"inc"	=> array(extGetParam( $GLOBALS["mimes"], 'inc', 'inc' ),   "extension/inc.png",   "\.inc$"),
	"sql"	=> array(extGetParam( $GLOBALS["mimes"], 'sql', 'SQL' ),   "extension/sql.png",   "\.sql$"),
	"pl"	=> array(extGetParam( $GLOBALS["mimes"], 'pl', 'Perl' ),    "extension/pl.png",    "\.pl$"),
	"cgi"	=> array(extGetParam( $GLOBALS["mimes"], 'cgi', 'CGI' ),   "extension/cgi.png",   "\.cgi$"),
	"py"	=> array(extGetParam( $GLOBALS["mimes"], 'py', 'Python' ),    "extension/py.png",    "\.py$"),
	"sh"	=> array(extGetParam( $GLOBALS["mimes"], 'sh', 'Shell' ),    "extension/sh.png",    "\.sh$"),
	"c" 	=> array(extGetParam( $GLOBALS["mimes"], 'c', 'C' ),     "extension/c.png",     "\.c$"),
	"cc"	=> array(extGetParam( $GLOBALS["mimes"], 'cc', 'CC' ),    "extension/cc.png",    "\.cc$"),
	"cpp"	=> array(extGetParam( $GLOBALS["mimes"], 'cpp', 'CPP' ),   "extension/cpp.png",   "\.cpp$"),
	"cxx"	=> array(extGetParam( $GLOBALS["mimes"], 'cxx', 'CXX' ),   "extension/cxx.png",   "\.cxx$"),
	"h" 	=> array(extGetParam( $GLOBALS["mimes"], 'h', 'H' ),     "extension/h.png",     "\.h$"),
	"hpp" 	=> array(extGetParam( $GLOBALS["mimes"], 'hpp', 'hpp' ),   "extension/hpp.png",   "\.hpp$"),
	"java"	=> array(extGetParam( $GLOBALS["mimes"], 'java', 'Java' ),  "extension/java.png",  "\.java$"),
	"class"	=> array(extGetParam( $GLOBALS["mimes"], 'class', 'Class' ), "extension/class.png", "\.class$"),
	"jar"	=> array(extGetParam( $GLOBALS["mimes"], 'jar', 'Jar' ),   "extension/jar.png",   "\.jar$"),

	// browser
	"htm"	=> array(extGetParam( $GLOBALS["mimes"], 'htm', 'HTML' ),   "extension/htm.png",   "\.htm$"),
	"html"	=> array(extGetParam( $GLOBALS["mimes"], 'html', 'HTML' ),  "extension/html.png",  "\.html$"),
	"shtml"	=> array(extGetParam( $GLOBALS["mimes"], 'shtml', 'sHTML' ), "extension/shtml.png", "\.shtml$"),
	"dhtml"	=> array(extGetParam( $GLOBALS["mimes"], 'dhtml', 'dHTML' ), "extension/dhtml.png", "\.dhtml$"),
	"xhtml"	=> array(extGetParam( $GLOBALS["mimes"], 'xhtml', 'XHTML' ), "extension/xhtml.png", "\.xhtml$"),
	"xml"	=> array(extGetParam( $GLOBALS["mimes"], 'xml', 'XML' ),   "extension/xml.png",   "\.xml$"),
	"js"	=> array(extGetParam( $GLOBALS["mimes"], 'js', 'JS' ),    "extension/js.png",    "\.js$"),
	"css"	=> array(extGetParam( $GLOBALS["mimes"], 'css', 'CSS' ),   "extension/css.png",   "\.css$"),
	
	// images
	"gif"	=> array(extGetParam( $GLOBALS["mimes"], 'gif', 'GIF' ),   "extension/gif.png",   "\.gif$"),
	"jpg"	=> array(extGetParam( $GLOBALS["mimes"], 'jpg', 'JPG' ),   "extension/jpg.png",   "\.jpg$"),
	"jpeg"	=> array(extGetParam( $GLOBALS["mimes"], 'jpeg', 'JPEG' ),  "extension/jpeg.png",  "\.jpeg$"),
	"bmp"	=> array(extGetParam( $GLOBALS["mimes"], 'bmp', 'Bitmap' ),   "extension/bmp.png",   "\.bmp$"),
	"png"	=> array(extGetParam( $GLOBALS["mimes"], 'png', 'PNG' ),   "extension/png.png",   "\.png$"),
	
	// compressed
	"zip"	=> array(extGetParam( $GLOBALS["mimes"], 'zip', 'ZIP' ),   "extension/zip.png",   "\.zip$"),
	"tar"	=> array(extGetParam( $GLOBALS["mimes"], 'tar', 'TAR' ),   "extension/tar.png",   "\.tar$"),
	"tgz"	=> array(extGetParam( $GLOBALS["mimes"], 'tgz', 'Tar/GZ' ),   "extension/tgz.png",   "\.tgz$"),
	"gz"	=> array(extGetParam( $GLOBALS["mimes"], 'gz', 'GZip' ),    "extension/gz.png",    "\.gz$"),


	"bz2"	=> array(extGetParam( $GLOBALS["mimes"], 'bz2', 'Bzip2' ),   "extension/bz2.png",   "\.bz2$"),
	"tbz"	=> array(extGetParam( $GLOBALS["mimes"], 'tbz', 'Tar/Bz2' ),   "extension/tbz.png",   "\.tbz$"),
	"rar"	=> array(extGetParam( $GLOBALS["mimes"], 'rar', 'RAR' ),   "extension/rar.png",   "\.rar$"),

	// music
	"mp3"	=> array(extGetParam( $GLOBALS["mimes"], 'mp3', 'Mp3' ),   "extension/mp3.png",   "\.mp3$"),
	"wav"	=> array(extGetParam( $GLOBALS["mimes"], 'wav', 'WAV' ),   "extension/wav.png",   "\.wav$"),
	"midi"	=> array(extGetParam( $GLOBALS["mimes"], 'midi', 'Midi' ),  "extension/midi.png",  "\.mid$"),
	"rm"	=> array(extGetParam( $GLOBALS["mimes"], 'real', 'Real Media' ),  "extension/rm.png",    "\.rm$"),
	"ra"	=> array(extGetParam( $GLOBALS["mimes"], 'real', 'Real Audio' ),  "extension/ra.png",    "\.ra$"),
	"ram"	=> array(extGetParam( $GLOBALS["mimes"], 'real', 'Real Media' ),  "extension/ram.png",   "\.ram$"),
	"pls"	=> array(extGetParam( $GLOBALS["mimes"], 'pls', 'pls' ),   "extension/pls.png",   "\.pls$"),
	"m3u"	=> array(extGetParam( $GLOBALS["mimes"], 'm3u', 'm3u' ),   "extension/m3u.png",   "\.m3u$"),

	// movie
	"mpg"	=> array(extGetParam( $GLOBALS["mimes"], 'mpg', 'MPG' ),   "extension/mpg.png",   "\.mpg$"),
	"mpeg"	=> array(extGetParam( $GLOBALS["mimes"], 'mpeg', 'MPG'),  "extension/mpeg.png",  "\.mpeg$"),
	"mov"	=> array(extGetParam( $GLOBALS["mimes"], 'mov', 'MOV' ),   "extension/mov.png",   "\.mov$"),
	"avi"	=> array(extGetParam( $GLOBALS["mimes"], 'avi', 'AVI' ),   "extension/avi.png",   "\.avi$"),
	"swf"	=> array(extGetParam( $GLOBALS["mimes"], 'swf', 'SWF' ),   "extension/swf.png",   "\.swf$"),
	
	// Micosoft / Adobe
	"doc"	=> array(extGetParam( $GLOBALS["mimes"], 'doc', 'Word' ),   "extension/doc.png",   "\.doc$"),
	"docx"	=> array(extGetParam( $GLOBALS["mimes"], 'docx', 'Word' ),  "extension/docx.png",  "\.docx$"),
	"xls"	=> array(extGetParam( $GLOBALS["mimes"], 'xls', 'Excel' ),   "extension/xls.png",   "\.xls$"),
	"xlsx"	=> array(extGetParam( $GLOBALS["mimes"], 'xlsx', 'Excel' ),  "extension/xlsx.png",  "\.xlsx$"),
	"rtf"	=> array(extGetParam( $GLOBALS["mimes"], 'rtf', 'Rich Text Format' ),  "extension/doc.png",  "\.rtf$"),
	
	"pdf"	=> array(extGetParam( $GLOBALS["mimes"], 'pdf', 'PDF' ),   "extension/pdf.png",   "\.pdf$")
);
//------------------------------------------------------------------------------
