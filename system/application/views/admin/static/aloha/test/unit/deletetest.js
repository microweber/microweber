/*
var tests = { 
			defaultCommand: 'delete',
	tests: [		
		{	start: 'foo\n\t\t\t[]bar',
			execResult: 'foo[]bar'
		},
		{	start: 'foo     []bar',
			execResult: 'foo[]bar'
		}
  	] 
  }
  */
var tests = {
	defaultCommand: 'delete',
	tests: [
		{  	start: '[]foo',
			execResult: '[]foo'
		},
		{  	start: '<span>[]foo</span>',
			execResult: '<span>[]foo</span>'
		},
		{  	exclude: [ 'msie', 'mozilla' ],
			start: '<span>{}</span>',
			execResult: '{}<span></span>'
		},
		{  	include: [ 'msie', 'mozilla' ],
			start: '<span>{}</span>',
			execResult: '<span>{}</span>'
		},
		{  	start: '<span>{}<br></span>',
			execResult: '<span>{}<br></span>'
		},
		{  	exclude: 'mozilla',
			start: '<span><br>{}</span>',
			execResult: '<span>{}<br data-test-exclude="msie"></span>'
		},
		{  	include: 'mozilla',
			start: '<span><br>{}</span>',
			execResult: '<span>{}</span>'
		},
		{  	start: '<span><br>{}<br></span>',
			execResult: '<span>{}<br></span>'
		},
		{	exclude: 'msie',
			start: '<p>f[]</p>',
			execResult: '<p>{}<br class="aloha-end-br"/></p>'
		},
		{	include: 'msie',
			start: '<p>f[]</p>',
			execResult: '<p>[]</p>'
		},
		{  	start: '<p>[]foo</p>',
			execResult: '<p>[]foo</p>'
		},
		{  	
			exclude: ['mozilla'],
			start: '<p>{}</p>',
			execResult: '{}<p></p>'
		},
		{  	
			include: ['mozilla'],
			start: '<p>{}</p>',
			execResult: '<p></p>{}'
		},
		{  	
			include: ['msie'],
			start: '<p>{}</p>',
			execResult: '<p>{}</p>'
		},
		{  	start: '<p>{}<br></p>',
			execResult: '<p>{}<br data-test-include="msie"></p>'
		},
		{  	exclude: 'mozilla',
			start: '<p><br>{}</p>',
			execResult: '<p>{}<br data-test-exclude="msie"></p>'
		},
		{  	include: 'mozilla',
			start: '<p><br>{}</p>',
			execResult: '<p>{}</p>'
		},
		{  	start: '<p><br>{}<br></p>',
			execResult: '<p>{}<br></p>'
		},
		{  	start: 'foo[]bar',
			execResult: 'fo[]bar'
		},
		{	start: '<span>foo</span>{}<span>bar</span>',
			execResult: '<span>fo[]</span><span>bar</span>'
		},
		{  	
			exclude: ['msie'],
			start: '<span>foo[</span><span>]bar</span>',
			execResult: '<span>fo[]</span><span>bar</span>'
		},
		{  	
			include: ['msie'],
			start: 'foo<span style=display:none>bar</span>[]baz',
			execResult: 'fo[]<span style=display:none>bar</span>baz'
		},
//		{  	start: 'foo<script>bar</script>[]baz',
//			execResult: 'foo<script>bar</script>[]baz'
//		},
	
		{  	start: 'fo&ouml;[]bar',
			execResult: 'fo[]bar'
		},
		{  	start: 'foo&#x308;[]bar',
			execResult: 'foo[]bar'
		},
		{  	start: 'foo&#x308;&#x327;[]bar',
			execResult: 'foo&#x308;[]bar'
		},
		{  	start: '&ouml;[]bar',
			execResult: '[]bar'
		},
		{  	start: 'o&#x308;[]bar',
			execResult: 'o[]bar'
		},
		{  	start: 'o&#x308;&#x327;[]bar',
			execResult: 'o&#x308;[]bar'
		},
	
		{  	start: '&#x5e9;&#x5c1;&#x5b8;[]&#x5dc;&#x5d5;&#x5b9;&#x5dd;',
			execResult: '&#x5e9;&#x5c1;[]&#x5dc;&#x5d5;&#x5b9;&#x5dd;'
		},
		{  	start: '&#x5e9;&#x5c1;&#x5b8;&#x5dc;&#x5d5;&#x5b9;[]&#x5dd;',
			execResult: '&#x5e9;&#x5c1;&#x5b8;&#x5dc;&#x5d5;[]&#x5dd;'
		},
		{  	start: '<p>foo</p><p>[]bar</p>',
			execResult: '<p>foo[]bar</p>'
		},
		{  	exclude: 'msie',					// TODO this test will always fail in IE, because the selection will always snap into the p
			start: '<p>foo</p>[]bar',
			execResult: '<p>foo[]bar</p>'
		},
		{  	start: 'foo<p>[]bar</p>',
			execResult: 'foo[]bar'
		},
		{  	start: '<p>foo<br></p><p>[]bar</p>',
			execResult: '<p>foo[]bar</p>'
		},
		{  	exclude: ['msie', 'mozilla'],
			start: '<p>foo</p><p>{}<br class="aloha-end-br"></p>',
			execResult: '<p>foo[]</p>'
		},
		{  	include: ['mozilla'],
			start: '<p>foo</p><p>{}<br class="aloha-end-br"></p>',
			execResult: '<p>foo{}</p>'
		},
		{  	include: 'msie',
			start: '<p>foo</p><p>{}</p>',
			execResult: '<p>foo[]</p>'
		},
		{  	exclude: 'msie',
			start: '<p>foo</p><p><br class="aloha-end-br"/></p><p>{}<br class="aloha-end-br"/></p>',
			execResult: '<p>foo</p><p>{}<br class="aloha-end-br"/></p>'
		},
		{  	include: 'msie',
			start: '<p>foo</p><p></p><p>{}</p>',
			execResult: '<p>foo</p><p>{}</p>'
		},
		{  	exclude: 'msie',					// TODO this test will always fail in IE, because the selection will always snap into the p
			start: '<p>foo<br></p>[]bar',
			execResult: '<p>foo[]bar</p>'
		},
		{  	start: 'foo<br><p>[]bar</p>',
			execResult: 'foo[]bar'
		},
		{  	
			start: '<p>foo<br><br></p><p>[]bar</p>',
			execResult: '<p>foo<br>[]bar</p>'
		},
		{  	start: '<p>foo<br><br></p><p>[]bar</p>',
			execResult: '<p>foo<br>[]bar</p>'
		},
		{  	exclude: 'msie',					// TODO this test will always fail in IE, because the selection will always snap into the p
			start: '<p>foo<br><br></p>[]bar',
			execResult: '<p>foo<br>[]bar</p>'
		},
		{  	exclude: 'mozilla',
			start: 'foo<br><br><p>[]bar</p>',
			execResult: 'foo<br><p>[]bar</p>'
		},
		{  	include: 'mozilla',
			start: 'foo<br><br><p>[]bar</p>',
			execResult: 'foo<br><p>{}bar</p>'
		},
		{  	start: '<div><p>foo</p></div><p>[]bar</p>',
			execResult: '<div><p>foo[]bar</p>'
		},
		{  	start: '<p>foo</p><div><p>[]bar</p></div>',
			execResult: '<p>foo[]bar</p>'
		},
		{  	start: '<div><p>foo</p></div><div><p>[]bar</p></div>',
			execResult: '<div><p>foo[]bar</p></div>'
		},
		{  	start: '<div><p>foo</p></div>[]bar',
			execResult: '<div><p>foo[]bar</p></div>'
		},
		{  	start: 'foo<div><p>[]bar</p></div>',
			execResult: 'foo[]bar'
		},
	
		{  	start: '<div>foo</div><div>[]bar</div>',
			execResult: '<div>foo[]bar</div>'
		},
		{  	exclude: 'msie',					// TODO this test will always fail in IE, because the selection will always snap into the p
			start: '<pre>foo</pre>[]bar',
			execResult: '<pre>foo[]bar</pre>'
		},
		{  	start: 'foo<br>[]bar',
			execResult: 'foo[]bar'
		},
		{  	exclude: 'mozilla',
			start: 'foo<br><b>[]bar</b>',
			execResult: 'foo[]<b>bar</b>'
		},
		{  	include: 'mozilla',
			start: 'foo<br><b>[]bar</b>',
			execResult: 'foo{}<b>bar</b>'
		},
		{  	start: 'foo<hr>[]bar',
			execResult: 'foo[]bar'
		},
		{  	exclude: 'mozilla',
			start: '<p>foo</p><hr><p>[]bar</p>',
			execResult: '<p>foo</p><p>[]bar</p>'
		},
		{  	include: 'mozilla',
			start: '<p>foo</p><hr><p>[]bar</p>',
			execResult: '<p>foo</p><p>{}bar</p>'
		},
		{  	exclude: 'mozilla',
			start: '<p>foo</p><br><p>[]bar</p>',
			execResult: '<p>foo</p><p>[]bar</p>'
		},
		{  	include: 'mozilla',
			start: '<p>foo</p><br><p>[]bar</p>',
			execResult: '<p>foo</p><p>{}bar</p>'
		},
		{  	exclude: 'mozilla',
			start: '<p>foo</p><br><br><p>[]bar</p>',
			execResult: '<p>foo</p><br><p>[]bar</p>'
		},
		{  	include: 'mozilla',
			start: '<p>foo</p><br><br><p>[]bar</p>',
			execResult: '<p>foo</p><br><p>{}bar</p>'
		},
		{ 
			exclude: ['msie','mozilla'],
		 	start: '<p>foo</p><img><p>[]bar</p>',
			execResult: '<p>foo</p><img>{}bar'
		},
		{ 
			include: ['msie', 'mozilla'],
		 	start: '<p>foo</p><img><p>[]bar</p>',
			execResult: '<p>foo</p><img>[]bar'
		},
//		{  	start: 'foo<img>[]bar',
//			execResult: 'foo[]bar'
//		},
	
		// IE and Chrome will behave differently on this one as IE will move
		// the range outside the <a> thus achieving a different behaviour
		{  	
			exclude: ['msie'],
			start: '<a>foo[]</a>bar',
			execResult: '<a>fo[]</a>bar'
		},
		{  	
			include: ['msie'],
			start: '<a>foo[]</a>bar',
			execResult: 'foo[]bar'
		},
		{  	start: '<a>foo</a>[]bar',
			execResult: 'foo[]bar'
		},
		{  	start: '<a href="/">foo</a>[]bar',
			execResult: 'foo[]bar'
		},
		{  	start: '<a name=abc>foo</a>[]bar',
			execResult: 'foo[]bar'
		},
		{  	start: '<a href="/" name=abc>foo</a>[]bar',
			execResult: 'foo[]bar'
		},
		{  	exclude: 'mozilla',
			start: '<span><a>foo</a></span>[]bar',
			execResult: '<span>foo[]</span>bar'
		},
		{  	include: 'mozilla',
			start: '<span><a>foo</a></span>[]bar',
			execResult: '<span>foo</span>[]bar'
		},
		{  	exclude: 'mozilla',
			start: '<span><a href="/">foo</a></span>[]bar',
			execResult: '<span>foo[]</span>bar'
		},
		{  	include: 'mozilla',
			start: '<span><a href="/">foo</a></span>[]bar',
			execResult: '<span>foo</span>[]bar'
		},
		{  	exclude: 'mozilla',
			start: '<span><a name=abc>foo</a></span>[]bar',
			execResult: '<span>foo[]</span>bar'
		},
		{  	include: 'mozilla',
			start: '<span><a name=abc>foo</a></span>[]bar',
			execResult: '<span>foo</span>[]bar'
		},
		{  	exclude: 'mozilla',
			start: '<span><a href="/" name=abc>foo</a></span>[]bar',
			execResult: '<span>foo[]</span>bar'
		},
		{  	include: 'mozilla',
			start: '<span><a href="/" name=abc>foo</a></span>[]bar',
			execResult: '<span>foo</span>[]bar'
		},
		{  	start: 'foo<a>[]bar</a>',
			execResult: 'fo[]<a>bar</a>'
		},
		{  	start: 'foo<a href="/">[]bar</a>',
			execResult: 'fo[]<a href="/">bar</a>'
		},
		{  	start: 'foo<a name=abc>[]bar</a>',
			execResult: 'fo[]<a name=abc>bar</a>'
		},
		{  	start: 'foo<a href="/" name=abc>[]bar</a>',
			execResult: 'fo[]<a href="/" name=abc>bar</a>'
		},
		{  	start: 'foo &nbsp;[]bar',
			execResult: 'foo []bar'
		},
		{  	start: 'foo&nbsp; []bar',
			execResult: 'foo []bar'
		},
		{  	start: 'foo&nbsp;&nbsp;[]bar',
			execResult: 'foo []bar'
		},
		{  	start: 'foo  []bar',
			execResult: 'foo[]bar'
		},
		{  	
			start: '<b>foo </b>&nbsp;[]bar',
			execResult: '<b>foo []</b>bar'
		},
		{  	
			start: '<b>foo&nbsp;</b> []bar',
			execResult: '<b>foo []</b>bar'
		},
		{  	
			start: '<b>foo&nbsp;</b>&nbsp;[]bar',
			execResult: '<b>foo []</b>bar'
		},
		{  	
			start: '<b>foo </b> []bar',
			execResult: '<b>foo[]</b>bar'
		},
	
//		// Tables with collapsed selection
//		{  	start: 'foo<table><tr><td>[]bar</table>baz',
//			execResult: 'foo<table><tr><td>[]bar</table>baz'
//		},
//		{  	start: 'foo<table><tr><td>bar</table>[]baz',
//			execResult: 'foo<table><tr><td>bar</table>[]baz'
//		},
//		{  	start: '<p>foo<table><tr><td>[]bar</table><p>baz',
//			execResult: '<p>foo<table><tr><td>[]bar</table><p>baz'
//		},
//		{  	start: '<p>foo<table><tr><td>bar</table><p>[]baz',
//			execResult: '<p>foo<table><tr><td>bar</table><p>[]baz'
//		},
//		{  	start: '<table><tr><td>foo<td>[]bar</table>',
//			execResult: '<table><tr><td>foo<td>[]bar</table>'
//		},
//		{  	start: '<table><tr><td>foo<tr><td>[]bar</table>',
//			execResult: '<table><tr><td>foo<tr><td>[]bar</table>'
//		},
//	
//		{  	start: 'foo<br><table><tr><td>[]bar</table>baz',
//			execResult: 'foo<br><table><tr><td>[]bar</table>baz'
//		},
//		{  	start: 'foo<table><tr><td>bar<br></table>[]baz',
//			execResult: 'foo<table><tr><td>bar<br></table>[]baz'
//		},
//		{  	start: '<p>foo<br><table><tr><td>[]bar</table><p>baz',
//			execResult: '<p>foo<br><table><tr><td>[]bar</table><p>baz'
//		},
//		{  	start: '<p>foo<table><tr><td>bar<br></table><p>[]baz',
//			execResult: '<p>foo<table><tr><td>bar<br></table><p>[]baz'
//		},
//		{  	start: '<table><tr><td>foo<br><td>[]bar</table>',
//			execResult: '<table><tr><td>foo<br><td>[]bar</table>'
//		},
//		{  	start: '<table><tr><td>foo<br><tr><td>[]bar</table>',
//			execResult: '<table><tr><td>foo<br><tr><td>[]bar</table>'
//		},
//	
//		{  	start: 'foo<br><br><table><tr><td>[]bar</table>baz',
//			execResult: 'foo<br><br><table><tr><td>[]bar</table>baz'
//		},
//		{  	start: 'foo<table><tr><td>bar<br><br></table>[]baz',
//			execResult: 'foo<table><tr><td>bar<br><br></table>[]baz'
//		},
//		{  	start: '<p>foo<br><br><table><tr><td>[]bar</table><p>baz',
//			execResult: '<p>foo<br><br><table><tr><td>[]bar</table><p>baz'
//		},
//		{  	start: '<p>foo<table><tr><td>bar<br><br></table><p>[]baz',
//			execResult: '<p>foo<table><tr><td>bar<br><br></table><p>[]baz'
//		},
//		{  	start: '<table><tr><td>foo<br><br><td>[]bar</table>',
//			execResult: '<table><tr><td>foo<br><br><td>[]bar</table>'
//		},
//		{  	start: '<table><tr><td>foo<br><br><tr><td>[]bar</table>',
//			execResult: '<table><tr><td>foo<br><br><tr><td>[]bar</table>'
//		},
//	
//		{  	start: 'foo<hr><table><tr><td>[]bar</table>baz',
//			execResult: 'foo<hr><table><tr><td>[]bar</table>baz'
//		},
//		{  	start: 'foo<table><tr><td>bar<hr></table>[]baz',
//			execResult: 'foo<table><tr><td>bar<hr></table>[]baz'
//		},
//		{  	start: '<table><tr><td>foo<hr><td>[]bar</table>',
//			execResult: '<table><tr><td>foo<hr><td>[]bar</table>'
//		},
//		{  	start: '<table><tr><td>foo<hr><tr><td>[]bar</table>',
//			execResult: '<table><tr><td>foo<hr><tr><td>[]bar</table>'
//		},
//	
		// Lists with collapsed selection
		{  	
			exclude: ['msie'],
			start: 'foo<ol><li>[]bar</li><li>baz</li></ol>',
			execResult: 'foo<p>[]bar</p><ol><li>baz</li></ol>'
		},
		{  	
			include: ['msie'],
			start: 'foo<ol><li>[]bar</li><li>baz</li></ol>',
			execResult: 'foo <p>[]bar</p><ol><li>baz</li></ol>'
		},
		{  	
			exclude: ['msie'],
			
			start: 'foo<br><ol><li>[]bar</li><li>baz</li></ol>',
			execResult: 'foo<p>[]bar</p><ol><li>baz</li></ol>'
		},
		{  	
			include: ['msie'],
			start: 'foo<br><ol><li>[]bar</li><li>baz</li></ol>',
			execResult: 'foo<br><p>[]bar</p><ol><li>baz</li></ol>'
		},
		{  	start: 'foo<br><br><ol><li>[]bar</li><li>baz</li></ol>',
			execResult: 'foo<br><br><p>[]bar</p><ol><li>baz</li></ol>'
		},
		{  	start: '<ol><li>foo</li><li>[]bar</li></ol>',
			execResult: '<ol><li>foo<br>[]bar</li></ol>'
		},
		{  	start: '<ol><li>foo<br></li><li>[]bar</li></ol>',
			execResult: '<ol><li>foo<br>[]bar</li></ol>'
		},
		{  	start: '<ol><li>foo<br><br></li><li>[]bar</li></ol>',
			execResult: '<ol><li>foo<br><br>[]bar</li></ol>'
		},
		{  	start: '<ol><li>foo</li><li>[]bar<br>baz</li></ol>',
			execResult: '<ol><li>foo<br>[]bar<br>baz</li></ol>'
		},
		{  	start: '<ol><li>foo<br>bar</li><li>[]baz</li></ol>',
			execResult: '<ol><li>foo<br>bar<br>[]baz</li></ol>'
		},

//		those tests have been removed as html5 allows only flow content within lists (http://dev.w3.org/html5/spec/Overview.html#the-li-element)
//
//		{  	start: '<ol><li><p>foo</p>{}bar</li></ol>',
//			execResult: '<ol><li><p>foo[]bar</p></li></ol>'
//		},
//		{  	start: '<ol><li><p>foo</li><li>[]bar</li></ol>',
//			execResult: '<ol><li><p>foo</p>[]bar</li></ol>'
//		},
//		{  	
//			exclude: ['msie'],
//			start: '<ol><li>foo</li><li><p>[]bar</li></ol>',
//			execResult: '<ol><li>foo<p>[]bar</p></li></ol>'
//		},
//		{  	
//			include: ['msie'],
//			start: '<ol><li>foo</li><li><p>[]bar</p></li></ol>',
//			execResult: '<ol><li>foo <p>[]bar</p></li></ol>'
//		},
//		{  	
//			exclude: ['msie'],
//			start: '<ol><li><p>foo</p></li><li><p>[]bar</p></li></ol>',
//			execResult: '<ol><li><p>foo</p><p>[]bar</p></li></ol>'
//		},
//		{  	
//			include: ['msie'],
//			start: '<ol><li><p>foo</p></li><li><p>[]bar</p></li></ol>',
//			execResult: '<ol><li><p>foo </p><p>[]bar</p></li></ol>'
//		},
		{  	
			start: '<ol><li>foo<ul><li>[]bar</li></ul></li></ol>',
			execResult: '<ol><li>foo</li><li>[]bar</li></ol>'
		},
		{  	
			exclude: ['msie'],
			start: 'foo<ol><ol><li>[]bar</li></ol></ol>',
			execResult: 'foo<ol><li>[]bar</li></ol>'
		},
		{  	
			include: ['msie'],
			start: 'foo<ol><ol><li>[]bar</li></ol></ol>',
			execResult: 'foo <ol><li>[]bar</li></ol>'
		},		
		{  	
			exclude: ['msie'],		
			start: 'foo<div><ol><li>[]bar</li></ol></div>',
			execResult: 'foo<div><p>[]bar</p></div>'
		},
		{  	
			include: ['msie'],		
			start: 'foo<div><ol><li>[]bar</li></ol></div>',
			execResult: 'foo <div><p>[]bar</p></div>'
		},
		{
			start: '<ul><li>foo</li><li><br>[]bar</li></ul>',
			execResult: '<ul><li>foo</li><li>[]bar</li></ul>'
		},

//		{  	start: 'foo<dl><dt>[]bar<dd>baz</dl>',
//			execResult: 'foo<dl><dt>[]bar<dd>baz</dl>'
//		},
//		{  	start: 'foo<dl><dd>[]bar</dl>',
//			execResult: 'foo<dl><dd>[]bar</dl>'
//		},
//		{  	start: '<dl><dt>foo<dd>[]bar</dl>',
//			execResult: '<dl><dt>foo<dd>[]bar</dl>'
//		},
//		{  	start: '<dl><dt>foo<dt>[]bar<dd>baz</dl>',
//			execResult: '<dl><dt>foo<dt>[]bar<dd>baz</dl>'
//		},
//		{  	start: '<dl><dt>foo<dd>bar<dd>[]baz</dl>',
//			execResult: '<dl><dt>foo<dd>bar<dd>[]baz</dl>'
//		},
	
		{  	start: '<ol><li>foo</ol>[]bar',
			execResult: '<ol><li>foo[]bar</li></ol>'
		},
		{  	start: '<ol><li>foo<br></ol>[]bar',
			execResult: '<ol><li>foo[]bar</li></ol>'
		},
		{  	start: '<ol><li>foo<br><br></ol>[]bar',
			execResult: '<ol><li>foo<br>[]bar</li></ol>'
		},
		{  	start: '<ol><li><br></ol>[]bar',
			execResult: '<ol><li>[]bar</li></ol>'
		},
		{  	
			exclude: ['msie'],
			start: '<ol><li>foo<li><br></ol>[]bar',
			execResult: '<ol><li>foo</li><li>[]bar</li></ol>'
		},
		{  	
			include: ['msie'],
			start: '<ol><li>foo<li><br></ol>[]bar',
			execResult: '<ol><li>foo </li><li>[]bar</li></ol>'
		},
	
		// Indented stuff with collapsed selection
		{  	
			start: 'foo<blockquote>[]bar</blockquote>',
			execResult: 'foo<br>[]bar'
		},
		{  	
			exclude: ['msie'],
			start: 'foo<blockquote><blockquote>[]bar</blockquote></blockquote>',
			execResult: 'foo<blockquote>[]bar</blockquote>'
		},
		{  	
			include: ['msie'],
			start: 'foo<blockquote><blockquote>[]bar</blockquote></blockquote>',
			execResult: 'foo <blockquote>[]bar</blockquote>'
		},
		{  	
			exclude: ['msie'],
			start: 'foo<blockquote><div>[]bar</div></blockquote>',
			execResult: 'foo<div>[]bar</div>' // not entirely sure if this is correct
		},
		{  	
			include: ['msie'],
			start: 'foo<blockquote><div>[]bar</div></blockquote>',
			execResult: 'foo <div>[]bar</div>' // not entirely sure if this is correct
		},
		{  	exclude: 'msie',
			start: 'foo<blockquote style="color: blue">[]bar</blockquote>',
			execResult: 'foo<div style="color: blue">[]bar</div>'
		},
		{  	include: 'msie',
			start: 'foo<blockquote style="color: blue">[]bar</blockquote>',
			execResult: 'foo <div style="color: blue">[]bar</div>'
		},
	
		{  	
			exclude: ['msie'],
			start: 'foo<blockquote><blockquote><p>[]bar<p>baz</blockquote></blockquote>',
			execResult: 'foo<blockquote><p>[]bar</p><blockquote><p>baz</p></blockquote></blockquote>'
		},
		{  	
			include: ['msie'],
			start: 'foo<blockquote><blockquote><p>[]bar<p>baz</blockquote></blockquote>',
			execResult: 'foo <blockquote><p>[]bar </p><blockquote><p>baz</p></blockquote></blockquote>'
		},
		{  	
			exclude: ['msie'],
			start: 'foo<blockquote><div><p>[]bar<p>baz</div></blockquote>',
			execResult: 'foo<div><p>[]bar</p><blockquote><p>baz</p></blockquote></div>'
		},
		{  	
			include: ['msie'],
			start: 'foo<blockquote><div><p>[]bar<p>baz</div></blockquote>',
			execResult: 'foo <div><p>[]bar </p><blockquote><p>baz</p></blockquote></div>'
		},
		{  	
			exclude: ['msie'],
			start: 'foo<blockquote style="color: blue"><p>[]bar<p>baz</blockquote>',
			execResult: 'foo<div style="color: blue"><p>[]bar</p><blockquote><p>baz</p></blockquote></div>'
		},
		{  	
			include: ['msie'],
			start: 'foo<blockquote style="color: blue"><p>[]bar<p>baz</blockquote>', 
			execResult: 'foo <div style="color: blue"><p>[]bar </p><blockquote><p>baz</p></blockquote></div>' // TODO this is wrong on ie 8.0.7600
		},		
	
		{  	
			exclude: ['msie'],
			start: 'foo<blockquote><p><b>[]bar</b><p>baz</blockquote>',
			execResult: 'foo<p><b>[]bar</b></p><blockquote><p>baz</p></blockquote>'
		},
		{  	
			include: ['msie'],
			start: 'foo<blockquote><p><b>[]bar</b><p>baz</blockquote>',
			execResult: 'foo <p><b>[]bar</b> </p><blockquote><p>baz</p></blockquote>'
		},
		{  	
			exclude: ['msie'],
			start: 'foo<blockquote><p><strong>[]bar</strong><p>baz</blockquote>',
			execResult: 'foo<p><strong>[]bar</strong></p><blockquote><p>baz</p></blockquote>'
		},
		{  	
			include: ['msie'],
			start: 'foo<blockquote><p><strong>[]bar</strong><p>baz</blockquote>',
			execResult: 'foo <p><strong>[]bar</strong> </p><blockquote><p>baz</p></blockquote>'
		},
		{  	
			exclude: ['msie'],
			start: 'foo<blockquote><p><span>[]bar</span><p>baz</blockquote>',
			execResult: 'foo<p><span>[]bar</span></p><blockquote><p>baz</p></blockquote>'
		},
		{  	
			include: ['msie'],
			start: 'foo<blockquote><p><span>[]bar</span><p>baz</blockquote>',
			execResult: 'foo <p><span>[]bar</span> </p><blockquote><p>baz</p></blockquote>'
		},
	
		{  	
			exclude: ['msie'],
			start: 'foo<blockquote><ol><li>[]bar</ol></blockquote><p>extra',
			execResult: 'foo<blockquote><p>[]bar</p></blockquote><p>extra</p>'
		},
		{  	
			include: ['msie'],
			start: 'foo<blockquote><ol><li>[]bar</ol></blockquote><p>extra',
			execResult: 'foo <blockquote><p>[]bar</p></blockquote><p>extra</p>'
		},
		{  	
			exclude: ['msie'],
			start: 'foo<blockquote>bar<ol><li>[]baz</ol>quz</blockquote><p>extra',
			execResult: 'foo<blockquote>bar<p>[]baz</p>quz</blockquote><p>extra</p>'
		},
		{  	
			include: ['msie'],
			start: 'foo<blockquote>bar<ol><li>[]baz</ol>quz</blockquote><p>extra',
			execResult: 'foo <blockquote>bar <p>[]baz</p>quz</blockquote><p>extra</p>'
		},
		{  	
			exclude: ['msie'],
			start: 'foo<blockquote><ol><li>bar</li><ol><li>[]baz</ol><li>quz</ol></blockquote><p>extra',
			execResult: 'foo<blockquote><ol><li>bar</li><li>[]baz</li><li>quz</li></ol></blockquote><p>extra</p>'
		},
		{  	
			include: ['msie'],
			start: 'foo<blockquote><ol><li>bar</li><ol><li>[]baz</ol><li>quz</ol></blockquote><p>extra',
			execResult: 'foo <blockquote><ol><li>bar</li><li>[]baz </li><li>quz</li></ol></blockquote><p>extra</p>'
		},
	
		// Invisible stuff with collapsed selection
		// NOTE on these broken tests setting the cursor after deletion is broken
		{  	start: 'foo<span></span>[]bar', // broken - doCleanup should fix this
			execResult: 'fo[]bar'
		},
		{  	start: 'foo<span><span></span></span>[]bar', // broken - doCleanup should fix this
			execResult: 'fo[]bar'
		},
		{  	start: 'foo<quasit></quasit>[]bar', // broken - doCleanup should fix this
			execResult: 'fo[]bar'
		},
		{  	start: 'foo<br><span></span>[]bar',
			execResult: 'foo[]bar'
		},
		{  	exclude: 'msie',
			start: '<span>foo<span></span></span>[]bar',
			execResult: '<span>fo[]<span></span></span>bar'
		},
		{  	include: 'msie',
			start: '<span>foo<span></span></span>[]bar',
			execResult: '<span>fo[]</span>bar'
		},
		{  	
			start: 'foo<span></span><span>[]bar</span>', // broken - doCleanup should fix this
			execResult: 'fo[]<span>bar</span>'
		},
		{  	start: 'foo<div><div><p>[]bar</div></div>',
			execResult: 'foo[]bar'
		},
		{  	start: 'foo<div><div><p><!--abc-->[]bar</div></div>',
			execResult: 'foo[]bar'
		},
		{  	start: 'foo<div><div><!--abc--><p>[]bar</div></div>',
			execResult: 'foo[]bar'
		},
		{  	start: 'foo<div><!--abc--><div><p>[]bar</div></div>',
			execResult: 'foo[]bar'
		},
		{  	start: 'foo<!--abc--><div><div><p>[]bar</div></div>',
			execResult: 'foo[]bar'
		},
		{  	start: '<div><div><p>foo</div></div>[]bar',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{  	start: '<div><div><p>foo</div></div><!--abc-->[]bar',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{  	start: '<div><div><p>foo</div><!--abc--></div>[]bar',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{  	start: '<div><div><p>foo</p><!--abc--></div></div>[]bar',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{  	start: '<div><div><p>foo<!--abc--></div></div>[]bar',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{  	start: '<div><div><p>foo</p></div></div><div><div><div>[]bar</div></div></div>',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{  	start: '<div><div><p>foo<!--abc--></p></div></div><div><div><div>[]bar</div></div></div>',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{  	start: '<div><div><p>foo</p><!--abc--></div></div><div><div><div>[]bar</div></div></div>',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{  	start: '<div><div><p>foo</p></div><!--abc--></div><div><div><div>[]bar</div></div></div>',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{  	start: '<div><div><p>foo</p></div></div><!--abc--><div><div><div>[]bar</div></div></div>',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{  	start: '<div><div><p>foo</p></div></div><div><!--abc--><div><div>[]bar</div></div></div>',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{  	start: '<div><div><p>foo</p></div></div><div><div><!--abc--><div>[]bar</div></div></div>',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
		{  	start: '<div><div><p>foo</p></div></div><div><div><div><!--abc-->[]bar</div></div></div>',
			execResult: '<div><div><p>foo[]bar</p></div></div>'
		},
	
		// Styled stuff with collapsed selection
		{  	
			start: '<p style="color:blue;">foo<p>[]bar',
			execResult: '<p><span style="color: blue; ">foo[]</span>bar</p>'
		},
		{  	
			start: '<p style="color:blue;">foo<p style="color:brown;">[]bar',
			execResult: '<p style="color:blue;">foo[]<span style="color:brown;">bar</span></p>'
		},
		{  	exclude: 'msie',	// ie does not recognize style="color:rgba"
			start: '<p style="color:blue">foo</p><p style="color:rgba(0,0,255,1)">[]bar</p>',
			execResult: '<p style="color:blue">foo[]bar</p>'
		},
		{  	exclude: 'msie',   // ie does not recognize style="color:rgba"
			start: '<p style="color:transparent">foo<p style="color:rgba(0,0,0,0)">[]bar',
			execResult: '<p style="color:transparent">foo[]bar</p>'
		},
		{  	
			start: '<p>foo<p style="color:brown">[]bar',
			execResult: '<p>foo[]<span style="color:brown">bar</span></p>'
		},
		{  	
			start: '<p><font color="blue">foo</font><p>[]bar',
			execResult: '<p><font color="blue">foo[]</font>bar</p>'
		},
		{  	
			start: '<p><font color="blue">foo</font><p><font color="brown">[]bar</font>',
			execResult: '<p><font color="blue">foo[]</font><font color="brown">bar</font></p>'
		},
		{
		  	start: '<p>foo<p><font color="brown">[]bar</font>',
			execResult: '<p>foo[]<font color="brown">bar</font></p>'
		},
		{  	
			exclude: ['msie'], // TODO IE8 will hang on this one
			start: '<p><span style="color:blue">foo</font><p>[]bar',
			execResult: '<p><span style="color:blue">foo[]</span>bar</p>'
		},
		{  	
			exclude: ['msie'], // TODO IE8 will hang on this one
			start: '<p><span style="color:blue">foo</font><p><span style="color:brown">[]bar</font>',
			execResult: '<p><span style="color:blue">foo[]</span><span style="color:brown">bar</span></p>'
		},
		{  	
			start: '<p>foo<p><span style="color:brown">[]bar</span>',
			execResult: '<p>foo[]<span style="color:brown">bar</span></p>'
		},
	
		{  	start: '<p style="background-color:aqua">foo<p>[]bar',
			execResult: '<p style="background-color:aqua">foo[]bar</p>'
		},
		{  	start: '<p style="background-color:aqua">foo<p style="background-color:tan">[]bar',
			execResult: '<p style="background-color:aqua">foo[]bar</p>'
			// execResult: '<p style="background-color:aqua">foo[]<span style="background-color:tan">bar</span></p>' // TODO this is the really expected behaviour
		},
		{  	start: '<p>foo<p style=background-color:tan>[]bar', // broken
			execResult: '<p>foo[]bar</p>' // TODO this is the really expected behaviour
		},
		{  	
			exclude: ['msie'], // TODO IE8 will hang on this one
			start: '<p><span style=background-color:aqua>foo</font><p>[]bar',
			execResult: '<p><span style="background-color:aqua">foo[]</span>bar</p>'
		},
		{  	
			start: '<p><span style="background-color:aqua">foo</span><p><span style="background-color:tan">[]bar</span>',
			execResult: '<p><span style="background-color:aqua">foo[]</span><span style="background-color:tan">bar</span></p>'
		},
		{  	
			start: '<p>foo<p><span style="background-color:tan">[]bar</span>',
			execResult: '<p>foo[]<span style="background-color:tan">bar</span></p>'
		},
		{  	
			start: '<p style="text-decoration:underline">foo<p>[]bar',
			execResult: '<p><u>foo[]</u>bar</p>'
		},
		{  	
			start: '<p style="text-decoration:underline">foo<p style="text-decoration:line-through">[]bar',
			execResult: '<p><u>foo[]</u><s>bar</s></p>'
		},
		{  	
			start: '<p>foo<p style="text-decoration:line-through">[]bar',
			execResult: '<p>foo[]<s>bar</s></p>'
		},
		{  	
			start: '<p><u>foo</u><p>[]bar',
			execResult: '<p><u>foo[]</u>bar</p>'
		},
		{  	
			start: '<p><u>foo</u><p><s>[]bar</s>',
			execResult: '<p><u>foo[]</u><s>bar</s></p>'
		},
		{  	
			start: '<p>foo<p><s>[]bar</s>',
			execResult: '<p>foo[]<s>bar</s></p>'
		},
		{  	exclude: 'msie',				// TODO this test will always fail in ie, because the selection will always snap into the p
			start: '<p style="color:blue">foo</p>[]bar',
			execResult: '<p><span style="color: blue; ">foo[]</span>bar</p>'
		},
		{  	
			start: 'foo<p style="color:brown">[]bar',
			execResult: 'foo[]<span style="brown">bar</span>'
		},
		
//		{  	start: '<div style="color:blue"><p style="color:green>foo</div>[]bar', // very broken doesnt even run in the testbox
//			execResult: '<div style="color:blue"><p style="color:green>foo</div>[]bar'
//		},
//		{  	start: '<div style="color:blue"><p style="color:green>foo</div><p style="color:brown">[]bar', // very broken doesnt even run in the testbox
//			execResult: '<div style="color:blue"><p style="color:green>foo</div><p style="color:brown">[]bar'
//		},
//		{  	start: '<p style="color:blue">foo<div style="color:brown"><p style="color:green>[]bar', // very broken doesnt even run in the testbox
//			execResult: '<p style="color:blue">foo<div style="color:brown"><p style="color:green">[]bar'
//		},
	
//		// Uncollapsed selection

		{  	start: 'foo[bar]baz',
			execResult: 'foo[]baz'
		},
		{  	
			exclude: ['msie'],
			start: '<p>foo<span style="color:#aBcDeF">[bar]</span>baz',
			execResult: '<p>foo[]<span style="color:#aBcDeF"></span>baz</p>' // this one actually works, but the true test result will contain an empty text node within the span
		},
		{  	
			include: ['msie'],
			start: '<p>foo<span style="color:#aBcDeF">[bar]</span>baz',
			execResult: '<p>foo<span style="color:#aBcDeF"></span>[]baz</p>' // this one actually works, but the true test result will contain an empty text node within the span
		},
		{  	
			exclude: ['msie'],
			start: '<p>foo<span style=color:#aBcDeF>{bar}</span>baz',
			execResult: '<p>foo[]<span style="color:#aBcDeF"></span>baz</p>' // this one actually works, but the true test result will contain an empty text node within the span
		},
		{  	
			include: ['msie'],
			start: '<p>foo<span style=color:#aBcDeF>{bar}</span>baz',
			execResult: '<p>foo<span style="color:#aBcDeF"></span>[]baz</p>' // this one actually works, but the true test result will contain an empty text node within the span
		},
		{  	exclude: 'msie',
			start: '<p>foo{<span style=color:#aBcDeF>bar</span>}baz', // broken - doCleanup should fix this
			execResult: '<p>foo[]baz</p>'
		},
		{  	include: 'msie',
			start: '<p>foo{<span style=color:#aBcDeF>bar</span>}baz',
			execResult: '<p>foo<span style=color:#aBcDeF></span>[]baz</p>'
		},
		{  	start: '<p>[foo<span style=color:#aBcDeF>bar]</span>baz',
			execResult: '<p>[]baz</p>'
		},
		{  	start: '<p>[foo<span style="color:#aBcDeF">bar]</span>baz</p>',
			execResult: '<p>[]baz</p>'
		},
		{  	start: '<p>[foo<span style="color:#aBcDeF">bar]</span>baz',
			execResult: '<p>[]baz</p>'
		},
		{  	start: '<p>foo<span style="color:#aBcDeF">[bar</span>baz]', // broken - doCleanup should fix this
			execResult: '<p>foo[]</p>'
		},
		{  	start: '<p>foo<span style="color:#aBcDeF">{bar</span>baz}', // broken - doCleanup should fix this
			execResult: '<p>foo[]</p>'
		},
		{  	start: '<p>foo<span style="color:#aBcDeF">[bar</span><span style="color:#fEdCbA">baz]</span>quz', // broken - doCleanup should fix this
			execResult: '<p>foo[]quz</p>'
		},
	
		{  	start: 'foo<b>[bar]</b>baz', // broken - doCleanup should fix this
			execResult: 'foo[]baz'
		},
		{  	start: 'foo<b>{bar}</b>baz', // broken - doCleanup should fix this
			execResult: 'foo[]baz'
		},
		{  	start: 'foo{<b>bar</b>}baz', // broken - doCleanup should fix this
			execResult: 'foo[]baz'
		},
		{  	start: 'foo<span>[bar]</span>baz', // broken - doCleanup should fix this
			execResult: 'foo[]baz'
		},
		{  	start: 'foo<span>{bar}</span>baz', // broken - doCleanup should fix this
			execResult: 'foo[]baz'
		},
		{  	start: 'foo{<span>bar</span>}baz', // broken - doCleanup should fix this
			execResult: 'foo[]baz'
		},

		{  	
			start: '<b>foo[bar</b><i>baz]quz</i>',
			execResult: '<b>foo[]</b><i>quz</i>'
		},
		{  	exclude: 'msie',
			start: '<p>foo</p><p>[bar]</p><p>baz</p>',
			execResult: '<p>foo</p><p>{}<br class="aloha-end-br"/></p><p>baz</p>'
		},
		{	include: 'msie',
			start: '<p>foo</p><p>[bar]</p><p>baz</p>',
			execResult: '<p>foo</p><p>[]</p><p>baz</p>'
		},
		{  	exclude: 'msie',
			start: '<p>foo</p><p>{bar}</p><p>baz</p>',
			execResult: '<p>foo</p><p>{}<br class="aloha-end-br"/></p><p>baz</p>'
		},
		{  	include: 'msie',
			start: '<p>foo</p><p>{bar}</p><p>baz</p>',
			execResult: '<p>foo</p><p>[]</p><p>baz</p>'
		},
		{  	exclude: 'msie',
			start: '<p>foo</p><p>{bar</p>}<p>baz</p>',
			execResult: '<p>foo</p><p>[]baz</p>'
		},
		{  	include: 'msie',				// in ie, it is not possible to select a whole paragraph
			start: '<p>foo</p><p>{bar</p>}<p>baz</p>',
			execResult: '<p>foo</p><p>[]</p><p>baz</p>'
		},
		{  	exclude: 'msie',
			start: '<p>foo</p>{<p>bar}</p><p>baz</p>',
			execResult: '<p>foo</p><p>{}<br class="aloha-end-br"/><p>baz</p>'
		},
		{  	include: 'msie',				// in ie, it is not possible to select a whole paragraph
			start: '<p>foo</p>{<p>bar</p>}<p>baz</p>',
			execResult: '<p>foo</p><p>[]</p><p>baz</p>'
		},
	
		{  	start: '<p>foo[bar<p>baz]quz',
			execResult: '<p>foo[]quz</p>'
		},
		{  	start: '<p>foo[bar<div>baz]quz</div>',
			execResult: '<p>foo[]quz</p>'
		},
		{  	start: '<p>foo[bar<h1>baz]quz</h1>',
			execResult: '<p>foo[]quz</p>'
		},
		{  	start: '<div>foo[bar</div><p>baz]quz',
			execResult: '<div>foo[]quz</div>'
		},
		{  	start: '<blockquote>foo[bar</blockquote><pre>baz]quz</pre>',
			execResult: '<blockquote>foo[]quz</blockquote>'
		},
	
		{  	
			start: '<p><b>foo[bar</b><p>baz]quz',
			execResult: '<p><b>foo[]</b>quz</p>'
		},
		{  	
			start: '<div><p>foo[bar</div><p>baz]quz',
			execResult: '<div><p>foo[]quz</p></div>'
		},
		{  	
			exclude: ['msie'],
			start: '<p>foo[bar<blockquote><p>baz]quz<p>qoz</blockquote>',
			execResult: '<p>foo[]quz</p><blockquote><p>qoz</p></blockquote>'
		},
		{  	
			include: ['msie'],
			start: '<p>foo[bar<blockquote><p>baz]quz<p>qoz</blockquote>',
			execResult: '<p>foo[]quz </p><blockquote><p>qoz</p></blockquote>'
		},
		{  	
			start: '<p>foo[bar<p style="color:blue">baz]quz', // broken - doCleanup should fix this
			execResult: '<p>foo[]<span style="color:blue">quz</span></p>'
		},
		{  	exclude: 'mozilla',
			start: '<p>foo[bar<p><b>baz]quz</b>',
			execResult: '<p>foo[]<b>quz</b></p>'
		},
		{  	include: 'mozilla',
			start: '<p>foo[bar<p><b>baz]quz</b>',
			execResult: '<p>foo{}<b>quz</b></p>'
		},
	
		{  	start: '<div><p>foo<p>[bar<p>baz]</div>', // broken - doCleanup should fix this
			execResult: '<div><p>foo[]</p><p></p></div>'
		},
		{  	exclude: 'msie',
			start: '<div><p>foo<p>[bar<p>baz]</div>',
			execResult: '<div><p>foo</p><p>[]<br class="aloha-end-br" data-test-exclude="msie"/></p></div>'
		},
		{  	include: 'msie',
			start: '<div><p>foo<p>[bar<p>baz]</div>',
			execResult: '<div><p>foo </p><p>[]<br class="aloha-end-br" data-test-exclude="msie"/></p></div>'
		},
		{  	start: 'foo[<br>]bar',
			execResult: 'foo[]bar'
		},
		{  	start: '<p>foo[</p><p>]bar</p>',
			execResult: '<p>foo[]bar</p>'
		},
		{  	start: '<p>foo[</p><p>]bar<br>baz</p>',
			execResult: '<p>foo[]bar<br>baz</p>'
		},
		{  	start: 'foo[<p>]bar</p>',
			execResult: 'foo[]bar'
		},
		{  	start: 'foo{<p>}bar</p>',
			execResult: 'foo[]bar'
		},
		{  	exclude: 'msie',		// it is impossible to get a selection like this in ie
			start: 'foo[<p>]bar<br>baz</p>',
			execResult: 'foo[]bar<p>baz</p>'
		},
		{  	start: 'foo[<p>]bar</p>baz',
			execResult: 'foo[]bar<br>baz'
		},
		{  	exclude: 'msie',		// it is impossible to get a selection like this in ie
			start: 'foo{<p>bar</p>}baz',
			execResult: 'foo[]baz'
		},
		{  	exclude: 'msie',		// it is impossible to get a selection like this in ie
			start: 'foo<p>{bar</p>}baz',
			execResult: 'foo<p>[]baz</p>'
		},
		{  	exclude: 'mozilla',
			start: 'foo{<p>bar}</p>baz',
			execResult: 'foo[]<br>baz'
		},
		{  	include: 'mozilla',
			start: 'foo{<p>bar}</p>baz',
			execResult: 'foo{}<br>baz'
		},
		{  	exclude: 'msie',		// it is impossible to get a selection like this in ie
			start: '<p>foo[</p>]bar',
			execResult: '<p>foo[]bar</p>'
		},
		{  	exclude: 'msie',		// it is impossible to get a selection like this in ie
			start: '<p>foo{</p>}bar',
			execResult: '<p>foo[]bar</p>'
		},
		{  	exclude: 'msie',		// it is impossible to get a selection like this in ie
			start: '<p>foo[</p>]bar<br>baz',
			execResult: '<p>foo[]bar</p>baz'
		},
		{  	exclude: 'msie',		// it is impossible to get a selection like this in ie
			start: '<p>foo[</p>]bar<p>baz</p>',
			execResult: '<p>foo[]bar</p><p>baz</p>'
		},
		{  	start: 'foo[<div><p>]bar</div>',
			execResult: 'foo[]bar'
		},
		{  	exclude: 'msie',		// it is impossible to get a selection like this in ie
			start: '<div><p>foo[</p></div>]bar',
			execResult: '<div><p>foo[]bar</p></div>'
		},
		{  	
			exclude: ['msie'],
			start: 'foo[<div><p>]bar</p>baz</div>',
			execResult: 'foo[]bar<div>baz</div>'
		},
		{  	
			include: ['msie'],
			start: 'foo[<div><p>]bar</p>baz</div>',
			execResult: 'foo[]bar <div>baz</div>'
		},
		{  	
			exclude: ['msie'],		
			start: 'foo[<div>]bar<p>baz</p></div>',
			execResult: 'foo[]bar<div><p>baz</p></div>'
		},
		{  	
			include: ['msie'],		
			start: 'foo[<div>]bar<p>baz</p></div>',
			execResult: 'foo[]bar <div><p>baz</p></div>'
		},
		{  	exclude: 'msie',
			start: '<div><p>foo</p>bar[</div>]baz',
			execResult: '<div><p>foo</p>bar[]baz</div>'
		},
		{  	exclude: 'msie',
			start: '<div>foo<p>bar[</p></div>]baz',
			execResult: '<div>foo<p>bar[]baz</p></div>'
		},
		{  	exclude: ['mozilla'],
			start: '<p>foo<br>{</p>]bar',
			execResult: '<p>foo[]bar</p>'
		},
		{  	exclude: ['mozilla'],
			start: '<p>foo<br><br>{</p>]bar',
			execResult: '<p>foo<br>[]bar<br></p>'
		},
		{  	exclude: ['mozilla'],
			start: 'foo<br>{<p>]bar</p>',
			execResult: 'foo[]bar'
		},
		{  	exclude: 'msie',
			start: '<p>foo<br>{</p>]bar',
			execResult: '<p>foo[]bar</p>'
		},
//		{  	start: '<p>foo<br><br>{</p>]bar', // this test seems a bit pointless to me, therefore disabled it. broken right now.
//			execResult: '<p>foo<br>[]bar<br></p>'
//		},
		// @todo NS_ERROR_DOM_INDEX_SIZE_ERR exception in FF: rangy-core.js line 2055 at:
		// "rangeProto.setStart = function(node, offset) { this.nativeRange.setStart(node, offset);"
		// see also deletetest.js for that problem
//		{  	start: 'foo<br>{<p>]bar</p>',
//			execResult: 'foo[]bar'
//		},
		{  	exclude: 'msie',
			start: 'foo<br><br>{<p>]bar</p>',
			execResult: 'foo<br><p>[]bar</p>'
		},
		{  	start: '<p>foo<br>{</p><p>}bar</p>',
			execResult: '<p>foo[]bar</p>'
		},
		{  	start: '<p>foo<br><br>{</p><p>}bar</p>',
			execResult: '<p>foo<br>[]bar</p>' // TODO not entirely sure if this is really correct.
		},

// no table tests for us as our tables are augmented with divs	
//		{  	start: '<table><tbody><tr><th>foo<th>[bar]<th>baz<tr><td>quz<td>qoz<td>qiz</table>',
//			execResult: '<table><tbody><tr><th>foo<th>[bar]<th>baz<tr><td>quz<td>qoz<td>qiz</table>'
//		},
//		{  	start: '<table><tbody><tr><th>foo<th>ba[r<th>b]az<tr><td>quz<td>qoz<td>qiz</table>',
//			execResult: '<table><tbody><tr><th>foo<th>ba[r<th>b]az<tr><td>quz<td>qoz<td>qiz</table>'
//		},
//		{  	start: '<table><tbody><tr><th>fo[o<th>bar<th>b]az<tr><td>quz<td>qoz<td>qiz</table>',
//			execResult: '<table><tbody><tr><th>fo[o<th>bar<th>b]az<tr><td>quz<td>qoz<td>qiz</table>'
//		},
//		{  	start: '<table><tbody><tr><th>foo<th>bar<th>ba[z<tr><td>q]uz<td>qoz<td>qiz</table>',
//			execResult: '<table><tbody><tr><th>foo<th>bar<th>ba[z<tr><td>q]uz<td>qoz<td>qiz</table>'
//		},
//		{  	start: '<table><tbody><tr><th>[foo<th>bar<th>baz]<tr><td>quz<td>qoz<td>qiz</table>',
//			execResult: '<table><tbody><tr><th>[foo<th>bar<th>baz]<tr><td>quz<td>qoz<td>qiz</table>'
//		},
//		{  	start: '<table><tbody><tr><th>[foo<th>bar<th>baz<tr><td>quz<td>qoz<td>qiz]</table>',
//			execResult: '<table><tbody><tr><th>[foo<th>bar<th>baz<tr><td>quz<td>qoz<td>qiz]</table>'
//		},
//		{  	start: '{<table><tbody><tr><th>foo<th>bar<th>baz<tr><td>quz<td>qoz<td>qiz</table>}',
//			execResult: '{<table><tbody><tr><th>foo<th>bar<th>baz<tr><td>quz<td>qoz<td>qiz</table>}'
//		},
//		{  	start: '<table><tbody><tr><td>foo<td>ba[r<tr><td>baz<td>quz<tr><td>q]oz<td>qiz</table>',
//			execResult: '<table><tbody><tr><td>foo<td>ba[r<tr><td>baz<td>quz<tr><td>q]oz<td>qiz</table>'
//		},

		{  	start: '<p>fo[o<table><tr><td>b]ar</table><p>baz',
			execResult: '<p>fo[]</p><table><tbody><tr><td>ar</td></tr></tbody></table><p>baz</p>'
		},
		{  	start: '<p>foo<table><tr><td>ba[r</table><p>b]az',
			execResult: '<p>foo</p><table><tbody><tr><td>ba[]</td></tr></tbody></table><p>az</p>'
		},
		{  	start: '<p>fo[o<table><tr><td>bar</table><p>b]az',
			execResult: '<p>fo[]az</p>'
		},

		{  	
			exclude: ['msie'],
			start: '<p>foo<ol><li>ba[r<li>b]az</ol><p>quz',
			execResult: '<p>foo</p><ol><li>ba[]az</li></ol><p>quz</p>'
		},
		{  	
			include: ['msie'],
			start: '<p>foo<ol><li>ba[r<li>b]az</ol><p>quz',
			execResult: '<p>foo </p><ol><li>ba[]az</li></ol><p>quz</p>'
		},
		{  	exclude: ['mozilla'],
			start: '<p>foo<ol><li>bar<li>[baz]</ol><p>quz',
			execResult: '<p>foo</p><ol><li>bar</li><li>{}</li></ol><p>quz</p>'
		},
		{  	include: ['mozilla'],
			start: '<p>foo<ol><li>bar<li>[baz]</ol><p>quz',
			execResult: '<p>foo</p><ol><li>bar</li><li>[]</li></ol><p>quz</p>'
		},
		{  	exclude: 'msie',
			start: '<p>foo</p><ol><li>bar<li>[baz]</ol><p>quz</p>',
			execResult: '<p>foo</p><ol><li>bar</li><li>{}</li></ol><p>quz</p>'
		},
		{  	include: 'msie',
			start: '<p>foo</p><ol><li>bar<li>[baz]</ol><p>quz</p>',
			execResult: '<p>foo</p><ol><li>bar </li><li>[]</li></ol><p>quz</p>'
		},
		{  	
			exclude: ['msie'],
			start: '<p>fo[o<ol><li>b]ar<li>baz</ol><p>quz',
			execResult: '<p>fo[]ar</p><ol><li>baz</li></ol><p>quz</p>'
		},
		{  	
			include: ['msie'],
			start: '<p>fo[o<ol><li>b]ar<li>baz</ol><p>quz',
			execResult: '<p>fo[]ar </p><ol><li>baz</li></ol><p>quz</p>'
		},
		{  	
			exclude: ['msie'],
			start: '<p>foo<ol><li>bar<li>ba[z</ol><p>q]uz',
			execResult: '<p>foo</p><ol><li>bar</li><li>ba[]uz</li></ol>'
		},
		{  	
			include: ['msie'],
			start: '<p>foo<ol><li>bar<li>ba[z</ol><p>q]uz',
			execResult: '<p>foo </p><ol><li>bar </li><li>ba[]uz</li></ol>'
		},
		{  	
			exclude: ['msie'],
			start: '<p>fo[o<ol><li>bar<li>b]az</ol><p>quz',
			execResult: '<p>fo[]az</p><p>quz</p>'
		},
		{  	
			include: ['msie'],
			start: '<p>fo[o<ol><li>bar<li>b]az</ol><p>quz',
			execResult: '<p>fo[]az </p><p>quz</p>'
		},
		{  	start: '<p>fo[o<ol><li>bar<li>baz</ol><p>q]uz',
			execResult: '<p>fo[]uz</p>'
		},
	
		{  	start: '<ol><li>fo[o</ol><ol><li>b]ar</ol>',
			execResult: '<ol><li>fo[]ar</li></ol>'
		},
		{  	start: '<ol><li>fo[o</ol><ul><li>b]ar</ul>',
			execResult: '<ol><li>fo[]ar</li></ol>'
		},
		{	exclude: 'msie',
			start: '<ol><li>foo<ol><li>[]bar</li><li>baz</li></ol></li></ol>',
			execResult: '<ol><li>foo</li><li>[]bar<ol><li>baz</li></ol></li></ol>'
		},
		{	include: 'msie',
			start: '<ol><li>foo<ol><li>[]bar</li><li>baz</li></ol></li></ol>',
			execResult: '<ol><li>foo</li><li>[]bar <ol><li>baz</li></ol></li></ol>'
		},
		{	exclude: 'msie',
			start: '<ol><li>foo<ol><li>bar<ol><li>[]baz</li><li>quz</li></ol></li></ol></li></ol>',
			execResult: '<ol><li>foo<ol><li>bar</li><li>[]baz<ol><li>quz</li></ol></li></li></ol>'
		},
		{	include: 'msie',
			start: '<ol><li>foo<ol><li>bar<ol><li>[]baz</li><li>quz</li></ol></li></ol></li></ol>',
			execResult: '<ol><li>foo <ol><li>bar</li><li>[]baz <ol><li>quz</li></ol></li></li></ol>'
		},

		{  	start: 'foo[<ol><li>]bar</ol>',
			execResult: 'foo[]bar'
		},
		{  	start: '<ol><li>foo[<li>]bar</ol>',
			execResult: '<ol><li>foo[]bar</li></ol>'
		},
// no definition list tests for us at this point of aloha
//		{  	start: 'foo[<dl><dt>]bar<dd>baz</dl>',
//			execResult: 'foo[<dl><dt>]bar<dd>baz</dl>'
//		},
//		{  	start: 'foo[<dl><dd>]bar</dl>',
//			execResult: 'foo[<dl><dd>]bar</dl>'
//		},
//		{  	start: '<dl><dt>foo[<dd>]bar</dl>',
//			execResult: '<dl><dt>foo[<dd>]bar</dl>'
//		},
//		{  	start: '<dl><dt>foo[<dt>]bar<dd>baz</dl>',
//			execResult: '<dl><dt>foo[<dt>]bar<dd>baz</dl>'
//		},
//		{  	start: '<dl><dt>foo<dd>bar[<dd>]baz</dl>',
//			execResult: '<dl><dt>foo<dd>bar[<dd>]baz</dl>'
//		},
	
		{  	start: '<b>foo [&nbsp;</b>bar]',
			execResult: '<b>foo&nbsp;[]</b>'
		},
	
		// Do we merge based on element names or the display property?
		{  	
			exclude: ['msie'],
			start: '<p style="display:inline">fo[o<p style="display:inline">b]ar',
			execResult: '<p style="display:inline">fo[]</p><p style="display:inline">ar</p>'
		},
		{  	
			include: ['msie'],
			start: '<p style="display:inline">fo[o<p style="display:inline">b]ar',
			execResult: '<p style="display:inline">fo[] </p><p style="display:inline">ar</p>'
		},
		{  	start: '<span style="display:block">fo[o</span><span style="display:block">b]ar</span>',
			execResult: '<span style="display:block">fo[]ar</span>'
		},
		{  	exclude: 'msie',
			start: '<span style="display:inline-block">fo[o</span><span style="display:inline-block">b]ar</span>',
			execResult: '<span style="display:inline-block">fo[]</span><span style="display:inline-block">ar</span>'
		},
		{  	exclude: 'msie',
			start: '<span style="display:inline-table">fo[o</span><span style="display:inline-table">b]ar</span>', // TODO some exception in IE!
			execResult: '<span style="display:inline-table">fo[]</span><span style="display:inline-table">ar</span>'
		},
		{  	exclude: 'msie',
			start: '<span style="display:none">fo[o</span><span style="display:none">b]ar</span>', // broken
			execResult: '<span style="display:none">fo[]ar</span>'
		},
		{  	exclude: 'msie',
			start: '<quasit style="display:block">fo[o</quasit><quasit style="display:block">b]ar</quasit>',
			execResult: '<quasit style="display:block">fo[]ar</quasit>'
		},
		{  	include: 'msie',
			start: '<quasit style="display:block">fo[o</quasit><quasit style="display:block">b]ar</quasit>',
			execResult: 'fo[]ar</quasit>'
		},
		{	start: 'foo\n\t\t\t[]bar',
			execResult: 'foo[]bar'
		},
		{	start: 'foo     []bar',
			execResult: 'foo[]bar'
		}
	]
}
