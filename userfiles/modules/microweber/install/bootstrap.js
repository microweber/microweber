/*
*************************************************

	SEBASTIAN NITU
	baseDemo Bootstrapping

	Created by Sebastian Nitu
	http://www.sebnitu.com

*************************************************
*/

/*-------------------------------------------
	Save Global Variables
---------------------------------------------*/

// Get the URL to this script directory
var scripts = document.getElementsByTagName('script');
var lastScript = scripts[scripts.length-1];
var myScript = lastScript.src;
var myScriptSrc = myScript.replace(/bootstrap.js/, '');

/*-------------------------------------------
	File Includes
---------------------------------------------*/

// Include baseDemo styles
document.write(unescape('%3Clink rel="stylesheet" media="all" href="' + myScriptSrc + 'bd.styles.css"%3E'));
// Check if jQuery exists and include local version if not
!window.jQuery && document.write(unescape('%3Cscript src="' + myScriptSrc + 'bd.jquery.min.js"%3E%3C/script%3E'));
// Included baseDemo JavaScript
document.write(unescape('%3Cscript src="' + myScriptSrc + 'bd.ui.js"%3E%3C/script%3E'));
// Mobile viewport optimized
document.write(unescape('%3Cmeta name="viewport" content="width=device-width, initial-scale=1.0"%3E'));

/*-------------------------------------------
	Fin
---------------------------------------------*/