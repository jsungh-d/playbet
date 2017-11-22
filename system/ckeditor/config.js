/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	config.skin = 'office2013';
	config.extraPlugins ='youtubebootstrap,autogrow,tableresize,fixed,imagecrop,simpleuploads';
	 config.toolbar = 
	 	[
	 		['Font','FontSize','-','Bold','Italic','Underline','Strike','TextColor','BGColor','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','Link','Unlink','Table','youtubebootstrap', 'addImage' ]
	 	];
	 /*config.toolbar =
		[
			{ name: 'document',    items : [ 'Source' ] },
			{ name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
			{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','-','RemoveFormat' ] },
			{ name: 'paragraph',   items : [ 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'] },
			{ name: 'colors',      items : [ 'TextColor','BGColor' ] },
			{ name: 'insert',      items : [ 'Image', 'addImage'] },
			{ name: 'tools',       items : [ 'Maximize', 'About' ] }
		];*/

	
};
