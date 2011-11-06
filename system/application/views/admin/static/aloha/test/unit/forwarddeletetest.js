// Just used for single testing 
var specifictests = {
		defaultValue: '',
		defaultCommand: 'forwarddelete',
		tests: [

			   ]
}
		      

/**
 * Tests that currently freeze ie
 */
/*
 		{	start: '<p><span style=background-color:aqua>foo[]</font><p><span style=background-color:tan>bar</font>',
			execResult: '<p><span style="background-color:aqua">foo[]</span><span style="background-color:tan">bar</span></p>'
		},
		{	start: '<p><span style=color:blue>foo[]</font><p><span style=color:brown>bar</font>',
			execResult: '<p><span style="color:blue">foo[]</span><span style="color:brown">bar</span></p>'
		},
*/


var alltests = {
		defaultValue: '',
		defaultCommand: 'forwarddelete',
		tests: [

/**
 * It is impossible to get a selection like this in ie
 */		
		{	exclude: 'msie',
			start: 'foo{<p>bar</p>}baz',
			execResult: 'foo[]baz'
		},
		{	exclude: 'msie',
			start: 'foo<p>{bar</p>}baz',
			execResult: 'foo<p>[]baz</p>'
		},
		{	exclude: 'msie',	
			start: '<p>foo[</p>]bar<br>baz',
			execResult: '<p>foo[]bar</p>baz'
		},
		{   exclude: 'msie',	
			start: '<p>foo{</p>}bar',
			execResult: '<p>foo[]bar</p>'
		},
		{	exclude: 'msie',
			start: '<p>foo[</p>]bar<p>baz</p>',
			execResult: '<p>foo[]bar</p><p>baz</p>'
		},
		{	exclude: 'msie',	
			start: '<div><p>foo[</p></div>]bar',
			execResult: '<div><p>foo[]bar</p></div>'
		},	        
		

/**
 * Tests that had to be adapted to work for ie 
 */		       
		        
		// These tests will currently not work in ie since ie won't accept this
		// cursor position. It won't allow the cursor to be placed directly in
		// front of an blocklevel element like div/pre/blockquote. 
		// This will only work when a space will be put between the blocklevel element 
		// and the cursor position. This additional space will then create problems 
		// when executing the forward delete command. A test that includes that
		// will just delete the space and than reposition the cursor inside the
		// next block level element and than ie magic kicks in which adds a
		// space just before the blocklevel element it just entered.
		{	exclude: 'msie',
			start: 'foo[]<p>bar</p>',
			execResult: 'foo[]bar'
		},
//		{	exclude: 'msie',
//			start: 'foo[]<table><tr><td><hr>bar</table>baz',
//			execResult: 'foo[]baz'
//		},
		// This test fails due to selection error. See newIETests
		{	exclude: 'msie',
			start: 'foo[]<blockquote>bar</blockquote>',
			execResult: 'foo[]bar'
		},
		{	exclude: 'msie',
			start: 'foo[]<blockquote><blockquote>bar</blockquote></blockquote>',
			execResult: 'foo[]bar'
		},
		{	exclude: 'msie',
			start: 'foo[]<blockquote><div>bar</div></blockquote>',
			execResult: 'foo[]bar'
		},
		{	exclude: 'msie',
			start: 'foo[]<blockquote style="color: blue">bar</blockquote>',
			execResult: 'foo[]<span style="color: blue; ">bar</span>'
		},
		{	exclude: 'msie',
			start: 'foo[]<blockquote><blockquote><p>bar<p>baz</blockquote></blockquote>',
			execResult: 'foo[]bar<blockquote><blockquote><p>baz</p></blockquote></blockquote>'
		},
		{	exclude: 'msie',
			start: 'foo[]<blockquote><div><p>bar<p>baz</div></blockquote>',
			execResult: 'foo[]bar<blockquote><div><p>baz</p></div></blockquote>'
		},
		{	exclude: 'msie',
			start: 'foo[]<blockquote style="color: blue"><p>bar<p>baz</blockquote>',
			execResult: 'foo[]<span style="color: blue; ">bar</span><blockquote style="color: blue"><p>baz</p></blockquote>'
		},
		{	exclude: 'msie',
			start: 'foo[]<blockquote><p><b>bar</b><p>baz</blockquote>',
			execResult: 'foo[]<b>bar</b><blockquote><p>baz</p></blockquote>'
		},
		{	exclude: 'msie',
			start: 'foo[]<blockquote><p><strong>bar</strong><p>baz</blockquote>',
			execResult: 'foo[]<strong>bar</strong><blockquote><p>baz</p></blockquote>'
		},
		{	exclude: 'msie',
			start: 'foo[]<blockquote><p><span>bar</span><p>baz</blockquote>',
			execResult: 'foo[]<span>bar</span><blockquote><p>baz</p></blockquote>'
		},
		{	exclude: 'msie',
			start: 'foo[]<blockquote><ol><li>bar</ol></blockquote><p>extra',
			execResult: 'foo[]bar<p>extra</p>'
		},
		{	exclude: 'msie',
			start: 'foo[]<blockquote>bar<ol><li>baz</ol>quz</blockquote><p>extra',
			execResult: 'foo[]bar<blockquote><ol><li>baz</li></ol>quz</blockquote><p>extra</p>'
		},
		{	exclude: ['msie', 'mozilla'],
			start: 'foo{}<p><br>',
			execResult: 'foo[]'
		},
		{	include: 'mozilla',
			start: 'foo{}<p><br>',
			execResult: 'foo{}'
		},
		{	include: 'msie',
			start: 'foo{}<p><br></p>',
			execResult: 'foo <p>{}</p>'
		},
		{	exclude: ['msie', 'mozilla'],
			start: 'foo{}<p><span><br></span>',
			execResult: 'foo[]'
		},
		{	include: ['mozilla'],
			start: 'foo{}<p><span><br></span>',
			execResult: 'foo{}'
		},
		{	include: 'msie',
			start: 'foo{}<p><span><br></span>',
			execResult: 'foo <p><span>{}</span>'
		},
		{	exclude: 'msie',
			start: 'foo[]<p style=color:brown>bar',
			execResult: 'foo[]<span style="color: rgb(165, 42, 42); ">bar</span>'
		},
		{	include: 'msie',
			start: 'foo[]<p style=color:brown>bar',
			execResult: 'foo <p>[]ar</p>'
		},
		{	exclude: 'msie',
			start: 'foo[]<div><ol><li>bar</ol></div>',
			execResult: 'foo[]bar'
		},
		{ 	exclude: 'msie',
			start: 'foo[]<div><div><p>bar</div></div>',
			execResult: 'foo[]bar'
		},
		{	include: 'msie',
			start: 'foo[]<div><ol><li>bar</ol></div>',
			execResult: 'foo <div><ol><li>[]ar</ol></div>'
		},
		{ 	include: 'msie',
			start: 'foo[]<div><div><p>bar</p></div></div>',
			execResult: 'foo <div><div><p>[]ar</p></div></div>'
		},
		{	include: 'msie',
			start: 'foo<blockquote><ol><li>bar[]</li><ol><li>baz</ol><li>quz</ol></blockquote><p>extra',
			execResult: 'foo <blockquote><ol><li>bar[]baz</li><li>quz</li></ol></blockquote><p>extra</p>'
		},
		{	include: 'msie',
			start: 'foo<table><tr><td>bar[]<br></table>baz',
			execResult: 'foo <table><tr><td>bar[]</table>baz'
		},
		{	include: 'msie',
			start: '<table><tr><td>foo[]<br><td>bar</table>',
			execResult: '<table><tr><td>foo[] <td>bar</table>'
		},
		{	include: 'msie',
			start: '<p>foo<ol><li>bar<li>ba[z</ol><p>q]uz',
			execResult: '<p>foo </p><ol><li>bar </li><li>ba[]uz</li></ol>'
		},
		{ 	include: 'msie',
			start: 'foo<table><tr><td>bar[]</table><br>baz',
			execResult: 'foo <table><tr><td>bar[]</table><br>baz'
		},
		

/**
 * IE Special cases
 */		
		
		// After deletion of the br tag the cursor will automatically be placed
		// inside the paragraph and a additional space will be added. See 'Tests
		// that currently can't work in ie.' for more information.
		{  	include: 'msie',
			start: 'foo[]<br><p>bar</p>',
			execResult: 'foo <p>[]bar</p>'
	  	},
	  	{  	exclude: 'msie',
			start: 'foo[]<br><p>bar</p>',
			execResult: 'foo[]bar'
	  	},
		{	exclude: ['msie', 'mozilla'],
			start: 'foo{}<p>',
			execResult: 'foo[]'
		},
		{	include: 'mozilla',
			start: 'foo{}<p>',
			execResult: 'foo{}'
		},
		{	include: 'msie',
			start: 'foo{}<p>',
			execResult: 'foo <p>{}</p>'
		},
        // Special case. The IE Currently transforms non 
        // breakable spaces in a very odd way.
		{	include: 'msie',
			start: 'foo &nbsp;[] bar',
			execResult: 'foo&nbsp; []bar'
		},
        // This test somehow creates a broken dom entry in ie
    	{	start: 'foo[]<a href="/">bar</a>',
			execResult: 'foo[]<a href="/">ar</a>'
		},
		
		
/**
 * Tests OK in IE 
 */
		
		{	start: '<ol><li>foo[]<li>bar</ol>',
			execResult: '<ol><li>foo[]bar</li></ol>'
		},
		{	start: '<ol><li><p>foo[]<li>bar</ol>',
			execResult: '<ol><li><p>foo[]bar</p></li></ol>'
		},
		{	start: '<ol><li>foo[]</ol>bar',
			execResult: '<ol><li>foo[]bar</li></ol>'
		},	        
		{	start: '<p><b>foo[bar</b><p>baz]quz',
			execResult: '<p><b>foo[]</b>quz</p>'
		},    
    	{	start: '<p>foo[bar<p style=color:blue>baz]quz',
			execResult: '<p>foo[]<span style="color: blue; ">quz</span></p>'
		},
		{	start: '<p>foo[bar<p><b>baz]quz</b>',
			execResult: '<p>foo[]<b>quz</b></p>'
		},    
		{	start: '<div><p>foo[bar</p></div><p>baz]quz</p>',
			execResult: '<div><p>foo[]quz</p></div>'
		},        
		{	start: '<p>{foo<span style=color:#aBcDeF>bar}</span>baz</p>',
			execResult: '<p>[]baz</p>'
		},
		{	start: '<p>[foo<span style=color:#aBcDeF>bar]</span>baz</p>',
			execResult: '<p>[]baz</p>'
		},
		{	start: '<ol><li><p>foo[]<li><p>bar</ol>',
			execResult: '<ol><li><p>foo[]bar</p></li></ol>'
		},
		{	start: '<p><span style=color:blue>foo[]</span><p>bar',
			execResult: '<p><span style="color:blue">foo[]</span>bar</p>'
		},
		{	start: '<ol><li>foo[<li>]bar</ol>',
			execResult: '<ol><li>foo[]bar</li></ol>'
		},
//		{	start: '<p>foo[]<br><table><tr><td>bar</td></tr></table><p>baz',
//			execResult: '<p>foo[]<table><tr><td>bar</td></tr></table><p>baz'
//		},
		{	start: '<p>fo[o<ol><li>bar<li>baz</ol><p>q]uz',
			execResult: '<p>fo[]uz</p>'
		},
		{	start: '<ol><li>fo[o</ol><ol><li>b]ar</ol>',
			execResult: '<ol><li>fo[]ar</li></ol>'
		},
		// After deletion the cursor jumps into the span and the spaces are somehow modified
		{	include: 'msie',
			start: 'foo <span>&nbsp;</span>[] bar',
			execResult: 'foo&nbsp;<span> []</span>bar'
		},
		{	start: '<b>foo[]&nbsp;</b>&nbsp;bar',
			execResult: '<b>foo[]</b> bar'
		},
		{	start: '<p>foo[]<p style=color:brown>bar',
			execResult: '<p>foo[]<span style="color: rgb(165, 42, 42); ">bar</span></p>'
		},
		{	include: 'msie',
			start: '<p>foo[]<hr><p>bar',
			execResult: '<p>foo[] </p><p>bar</p>'
		},
		{	start: '<div>foo[]</div><div>bar</div>',
			execResult: '<div>foo[]bar</div>'
		},
		{	start: '<pre>foo[]</pre>bar',
			execResult: '<pre>foo[]bar</pre>'
		},
		{	start: 'foo[]<br>bar',
			execResult: 'foo[]bar'
		},
		{	start: '<b>foo[]</b><br>bar',
			execResult: '<b>foo[]</b>bar'
		},
		{	start: 'foo[]<hr>bar',
			execResult: 'foo[]bar'
		},
		{  	start: 'fo[o<b>b]ar</b>baz',
			execResult: 'fo[]<b>ar</b>baz'
		},
		// Collapsed selection
		{  	start: 'foo[]',
			execResult: 'foo[]'
		},	        
		{  	start: '<span>foo[]</span>',
			execResult: '<span>foo[]</span>'
		},
		{  	start: '<p>foo[]</p>',
			execResult: '<p>foo[]</p>'
		},
		{  	start: 'foo[]bar',
			execResult: 'foo[]ar'
		},
		{  	start: '<span>foo</span>{}<span>bar</span>',
			execResult: '<span>foo[]</span><span>ar</span>'
		},
		{  	start: '<span>foo[</span><span>]bar</span>',
			execResult: '<span>foo[]</span><span>ar</span>'
		},
		{  	start: 'foo[]<span style=display:none>bar</span>baz',
			execResult: 'foo[]az'
		},	        
		{  	start: 'fo[]&ouml;bar',
			execResult: 'fo[]bar'
		},
		{  	start: 'fo[]bar',
			execResult: 'fo[]ar'
		},
		{  	start: 'fo[]o&#x308;&#x327;bar',
			execResult: 'fo[]bar'
		},
		{  	start: '[]&ouml;bar',
			execResult: '[]bar'
		},
		{  	start: '[]o&#x308;bar',
			execResult: '[]bar'
		},
		{  	start: '[]o&#x308;&#x327;bar',
			execResult: '[]bar'
		},
		{  	start: '<p>foo[]</p><p>bar</p>',
			execResult: '<p>foo[]bar</p>'
		},
		{  	start: '<p>foo[]</p>bar',
			execResult: '<p>foo[]bar</p>'
		},
		{ 	include: 'msie',
			start: '<p>foo[]<br></p><p>bar</p>',
			execResult: '<p>foo[]</p><p>bar</p>'
		},
		{   exclude: 'msie',
			start: '<p>foo[]<br></p><p>bar</p>',
			execResult: '<p>foo[]bar</p>'
		},	
		{  	include: 'msie',
			start: '<p>foo[]<br></p>bar',
			execResult: '<p>foo[]</p>bar'
		},
		{ 	exclude: 'msie',
			start: '<p>foo[]<br></p>bar',
			execResult: '<p>foo[]bar</p>'
		},
		{  	exclude: 'msie',
			start: 'foo[]<br><p>bar</p>',
			execResult: 'foo[]bar'
		},
		{	start: 'foo[]<img src=../AlohaEditorLogo.png>bar',
			execResult: 'foo[]bar'
		},
		{	start: 'foo[]<a>bar</a>',
			execResult: 'foo[]<a>ar</a>'
		},
		{	start: '<b>foo[]&nbsp;</b> bar',
			execResult: '<b>foo[]</b> bar'
		},
		{	start: '<b>foo[] </b> bar',
			execResult: '<b>foo[]</b>bar'
		},
		{	start: '<pre>foo []&nbsp;</pre>',
			execResult: '<pre>foo []</pre>'
		},
		{	start: '<pre>[]&nbsp; foo</pre>',
			execResult: '<pre>[] foo</pre>'
		},
		{	start: '<pre>foo[]&nbsp; bar</pre>',
			execResult: '<pre>foo[] bar</pre>'
		},
		{	start: '[]&nbsp; foo',
			execResult: '[]&nbsp;foo'
		},
		{	start: 'foo[]&nbsp; bar',
			execResult: 'foo[] bar'
		},
		{	start: 'foo[]&nbsp;&nbsp;bar',
			execResult: 'foo[] bar'
		},
		{	start: 'foo[]  bar',
			execResult: 'foo[]bar'
		},
		{	start: 'foo[] &nbsp; bar',
			execResult: 'foo[]&nbsp; bar'
		},
		{	start: 'foo []&nbsp; bar',
			execResult: 'foo []bar'
		},
		{	start: 'foo[] <span>&nbsp;</span> bar',
			execResult: 'foo[]<span>&nbsp;</span> bar'
		},
		{	start: '<b>foo[] </b>&nbsp;bar',
			execResult: '<b>foo[]</b> bar'
		},        
        {	include: 'msie',
        	start: 'foo 1&nbsp;[] bar',
			execResult: 'foo 1 []bar'
		},
		{	exclude: 'msie',
			start: 'foo &nbsp;[] bar',
			execResult: 'foo&nbsp;[]bar'
		},	        
        {	start: 'foo[<p>]bar</p>baz',
			execResult: 'foo[]bar<br>baz'
		},
		{	start: '<p><u>foo[]</u><p>bar',
			execResult: '<p><u>foo[]</u>bar</p>'
		},
		{	start: '<p><u>foo[]</u><p><s>bar</s>',
			execResult: '<p><u>foo[]</u><s>bar</s></p>'
		},
		{	start: '<p>foo[]<p><s>bar</s>',
			execResult: '<p>foo[]<s>bar</s></p>'
		},
		{	start: 'foo[<br>]bar',
			execResult: 'foo[]bar'
		},
		{	start: '<p>foo[</p><p>]bar</p>',
			execResult: '<p>foo[]bar</p>'
		},
		{	start: '<p>foo[</p><p>]bar<br>baz</p>',
			execResult: '<p>foo[]bar<br>baz</p>'
		},
		{	start: 'foo[<p>]bar</p>',
			execResult: 'foo[]bar'
		},
		{	start: 'foo{<p>}bar</p>',
			execResult: 'foo[]bar'
		},
		{	start: '<div style=white-space:nowrap>foo[] &nbsp;bar</div>',
			execResult: '<div style=white-space:nowrap>foo[] bar</div>'
		},
		{	start: '<div style=white-space:nowrap>foo[]&nbsp; bar</div>',
			execResult: '<div style=white-space:nowrap>foo[] bar</div>'
		},
		{	start: '<div style=white-space:pre-line>foo[] &nbsp;bar</div>',
			execResult: '<div style=white-space:pre-line>foo[] bar</div>'
		},
		{	start: '<div style=white-space:pre-line>foo[]&nbsp; bar</div>',
			execResult: '<div style=white-space:pre-line>foo[] bar</div>'
		},
		{	start: '<div style=white-space:pre-line>foo[]  bar</div>',
			execResult: '<div style=white-space:pre-line>foo[]bar</div>'
		},
		{	start: '<div style=white-space:pre>foo []&nbsp;</div>',
			execResult: '<div style=white-space:pre>foo []</div>'
		},
		{	start: '<div style=white-space:pre>[]&nbsp; foo</div>',
			execResult: '<div style=white-space:pre>[] foo</div>'
		},      
		{	start: '<div style=white-space:pre>foo[]&nbsp; bar</div>',
			execResult: '<div style=white-space:pre>foo[] bar</div>'
		},
		{	start: '<div style=white-space:pre>foo[]  bar</div>',
			execResult: '<div style=white-space:pre>foo[]bar</div>'
		},
		{	start: '<div style=white-space:pre-wrap>foo []&nbsp;</div>',
			execResult: '<div style=white-space:pre-wrap>foo []</div>'
		},
		{	start: '<div style=white-space:pre-wrap>[]&nbsp; foo</div>',
			execResult: '<div style=white-space:pre-wrap>[] foo</div>'
		},
        {	start: '<div style=white-space:pre-wrap>foo[]&nbsp; bar</div>',
			execResult: '<div style=white-space:pre-wrap>foo[] bar</div>'
		},
		{	start: '<div style=white-space:pre-wrap>foo[]  bar</div>',
			execResult: '<div style=white-space:pre-wrap>foo[]bar</div>'
		},
		{	start: '<div><div><p>foo[]</p></div></div><div><div><div>bar</div></div></div>',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{	start: '<div><div><p>foo[]</div></div>bar',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{	start: '<div><div><p>foo[]</div></div><!--abc-->bar',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{	start: '<div><div><p>foo[]</div><!--abc--></div>bar',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{	start: '<div><div><p>foo[]</p><!--abc--></div></div>bar',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
    	{	start: '<div>foo[bar</div><p>baz]quz',
			execResult: '<div>foo[]quz</div>'
		},
		{	start: '<blockquote>foo[bar</blockquote><pre>baz]quz</pre>',
			execResult: '<blockquote>foo[]quz</blockquote>'
		},
		{	exclude: 'msie',
			start: 'foo[<div><p>]bar</p>baz</div>',
			execResult: 'foo[]bar<div>baz</div>'
		},
		{	include: 'msie',
			start: 'foo[<div><p>]bar</p>baz</div>',
			execResult: 'foo[]bar <div>baz</div>'
		},	
		{	start: 'foo[bar]baz',
			execResult: 'foo[]baz'
		},
		{	start: '<p style=text-decoration:underline>foo[]<p>bar',
			execResult: '<p><u>foo[]</u>bar</p>'
		},
		{	start: '<p style=color:blue>foo[]</p>bar',
			execResult: '<p><span style="color: blue; ">foo[]</span>bar</p>'
		},
		{   exclude: 'msie',
	  		start: '<a>foo[]</a>bar',
			execResult: '<a>foo[]</a>ar'
		},
		{   include: 'msie',
	  		start: '<a>foo[]</a>bar',
			execResult: '<a>foo</a>[]ar'
		},  
		{	start: '<p>foo[]</p><br><p>bar</p>',
			execResult: '<p>foo[]</p><p>bar</p>'
		},
		{	start: 'foo[]<a name=abc>bar</a>',
			execResult: 'foo[]<a name=abc>ar</a>'
		},
		{	start: 'foo[]<a href=/ name=abc>bar</a>',
			execResult: 'foo[]<a href=/ name=abc>ar</a>'
		},
		{	start: 'foo[]<span><a>bar</a></span>',
			execResult: 'foo[]<span><a>ar</a></span>'
		},
		{	start: '<pre>foo[]  bar</pre>',
			execResult: '<pre>foo[] bar</pre>'
		},
		{	start: '<pre>foo[] &nbsp;bar</pre>',
			execResult: '<pre>foo[]&nbsp;bar</pre>'
		},
		{	exclude: 'msie',
			start: 'foo<blockquote><ol><li>bar[]</li><ol><li>baz</ol><li>quz</ol></blockquote><p>extra',
			execResult: 'foo<blockquote><ol><li>bar[]baz</li><li>quz</li></ol></blockquote><p>extra</p>'
		},
		{	start: '<div><p>foo[]</p></div><p>bar</p>',
			execResult: '<div><p>foo[]bar</p></div>'
		},
		{	start: '<p>foo[]</p><div><p>bar</p></div>',
			execResult: '<p>foo[]bar</p>'
		},
		{	start: '<div><p>foo[]</p></div><div><p>bar</p></div>',
			execResult: '<div><p>foo[]bar</p></div>'
		},
		{	start: '<div><p>foo[]</p></div>bar',
			execResult: '<div><p>foo[]bar</p></div>'
		},
		{	start: '<p>foo[]</p><br><br><p>bar</p>',
			execResult: '<p>foo[]</p><br><p>bar</p>'
		},
		{	start: '<p>foo[]</p><img src=../AlohaEditorLogo.png><p>bar',
			execResult: '<p>foo[]<img src="../AlohaEditorLogo.png"></p><p>bar</p>'
		},
		{	start: 'foo[]<span><a name=abc>bar</a></span>',
			execResult: 'foo[]<span><a name=abc>ar</a></span>'
		},
		{	start: 'foo[]<span><a href=/ name=abc>bar</a></span>',
			execResult: 'foo[]<span><a href=/ name=abc>ar</a></span>'
		},
		{	exclude: 'msie',
			start: '<a name=abc>foo[]</a>bar',
			execResult: '<a name=abc>foo[]</a>ar'
		},
		{	include: 'msie',
			start: '<a name=abc>foo[]</a>bar',
			execResult: '<a name=abc>foo</a>[]ar'
		},
		{	exclude: 'msie',
			start: '<a href=/ name=abc>foo[]</a>bar',
			execResult: '<a href=/ name=abc>foo[]</a>ar'
		},
		{	include: 'msie',
			start: '<a href=/ name=abc>foo[]</a>bar',
			execResult: '<a href=/ name=abc>foo</a>[]ar'
		},
		{	start: '<div style=white-space:nowrap>foo[]  bar</div>',
			execResult: '<div style=white-space:nowrap>foo[]bar</div>'
		},
//		{	exclude: 'msie',
//			start: 'foo<table><tr><td>bar[]<br></table>baz',
//			execResult: 'foo<table><tr><td>bar[]</table>baz'
//		},
//		{	start: '<p>foo<table><tr><td>bar[]<br></table><p>baz',
//			execResult: '<p>foo<table><tr><td>bar[]</table><p>baz'
//		},
//		{	exclude: 'msie',
//			start: '<table><tr><td>foo[]<br><td>bar</table>',
//			execResult: '<table><tr><td>foo[]<td>bar</table>'
//		},
//		{	start: '<table><tr><td>foo[]<br><tr><td>bar</table>',
//			execResult: '<table><tr><td>foo[]<tr><td>bar</table>'
//		},
//		{	exclude: 'msie',	
//			start: 'foo<table><tr><td>bar[]</table><br>baz',
//			execResult: 'foo<table><tr><td>bar[]</table><br>baz'
//		},
		
		{	exclude: 'msie',
			start: 'foo[]<ol><li>bar<li>baz</ol>',
			execResult: 'foo[]bar<ol><li>baz</li></ol>'
		},
		{	include: 'msie',
			start: 'foo[]<ol><li>bar<li>baz</ol>',
			execResult: 'foo <ol><li>[]ar </li><li>baz</li></ol>'
		},
		{	exclude: 'msie',
			start: 'foo[]<br><ol><li>bar<li>baz</ol>',
			execResult: 'foo[]bar<ol><li>baz</li></ol>'
		},
		{	include: 'msie',
			start: 'foo[]<br><ol><li>bar<li>baz</ol>',
			execResult: 'foo <ol><li>[]bar </li><li>baz</li></ol>'
		},
//		{	start: '<div><div><p>foo[]<!--abc--></p></div></div><div><div><div>bar</div></div></div>',
//			execResult: '<div><div><p>foo[]bar</p></div></div>'
//		},
		{	start: '<div><div><p>foo[]</p><!--abc--></div></div><div><div><div>bar</div></div></div>',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{	start: '<div><div><p>foo[]</p></div><!--abc--></div><div><div><div>bar</div></div></div>',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{	start: '<div><div><p>foo[]</p></div></div><!--abc--><div><div><div>bar</div></div></div>',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{	start: '<div><div><p>foo[]</p></div></div><div><!--abc--><div><div>bar</div></div></div>',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{	start: '<div><div><p>foo[]</p></div></div><div><div><!--abc--><div>bar</div></div></div>',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{	start: '<div><div><p>foo[]</p></div></div><div><div><div><!--abc-->bar</div></div></div>',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{	start: '<p style=color:blue>foo[]<p>bar',
			execResult: '<p><span style="color: blue; ">foo[]</span>bar</p>'
		},
		{	start: '<p style=color:blue>foo[]<p style=color:brown>bar',
			execResult: '<p style="color:blue">foo[]<span style="color: rgb(165, 42, 42); ">bar</span></p>'
		},

			


/**
 * Cases we don't care about
 */		
//		Yeah well I dunno
//	    {  	start: '[]&#x5e9;&#x5c1;&#x5b8;&#x5dc;&#x5d5;&#x5b9;&#x5dd;', 
//			execResult: '[]&#x5e9;&#x5c1;&#x5b8;&#x5dc;&#x5d5;&#x5b9;&#x5dd;'
//		},
//		Don't care about that case
//		{  	start: 'foo[]<script>bar</script>baz', 
//			execResult: 'foo[]<script>bar</script>baz'
//		},
//		{	start: 'foo[]<div><div><p><!--abc-->bar</div></div>',
//			execResult: 'foo[]bar'
//		},
//		{	start: 'foo[]<div><div><!--abc--><p>bar</div></div>',
//			execResult: 'foo[]bar'
//		},
//		{	start: 'foo[]<div><!--abc--><div><p>bar</div></div>',
//			execResult: 'foo[]bar'
//		},
//		{	start: 'foo[]<!--abc--><div><div><p>bar</div></div>',
//			execResult: 'foo[]bar'
//		},
//		{	start: '<div><div><p>foo[]<!--abc--></div></div>bar',
//			execResult: '<div><div><p>foo[]bar</p></div></div>'
//		},
		
		
	        
/**
 * Tests with errors in ie
 */	        
		{	exclude: 'msie',
			start: 'foo <span>&nbsp;</span> []bar',
			execResult: 'foo <span>&nbsp;[]</span> []ar'
		},
		{	exclude: 'msie',
			start: 'foo[]<quasit></quasit>bar',
			execResult: 'foo[]ar'
		},
		{	include: 'msie',
			start: 'foo[]<quasit></quasit>bar',
			execResult: 'foo<quasit></quasit>[]ar'
		},
		{	exclude: 'msie',
			start: '<a href="/">foo[]</a>bar',
			execResult: '<a href="/">foo[]</a>ar'
		},
		{	include: 'msie',
			start: '<a href="/">foo[]</a>bar',
			execResult: '<a href="/">foo</a>[]ar'
		},
		// This tests creates a broken dom node
		{	start: 'foo[]<span><a href="/">bar</a></span>',
			execResult: 'foo[]<span><a href="/">ar</a></span>'
		},
    	{	start: '<p>foo[]</p><hr><p>bar</p>',
			execResult: '<p>foo[]</p><p>bar</p>'
		},	
//		{	start: '<p>foo[bar<div>baz]quz</div></p>', // TODO nesting div's into p's is not allowed
//			execResult: '<p>foo[]quz</p>'
//		},
		{	start: '<p>foo[bar<p>baz]quz</p>',
			execResult: '<p>foo[]quz</p>'
		},
		{	start: '<p>foo[bar</p><h1>baz]quz</h1>',
			execResult: '<p>foo[]quz</p>'
		},	        
		{	start: 'foo[<div><p>]bar</div>',
			execResult: 'foo[]bar'
		},
		{	start: '<ol><li>foo[]</li><li><p>bar</p></li></ol>',
			execResult: '<ol><li>foo[]bar</li></ol>'
		},
		{	start: '<p style=background-color:aqua>foo[]</p><p>bar</p>',
			execResult: '<p style="background-color:aqua">foo[]bar</p>'
		},
		{	start: '<p style=background-color:aqua>foo[]<p style=background-color:tan>bar',
			execResult: '<p style="background-color:aqua">foo[]bar</p>'
		},
		{	start: '<ol><li>fo[o</ol><ul><li>b]ar</ul>',
			execResult: '<ol><li>fo[]ar</li></ol>'
		},
		{	start: '<p>foo[]<p><span style=color:brown>bar</span>',
			execResult: '<p>foo[]<span style="color:brown">bar</span></p>'
		},
		{	start: 'foo[<ol><li>]bar</ol>',
			execResult: 'foo[]bar'
		},
		{	start: '<p>foo[]</p><p style=background-color:tan>bar</p>',
			execResult: '<p>foo[]bar</p>'
		},
		{	exclude: 'msie',
			start: '<p>foo<span style=color:#aBcDeF>[bar</span><span style=color:#fEdCbA>baz]</span>quz</p>',
			execResult: '<p>foo[]<span style="color:#aBcDeF"></span>quz</p>'
		},
		{	include: 'msie',
			start: '<p>foo<span style=color:#aBcDeF>[bar</span><span style=color:#fEdCbA>baz]</span>quz</p>',
			execResult: '<p>foo<span style="color:#aBcDeF"></span>[]quz</p>'
		},
		{	start: '<p>foo</p><ol><li>ba[r<li>b]az</ol><p>quz</p>',
			execResult: '<p>foo</p><ol><li>ba[]az</li></ol><p>quz</p>'
		},
		{	exclude: 'msie',	
			start: '<p>foo<ol><li>bar<li>[baz]</ol><p>quz',
			execResult: '<p>foo</p><ol><li>bar</li><li>{}</li></ol><p>quz</p>'
		},
		{	include: 'msie',	
			start: '<p>foo<ol><li>bar<li>[baz]</ol><p>quz',
			execResult: '<p>foo </p><ol><li>bar </li><li>[]</li></ol><p>quz</p>'
		},
		{	exclude: 'msie',
			start: '<p>fo[o<ol><li>b]ar<li>baz</ol><p>quz',
			execResult: '<p>fo[]ar</p><ol><li>baz</li></ol><p>quz</p>'
		},
		{	include: 'msie',	
			start: '<p>fo[o<ol><li>b]ar<li>baz</ol><p>quz',
			execResult: '<p>fo[]ar </p><ol><li>baz</li></ol><p>quz</p>'
		},
		{	exclude: 'msie',
			start: '<p>foo<ol><li>bar<li>ba[z</ol><p>q]uz',
			execResult: '<p>foo</p><ol><li>bar</li><li>ba[]uz</li></ol>'
		},
		{	exclude: 'msie',
			start: '<p>fo[o<ol><li>bar<li>b]az</ol><p>quz',
			execResult: '<p>fo[]az</p><p>quz</p>'
		},
		{	include: 'msie',
			start: '<p>fo[o<ol><li>bar<li>b]az</ol><p>quz',
			execResult: '<p>fo[]az </p><p>quz</p>'
		},
		{	start: '<p><span style=background-color:aqua>foo[]</span></p>bar',
			execResult: '<p><span style=background-color:aqua>foo[]</span>bar</p>'
		},
		{	start: '<p>foo[]</p><p><span style=background-color:tan>bar</span></p>',
			execResult: '<p>foo[]<span style="background-color:tan">bar</span></p>'
		},
		{	start: '<p style=text-decoration:underline>foo[]<p style=text-decoration:line-through>bar',
			execResult: '<p><u>foo[]</u><s>bar</s></p>'
		},
		{	start: '<p>foo[]</p><p style=text-decoration:line-through>bar</p>',
			execResult: '<p>foo[]<s>bar</s></p>'
		},
		{	start: '<div style=color:blue><p style=color:green>foo[]</div>bar',
			execResult: '<div><p><span style="color: green; ">foo[]</span>bar</p></div>'
		},
		{	start: '<div style=color:blue><p style=color:green>foo[]</p><p style=color:brown>bar</p></div>',
			execResult: '<div style="color:blue"><p style="color:green">foo[]<span style="color: rgb(165, 42, 42); ">bar</span></p></div>'
		},
		{	start: '<p style=color:blue>foo[]<div style=color:brown><p style=color:green>bar',
			execResult: '<p style="color:blue">foo[]<span style="color: green; ">bar</span></p>'
		},
		{	start: '<p>foo<span style=color:#aBcDeF>[bar</span>baz]</p>',
			execResult: '<p>foo<span style="color:#aBcDeF"></span>{}</p>'
		},
		{	start: '<p>foo<span style=color:#aBcDeF>{bar</span>baz}',
			execResult: '<p>foo<span style="color:#aBcDeF"></span>{}</p>'
		},
		// This test fails since ie places the collapsed range at the beginning of bar.
		{  	exclude: 'msie',
			start: 'foo[]<p>bar</p>',
			execResult: 'foo[]bar'
		},
    	{  	exclude: 'msie',
			start: '<p>{}<br></p>foo',
			execResult: '<p>[]foo</p>'
		},
    	{  	include: 'msie',
			start: '<p>{}<br></p>foo',
			execResult: '<p>{}</p>foo'
		},
		{  	exclude: 'msie',
			start: '<p>{}<span><br></span></p>foo',
			execResult: '<p><span></span></p>[]foo'
		},
		{  	include: 'msie',
			start: '<p>{}<span><br></span></p>foo',
			execResult: '<p><span>{}</span></p>foo'
		},
		{	exclude: 'msie',
			start: 'foo{}<br><p><br></p>',
			execResult: 'foo[]'
		},
		{	include: 'msie',
			start: 'foo{}<br><p><br></p>',
			execResult: 'foo <p>{}<br></p>'
		},
		{	exclude: 'msie',
			start: 'foo{}<span><br></span><p><br></p>',
			execResult: 'foo[]'
		},
		{	include: 'msie',
			start: 'foo{}<span><br></span><p><br></p>',
			execResult: 'foo<span></span> <p>{}<br/></p>'
		},
		{	exclude: 'msie',
			start: 'foo{}<br><p><span><br></span></p>',
			execResult: 'foo[]'
		},
		{	include: 'msie',
			start: 'foo{}<br><p><span><br></span></p>',
			execResult: 'foo <p><span>{}<br></span></p>'
		},
		{	exclude: 'msie',
			start: 'foo{}<span><br></span><p><span><br></span>',
			execResult: 'foo[]'
		},
		{	include: 'msie',
			start: 'foo{}<span><br></span><p><span><br></span>',
			execResult: 'foo<span></span> <p><span>{}<br/></span></p>'
		},
		{	include: 'msie',
			start: 'foo []&nbsp;',
			execResult: 'foo&nbsp;[]'
		},
		{	exclude: 'msie',
			start: 'foo []&nbsp;',
			execResult: 'foo []'
		},
		{	include: 'msie',	
			start: 'foo[] &nbsp;bar',
			execResult: 'foo[] bar'
		},
		{	exclude: 'msie',	
			start: 'foo[] &nbsp;bar',
			execResult: 'foo[]bar'
		},
		{	exclude: 'msie',	
			start: '<dl><dt>foo[<dt>]bar<dd>baz</dl>',
			execResult: '<dl><dt>foo[]bar<dd>baz</dl>'
		},
		{	include: 'msie',	
			start: '<dl><dt>foo[<dt>]bar<dd>baz</dl>',
			execResult: '<dl><dt>foo[]bar <dd>baz</dl>'
		},
		{	exclude: 'msie',		// this selection cannot be done in IE
			start: '<ol><li>foo[]<ul><li>bar</ul></li></ol>',
			execResult: '<ol><li>foo[]bar</li></ol>'
		},
		{	exclude: 'msie',		// this selection cannot be done in IE
			start: 'foo[]<ol><ol><li>bar</ol></ol>',
			execResult: 'foo[]bar'
		},
		{	exclude: 'msie',	
			start: '<p>foo<span style=color:#aBcDeF>[bar]</span>baz',
			execResult: '<p>foo[]<span style="color:#aBcDeF"></span>baz</p>'
		},
		{	include: 'msie',	
			start: '<p>foo<span style=color:#aBcDeF>[bar]</span>baz',
			execResult: '<p>foo<span style="color:#aBcDeF"></span>[]baz</p>'
		},
		{	start: '<p>foo<span style=color:#aBcDeF>{bar}</span>baz',
			execResult: '<p>foo<span style="color:#aBcDeF"></span>[]baz</p>'
		},
		{	exclude: 'msie',		// this selection cannot be done in IE
			start: '<p>foo{<span style=color:#aBcDeF>bar</span>}baz',
			execResult: '<p>foo[]baz</p>'
		},
		{	exclude: 'msie',
			start: 'foo[<div>]bar<p>baz</p></div>',
			execResult: 'foo[]bar<div><p>baz</p></div>'
		},
		{	include: 'msie',
			start: 'foo[<div>]bar<p>baz</p></div>',
			execResult: 'foo[]bar <div><p>baz</p></div>'
		},
		{	start: '<div><p>foo</p>bar[</div>]baz',
			execResult: '<div><p>foo</p>bar[]baz</div>'
		},
		{	exclude: 'msie',	
			start: '<div>foo<p>bar[</p></div>]baz',
			execResult: '<div>foo<p>bar[]baz</p></div>'
		},
		{	include: 'msie',
			start: '<div>foo<p>bar[</p></div>]baz',
			execResult: '<div>foo <p>bar[]baz</p></div>'
		},
		
		{	exclude: 'msie',		// this is an impossible selection in IE
			start: '<p>foo<br>{</p>]bar',
			execResult: '<p>foo[]bar</p>'
		},
		{	exclude: ['msie', 'mozilla'],		// this is an impossible selection in IE
			start: '<p>foo<br><br>{</p>]bar',
			execResult: '<p>foo[]bar</p>'
		},
		//@todo NS_ERROR_DOM_INDEX_SIZE_ERR exception in FF: rangy-core.js line 2055 at:
		//"rangeProto.setStart = function(node, offset) { this.nativeRange.setStart(node, offset);"
		//see also deletetest.js for that problem
		// This selection is not possible in ie
		{ 	exclude: 'msie',
			start: 'foo{<br><p>}bar</p>', 
	 		execResult: 'foo[]bar' 
		},
		// This selection is not possible in ie
		{	exclude: ['msie', 'mozilla'],
			start: 'foo<br><br>{<p>]bar</p>',
			execResult: 'foo[]bar'
		},
		{	include: ['mozilla'], // correct?!
			start: 'foo<br><br>{<p>]bar</p>',
			execResult: 'foo<br>bar{}'
		},
		{	start: '<p>foo<br>{</p><p>}bar</p>',
			execResult: '<p>foo[]bar</p>'
		},
		// This selection is not possible in ie
		{	exclude: ['msie', 'mozilla'],
			start: '<p>foo<br><br>{</p><p>}bar</p>',
			execResult: '<p>foo[]bar</p>'
		},
		{	include: 'mozilla', // correct?!
			start: '<p>foo<br><br>{</p><p>}bar</p>',
			execResult: '<p>foo<br>[]bar</p>'
		},
		{	exclude: 'msie',
			start: '<p>foo[bar<blockquote><p>baz]quz<p>qoz</blockquote', // interesting... is this broken by intention?
			execResult: '<p>foo[]quz</p><blockquote><p>qoz</p></blockquote>'
		},
		{	include: 'msie',
			start: '<p>foo[bar<blockquote><p>baz]quz<p>qoz</blockquote', // interesting... is this broken by intention?
			execResult: '<p>foo[]quz </p><blockquote><p>qoz</p></blockquote>'
		},
		{	include: 'msie',
			start: '<dl><dt>foo<dd>bar[<dd>]baz</dl>',
			execResult: '<dl><dt>foo <dd>bar[]baz</dl>'
		},			
		{	exclude: 'msie',
			start: '<dl><dt>foo<dd>bar[<dd>]baz</dl>',
			execResult: '<dl><dt>foo<dd>bar[]baz</dl>'
		},
		{	include: 'msie',
			start: '<div><p>foo</p><p>[bar</p><p>baz]</p></div>',
			execResult: '<div><p>foo</p><p>[]</p></div>'
		},
		{	include: 'mozilla',
			start: '<div><p>foo</p><p>[bar</p><p>baz]</p></div>',
			execResult: '<div><p>foo</p><p>{}</p></div>'
		},
		{	exclude: ['msie','mozilla'],
			start: '<div><p>foo</p><p>[bar</p><p>baz]</p></div>',
			execResult: '<div><p>foo[]</p><p></p></div>'
		},
		// Its not possible to create a selection like this in ie and chrome
		{	exclude: 'msie',
			start: 'foo[<p>]bar<br>baz</p>',
			execResult: 'foo[]bar<p>baz</p>'
		},
		{	exclude: ['msie', 'mozilla'],
			start: 'foo<b>[bar]</b>baz',
			execResult: 'foo[]<b></b>baz'
		},
		{	include: 'msie',
			start: 'foo<b>[bar]</b>baz',
			execResult: 'foo<b></b>[]baz'
		},
		{	include: 'mozilla',
			start: 'foo<b>[bar]</b>baz',
			execResult: 'foo<b>[]</b>baz'
		},
		{	exclude: 'msie',
			start: '<p>foo</p><p>[bar]</p><p>baz</p>',
			execResult: '<p>foo[]</p><p></p><p>baz</p>'
		},
		{	include: 'msie',
			start: '<p>foo</p><p>[bar]</p><p>baz</p>',
			execResult: '<p>foo</p><p>[]</p><p>baz</p>'
		},
		// This selection is not supported by ie. {} will be transformed to []
		{	exclude: 'msie',
			start: '<p>foo</p><p>{bar}</p><p>baz</p>',
			execResult: '<p>foo[]</p><p>baz</p>'
		},
		{	exclude: ['msie','mozilla'],
			start: '<p>foo</p><p>{bar</p>}<p>baz</p>',
			execResult: '<p>foo[]</p><p>baz</p>'
		},
		{	include: 'msie',
			start: '<p>foo</p><p>{bar</p>}<p>baz</p>',
			execResult: '<p>foo</p><p>[]</p><p>baz</p>'
		},
		{	include: 'mozilla',
			start: '<p>foo</p><p>{bar</p>}<p>baz</p>',
			execResult: '<p>foo</p><p>{}</p><p>baz</p>'
		},
		// Selection not supported by ie
		{	exclude: ['msie','mozilla'], 
			start: '<p>foo</p>{<p>bar}</p><p>baz</p>',
			execResult: '<p>foo[]</p><p>baz</p>'
		},
		{	include: 'mozilla',
			start: '<p>foo</p>{<p>bar}</p><p>baz</p>',
			execResult: '<p>foo</p>{}<p>baz</p>'
		},
		// Selection not supported by ie
		{	exclude: ['msie','mozilla'], 
			start: '<p>foo</p>{<p>bar</p>}<p>baz</p>',
			execResult: '<p>foo[]</p><p>baz</p>'
		},
		{	include: 'mozilla', 
			start: '<p>foo</p>{<p>bar</p>}<p>baz</p>',
			execResult: '<p>foo</p>{}<p>baz</p>'
		},
       	{	exclude: 'msie',
       		start: '<span>foo[]<span></span></span>bar',
			execResult: '<span>foo[]</span>ar'
		},
		{	start: '<div style=white-space:pre>foo[] &nbsp;bar</div>',
			execResult: '<div style=white-space:pre>foo[]&nbsp;bar</div>'
		},
		{	exclude: 'msie',
			start: '<div style=white-space:pre-wrap>foo[] &nbsp;bar</div>',
			execResult: '<div style=white-space:pre-wrap>foo[] bar</div>'
		},
		{	include: 'msie',
			start: '<div style=white-space:pre-wrap>foo[] &nbsp;bar</div>',
			execResult: '<div style=white-space:pre-wrap>foo[]&nbsp;bar</div>'
		},
		{	exclude: 'msie',	
			start: '<div style=white-space:pre-line>[]&nbsp; foo</div>',
			execResult: '<div style=white-space:pre-line>[] foo</div>'
		},
		{	include: 'msie',	
			start: '<div style=white-space:pre-line>[]&nbsp; foo</div>',
			execResult: '<div style=white-space:pre-line>[]&nbsp;foo</div>'
		},
		{	start: '<div style=white-space:nowrap>[]&nbsp; feo</div>',
			execResult: '<div style=white-space:nowrap>[]&nbsp;feo</div>'
		},
		{	start: '<div style=white-space:nowrap>[]&nbsp;feo</div>',
			execResult: '<div style=white-space:nowrap>[]feo</div>'
		},
		{	exclude: 'mozilla',
			start: '<ol><li>foo[]</li><br></ol><p>bar</p>',
			execResult: '<ol><li>foo[]</li></ol><p>bar</p>'
		},
		{	include: 'mozilla',
			start: '<ol><li>foo[]</li><br></ol><p>bar</p>',
			execResult: '<ol><li>foo{}</li></ol><p>bar</p>'
		},
		{	start: '<ol><li>{}</li><br></ol><p>bar',
			execResult: '<ol><li>{}</li></ol><p>bar</p>'
		},
		{	start: '<ol><li>foo[]<br></li></ol>bar',
			execResult: '<ol><li>foo[]</li></ol>bar'
		},
		{	start: '<ol><li>{}<br></li></ol>bar',
			execResult: '<ol><li>{}</li></ol>bar'
		},
		{	start: '<ol><li>foo</li><li>{}<br></li></ol>bar',
			execResult: '<ol><li>foo</li><li>{}</li></ol>bar'
		},
		{	start: '<ol><li>foo[]</li></ol><p>bar</p>',
			execResult: '<ol><li>foo[]bar</li></ol>'
		},
		{	start: '<ol><li>foo</li><li>{}<br></li></ol><p>bar',
			execResult: '<ol><li>foo</li><li>{}</li></ol><p>bar'
		},
		{	exclude: 'mozilla',
			start: '<ol><li>foo[]</li></ol><br>',
			execResult: '<ol><li>foo[]</li></ol>'
		},
		{	include: 'mozilla',
			start: '<ol><li>foo[]</li></ol><br>',
			execResult: '<ol><li>foo{}</li></ol>'
		},
		{	start: '<ol><li>foo[]<br></li></ol><br>',
			execResult: '<ol><li>foo[]</li></ol><br>'
		},
		{	start: '<ol><li>{}<br></li></ol><br>',
			execResult: '<ol><li>{}</li></ol><br>'
		},
		{	start: '<ol><li>foo</li><li>{}<br></li></ol><br>',
			execResult: '<ol><li>foo</li><li>{}</li></ol><br>'
		},
		{	exclude: 'msie',
			start: '<ol><li>foo[]</li></ol><p><br></p>',
			execResult: '<ol><li>foo[]</li></ol>'
		},
		{	include: 'msie',
			start: '<ol><li>foo[]</li></ol><p></p>',
			execResult: '<ol><li>foo[]</li></ol>'
		},
		{	start: '<ol><li>foo[]<br></li></ol><p><br></p>',
			execResult: '<ol><li>foo[]</li></ol><p><br></p>'
		},
		{	start: '<ol><li>{}<br></li></ol><p><br></p>',
			execResult: '<ol><li>{}</li></ol><p><br></p>'
		},
		{	start: '<ol><li>foo</li><li>{}<br></li></ol><p><br></p>',
			execResult: '<ol><li>foo</li><li>{}</li></ol><p><br></p>'
		},
		{	exclude: 'msie',	
			start: 'foo<b>{bar}</b>baz',
			execResult: 'foo[]baz'
		},
		{	include: 'msie',	
			start: 'foo<b>{bar}</b>baz',
			execResult: 'foo<b></b>[]baz'
		},
		{	exclude: 'msie',
			start: 'foo{<b>bar</b>}baz',
			execResult: 'foo[]<b></b>baz'
		},
		{	include: 'msie',
			start: 'foo{<b>bar</b>}baz',
			execResult: 'foo<b></b>[]baz'
		},
		{	exclude: 'msie',
			start: 'foo<span>[bar]</span>baz',
			execResult: 'foo[]<span></span>baz'
		},
		{	include: 'msie',
			start: 'foo<span>[bar]</span>baz',
			execResult: 'foo<span></span>[]baz'
		},
		{	exclude: 'msie',
			start: 'foo<span>{bar}</span>baz',
			execResult: 'foo[]baz'
		},
		{	include: 'msie',
			start: 'foo<span>{bar}</span>baz',
			execResult: 'foo<span></span>[]baz'
		},
		{	exclude: 'msie',
			start: 'foo{<span>bar</span>}baz',
			execResult: 'foo[]baz'
		},
		{	include: 'msie',
			start: 'foo{<span>bar</span>}baz',
			execResult: 'foo<span></span>[]baz'
		},
		{	start: '<b>foo[bar</b><i>baz]quz</i>',
			execResult: '<b>foo[]</b><i>quz</i>'
		},
		{	exclude: 'msie',	
			start: 'foo[]<span><span></span></span>bar',
			execResult: 'foo[]ar'
		},
		{	include: 'msie',	
			start: 'foo[]<span><span></span></span>bar',
			execResult: 'foo<span><span></span></span>[]ar'
		},
		{	exclude: 'msie',	
			start: 'foo[]<span></span><br>bar',
			execResult: 'foo[]bar'
		},
		{	include: 'msie',	
			start: 'foo[]<span></span><br>bar',
			execResult: 'foo<span></span>[]bar'
		},
		{	include: 'msie',
			start: '<ol><li>foo[]<br></li><li>bar</li></ol>',
			execResult: '<ol><li>foo[]</li><li>bar</li></ol>'
		},
		{	exclude: 'msie',
			start: '<ol><li>foo[]<br></li><li>bar</li></ol>',
			execResult: '<ol><li>foo[]bar</li></ol>'
		},
		{	start: '<ol><li>foo[]<li>bar<br>baz</ol>',
			execResult: '<ol><li>foo[]bar<br>baz</li></ol>'
		},			
		{	exclude: 'msie',
			start: 'foo []<span>&nbsp;</span> bar',
			execResult: 'foo []<span></span> bar'
		},
		{	include: 'msie',
			start: 'foo []<span>&nbsp;</span> bar',
			execResult: 'foo <span></span>[]bar'
		},
		{	include: 'msie',	
			start: 'foo<p>{bar</p>}baz',
			execResult: 'foo <p>[]</p>baz'
		},
		{	exclude: 'mozilla',
			start: 'foo{<p>bar}</p>baz',
			execResult: 'foo[]<br>baz'
		},
		{	include: 'mozilla',
			start: 'foo{<p>bar}</p>baz',
			execResult: 'foo{}<br>baz'
		},
		{	start: '<p>foo[</p>]bar',
			execResult: '<p>foo[]bar</p>'
		},
		{	exclude: 'msie',
			start: 'foo[]<span></span><span>bar</span>',
			execResult: 'foo[]<span>ar</span>'
		},
		// IE jumps into the empty span after the character of the next textnode has been deleted. Deletion of the emptyspan will be omitted
		{	include: 'msie',
			start: 'foo[]<span></span><span>bar</span>',
			execResult: 'foo<span></span>{}<span>ar</span>'
		},
		{	include: 'msie',
			start: 'foo[] <span></span><span>bar</span>',
			execResult: 'foo<span></span>{}<span>bar</span>'
		},
		{	exclude: 'msie',
			start: 'foo<span>{}</span><span>bar</span>',
			execResult: 'foo[]<span></span>{}<span>ar</span>'
		},
		//  IE will automatically jump between both spans since there is no free space in the textnode
		{	include: 'msie',
			start: 'foo<span>{}</span><span>bar</span>',
			execResult: 'foo<span></span>{}<span>ar</span>'
		},
		{	exclude: 'msie',	
			start: 'foo[]<span></span>bar',
			execResult: 'foo[]ar'
		},
		{	include: 'msie',	
			start: 'foo[]<span></span>bar',
			execResult: 'foo<span></span>[]ar'
		},
		{	start: 'foo[]\n\t\t\tbar',
			execResult: 'foo[]bar'
		},
		{	start: 'foo[]     bar',
			execResult: 'foo[]bar'
		}
	

// Tests with no expected result
//			{	start: '<p><font color=blue>foo[]</font><p>bar',
//				execResult: ''
//			},
//			{	start: '<p><font color=blue>foo[]</font><p><font color=brown>bar</font>',
//				execResult: ''
//			},
//			{	start: '<p>foo[]<p><font color=brown>bar</font>',
//				execResult: ''
//			},
//			{  	start: '&#x5e9;&#x5c1;&#x5b8;&#x5dc;[]&#x5d5;&#x5b9;&#x5dd;',
//				execResult: ''
//			},
//			{	start: 'foo[<dl><dt>]bar<dd>baz</dl>',
//				execResult: ''
//			},
//			{	start: 'foo[<dl><dd>]bar</dl>',
//				execResult: ''
//			},
//			{	start: '<dl><dt>foo[<dd>]bar</dl>',
//				execResult: ''
//			},
//			{	start: '<table><tbody><tr><th>foo<th>[bar]<th>baz<tr><td>quz<td>qoz<td>qiz</table>',
//				execResult: ''
//			},
//			{	start: '<table><tbody><tr><th>foo<th>ba[r<th>b]az<tr><td>quz<td>qoz<td>qiz</table>',
//				execResult: ''
//			},
//			{	start: '<table><tbody><tr><th>fo[o<th>bar<th>b]az<tr><td>quz<td>qoz<td>qiz</table>',
//				execResult: ''
//			},
//			{	start: '<table><tbody><tr><th>foo<th>bar<th>ba[z<tr><td>q]uz<td>qoz<td>qiz</table>',
//				execResult: ''
//			},
//			{	start: '<table><tbody><tr><th>[foo<th>bar<th>baz]<tr><td>quz<td>qoz<td>qiz</table>',
//				execResult: ''
//			},
//			{	start: '<table><tbody><tr><th>[foo<th>bar<th>baz<tr><td>quz<td>qoz<td>qiz]</table>',
//				execResult: ''
//			},
//			{	start: '{<table><tbody><tr><th>foo<th>bar<th>baz<tr><td>quz<td>qoz<td>qiz</table>}',
//				execResult: ''
//			},
//			{	start: '<table><tbody><tr><td>foo<td>ba[r<tr><td>baz<td>quz<tr><td>q]oz<td>qiz</table>',
//				execResult: ''
//			},
//			{	start: '<p>fo[o<table><tr><td>b]ar</table><p>baz',
//				execResult: ''
//			},
//			{	start: '<p>foo<table><tr><td>ba[r</table><p>b]az',
//				execResult: ''
//			},
//			{	start: '<p>fo[o<table><tr><td>bar</table><p>b]az',
//				execResult: ''
//			},			
//			{	start: 'foo[]<dl><dt>bar<dd>baz</dl>',
//				execResult: ''
//			},
//			{	start: 'foo[]<dl><dd>bar</dl>',
//				execResult: ''
//			},
//			{	start: '<dl><dt>foo[]<dd>bar</dl>',
//				execResult: ''
//			},
//			{	start: '<dl><dt>foo[]<dt>bar<dd>baz</dl>',
//				execResult: ''
//			},
//			{	start: '<dl><dt>foo<dd>bar[]<dd>baz</dl>',
//				execResult: ''
//			},
//			{	start: '<table><tr><td>foo[]<td><hr>bar</table>',
//				execResult: ''
//			},
//			{	start: '<table><tr><td>foo[]<tr><td><hr>bar</table>',
//				execResult: ''
//			},
//			{	start: 'foo[]<table><tr><td>bar</table>baz',
//				execResult: ''
//			},
//			{	start: 'foo<table><tr><td>bar[]</table>baz',
//				execResult: ''
//			},
//			{	start: '<p>foo[]<table><tr><td>bar</table><p>baz',
//				execResult: ''
//			},
//			{	start: '<table><tr><td>foo[]<td>bar</table>',
//				execResult: ''
//			},
//			{	start: '<table><tr><td>foo[]<tr><td>bar</table>',
//				execResult: ''
//			},
//			{	start: 'foo[]<br><table><tr><td>bar</table>baz',
//				execResult: ''
//			},
//			{	start: '<table><tr><td>{}</table>foo', // no td specific tests for us.
//				execResult: ''
//			},
//			{	start: '<table><tr><td>{}<br></table>foo',
//				execResult: ''
//			},
//			{	start: '<table><tr><td>{}<span><br></span></table>foo',
//				execResult: ''
//			}
			
		]
}

//var tests = specifictests;
var tests = alltests;