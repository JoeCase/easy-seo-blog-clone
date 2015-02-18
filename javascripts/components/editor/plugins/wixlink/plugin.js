/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.plugins.add( 'wixlink', {
	requires: 'dialog',
	lang: 'en', // %REMOVE_LINE_CORE%
	icons: 'wixlink,wixmail,wixunlink', // %REMOVE_LINE_CORE%
	init: function( editor ) {
		var wixlinkAllowedRequired = 'a[href,target]',
			wixmailAllowedRequired = 'a[href]';

		// Add the wixlink, wixmail and wixunlink buttons.
		editor.addCommand( 'wixlink', new CKEDITOR.dialogCommand( 'wixlink', {
			allowedContent: wixlinkAllowedRequired,
			requiredContent: wixlinkAllowedRequired
		} ) );
		editor.addCommand( 'wixmail', new CKEDITOR.dialogCommand( 'wixmail', {
			allowedContent: wixmailAllowedRequired,
			requiredContent: wixmailAllowedRequired
		} ) );
		editor.addCommand( 'wixunlink', new CKEDITOR.wixunlinkCommand() );

		editor.setKeystroke( CKEDITOR.CTRL + 76 /*L*/, 'wixlink' );
		editor.setKeystroke( CKEDITOR.CTRL + 77 /*M*/, 'wixmail' );

		if ( editor.ui.addButton ) {
			editor.ui.addButton( 'wixlink', {
				label: editor.lang.wixlink.toolbar,
				command: 'wixlink',
				toolbar: 'links,10'
			});
			editor.ui.addButton( 'wixmail', {
				label: editor.lang.wixlink.mail,
				command: 'wixmail',
				toolbar: 'links,20'
			});
			editor.ui.addButton( 'wixunlink', {
				label: editor.lang.wixlink.unlink,
				command: 'wixunlink',
				toolbar: 'links,30'
			});
		}

		CKEDITOR.dialog.add( 'wixlink', this.path + 'dialogs/wixlink.js' );
		
		CKEDITOR.dialog.add( 'wixmail', this.path + 'dialogs/wixmail.js' );

		editor.on( 'doubleclick', function( evt ) {
			var element = CKEDITOR.plugins.wixlink.getSelectedLink( editor ) || evt.data.element;

			if ( !element.isReadOnly() ) {
				if ( element.is( 'a' ) ) {
					if (element.hasAttribute( 'href' ) && element.getAttribute( 'href' ).indexOf('mailto:') === 0) {
						evt.data.dialog = 'wixmail';
						editor.getSelection().selectElement( element );
					}
					else {
						evt.data.dialog = 'wixlink';
						editor.getSelection().selectElement( element );
					}
				}
			}
		});

		// If the "menu" plugin is loaded, register the menu items.
		if ( editor.addMenuItems ) {
			editor.addMenuItems({
				wixlink: {
					label: editor.lang.wixlink.menu,
					command: 'wixlink',
					group: 'wixlink',
					order: 1
				},
				wixmail: {
					label: editor.lang.wixlink.mail,
					command: 'wixmail',
					group: 'wixlink',
					order: 3
				},
				wixunlink: {
					label: editor.lang.wixlink.unlink,
					command: 'wixunlink',
					group: 'wixlink',
					order: 5
				}
			});
		}

		// If the "contextmenu" plugin is loaded, register the listeners.
		if ( editor.contextMenu ) {
			editor.contextMenu.addListener( function( element, selection ) {
				if ( !element || element.isReadOnly() )
					return null;

				var anchor = CKEDITOR.plugins.wixlink.getSelectedLink( editor );

				var menu = {};

				if ( anchor.getAttribute( 'href' ) && anchor.getChildCount() )
					menu = { wixlink: CKEDITOR.TRISTATE_OFF, wixmail: CKEDITOR.TRISTATE_OFF, wixunlink: CKEDITOR.TRISTATE_OFF };

				return menu;
			});
		}
	}
});

/**
 * Set of wixlink plugin's helpers.
 *
 * @class
 * @singleton
 */
CKEDITOR.plugins.wixlink = {
	/**
	 * Get the surrounding wixlink element of current selection.
	 *
	 *		CKEDITOR.plugins.wixlink.getSelectedLink( editor );
	 *
	 *		// The following selection will all return the wixlink element.
	 *
	 *		<a href="#">li^nk</a>
	 *		<a href="#">[wixlink]</a>
	 *		text[<a href="#">wixlink]</a>
	 *		<a href="#">li[nk</a>]
	 *		[<b><a href="#">li]nk</a></b>]
	 *		[<a href="#"><b>li]nk</b></a>
	 *
	 * @since 3.2.1
	 * @param {CKEDITOR.editor} editor
	 */
	getSelectedLink: function( editor ) {
		var selection = editor.getSelection();
		var selectedElement = selection.getSelectedElement();
		if ( selectedElement && selectedElement.is( 'a' ) )
			return selectedElement;

		var range = selection.getRanges( true )[ 0 ];

		if ( range ) {
			range.shrink( CKEDITOR.SHRINK_TEXT );
			return editor.elementPath( range.getCommonAncestor() ).contains( 'a', 1 );
		}
		return null;
	}
};

// TODO Much probably there's no need to expose these as public objects.

CKEDITOR.wixunlinkCommand = function() {};
CKEDITOR.wixunlinkCommand.prototype = {
	exec: function( editor ) {
		var style = new CKEDITOR.style( { element:'a',type:CKEDITOR.STYLE_INLINE,alwaysRemoveElement:1 } );
		editor.removeStyle( style );
	},

	refresh: function( editor, path ) {
		// Despite our initial hope, document.queryCommandEnabled() does not work
		// for this in Firefox. So we must detect the state by element paths.

		var element = path.lastElement && path.lastElement.getAscendant( 'a', true );

		if ( element && element.getName() == 'a' && element.getAttribute( 'href' ) && element.getChildCount() )
			this.setState( CKEDITOR.TRISTATE_OFF );
		else
			this.setState( CKEDITOR.TRISTATE_DISABLED );
	},

	contextSensitive: 1,
	startDisabled: 1,
	requiredContent: 'a[href]'
};
