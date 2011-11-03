var tests = {
	defaultCommand: 'insertparagraph',
	tests: [		        
//		{  	start: '[]foo',
//			execResult: '<p><br></p><p>[]foo</p>'
//		},
//		{  	start: 'foo[bar]baz',
//			execResult: '<p>foo</p><p>[]baz</p>'
//		},
//		
////		{  	start: 'fo[o<table><tr><td>b]ar</table>',
////			execResult: 'fo[o<table><tr><td>b]ar</table>'
////		},
////		{  	start: '<table><tr><td>[foo<td>bar]<tr><td>baz<td>quz</table>',
////			execResult: '<table><tr><td>[foo<td>bar]<tr><td>baz<td>quz</table>'
////		},
////		{  	start: '<table><tbody data-start=0 data-end=1><tr><td>foo<td>bar<tr><td>baz<td>quz</table>',
////			execResult: '<table><tbody data-start=0 data-end=1><tr><td>foo<td>bar<tr><td>baz<td>quz</table>'
////		},
////		{  	start: '<table><tr><td>fo[o</table>b]ar',
////			execResult: '<table><tr><td>fo[o</table>b]ar'
////		},
////		{  	start: '<table><tr><td>fo[o<td>b]ar<td>baz</table>',
////			execResult: '<table><tr><td>fo[o<td>b]ar<td>baz</table>'
////		},
////		{  	start: '{<table><tr><td>foo</table>}',
////			execResult: '{<table><tr><td>foo</table>}'
////		},
////		{  	start: '<table><tr><td>[foo]</table>',
////			execResult: '<table><tr><td>[foo]</table>'
////		},
//		
//		{  	start: '<ol><li>[foo]<li>bar</ol>',
//			execResult: '<ol><li><br></li>{}<br><li>bar</ol>'
//		},
//		{  	start: '<ol><li>f[o]o<li>bar</ol>',
//			execResult: '<ol><li>f</li><li>[]o</li><li>bar</ol>'
//		},

//		{  	start: 'foo[]',
//			execResult: '<p>foo</p><p>[]</p>'
//		},
//		{  	start: '<span>foo[]</span>',
//			execResult: '<p><span>foo</span></p><p>{}<br></p>'
//		},
		{  	start: 'foo[]<br>',
			execResult: '<p>foo</p><p>[]<br></p>'
		},
		{  	start: 'foo[]bar',
			execResult: '<p>foo</p><p>[]bar</p>'
		},
		
		{  	start: '<address>[]foo</address>',
			execResult: '<address>[]foo</address>'
		},
		{  	start: '<address>foo[]</address>',
			execResult: '<address>foo[]</address>'
		},
		{  	start: '<address>foo[]<br></address>',
			execResult: '<address>foo[]<br></address>'
		},
		{  	start: '<address>foo[]bar</address>',
			execResult: '<address>foo[]bar</address>'
		},
		
		{  	start: '<div>[]foo</div>',
			execResult: '<div><br></div><div>[]foo</div>'
		},
		{  	start: '<div>foo[]</div>',
			execResult: '<div>foo</div><div>[]<br></div>'
		},
		{  	start: '<div>foo[]<br></div>',
			execResult: '<div>foo</div><div>[]<br></div>'
		},
		{  	start: '<div>foo[]bar</div>',
			execResult: '<div>foo[]bar</div>'
		},
		
//		{  	start: '<dl><dt>[]foo<dd>bar</dl>',
//			execResult: '<dl><dt>[]foo<dd>bar</dl>'
//		},
//		{  	start: '<dl><dt>foo[]<dd>bar</dl>',
//			execResult: '<dl><dt>foo[]<dd>bar</dl>'
//		},
//		{  	start: '<dl><dt>foo[]<br><dd>bar</dl>',
//			execResult: '<dl><dt>foo[]<br><dd>bar</dl>'
//		},
//		{  	start: '<dl><dt>foo[]bar<dd>baz</dl>',
//			execResult: '<dl><dt>foo[]bar<dd>baz</dl>'
//		},
//		{  	start: '<dl><dt>foo<dd>[]bar</dl>',
//			execResult: '<dl><dt>foo<dd>[]bar</dl>'
//		},
//		{  	start: '<dl><dt>foo<dd>bar[]</dl>',
//			execResult: '<dl><dt>foo<dd>bar[]</dl>'
//		},
//		{  	start: '<dl><dt>foo<dd>bar[]<br></dl>',
//			execResult: '<dl><dt>foo<dd>bar[]<br></dl>'
//		},
//		{  	start: '<dl><dt>foo<dd>bar[]baz</dl>',
//			execResult: '<dl><dt>foo<dd>bar[]baz</dl>'
//		},
//		{  	start: '<h1>[]foo</h1>',
//			execResult: '<h1>[]foo</h1>'
//		},
//		{  	start: '<h1>foo[]</h1>',
//			execResult: '<h1>foo[]</h1>'
//		},
//		{  	start: '<h1>foo[]<br></h1>',
//			execResult: '<h1>foo[]<br></h1>'
//		},
//		{  	start: '<h1>foo[]bar</h1>',
//			execResult: '<h1>foo[]bar</h1>'
//		},
//		{  	start: '<ol><li>[]foo</ol>',
//			execResult: '<ol><li>[]foo</ol>'
//		},
//		{  	start: '<ol><li>foo[]</ol>',
//			execResult: '<ol><li>foo[]</ol>'
//		},
//		{  	start: '<ol><li>foo[]<br></ol>',
//			execResult: '<ol><li>foo[]<br></ol>'
//		},
//		{  	start: '<ol><li>foo[]bar</ol>',
//			execResult: '<ol><li>foo[]bar</ol>'
//		},
//		{  	start: '<p>[]foo</p>',
//			execResult: '<p>[]foo</p>'
//		},
//		{  	start: '<p>foo[]</p>',
//			execResult: '<p>foo[]</p>'
//		},
//		{  	start: '<p>foo[]<br></p>',
//			execResult: '<p>foo[]<br></p>'
//		},
//		{  	start: '<p>foo[]bar</p>',
//			execResult: '<p>foo[]bar</p>'
//		},
//		{  	start: '<pre>[]foo</pre>',
//			execResult: '<pre>[]foo</pre>'
//		},
//		{  	start: '<pre>foo[]</pre>',
//			execResult: '<pre>foo[]</pre>'
//		},
//		{  	start: '<pre>foo[]<br></pre>',
//			execResult: '<pre>foo[]<br></pre>'
//		},
//		{  	start: '<pre>foo[]bar</pre>',
//			execResult: '<pre>foo[]bar</pre>'
//		},
//
//		{  	start: '<pre>foo[]<br><br></pre>',
//			execResult: '<pre>foo[]<br><br></pre>'
//		},
//		{  	start: '<pre>foo<br>{}<br></pre>',
//			execResult: '<pre>foo<br>{}<br></pre>'
//		},
//		{  	start: '<pre>foo&#10;[]</pre>',
//			execResult: '<pre>foo&#10;[]</pre>'
//		},
//		{  	start: '<pre>foo[]&#10;</pre>',
//			execResult: '<pre>foo[]&#10;</pre>'
//		},
//		{  	start: '<pre>foo&#10;[]&#10;</pre>',
//			execResult: '<pre>foo&#10;[]&#10;</pre>'
//		},
//
//		{  	start: '<xmp>foo[]bar</xmp>',
//			execResult: '<xmp>foo[]bar</xmp>'
//		},
//		{  	start: '<script>foo[]bar</script>baz',
//			execResult: '<script>foo[]bar</script>baz'
//		},
//		{  	start: '<div style=display:none>foo[]bar</div>baz',
//			execResult: '<div style=display:none>foo[]bar</div>baz'
//		},
//		{  	start: '<listing>foo[]bar</listing>',
//			execResult: '<listing>foo[]bar</listing>'
//		},
//
//		{  	start: '<ol><li>{}<br></li></ol>',
//			execResult: '<ol><li>{}<br></li></ol>'
//		},
//		{  	start: 'foo<ol><li>{}<br></li></ol>',
//			execResult: 'foo<ol><li>{}<br></li></ol>'
//		},
//		{  	start: '<ol><li>{}<br></li></ol>foo',
//			execResult: '<ol><li>{}<br></li></ol>foo'
//		},
//		{  	start: '<ol><li>foo<li>{}<br></ol>',
//			execResult: '<ol><li>foo<li>{}<br></ol>'
//		},
//		{  	start: '<ol><li>{}<br><li>bar</ol>',
//			execResult: '<ol><li>{}<br><li>bar</ol>'
//		},
//		{  	start: '<ol><li>foo</li><ul><li>{}<br></ul></ol>',
//			execResult: '<ol><li>foo</li><ul><li>{}<br></ul></ol>'
//		},
//
//		{  	start: '<dl><dt>{}<br></dt></dl>',
//			execResult: '<dl><dt>{}<br></dt></dl>'
//		},
//		{  	start: '<dl><dt>foo<dd>{}<br></dl>',
//			execResult: '<dl><dt>foo<dd>{}<br></dl>'
//		},
//		{  	start: '<dl><dt>{}<br><dd>bar</dl>',
//			execResult: '<dl><dt>{}<br><dd>bar</dl>'
//		},
//		{  	start: '<dl><dt>foo<dd>bar<dl><dt>{}<br><dd>baz</dl></dl>',
//			execResult: '<dl><dt>foo<dd>bar<dl><dt>{}<br><dd>baz</dl></dl>'
//		},
//		{  	start: '<dl><dt>foo<dd>bar<dl><dt>baz<dd>{}<br></dl></dl>',
//			execResult: '<dl><dt>foo<dd>bar<dl><dt>baz<dd>{}<br></dl></dl>'
//		},
//
//		{  	start: '<h1>foo[bar</h1><p>baz]quz</p>',
//			execResult: '<h1>foo[bar</h1><p>baz]quz</p>'
//		},
//		{  	start: '<p>foo[bar</p><h1>baz]quz</h1>',
//			execResult: '<p>foo[bar</p><h1>baz]quz</h1>'
//		},
//		{  	start: '<p>foo</p>{}<br>',
//			execResult: '<p>foo</p>{}<br>'
//		},
//		{  	start: '{}<br><p>foo</p>',
//			execResult: '{}<br><p>foo</p>'
//		},
//		{  	start: '<p>foo</p>{}<br><h1>bar</h1>',
//			execResult: '<p>foo</p>{}<br><h1>bar</h1>'
//		},
//		{  	start: '<h1>foo</h1>{}<br><p>bar</p>',
//			execResult: '<h1>foo</h1>{}<br><p>bar</p>'
//		},
//		{  	start: '<h1>foo</h1>{}<br><h2>bar</h2>',
//			execResult: '<h1>foo</h1>{}<br><h2>bar</h2>'
//		},
//		{  	start: '<p>foo</p><h1>[bar]</h1><p>baz</p>',
//			execResult: '<p>foo</p><h1>[bar]</h1><p>baz</p>'
//		},
//		{  	start: '<p>foo</p>{<h1>bar</h1>}<p>baz</p>',
//			execResult: '<p>foo</p>{<h1>bar</h1>}<p>baz</p>'
//		},
//
//		{  	start: '<table><tr><td>foo[]bar</table>',
//			execResult: '<table><tr><td>foo[]bar</table>'
//		},
//		{  	start: '<table><tr><td><p>foo[]bar</table>',
//			execResult: '<table><tr><td><p>foo[]bar</table>'
//		},
//
//		{  	start: '<blockquote>[]foo</blockquote>',
//			execResult: '<blockquote>[]foo</blockquote>'
//		},
//		{  	start: '<blockquote>foo[]</blockquote>',
//			execResult: '<blockquote>foo[]</blockquote>'
//		},
//		{  	start: '<blockquote>foo[]<br></blockquote>',
//			execResult: '<blockquote>foo[]<br></blockquote>'
//		},
//		{  	start: '<blockquote>foo[]bar</blockquote>',
//			execResult: '<blockquote>foo[]bar</blockquote>'
//		},
//		{  	start: '<blockquote><p>[]foo</blockquote>',
//			execResult: '<blockquote><p>[]foo</blockquote>'
//		},
//		{  	start: '<blockquote><p>foo[]</blockquote>',
//			execResult: '<blockquote><p>foo[]</blockquote>'
//		},
//		{  	start: '<blockquote><p>foo[]bar</blockquote>',
//			execResult: '<blockquote><p>foo[]bar</blockquote>'
//		},
//		{  	start: '<blockquote><p>foo[]<p>bar</blockquote>',
//			execResult: '<blockquote><p>foo[]<p>bar</blockquote>'
//		},
//		{  	start: '<blockquote><p>foo[]bar<p>baz</blockquote>',
//			execResult: '<blockquote><p>foo[]bar<p>baz</blockquote>'
//		},
//
//		{  	start: '<span>foo[]bar</span>',
//			execResult: '<span>foo[]bar</span>'
//		},
//		{  	start: '<span>foo[]bar</span>baz',
//			execResult: '<span>foo[]bar</span>baz'
//		},
//		{  	start: '<b>foo[]bar</b>',
//			execResult: '<b>foo[]bar</b>'
//		},
//		{  	start: '<b>foo[]bar</b>baz',
//			execResult: '<b>foo[]bar</b>baz'
//		},
//		{  	start: '<b>foo[]</b>bar',
//			execResult: '<b>foo[]</b>bar'
//		},
//		{  	start: 'foo<b>[]bar</b>',
//			execResult: 'foo<b>[]bar</b>'
//		},
//		{  	start: '<b>foo[]</b><i>bar</i>',
//			execResult: '<b>foo[]</b><i>bar</i>'
//		},
//		{  	start: '<b id=x class=y>foo[]bar</b>',
//			execResult: '<b id=x class=y>foo[]bar</b>'
//		},
//		{  	start: '<i><b>foo[]bar</b>baz</i>',
//			execResult: '<i><b>foo[]bar</b>baz</i>'
//		},
//
//		{  	start: '<p><b>foo[]bar</b></p>',
//			execResult: '<p><b>foo[]bar</b></p>'
//		},
//		{  	start: '<p><b>[]foo</b></p>',
//			execResult: '<p><b>[]foo</b></p>'
//		},
//		{  	start: '<p><b id=x class=y>foo[]bar</b></p>',
//			execResult: '<p><b id=x class=y>foo[]bar</b></p>'
//		},
//		{  	start: '<div><b>foo[]bar</b></div>',
//			execResult: '<div><b>foo[]bar</b></div>'
//		},
//
//		{  	start: '<a href=foo>foo[]bar</a>',
//			execResult: '<a href=foo>foo[]bar</a>'
//		},
//		{  	start: '<a href=foo>foo[]bar</a>baz',
//			execResult: '<a href=foo>foo[]bar</a>baz'
//		},
//		{  	start: '<a href=foo>foo[]</a>bar',
//			execResult: '<a href=foo>foo[]</a>bar'
//		},
//		{  	start: 'foo<a href=foo>[]bar</a>',
//			execResult: 'foo<a href=foo>[]bar</a>'
//		},
//
//		{  	start: '<p>foo[]<!--bar-->',
//			execResult: '<p>foo[]<!--bar-->'
//		},
//		{  	start: '<p><!--foo-->[]bar',
//			execResult: '<p><!--foo-->[]bar'
//		},
//
//		{  	start: '<p>foo<span style=color:#aBcDeF>[bar]</span>baz',
//			execResult: '<p>foo<span style=color:#aBcDeF>[bar]</span>baz'
//		},
//		{  	start: '<p>foo<span style=color:#aBcDeF>{bar}</span>baz',
//			execResult: '<p>foo<span style=color:#aBcDeF>{bar}</span>baz'
//		},
//		{  	start: '<p>foo{<span style=color:#aBcDeF>bar</span>}baz',
//			execResult: '<p>foo{<span style=color:#aBcDeF>bar</span>}baz'
//		},
//		{  	start: '<p>[foo<span style=color:#aBcDeF>bar]</span>baz',
//			execResult: '<p>[foo<span style=color:#aBcDeF>bar]</span>baz'
//		},
//		{  	start: '<p>{foo<span style=color:#aBcDeF>bar}</span>baz',
//			execResult: '<p>{foo<span style=color:#aBcDeF>bar}</span>baz'
//		},
//		{  	start: '<p>foo<span style=color:#aBcDeF>[bar</span>baz]',
//			execResult: '<p>foo<span style=color:#aBcDeF>[bar</span>baz]'
//		},
//		{  	start: '<p>foo<span style=color:#aBcDeF>{bar</span>baz}',
//			execResult: '<p>foo<span style=color:#aBcDeF>{bar</span>baz}'
//		},
//		{  	start: '<p>foo<span style=color:#aBcDeF>[bar</span><span style=color:#fEdCbA>baz]</span>quz',
//			execResult: '<p>foo<span style=color:#aBcDeF>[bar</span><span style=color:#fEdCbA>baz]</span>quz'
//		}
		
	]
}

