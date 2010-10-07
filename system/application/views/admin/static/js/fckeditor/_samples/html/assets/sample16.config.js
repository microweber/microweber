/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2008 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * Configuration settings used by the XHTML 1.1 sample page (sample14.html).
 */

// Our intention is force all formatting features to use CSS classes or
// semantic aware elements.

/**
 * Core styles.
 */
FCKConfig.CoreStyles.Bold			= { Element : 'b' } ;
FCKConfig.CoreStyles.Italic			= { Element : 'i' } ;
FCKConfig.CoreStyles.Underline		= { Element : 'u' } ;

/**
 * Font face
 */
// Define the way font elements will be applied to the document. The "span"
// element will be used. When a font is selected, the font name defined in the
// above list is passed to this definition with the name "Font", being it
// injected in the "class" attribute.
// We must also instruct the editor to replace span elements that are used to
// set the font (Overrides).
FCKConfig.CoreStyles.FontFace =
	{
		Element		: 'font',
		Attributes	: { 'face' : '#("Font")' }
	} ;

/**
 * Font sizes.
 * The CSS part of the font sizes isn't used by Flash, it is there to get the
 * font rendered correctly in FCKeditor.
 */
FCKConfig.FontSizes		= '8/8px;9/9px;10/10px;11/11px;12/12px;14/14px;16/16px;18/18px;20/20px;22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px' ;
FCKConfig.CoreStyles.Size =
	{
		Element		: 'font',
		Attributes	: { 'size' : '#("Size")' },
		Styles		: { 'font-size' : '#("Size","fontSize")' }
	} ;

/**
 * Font colors.
 */
FCKConfig.EnableMoreFontColors = true ;
FCKConfig.CoreStyles.Color =
	{
		Element		: 'font',
		Attributes	: { 'color' : '#("Color")' }
	} ;
/**
 * Styles combo.
 */
FCKConfig.StylesXmlPath = '' ;
FCKConfig.CustomStyles =
	{
	} ;

/**
 * Toolbar set for Flash HTML editing.
 */
FCKConfig.ToolbarSets['Flash'] = [
	['Source','-','Bold','Italic','Underline','-','UnorderedList','-','Link','Unlink'],
	['FontName','FontSize','-','About']
] ;

/**
 * Flash specific formatting settings.
 */
FCKConfig.EditorAreaStyles = 'p, ol, ul {margin-top: 0px; margin-bottom: 0px;}' ;
FCKConfig.FormatSource = false ;
FCKConfig.FormatOutput = false ;
