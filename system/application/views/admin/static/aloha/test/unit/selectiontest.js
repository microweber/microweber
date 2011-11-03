/*!
 * This file is part of Aloha Editor
 * Author & Copyright (c) 2010 Gentics Software GmbH, aloha@gentics.com
 * Licensed unter the terms of http://www.aloha-editor.com/license.html
 */

define(
[ 'testutils' ],
function( TestUtils ) {
    

    // http://dev.w3.org/html5/markup/spec.html#common-models
    var 
        specialTests = [
            // 'start[and]expected'
            // [ '[st]art', '[ex]pected' ]
            '{}',
            [ '{}foo', '[]foo' ],
            'foo[]',
            [ 'foo{}', 'foo[]' ],
            '[foo]',
            [ '{foo}', '[foo]' ],
            '[bam]foo', 
            [ '{bam]foo', '[bam]foo' ],
            'bam[foo]', 
            [ 'bam[foo}', 'bam[foo]' ],
            'bam[]foo', 
            'foo[bar]baz',
            
            // special characters
            // Attention second "ö" is "o&#x308;"
// Ignoring [ 'foo[&#x308;]baz', 'foo¨[]baz' ],
            [ 'foo[\0]baz', 'foo[]baz' ],
            [ 'foo[\x07]baz', 'foo[\x07]baz']
// qunit URIError 'foo[\ud800]baz',
        ],
        voidTests = [
            // br
            [ 'foo[]<br>baz', 'foo[]<br>baz' ],
            [ 'foo<br>[]baz', 'foo<br>[]baz' ],
            [ 'foo[}<br>baz', 'foo[]<br>baz' ],
            [ 'foo<br>{]baz', 'foo<br>[]baz' ],
            [ 'foo[<br>]baz', 'foo{<br>}baz' ],
            [ 'foo{<br>]baz', 'foo{<br>}baz' ],
            [ 'foo[<br>}baz', 'foo{<br>}baz' ],
            [ 'foo{<br>}baz', 'foo{<br>}baz' ],
            [ '[foo<br>]baz', '[foo<br>}baz' ],
            [ 'foo[<br>baz]', 'foo{<br>baz]' ],
            [ 'foo[<br><br>}baz', 'foo{<br><br>}baz' ],
            [ 'foo[<br><br>]baz', 'foo{<br><br>}baz' ],
            [ 'foo{<br><br>}baz', 'foo{<br><br>}baz' ],
            [ 'foo{<br><br>]baz', 'foo{<br><br>}baz' ],
            [ '<span data-end=2>foo[<br><br>baz</span>', '<span>foo{<br>}<br>baz</span>' ],
            [ '<span data-end=2>foo{<br><br>baz</span>', '<span>foo{<br>}<br>baz</span>' ],
            [ '<span data-start=2>foo<br><br>]baz</span>', '<span>foo<br>{<br>}baz</span>' ],
            [ '<span data-start=2>foo<br><br>}baz</span>', '<span>foo<br>{<br>}baz</span>' ],
            // br wraped in phrasing
            [ 'foo<span>{}<br></span>baz', 'foo[]<span><br></span>baz' ],
            [ 'foo<span><br>{}</span>baz', 'foo<span><br></span>[]baz' ],
            [ 'foo{<span>}<br></span>baz', 'foo[]<span><br></span>baz' ],
            [ 'foo<span><br>{</span>}baz', 'foo<span><br></span>[]baz' ],
            [ 'foo[<span>}<br></span>baz', 'foo[]<span><br></span>baz' ],
            [ 'foo<span><br>{</span>]baz', 'foo<span><br></span>[]baz' ],
            [ 'foo{<span><br></span>}baz', 'foo<span>{<br>}</span>baz' ],
            [ 'foo[<span><br></span>]baz', 'foo<span>{<br>}</span>baz' ]
            // // br wrapped in flow
			// <p>hello</p><p> {}<br /></p>
            // [ 'foo<div>{}<br></div>baz', 'foo<div>{}<br></div>baz' ],
            // [ 'foo<div><br>{}</div>baz', 'foo<div>{}<br></div>baz' ],
            // [ 'foo{<div>}<br></div>baz', 'foo[<div>}<br></div>baz' ],
            // [ 'foo<div><br>{</div>}baz', 'foo<div>{<br></div>]baz' ],
            // [ 'foo[<div>}<br></div>baz', 'foo[<div>}<br></div>baz' ],
            // [ 'foo<div><br>{</div>]baz', 'foo<div>{<br></div>]baz' ],
            // [ 'foo{<div><br></div>}baz', 'foo[<div><br></div>]baz' ],
            // [ 'foo[<div><br></div>]baz', 'foo[<div><br></div>]baz' ]
        ],
        phrasingTests = [
			
			[ '{<b>foo]</b>', '<b>[foo]</b>' ],
			[ 'foo{<b>bar]</b>', 'foo<b>[bar]</b>' ],
			[ '{<b></b><p>foo]</p>', '<b></b><p>[foo]</p>' ],
			[ 'foo<p>{<b></b></p>bar]', 'foo<p><b></b></p>[bar]' ],
			[ 'foo<p></p>{<b></b><p></p>bar]', 'foo<p></p><b></b><p></p>[bar]' ],
			[ '<p>foo</p>{<b></b>bar]', '<p>foo</p><b></b>[bar]' ],
			[ 'foo{<b></b><p>bar]</p>', 'foo<b>{</b><p>bar]</p>' ],
			[ '<p>foo{<b></b></p>bar]', '<p>foo<b>{</b></p>bar]' ],
			[ 'foo{<b></b><u></u><p>bar]</p>', 'foo<b></b><u>{</u><p>bar]</p>' ],
			[ '<p>foo{<b></b><u></u></p>bar]', '<p>foo<b></b><u>{</u></p>bar]' ],
			
			'foo<span>[bar]</span>baz',
			[ 'foo[<span>bar</span>]baz', 'foo<span>[bar]</span>baz' ],
			[ 'foo<span>{bar}</span>baz', 'foo<span>[bar]</span>baz' ],
            [ 'foo{<span>bar</span>}baz', 'foo<span>[bar]</span>baz' ],
			[ 'foo<span>{bar]</span>baz', 'foo<span>[bar]</span>baz' ],
            [ 'foo<span>{bar</span>]baz', 'foo<span>[bar]</span>baz' ],
            [ 'foo<span>[bar}</span>baz', 'foo<span>[bar]</span>baz' ],
            [ 'foo[<span>bar}</span>baz', 'foo<span>[bar]</span>baz' ],
            
            '[foo<span>bar]</span>baz', 
            [ '[foo<span>]bar</span>baz', '[foo]<span>bar</span>baz' ],
            [ 'foo[<span>bar]</span>baz', 'foo<span>[bar]</span>baz' ],
            [ 'foo[<span>]bar</span>baz', 'foo[]<span>bar</span>baz' ],
            [ '{foo<span>bar}</span>baz', '[foo<span>bar]</span>baz' ],
            [ '{foo<span>}bar</span>baz', '[foo]<span>bar</span>baz' ],
            [ 'foo{<span>bar}</span>baz', 'foo<span>[bar]</span>baz' ],
            [ 'foo{<span>}bar</span>baz', 'foo[]<span>bar</span>baz' ],
            
            [ 'foo<span>[bar</span>baz]', 'foo<span>[bar</span>baz]' ],
            [ 'foo<span>bar[</span>baz]', 'foo<span>bar</span>[baz]' ],
            [ 'foo<span>[bar</span>]baz', 'foo<span>[bar]</span>baz' ],
            [ 'foo<span>bar[</span>]baz', 'foo<span>bar[]</span>baz' ],
            [ 'foo<span>{bar</span>baz}', 'foo<span>[bar</span>baz]' ],
            [ 'foo<span>bar{</span>baz}', 'foo<span>bar</span>[baz]' ],
            [ 'foo<span>{bar</span>}baz', 'foo<span>[bar]</span>baz' ],
            [ 'foo<span>bar{</span>}baz', 'foo<span>bar[]</span>baz' ],
            
            [ 'foo{<span><b><b><b>bar}</b></b></b></span>baz', 'foo<span><b><b><b>[bar]</b></b></b></span>baz' ],
            [ 'foo<span><b>{<b><b>bar}</b></b></b></span>baz', 'foo<span><b><b><b>[bar]</b></b></b></span>baz' ],
            [ 'foo{<span><b><b><b>}bar</b></b></b></span>baz', 'foo[]<span><b><b><b>bar</b></b></b></span>baz' ],
            [ 'foo<span><b>{<b><b>}bar</b></b></b></span>baz', 'foo[]<span><b><b><b>bar</b></b></b></span>baz' ],
            [ 'foo{<span><b><b><b>]bar</b></b></b></span>baz', 'foo[]<span><b><b><b>bar</b></b></b></span>baz' ],
            [ 'foo<span><b>{<b><b>]bar</b></b></b></span>baz', 'foo[]<span><b><b><b>bar</b></b></b></span>baz' ],
            
            [ 'foo{<span><b><br><b><b>bar}</b></b></b></span>baz', 'foo<span><b>{<br><b><b>bar]</b></b></b></span>baz' ],
            [ 'foo{<span><i><u><b>bar}</b></u></i></span>baz', 'foo<span><i><u><b>[bar]</b></u></i></span>baz' ],
            
            [ 'foo<i></i>{<span><b><b>}bar</b></b></span>baz', 'foo[]<i></i><span><b><b>bar</b></b></span>baz' ],
            [ 'foo<i>a</i>{<span><b><b>}bar</b></b></span>baz', 'foo<i>a[]</i><span><b><b>bar</b></b></span>baz' ],
            [ 'foo<i>a</i>{<span><b><b>}bar</b></b></span>baz', 'foo<i>a[]</i><span><b><b>bar</b></b></span>baz' ],
            
            [ '<i></i>{<span><b><b>}bar</b></b></span>baz', '<i></i><span><b><b>[]bar</b></b></span>baz' ],
			[ '<span>{<span><b><b>}bar</b></b></span>baz</span>', '<span><span><b><b>[]bar</b></b></span>baz</span>' ],
			[ 'test<span>{<span><b><b>}bar</b></b></span>baz</span>', 'test[]<span><span><b><b>bar</b></b></span>baz</span>' ],
            [ '<b><i>foo</i></b>{<span><b><b>}bar</b></b></span>baz', '<b><i>foo[]</i></b><span><b><b>bar</b></b></span>baz' ],
            [ '<b><i></i></b>{<span><b><b>}bar</b></b></span>baz', '<b><i></i></b><span><b><b>[]bar</b></b></span>baz' ],
            [ '<b>foo<i></i></b>{<span><b><b>}bar</b></b></span>baz', '<b>foo[]<i></i></b><span><b><b>bar</b></b></span>baz' ],
            [ '{<span><b><b>}bar</b></b></span>baz', '<span><b><b>[]bar</b></b></span>baz' ],
            
            'foo<span>[bar</span><span>baz]</span>bam',
            [ 'foo<span>bar[</span><span>]baz</span>bam', 'foo<span>bar[]</span><span>baz</span>bam' ]
        ],
        nestedPhrasingTests = [
            [ 'foo{<span><span><span><span>bar}</span></span></span></span>baz', 'foo<span><span><span><span>[bar]</span></span></span></span>baz' ],
            [ 'foo<span><span>{<span><span>bar}</span></span></span></span>baz', 'foo<span><span><span><span>[bar]</span></span></span></span>baz' ],
            [ 'foo{<span><span><span><span>}bar</span></span></span></span>baz', 'foo[]<span><span><span><span>bar</span></span></span></span>baz' ],
            [ 'foo<span><span>{<span><span>}bar</span></span></span></span>baz', 'foo[]<span><span><span><span>bar</span></span></span></span>baz' ],
            [ 'foo{<span><span><span><span>]bar</span></span></span></span>baz', 'foo[]<span><span><span><span>bar</span></span></span></span>baz' ],
            [ 'foo<span><span>{<span><span>]bar</span></span></span></span>baz', 'foo[]<span><span><span><span>bar</span></span></span></span>baz' ],
            [ 'foo{<span><span><br><span><span>bar}</span></span></span></span>baz', 'foo<span><span>{<br><span><span>bar]</span></span></span></span>baz' ],
            [ 'foo{<span><i><u><b>bar}</b></u></i></span>baz', 'foo<span><i><u><b>[bar]</b></u></i></span>baz' ],

            'foo<span>[bar</span><span>baz]</span>bam',
            [ 'foo<span>bar[</span><span>]baz</span>bam', 'foo<span>bar[]</span><span>baz</span>bam' ]
        ],
        
        /**
         * Special new IE tests
         */
        newIETests = [
                   [ 'foo{<b></b><p>bar]</p>', 'foo<b>{</b><p>bar]</p>' ],
                   [ 'foo[]<blockquote>bar</blockquote>', 'foo[]<blockquote>bar</blockquote>' ],
                   [ 'foo[]<div><p>bar</p></div>', 'foo[]<div><p>bar</p></div>' ],
                   [ 'foo[] <div><p>bar</p>', 'foo []<p>bar</p>' ],
                   [ 'foo <span>&nbsp;</span>[] bar', 'foo <span>&nbsp;</span>[] bar'] 
        ],
        
		correctRangeTests = [

//*
			//
			// getStartPositionFromFrontOfBlockNode ( all pass in all browsers )
			//
			[ 'foo{<p>bar]</p>', 'foo[<p>bar]</p>' ],
			[ '<b>foo</b>{<p>bar]</p>', '<b>foo[</b><p>bar]</p>' ],
			[ 'foo<div>{<p>bar]</p></div>', 'foo<div><p>[bar]</p></div>' ],
			[ '<b>foo</b><div>{<p>bar]</p></div>', '<b>foo</b><div><p>[bar]</p></div>' ],
			[ '<b>foo<u>foo1<i>foo2</i></u></b>{<p>bar]</p>', '<b>foo<u>foo1<i>foo2[</i></u></b><p>bar]</p>' ],
			[ 'foo<b></b>{<p>bar]</p>', 'foo<b>{</b><p>bar]</p>' ],
			[ 'foo<b></b><u></u>{<p>bar]</p>', 'foo<b></b><u>{</u><p>bar]</p>' ],
			[ 'foo<b><u></u></b>{<p>bar]</p>', 'foo<b><u>{</u></b><p>bar]</p>' ],
			[ '<u><i>foo</i></u><b></b>{<p>bar]</p>', '<u><i>foo</i></u><b>{</b><p>bar]</p>' ],
			[ '<b></b>{<p>foo]</p>', '<b></b><p>[foo]</p>' ],
			[ '<b><b></b></b>{<p>bar]</p>', '<b><b></b></b><p>[bar]</p>' ],
			[ '{<p>foo]</p>', '<p>[foo]</p>' ],
			[ '{<p><b>foo</b>bar]</p>', '<p><b>[foo</b>bar]</p>' ],
			[ '{<p><b></b>foo]</p>', '<p><b></b>[foo]</p>' ],
			[ 'foo<p></p>{<p>bar]</p>', 'foo<p></p><p>[bar]</p>' ],
			[ '<i>foo</i><b></b>{<p>bar]</p>', '<i>foo</i><b>{</b><p>bar]</p>' ],
			[ 'foo{<div><p>bar]</p></div>', 'foo[<div><p>bar]</p></div>' ],
			[ '<i>foo</i>{<div><p>bar]</p></div>', '<i>foo[</i><div><p>bar]</p></div>' ],
			[ '<p>foo</p>{<p>bar]</p>', '<p>foo</p><p>[bar]</p>' ],
			
//*/

//*
			//
			// getStartPositionFromEndOfBlockNode ( a few will never work on ie )
			//
			[ '<p>foo{</p><p>bar]</p>', '<p>foo[</p><p>bar]</p>' ],
			[ '<p>foo{</p>bar]', '<p>foo[</p>bar]' ],
			
			[ '<p>{</p><p>foo]</p>', '<p></p><p>[foo]</p>' ],
			[ '<p>{</p><b>foo]</b>', '<p></p><b>[foo]</b>' ],
			[ '<p>{</p>foo]', '<p></p>[foo]' ],
			
			[ '<div><p>{</p></div><p>bar]</p>', '<div><p></p></div><p>[bar]</p>' ],
			[ '<div><p>{</p><i></i>}<p></p></div>bar', '<div><p></p><i></i><p></p></div>[]bar' ],
			[ '<p>{</p><u></u>bar]', '<p></p><u></u>[bar]' ],
			[ '<p>{</p><p></p>bar]', '<p></p><p></p>[bar]' ],
			
			[ 'foo<p>{</p><p>bar]</p>', 'foo<p></p><p>[bar]</p>' ],
			[ '<p>{</p>}<p></p>bar', '<p></p><p></p>[]bar' ],
			[ '<p>{</p>}<p></p>', '{}<p></p><p></p>' ],
			[ '<p>foo{</p><p>bar}</p><p>baz</p>', '<p>foo[</p><p>bar]</p><p>baz</p>' ],
			
//*/

//*
			//
			// getEndPositionFromFrontOfInlineNode
			//
			// target position "...</p>]bar" or "...</p>}]bar" will alway be moved in ie
			//
			[ '[foo<b>}<u></u>bar</b>', '[foo]<b><u></u>bar</b>' ],
			[ '[foo<i>}<b><u></u>bar</b></i>', '[foo]<i><b><u></u>bar</b></i>' ],
			[ '[foo<p></p>}<b>bar</b>', '[foo<p></p><b>}bar</b>' ],
			[ '[foo<div><p>}<b>bar</b></p></div>', '[foo<div><p>}<b>bar</b></p></div>' ],
			
			[ '<p>[foo</p>}<b></b><p>bar</p>', '<p>[foo</p><b></b><p>}bar</p>' ], //dodgy
			
			[ '[foo<i><u></u><b>}bar</b></i>', '[foo]<i><u></u><b>bar</b></i>' ],
			[ '[foo<u></u><b>}bar</b>', '[foo]<u></u><b>bar</b>' ],
			[ '{<b>}bar</b>', '<b>[]bar</b>' ], // dodgy
			[ '[foo<p>}bar</p>', '[foo<p>}bar</p>' ],
			[ '[foo<p><b>}bar</b></p>', '[foo<p>}<b>bar</b></p>' ],
			[ '[foo<p><b></b>}bar</p>', '[foo<p>}<b></b>bar</p>' ],
			[ '[foo<div><p>}bar</p></div>', '[foo<div><p>}bar</p></div>' ],
			[ '{}foo', '[]foo' ],
			[ '[foo<p></p>}bar', '[foo<p></p>]bar' ],
			[ '<p>[foo</p>}bar', '<p>[foo</p>]bar' ],
			
//*/

//*
			//
			// getEndPositionFromFrontOfBlockNode
			//
			// most of these will fail in ie
			// TODO: convert "{}" to "[]"
			//
			[ '[foo}<p>bar</p>', '[foo]<p>bar</p>' ],
			[ '<b>[foo</b>}<p>bar</p>', '<b>[foo]</b><p>bar</p>' ],
			[ '<b>[foo</b>}<p></p>bar', '<b>[foo]</b><p></p>bar' ],
			[ '<b>[foo</b>}<p><b></b>bar</p>', '<b>[foo]</b><p><b></b>bar</p>' ],

			[ '<b>[foo</b>}<p></p>', '<b>[foo]</b><p></p>' ],
			[ '<div><b>[foo</b>}<p></p></div>bar', '<div><b>[foo]</b><p></p></div>bar' ],
	
			[ '<b>[foo</b>}<p></p>', '<b>[foo]</b><p></p>' ],
			[ '<b>[foo</b>}<p></p><p>bar</p>', '<b>[foo]</b><p></p><p>bar</p>' ],

			[ '{}<p>foo</p>', '<p>[]foo</p>' ],
			[ '<b>foo</b>{}<p>bar</p>', '<b>foo[]</b><p>bar</p>' ], // !!! IE Will not accept our expected range
			[ '<p>foo</p>{}<p>bar</p>', '<p>foo</p><p>[]bar</p>' ],

			[ '[foo<div>}<p>bar</p></div>', '[foo<div><p>}bar</p></div>' ],
			[ '<div><p>[foo</p></div><div>}<p>bar</p></div>', '<div><p>[foo</p></div><div><p>}bar</p></div>' ],
		
			[ '<p>[foo</p>}<div>bar</div>', '<p>[foo</p><div>}bar</div>' ],
			[ '<p>[foo</p>}<div><b>bar</b></div>', '<p>[foo</p><div>}<b>bar</b></div>' ],
			[ '<p>[foo</p>}<p></p><div>bar</div>', '<p>[foo</p><p></p><div>}bar</div>' ],
			[ '<p>[foo</p>}<p></p><div></div><b>bar</b>', '<p>[foo</p><p></p><div></div><b>}bar</b>' ],
			[ '<p>[foo</p>}<p><b>bar</b></p>', '<p>[foo</p><p>}<b>bar</b></p>' ],
			
			[ '[foo}<p>bar</p>', '[foo]<p>bar</p>' ],
			[ '<p>[foo</p>}<p>bar</p>', '<p>[foo</p><p>}bar</p>' ],
			[ '<p>[foo</p>}<p><b></b>bar</p>', '<p>[foo</p><p>}<b></b>bar</p>' ],
			[ '<p>[foo</p>}<p><b>bar</b></p>', '<p>[foo</p><p>}<b>bar</b></p>' ],
			[ '<p>[foo</p>}<div><p>test</p></div>', '<p>[foo</p><div><p>}test</p></div>' ],
			[ '<p>[foo</p>}<div></div><p>bar</p>', '<p>[foo</p><div></div><p>}bar</p>' ],
			
			[ '<p>[foo</p>}<p></p>bar', '<p>[foo</p><p></p>]bar' ],
			[ '<div><p>[foo</p>}<p></p></div>bar', '<div><p>[foo]</p><p></p></div>bar' ],
			[ '<div><p>{</p><b></b>}<p></p></div>bar', '<div><p></p><b></b><p></p></div>[]bar' ],
			[ '<p>{</p>}<p></p>bar', '<p></p><p></p>[]bar' ],
			[ '<p>{</p>}<p></p>', '{}<p></p><p></p>' ],
			
//*/

//*
			//
			// getEndPositionFromEndOfBlockNode
			//
			// all ok, but some must fail in ie
			//
			[ '<p>[foo}</p>', '<p>[foo]</p>' ],
			[ '[foo<p>}</p>', '[foo]<p></p>' ],
			
			[ '[foo<div><p>}</p></div>', '[foo]<div><p></p></div>' ],
			[ '<p>[foo<b>bar</b>}</p>', '<p>[foo<b>bar]</b></p>' ],
			[ '<p>[foo<b>bar</b>test}</p>', '<p>[foo<b>bar</b>test]</p>' ],
			[ '<p>[foo<b>bar</b>}</p>test', '<p>[foo<b>bar]</b></p>test' ],

			[ '[foo<div><p><u>bar</u></p>}</div>', '[foo<div><p><u>bar]</u></p></div>' ],
			[ '[foo<div><p><u></u></p>}</div>', '[foo]<div><p><u></u></p></div>' ],
			[ '[foo<div><p>bar<u></u></p>}</div>', '[foo<div><p>bar]<u></u></p></div>' ]

			[ '[foo<p></p>}', '[foo]<p></p>' ],
			[ '[foo<div><p></p></div>}', '[foo]<div><p></p></div>' ],
			[ '[foo<div><p><u></u></p></div>}', '[foo]<div><p><u></u></p></div>' ],
			
			[ '<p>foo</p>test{<p>bar</p>}<p>baz</p>', '<p>foo</p>test[<p>bar</p><p>}baz</p>' ],
			[ '<p>foo{</p><p>bar}</p><p>baz</p>', '<p>foo[</p><p>bar]</p><p>baz</p>' ],
			[ '<p>foo</p>{<p>bar}</p><p>baz</p>', '<p>foo</p><p>[bar]</p><p>baz</p>' ]
			
//*/
	
//*
			//
			// Start position at start of editing host
			// ie has some issues
			//
			[ '{}<p></p>', '{}<p></p>' ],
			[ '{<p>}</p>', '{}<p></p>' ],
			[ '{<p></p>}', '{}<p></p>' ],
			[ '{<p></p><p></p>}', '{}<p></p><p></p>' ],
			[ '{<p>}foo</p>', '<p>[]foo</p>' ],
			[ '{<p>}</p>', '{}<p></p>' ]

			[ '{<p></p><p>}</p>', '{}<p></p><p></p>' ],
			[ '{<p></p><p>}foo</p>', '<p></p><p>[]foo</p>' ],

			[ '{<p></p><div><p>}foo</p></div>', '<p></p><div><p>[]foo</p></div>' ],
			[ '{<p></p><div><p>}</p></div>', '{}<p></p><div><p></p></div>' ],

			[ '{<p></p><div></div><p>}foo</p>', '<p></p><div></div><p>[]foo</p>' ], // IE won't accept
			[ '{<p></p><div></div><p>}</p>', '{}<p></p><div></div><p></p>' ],
			
//*/

//*
			//
			// getStartPositionFromFrontOfInlineNode
			//
			// ie will fail in some
			//
			[ 'foo{<b></b><p>bar]</p>', 'foo<b>{</b><p>bar]</p>' ],
			[ 'foo{<b></b><u></u><p>bar]</p>', 'foo<b></b><u>{</u><p>bar]</p>' ],
			[ 'foo{<b></b><div></div><p>bar]</p>', 'foo<b>{</b><div></div><p>bar]</p>' ],
			[ '<b>foo{<u></u></b><p>bar]</p>', '<b>foo<u>{</u></b><p>bar]</p>' ],
			[ 'foo{<b></b><div><u></u></div><p>bar]</p>', 'foo<b>{</b><div><u></u></div><p>bar]</p>' ],
			[ '<p>foo{<b></b></p><div><u></u></div><p>bar]</p>', '<p>foo<b>{</b></p><div><u></u></div><p>bar]</p>' ],
			[ '<div>foo{<b></b><i></i></div><div><u></u></div><p>bar]</p>', '<div>foo<b></b><i>{</i></div><div><u></u></div><p>bar]</p>' ],
			[ '<div>foo{<b></b><p></p></div><p>bar]</p>', '<div>foo<b>{</b><p></p></div><p>bar]</p>' ],
			[ '<div>foo{<b></b><p></p></div><div><u></u></div><p>bar]</p>', '<div>foo<b>{</b><p></p></div><div><u></u></div><p>bar]</p>' ],
			[ '<div>foo<p>test{<b></b></p></div><div><u></u></div><p>bar]</p>', '<div>foo<p>test<b>{</b></p></div><div><u></u></div><p>bar]</p>' ],
			
			[ '{<b></b><p>foo]</p>', '<b></b><p>[foo]</p>' ],
			[ '{<b></b><u></u><p>foo]</p>', '<b></b><u></u><p>[foo]</p>' ],
			[ '{<b></b><div></div><p>foo]</p>', '<b></b><div></div><p>[foo]</p>' ],
			[ '<b>{<u></u></b><p>foo]</p>', '<b><u></u></b><p>[foo]</p>' ],
			[ '{<b></b><div><u></u></div><p>foo]</p>', '<b></b><div><u></u></div><p>[foo]</p>' ],
			[ '<p>{<b></b></p><div><u></u></div><p>bar]</p>', '<p><b></b></p><div><u></u></div><p>[bar]</p>' ],
			
			[ 'foo{<b></b><p>}</p>', 'foo[]<b></b><p></p>' ],
			[ 'foo{<b></b><u></u><p>}</p>', 'foo[]<b></b><u></u><p></p>' ],
			[ 'foo{<b></b><div></div><p>}</p>', 'foo[]<b></b><div></div><p></p>' ],
			[ '<b>foo{<u></u></b><p>}</p>', '<b>foo[]<u></u></b><p></p>' ],
			[ 'foo{<b></b><div><u></u></div><p>}</p>', 'foo[]<b></b><div><u></u></div><p></p>' ],
			[ '<p>foo{<b></b></p><div><u></u></div><p>}</p>', '<p>foo[]<b></b></p><div><u></u></div><p></p>' ],
			
			[ '{<b></b><p>}</p>', '{}<b></b><p></p>' ],
			[ '{<b></b><u></u><p>}</p>', '{}<b></b><u></u><p></p>' ],
			[ '{<b></b><div></div><p>}</p>', '{}<b></b><div></div><p></p>' ],
			[ '<b>{<u></u></b><p>}</p>', '{}<b><u></u></b><p></p>' ], // IE Fails very strangly here
			[ '{<b></b><div><u></u></div><p>}</p>', '{}<b></b><div><u></u></div><p></p>' ],
			[ '<p>{<b></b></p><div><u></u></div><p>}</p>', '{}<p><b></b></p><div><u></u></div><p></p>' ],
			[ '<b>foo{<u></u></b>bar]', '<b>foo<u></u></b>[bar]' ],
			
//*/			

//*
			//
			// getStartPositionFromEndOfInlineNode
			//
			// many will fail in ie
			//
			// With text node inside container node and right text node
			[ '<b>foo{</b>bar]', '<b>foo</b>[bar]' ],
			[ '<b>foo{</b><b></b>bar]', '<b>foo</b><b></b>[bar]' ],
			[ '<b>foo{</b><b>bar]</b>', '<b>foo</b><b>[bar]</b>' ],
			[ '<b><u>foo</u>{</b>bar]', '<b><u>foo</u></b>[bar]' ],
			[ '<b>foo<u>{</u></b>bar]', '<b>foo<u></u></b>[bar]' ],
			// ... With block node between start container and right text node
			[ '<b>foo{</b><p></p>bar]', '<b>foo[</b><p></p>bar]' ],
			[ '<b>foo{</b><p>bar]</p>', '<b>foo[</b><p>bar]</p>' ],
			[ '<p><b>foo{</b></p>bar]', '<p><b>foo[</b></p>bar]' ],
			// With text node left and right of container node, and none inside
			[ 'foo<b>{</b>bar]', 'foo<b></b>[bar]' ],
			[ 'foo<b>{</b><b></b>bar]', 'foo<b></b><b></b>[bar]' ],
			[ 'foo<b>{</b><b>bar]</b>', 'foo<b></b><b>[bar]</b>' ],
			// ... With block node between start container and right text node
			[ 'foo<b>{</b><p>bar]</p>', 'foo<b>{</b><p>bar]</p>' ],
			[ 'foo<b>{</b><p></p>bar]', 'foo<b>{</b><p></p>bar]' ],
			[ 'foo<b>{</b><p></p>}', 'foo[]<b></b><p></p>' ],
			
			// With text node left of container, none right and none inside
			[ 'foo<b>{</b>}', 'foo[]<b></b>' ],
			[ 'foo<p><b>{</b>}</p>', 'foo[]<p><b></b></p>' ],
			// With text node inside of container, none to right
			[ '<b><u>foo</u>{</b>}', '<b><u>foo[]</u></b>' ],
			[ '<b>foo<u></u>{</b>}', '<b>foo[]<u></u></b>' ],
			[ '<b><u>foo</u>{</b><p></p>}', '<b><u>foo[]</u></b><p></p>' ],
			
			[ '<b>foo{</b>}', '<b>foo[]</b>' ],

			// With a text node left, and right of start container
			[ 'foo<b>{</b><p>bar]</p>', 'foo<b>{</b><p>bar]</p>' ],
			[ 'foo<b>{</b><u></u><p>bar]</p>', 'foo<b></b><u>{</u><p>bar]</p>' ],
			[ 'foo<b>{</b><div></div><p>bar]</p>', 'foo<b>{</b><div></div><p>bar]</p>' ],
			[ '<b>foo<u>{</u></b><p>bar]</p>', '<b>foo<u>{</u></b><p>bar]</p>' ],
			[ 'foo<b>{</b><div><u></u></div><p>bar]</p>', 'foo<b>{</b><div><u></u></div><p>bar]</p>' ],
			[ '<p>foo<b>{</b></p><div><u></u></div><p>bar]</p>', '<p>foo<b>{</b></p><div><u></u></div><p>bar]</p>' ],
			// With a text node inside start container
			[ '<b>foo{</b><p>bar]</p>', '<b>foo[</b><p>bar]</p>' ],
			[ '<b>foo{</b><u></u><p>bar]</p>', '<b>foo</b><u>{</u><p>bar]</p>' ],
			[ '<b>foo{</b><div></div><p>bar]</p>', '<b>foo[</b><div></div><p>bar]</p>' ],
			[ '<p><b>foo{</b></p><div><u></u></div><p>bar]</p>', '<p><b>foo[</b></p><div><u></u></div><p>bar]</p>' ],
			// With no text node left of start container, but with one on right
			[ '<b>{</b><p>foo]</p>', '<b></b><p>[foo]</p>' ],
			[ '<b>{</b><u></u><p>foo]</p>', '<b></b><u></u><p>[foo]</p>' ],
			[ '<b>{</b><div></div><p>foo]</p>', '<b></b><div></div><p>[foo]</p>' ],
			[ '<b><u>{</u></b><p>foo]</p>', '<b><u></u></b><p>[foo]</p>' ],
			[ '<b>{</b><div><u></u></div><p>foo]</p>', '<b></b><div><u></u></div><p>[foo]</p>' ],
			[ '<p><b>{</b></p><div><u></u></div><p>bar]</p>', '<p><b></b></p><div><u></u></div><p>[bar]</p>' ],
			// With text node left of start container, but none on the right
			[ 'foo<b>{</b><p>}</p>', 'foo[]<b></b><p></p>' ],
			[ 'foo<b>{</b><u></u><p>}</p>', 'foo[]<b></b><u></u><p></p>' ],
			[ 'foo<b>{</b><div></div><p>}</p>', 'foo[]<b></b><div></div><p></p>' ],
			[ '<b>foo<u>{</u></b><p>}</p>', '<b>foo[]<u></u></b><p></p>' ],
			[ 'foo<b>{</b><div><u></u></div><p>}</p>', 'foo[]<b></b><div><u></u></div><p></p>' ],
			[ '<p>foo<b>{</b></p><div><u></u></div><p>}</p>', '<p>foo[]<b></b></p><div><u></u></div><p></p>' ],
			
//*/

//*
			//
			// getStartPositionFromFrontOfTextNode
			//
			// ok an all browser
			//
			[ '<p>{foo]</p>', '<p>[foo]</p>' ],
			[ '<b>{foo]</b>', '<b>[foo]</b>' ],
			[ 'foo<p>{bar]</p>', 'foo<p>[bar]</p>' ],
			[ 'foo<b>{bar]</b>', 'foo<b>[bar]</b>' ],
			
//*/

//*
			//
			// getEndPositionFromFrontOfTextNode
			//
			// ie will fail some of these
			//
			[ '[foo}bar', '[foobar]' ],
			[ '[foo<p>}bar</p>', '[foo<p>}bar</p>' ],
			[ '[foo<p>}bar</p>test', '[foo<p>}bar</p>test' ],
			[ '[foo<div><p>}bar</p></div>', '[foo<div><p>}bar</p></div>' ],
			[ '<p>[foo</p><b></b>}bar', '<p>[foo</p><b>}</b>bar' ],
			[ '[foo<p></p><b>}bar</b>', '[foo<p></p><b>}bar</b>' ],
			[ '[foo<i><b>}bar</b></i>', '[foo]<i><b>bar</b></i>' ],
			[ '<i>[foo</i><b></b>}bar', '<i>[foo]</i><b></b>bar' ],
			[ '<p>[foo</p><u></u><b></b>}bar', '<p>[foo</p><u>}</u><b></b>bar' ],
			[ '[foo<p><i></i><b></b>}bar</p>', '[foo<p>}<i></i><b></b>bar</p>' ],
			[ '[foo<p><b></b>}bar</p>', '[foo<p>}<b></b>bar</p>' ],
			[ '<p><b>[foo</b></p><p>}bar</p>', '<p><b>[foo</b></p><p>}bar</p>' ],
			[ '[foo<p><b>}bar</b></p>', '[foo<p>}<b>bar</b></p>' ],
			[ '[foo<b>}bar</b>', '[foo]<b>bar</b>' ],	
			[ '[foo<p></p>}bar', '[foo<p></p>]bar' ],
			[ '<p>[foo</p>}bar', '<p>[foo</p>]bar' ],
			[ '{<p>foo</p><p>}bar</p>', '<p>[foo</p><p>}bar</p>' ],
			[ '[foo<p></p><b></b>}bar', '[foo<p></p><b>}</b>bar' ],
			[ '[foo<p></p><i></i><b></b>}bar', '[foo<p></p><i>}</i><b></b>bar' ],
			[ '[foo<p><i></i></p><b></b>}bar', '[foo<p><i></i></p><b>}</b>bar' ],
			
			[ '{<p></p><p>}foo</p>', '<p></p><p>[]foo</p>' ],
			[ '{<p><b></b></p><p>}bar</p>', '<p><b></b></p><p>[]bar</p>' ],
			[ '<p>{}foo</p>', '<p>[]foo</p>' ],
			
//*/

//*
			//
			// getEndPositionFromEndOfInlineNode
			//
			// ie must fai on some of these
			//
			[ 'foo<b>{}</b>bar', 'foo[]<b></b>bar'],
			[ 'foo[]<span></span>bar', 'foo[]<span></span>bar' ],
			[ '<b>[foo}</b>', '<b>[foo]</b>' ],
			[ '<b>[foo}</b>bar', '<b>[foo]</b>bar' ],
			[ '[foo<b>}</b>', '[foo]<b></b>' ],
			[ '{<b>}</b>bar', '<b></b>[]bar' ],
			
			[ '<b>[foo<i>bar</i>}</b>', '<b>[foo<i>bar]</i></b>' ],
			[ '[foo<b><i>bar</i>}</b>', '[foo<b><i>bar]</i></b>' ],
			
			[ '<b>[foo<i></i>}</b>', '<b>[foo]<i></i></b>' ],
			[ '<b>[foo<u></u><i></i>}</b>', '<b>[foo]<u></u><i></i></b>' ],
			[ '<b>[foo<u><i></i></u>}</b>', '<b>[foo]<u><i></i></u></b>' ],
			[ '<b>[foo</b><p></p><i>}</i>', '<b>[foo]</b><p></p><i></i>' ],

			['[foo<p></p><b>}</b>bar', '[foo<p></p><b>}</b>bar'],
			['[foo<p><b>}</b>bar</p>', '[foo<p>}<b></b>bar</p>'],
			['[foo<p><b>}</b>bar</p>', '[foo<p>}<b></b>bar</p>'],
			['[foo<p></p><b>}</b>', '[foo]<p></p><b></b>'],
			['[foo<p><b>}</b></p>', '[foo]<p><b></b></p>'],

			[ '[foo<p><b>}</b></p>bar', '[foo<p><b></b></p>]bar' ],
			[ '[foo<p><b>}</b>bar</p>', '[foo<p>}<b></b>bar</p>' ],
			[ '[foo<p><b>}</b>bar</p>', '[foo<p>}<b></b>bar</p>' ],

			[ '<b>[foo</b><p><i>}</i></p>', '<b>[foo]</b><p><i></i></p>' ],
			[ '<b>[foo</b><p><b></b><i>}</i></p>', '<b>[foo]</b><p><b></b><i></i></p>' ],
			[ '<b>[foo</b><p><i>}</i></p>bar', '<b>[foo</b><p><i></i></p>]bar' ],
			[ '<b>[foo</b><p>bar<i>}</i></p>', '<b>[foo</b><p>bar]<i></i></p>' ],
			[ '<p>[foo</p><b><i></i>}</b>', '<p>[foo]</p><b><i></i></b>' ],
			[ '<p>[foo</p><b><i></i>}</b>bar', '<p>[foo</p><b>}<i></i></b>bar' ],
			
			[ '[foo<p></p><b>}</b>bar', '[foo<p></p><b>}</b>bar' ],
			[ '[foo<p></p><i>}</i><b></b>bar', '[foo<p></p><i>}</i><b></b>bar' ],
			[ '[foo<p><i></i></p><b>}</b>bar', '[foo<p><i></i></p><b>}</b>bar' ],
			
//*/

//*
			//
			// IE does not accept our expected selection for the next 3 tests
			//
			// TODO: convert collapsed selections "{}" to "[]"
			// FIXME: void elements
			//
			[ '<div></div>{<b></b><p>}</p>', '{}<div></div><b></b><p></p>' ],
			[ '<div>{<b></b></div><p>}</p>', '{}<div><b></b></div><p></p>' ],
			[ '<b></b><div>{<b></b></div><p>}</p>', '{}<b></b><div><b></b></div><p></p>' ]
			
			[ 'foo{<b><i></i></b>}', 'foo{}<b><i></i></b>' ],
			[ 'foo{<b></b>}', 'foo{}<b></b>' ],
			[ '{<b></b>}', '{}<b></b>' ],
			
			[ '<p>{<b></b>}</p>', '{}<p><b></b></p>' ],
			[ '<p>{<b></b>}</p>', '{}<p><b></b></p>' ],
			[ '<i>{<b></b>}</i>', '{}<i><b></b></i>' ],
			[ '<p><i>{<b></b>}</i></p>', '{}<p><i><b></b></i></p>' ],
			[ '<i></i>{<b></b>}', '{}<i></i><b></b>' ],
			[ '<p></p>{<b></b>}', '{}<p></p><b></b>' ],
			[ '<p><i></i></p>{<b></b>}', '{}<p><i></i></p><b></b>' ],
			
			[ '{<b></b><p></p>}', '{}<b></b><p></p>' ],
			[ '<div><p>{<b></b></p></div><p></p>}', '{}<div><p><b></b></p></div><p></p>' ],
			[ 'foo<div><p>{<b></b></p></div><p></p>}', 'foo[]<div><p><b></b></p></div><p></p>' ],
			
//*/
			
/*
			// FIXME void elements

			[ '<p>[foo</p><p>bar]</p><p>baz</p>', '<p>[foo</p><p>bar]</p><p>baz</p>' ],
			[ '<p>[foo</p><p>]bar</p><p>baz</p>', '<p>[foo</p><p>}bar</p><p>baz</p>' ],
			[ '<p>foo[</p><p>]bar</p><p>baz</p>', '<p>foo[</p><p>}bar</p><p>baz</p>' ],
			
			[ '<p>foo</p><p>{bar</p><p>}baz</p>', '<p>foo</p><p>[bar</p><p>}baz</p>' ]
			
            [ 'foo<p>{<i><u><b>bar}</b></u></i></p>baz', 'foo<p><i><u><b>[bar]</b></u></i></p>baz' ],
            [ 'foo<p>{<i></i><u><b>bar}</b></u></p>baz', 'foo<p><i></i><u><b>[bar]</b></u></p>baz' ],
            [ 'foo<p>{<i><br><b>bar}</b></i></p>baz', 'foo<p><i>{<br><b>bar]</b></i></p>baz' ],
            
            [ '<p>foo[</p><hr><p>]baz</p>', '<p>foo[</p><hr><p>}baz</p>' ],
            [ '<p>foo[</p><hr><p>}baz</p>', '<p>foo[</p><hr><p>}baz</p>' ],
            [ '<p>foo[</p><hr>}<p>baz</p>', '<p>foo[</p><hr><p>}baz</p>' ],
            [ '<p>foo[</p>}<hr><p>baz</p>', '<p>foo[</p>}<hr><p>baz</p>' ],
            [ '<p>foo{</p><hr><p>]baz</p>', '<p>foo[</p><hr><p>}baz</p>' ],
            [ '<p>foo</p>{<hr><p>]baz</p>', '<p>foo</p>{<hr><p>}baz</p>' ],
            [ '<p>foo</p><hr>{<p>]baz</p>', '<p>foo</p><hr><p>[]baz</p>' ]
			
			[ '{<b>foo</b><p>}bar</p>', '<b>[foo</b><p>}bar</p>' ],
			[ '{<b></b><p>}foo</p>', '<b></b><p>[]foo</p>' ],
			[ '{<p>foo</p><p>}bar</p>', '<p>[foo</p><p>}bar</p>' ],
			[ '{<p><b></b></p><p>}foo</p>', '<p><b></b></p><p>[]foo</p>' ],
			[ '{<p></p><p>}foo</p>', '<p></p><p>[]foo</p>' ],
			[ '<p>[}foo</p>', '<p>[]foo</p>' ],
			[ '<p>{}foo</p>', '<p>[]foo</p>' ],
			[ '{<p>}foo</p>', '<p>[]foo</p>' ],
			[ '{}<p>foo</p>', '<p>[]foo</p>' ],
			[ '{}<div><p>bar</p></div>', '<div><p>[]bar</p></div>' ],
			[ '[foo<p><b>}bar</b></p>', '[foo<p>}<b>bar</b></p>' ],
			[ '<span><b>[foo</b></span><p>}bar</p>', '<span><b>[foo</b></span><p>}bar</p>' ],

//*/
			[]
        ],
		
        flowTests = [],
		
        flowHostTests = [         

//*

            [ '<div>foo[</div><hr><div>]baz</div>', '<div>foo[</div><hr><div>}baz</div>' ],
            [ '<div>foo[</div><hr><div>}baz</div>', '<div>foo[</div><hr><div>}baz</div>' ],
            [ '<div>foo[</div><hr>}<div>baz</div>', '<div>foo[</div><hr><div>}baz</div>' ],
            [ '<div>foo[</div>}<hr><div>baz</div>', '<div>foo[</div>}<hr><div>baz</div>' ],
            [ '<div>foo{</div><hr><div>]baz</div>', '<div>foo[</div><hr><div>}baz</div>' ],
            [ '<div>foo</div>{<hr><div>]baz</div>', '<div>foo</div>{<hr><div>}baz</div>' ],
            [ '<div>foo</div><hr>{<div>]baz</div>', '<div>foo</div><hr><div>[]baz</div>' ],

//*/

//*
			[ 'foo{<div><div>bar]</div></div>', 'foo[<div><div>bar]</div></div>' ],
			[ 'foo<div>{<div>bar]</div></div>', 'foo<div><div>[bar]</div></div>' ],
			[ 'foo<div>{<div>bar}</div></div>', 'foo<div><div>[bar]</div></div>' ],
			[ 'foo<div>{<div>bar</div>}</div>', 'foo<div><div>[bar]</div></div>' ]
//*/
		
            // not a different flow test [ 'foo{<div><div><div><div>}bar</div></div></div></div>baz', 'foo[<div><div><div><div>}bar</div></div></div></div>baz' ],
            // [ 'foo<div><div>{<div><div>}bar</div></div></div></div>baz', 'foo<div><div><div><div>[]bar</div></div></div></div>baz' ],
            // [ 'foo{<div><div><div><div>]bar</div></div></div></div>baz', 'foo[<div><div><div><div>}bar</div></div></div></div>baz' ],
            // [ 'foo<div><div>{<div><div>]bar</div></div></div></div>baz', 'foo<div><div><div><div>[]bar</div></div></div></div>baz' ],
            // [ 'foo<div>{<div><br><div><div>bar}</div></div></div></div>baz', 'foo<div><div>{<br><div><div>bar]</div></div></div></div>baz' ]
			
        ],
        // dl, dd, dt not covered by tests
        // http://www.w3.org/wiki/HTML_lists#Nesting_lists
        listTests = [
            [ 'foo<ol><li>[bar]</li></ol>baz', 'foo<ol><li>[bar]</li></ol>baz' ],
            [ 'foo<ol><li data-start=0>bar]</li></ol>baz', 'foo<ol><li>[bar]</li></ol>baz' ],
            [ 'foo<ol><li>[bar}</li></ol>baz', 'foo<ol><li>[bar]</li></ol>baz' ],
            [ 'foo<ol><li>{bar}</li></ol>baz', 'foo<ol><li>[bar]</li></ol>baz' ],
            [ 'foo<ol data-start=0><li>bar]</li></ol>baz', 'foo<ol><li>[bar]</li></ol>baz' ],
            [ 'foo<ol data-start=0 data-end=1><li>bar</li></ol>baz', 'foo<ol><li>[bar]</li></ol>baz' ],
            [ 'foo[<ol><li>bar</li></ol>]baz', 'foo[<ol><li>bar</li></ol>]baz' ],
            [ 'foo{<ol><li>bar</li></ol>}baz', 'foo[<ol><li>bar</li></ol>]baz' ],
            [ 'foo[<ol><li>bar]</li></ol>baz', 'foo[<ol><li>bar]</li></ol>baz' ],
            [ 'foo{<ol><li>bar}</li></ol>baz', 'foo[<ol><li>bar]</li></ol>baz' ],
            [ 'foo[<ol><li>]bar</li></ol>baz', 'foo[<ol><li>}bar</li></ol>baz' ],
            [ 'foo{<ol><li>}bar</li></ol>baz', 'foo[<ol><li>}bar</li></ol>baz' ],
            [ 'foo<ol><li>bar[</li><li>]bar</li></ol>baz', 'foo<ol><li>bar[</li><li>}bar</li></ol>baz' ],
            [ 'foo<ol><li>bar{</li><li>}bar</li></ol>baz', 'foo<ol><li>bar[</li><li>}bar</li></ol>baz' ],
            [ 'foo<ol><li>[bar<ol><li>]bam</li></ol></li></ol>baz', 'foo<ol><li>[bar<ol><li>}bam</li></ol></li></ol>baz' ]
        ];
/* tables are handled differently in Aloha Editor as every td, th's content is wrapped in a div.
        tableTests = [
            'foo[<table><tbody><tr><td>bar]baz</td></tr></tbody></table>',
            ['foo<table data-start=0 data-end=1><tbody><tr><td>barbaz</td></tr></tbody></table>', 'foo{<table><tbody><tr><td>barbaz</td></tr></tbody></table>}' ],
            ['foo<table><tbody data-start=0 data-end=1><tr><td>barbaz</td></tr></tbody></table>', 'foo<table><tbody><tr><td>[barbaz</td></tr></tbody></table>}' ],
            ['foo<table><tbody><tr data-start=0 data-end=1><td>barbaz</td></tr></tbody></table>', 'foo<table><tbody><tr><td>[barbaz</td></tr></tbody></table>}' ],
            ['foo<table><tbody><tr><td>{barbaz}</td></tr></tbody></table>', 'foo<table><tbody><tr><td>[barbaz]</td></tr></tbody></table>' ],
            ['foo<table><tbody><tr><td>{barbaz}</td><td>bamboo</td></tr></tbody></table>', 'foo<table><tbody><tr><td>[barbaz]</td><td>bamboo</td></tr></tbody></table>' ],
            ['foo<table><tbody><tr><td>{barbaz</td><td>}bamboo</td></tr></tbody></table>', 'foo<table><tbody><tr><td>[barbaz</td><td>}bamboo</td></tr></tbody></table>' ]
        ]
*/
    
    // All other tests are done when Aloha is ready
    Aloha.ready( function() {

        var 
            editable = Aloha.jQuery( '#edit' ),
            converter = Aloha.jQuery('<div>'),
            tests = [],
            /* 
             * Void elements http://dev.w3.org/html5/markup/spec.html#void-elements
             * 
             * area, base, br, col, command, embed, hr, img, input,
             * keygen, link, meta, param, source, track, wbr
             * 
             * - area, base,col, command, embed, keygen, link, meta, param, 
             *   source, track, wbr are not covered by tests
             * 
             */
            voidElements = [ 'hr', 'img', 'input' ],
            /*
             * All phrasing elements http://dev.w3.org/html5/markup/common-models.html#common.elem.phrasing
             * 
             * a, em, strong, small, mark, abbr, dfn, i, b, s, u, code,
             * var, samp, kbd, sup, sub, q, cite, span, bdo, bdi, br,
             * wbr, ins, del, img, embed, object, iframe, map, area,
             * script, noscript, ruby, video, audio, input, textarea,
             * select, button, label, output, datalist, keygen, progress,
             * command, canvas, time, meter
             * 
             * - br, img, embed is tested in void elements tests
             * - object, iframe, map, area, script, noscript, ruby, 
             * - video, audio, input, textarea, select, button, label, 
             *   output, datalist, keygen, progress, command, canvas, time,
             *   meter are not covered by tests
             * 
             */
            phrasingElements = [ 'a', 'em', 'strong', 'small', 'mark', 'abbr', 'dfn',
                         'i', 'b', 's', 'u', 'code', 'var', 'samp', 'kbd', 'sup',
                         'sub', 'q', 'cite', 'bdo', 'bdi', 'ins', 'del',
                         'ruby', 'time' ],
            /* 
             * All flow elements http://dev.w3.org/html5/markup/common-models.html#common.elem.flow
             * 
             * phrasing elements, a, p, hr, pre, ul, ol, dl,
             * div, h1, h2, h3, h4, h5, h6, hgroup, address, 
             * blockquote, ins, del, object, map, noscript, section,
             * nav, article, aside, header, footer, video, audio,
             * figure, table, form, fieldset, menu, canvas, details
             * 
             * - a, ins, del is tested in pharasing
             * - hr is tested in void tests
             * - ul, ol, dl are tested in list tests
             * - object, map, noscript, section, nav, article, aside, 
             *   header, footer, video, audio, figure, table, form, fieldset, 
             *   menu, canvas, details are not covered by tests
             * 
             */ 
            flowElements = [ 'pre', 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
                     'address', 'blockquote' ],
            flowHostElements = [ 'address', 'blockquote' ],
            newTests,
            newTest;
        
        function convertTests( replaceTag, newTag, tests ) {
            newTests = [];
            for ( var i = 0; i < tests.length; i++ ) {
                // ie hack :/
                if ( !tests[i] ) {
                    continue;
                }
                if ( jQuery.isArray ( tests[i]) ) {
                    newTest = [];
                    newTest[0] = tests[i][0].replace( replaceTag, newTag );
                    newTest[1] = tests[i][1].replace( replaceTag, newTag );
                } else {
                    newTest = tests[i].replace( replaceTag, newTag );
                }
                newTests.push( newTest );
            }
            return newTests;
        };
        
        tests = tests.concat(
        		
        	correctRangeTests,
			
			// newIETests,
        	
            // specialTests,
            
            // voidTests, // <br>
            
            // phrasingTests,
            
			// flowTests, // <p>
            
            // flowHostTests, // flow elements host
            
            // listTests,
            
            [] // I am here to prevent trailing commas and make your life easier :D
        );
        
        for ( var i = 0; i < voidElements.length; i++ ) {
            // ie hack :/
            if ( !tests[i] ) {  continue; }
			// tests = tests.concat( convertTests ( /br/g, voidElements[i], voidTests ) );
        }       
        // full phrasing tests
        for ( var i = 0; i < phrasingElements.length; i++ ) {
			// ie hack :/
			if ( !tests[i] ) {  continue; }
			// tests = tests.concat( convertTests ( /span/g, phrasingElements[i], phrasingTests ) );
        }
        for ( var i = 0; i < phrasingElements.length; i++ ) {
			// ie hack :/
			if ( !tests[i] ) {  continue; }
			// even if specified in HTML5 a cannot nest all phrasing (itself)
			if ( phrasingElements[i] == 'a' ) { continue; }
			// tests = tests.concat( convertTests ( /span/g, phrasingElements[i], nestedPhrasingTests ) );
        }
        // full flow tests
        for ( var i = 0; i < flowElements.length; i++ ) {
            // ie hack :/
            if ( !tests[i] ) {  continue; }
            //tests = tests.concat( convertTests ( /p/g, flowElements[i], flowTests ) );
        }
        // full flow host tests
        for ( var i = 0; i < flowHostElements.length; i++ ) {
            // ie hack :/
            if ( !tests[i] ) {  continue; }
            // tests = tests.concat( convertTests ( /div/g, flowHostElements[i], flowHostTests ) );
        }
        
        // aloha'fy the editable
        editable.aloha();
        
        for ( var i = tests_start; i < tests_stop && i < tests.length ; i++ ) {
            // ie hack :/
            if ( !tests[i] ) {  continue; }
            var 
                start = typeof tests[i] === 'string' ? tests[i] : tests[i][0],
                expected = typeof tests[i] === 'string' ? tests[i] : tests[i][1],
                desc = converter.text(start).html(); // + ' -> ' + converter.text(expected).html();
            
            module( 'Selection ' + (i+1) + ' : ' + desc, {
                setup: function() {
                    // fill the editable area with the start value
                    editable.html( this.start );
                    editable.focus();
                },
                teardown: function() {
                    // goodbye
                }
            });
            
            test( name, {start:start, expected:expected}, function() {
                var 
                    // place the selection (and remove the selection marker)
                    startRange = TestUtils.addRange( editable ),
                    endRange,
                    result;
                
                // remove all ranges
                Aloha.getSelection().removeAllRanges();
                
                // create a range object
                var testRange = Aloha.createRange();
                
                // set the range
                testRange.setStart( startRange.startContainer, startRange.startOffset );
                testRange.setEnd( startRange.endContainer, startRange.endOffset );
                
                // place the marker at the selection
                Aloha.getSelection().addRange( testRange );
                
                // get the selected Range
                endRange = Aloha.getSelection().getRangeAt( 0 );
                
                // add markers to selection
                TestUtils.addBrackets(endRange);
				
                // get the content of the editable
                result = Aloha.editables[ 0 ].getContents();

				// IE creates benign new lines, which cause false failures.
				// We therefore remove them for our unit tests 
				result = result.replace( /[\s\n\r]/g, '' );

                // compare the result with the expected result
                deepEqual( result.toLowerCase(), this.expected, 'Check Operation Result' );
            });
        }
    });
});
