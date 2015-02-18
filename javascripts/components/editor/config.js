/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
/*
CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for a single toolbar row.
	config.toolbarGroups = [
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		// On the basic preset, clipboard and undo is handled by keyboard.
		// Uncomment the following line to enable them on the toolbar as well.
		// { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'forms' },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'tools' },
		{ name: 'others' },
		{ name: 'about' }
	];

	// The default plugins included in the basic setup define some buttons that
	// we don't want too have in a basic editor. We remove them here.
	config.removeButtons = 'Anchor,Strike,Subscript,Superscript,About';

	// Considering that the basic setup doesn't provide pasting cleanup features,
	// it's recommended to force everything to be plain text.
	config.forcePasteAsPlainText = true;

	// Let's have it basic on dialogs as well.
	config.removeDialogTabs = 'link:advanced';
};
*/
/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	var insertItems = [];
	
	// Set language to EN
	config.language = 'en';

	// Replace deafult Link plugin by WixLink plugin
	config.extraPlugins = 'codemirror,wixlink,wpmore';
	
	// Enable Wixgallery only for Chrome and Safari
	if ( CKEDITOR.env.webkit ) {
		config.extraPlugins += ',wixgallery';
		insertItems.push('wixgallery');
	}
	config.removePlugins = 'autogrow,link,image';
	
	// Disables the ability of resize objects (image and tables) in the editing area.
	config.disableObjectResizing = true;
	
	// config.emailProtection = 'encode';
	
	// The toolbar groups arrangement, optimized for a single toolbar row.
	config.toolbar = [
  		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
  		{ name: 'excerpt', items: [ 'WPMore'] },
  		{ name: 'styles', items: [ 'Font', 'FontSize' ] },
  		{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
  		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
  		'/',
  		{ name: 'lists', groups: [ 'list', 'indent'], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent' ] },
  		{ name: 'paragraph', groups: [ 'blocks', 'align', 'bidi' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl' ] },
  		{ name: 'links', items: [ 'wixlink', 'wixmail', 'wixunlink' ] },
  		{ name: 'insert', items: insertItems },
  		{ name: 'tools', items: [ 'Maximize' ] }
  	];
	// Toolbar configuration generated automatically by the editor based on config.toolbarGroups.
	 config.allowedContent = true;
  //	CKEDITOR.config.allowedContent = true;
	
	// The default plugins included in the basic setup define some buttons that
	// we don't want too have in a basic editor. We remove them here.
	// config.removeButtons = 'Anchor,Strike,Subscript,Superscript,About';
	
	// Considering that the basic setup doesn't provide pasting cleanup features,
	// it's recommended to force everything to be plain text.
	//config.forcePasteAsPlainText = true;
	
	// Load the 'mystyles' styles set from styles.js file.
	config.stylesSet = 'mystyles';

	// Let's have it basic on dialogs as well.
	//config.removeDialogTabs = 'link:advanced';
	
	// Sets whether the editable should have the focus when editor is loading for the first time.
	config.startupFocus = false;
	
	// If the dialog has more than one tab, put focus into the first tab as soon as dialog is opened.
	//config.dialog_startupFocusTab = true;
	
	config.pasteFromWordRemoveFontStyles = false;
	config.pasteFromWordNumberedHeadingToList = true;
	config.pasteFromWordRemoveStyles = false;
	
	//Config source highlight
	config.codemirror = {

	    // Set this to the theme you wish to use (codemirror themes)
	    theme: 'default',
	    // Whether or not you want to show line numbers
	    lineNumbers: true,
	    // Whether or not you want to use line wrapping
	    lineWrapping: true,
	    // Whether or not you want to highlight matching braces
	    matchBrackets: true,
	    // Whether or not you want tags to automatically close themselves
	    autoCloseTags: true,
	    // Whether or not you want Brackets to automatically close themselves
	    autoCloseBrackets: true,
	    // Whether or not to enable search tools, CTRL+F (Find), CTRL+SHIFT+F (Replace), CTRL+SHIFT+R (Replace All), CTRL+G (Find Next), CTRL+SHIFT+G (Find Previous)
	    enableSearchTools: true,
	    // Whether or not you wish to enable code folding (requires 'lineNumbers' to be set to 'true')
	    enableCodeFolding: true,
	    // Whether or not to enable code formatting
	    enableCodeFormatting: true,
	    // Whether or not to automatically format code should be done when the editor is loaded
	    autoFormatOnStart: true,	
	    // Whether or not to automatically format code should be done every time the source view is opened
	    autoFormatOnModeChange: true,	
	    // Whether or not to automatically format code which has just been uncommented
	    autoFormatOnUncomment: true,
	    // Whether or not to highlight the currently active line
	    highlightActiveLine: true,	
	    // Whether or not to show the search Code button on the toolbar
	    showSearchButton: true,	
	    // Whether or not to highlight all matches of current word/selection
	    highlightMatches: true,	
	    // Whether or not to show the format button on the toolbar
	    showFormatButton: true,	
	    // Whether or not to show the comment button on the toolbar
	    showCommentButton: true,	
	    // Whether or not to show the uncomment button on the toolbar
	    showUncommentButton: true
    };
};
