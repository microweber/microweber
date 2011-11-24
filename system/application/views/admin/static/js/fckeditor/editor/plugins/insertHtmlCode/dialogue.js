/*
 * File Name: dialogue.js
 * 	Scripts for the fck_insertCode.html page.
 * 
 * File Authors:
 * 		Michel Staelens (michel.staelens@wanadoo.fr)
 * 		Bernadette Cierzniak
 * 		Abdul-Aziz Al-Oraij (top7up@hotmail.com)
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 *      Nico Piponides
 */

function afficher(txt)
{
	document.getElementById( 'insCode_area' ).value = txt ;
}

function rechercher()
{
	return document.getElementById( 'insCode_area' ).value ;
}