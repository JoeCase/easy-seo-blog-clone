/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.dialog.add( 'wixlink', function( editor ) {
	var plugin = CKEDITOR.plugins.wixlink;

	// Loads the parameters in a selected wixlink to the wixlink dialog fields.
	var urlRegex = /^\s*((?:(?:http|https|ftp|news):)?\/\/|mailto:)?([^\s]+\.[^\s]+)\s*$/,
		selectableTargets = /^(_(?:self|top|parent|blank))$/;

	var parseLink = function( editor, element ) {
			var href = ( element && ( element.data( 'cke-saved-href' ) || element.getAttribute( 'href' ) ) ) || '',
				retval = {
					text: {
						enabled: true,
						text: ''
					}
				},
				range = ( editor && editor.getSelection() && editor.getSelection().getRanges(1)[0] ) || null;

			// urlRegex matches empty strings, so need to check for href as well.
			if ( href ) {
				retval.type = 'url';
				retval.url = {};
				retval.url.url = href;
			} else {
				retval.type = 'url';
				retval.url = {};
				retval.url.url = '';
			}
			
			// check selected text
			if (range && !range.collapsed) {
				var selectedText = editor.getSelection().getSelectedText();
				// if selection starts and ends in same container -> allow plain text editing
				retval.text.enabled = ( range.startContainer.$ === range.endContainer.$ );
				retval.text.text = selectedText;
				
				var urlParsed = selectedText.match(urlRegex) || false;
				if ( !retval.url.url && urlParsed ) {
					retval.url.url = (!urlParsed[1] ? 'http://' : urlParsed[1]) + urlParsed[2];
				}
			}

			// Load target and popup settings.
			if ( element && element.hasAttribute( 'target' )) {
				var target = element.getAttribute( 'target' );
				retval.target = {};

				if ( target && target.match( selectableTargets ) )
					retval.target.type = target;
			}
			
			// Record down the selected element in the dialog.
			this._.selectedElement = element;
			return retval;
		};

	var setupParams = function( page, data ) {
			if ( data[ page ] )
				this.setValue( data[ page ][ this.id ] || '' );
		};

	var commitParams = function( page, data ) {
			if ( !data[ page ] )
				data[ page ] = {};

			data[ page ][ this.id ] = this.getValue() || '';
		};

	function unescapeSingleQuote( str ) {
		return str.replace( /\\'/g, '\'' );
	}

	function escapeSingleQuote( str ) {
		return str.replace( /'/g, '\\$&' );
	}

	function getLinkClass( ele ) {
		return ele.getAttribute( 'class' );
	}

	var commonLang = editor.lang.common,
		linkLang = editor.lang.wixlink;

	return {
		title: linkLang.title,
		minWidth: 350,
		minHeight: 230,
		contents: [
			{
			id: 'info',
			label: linkLang.info,
			title: linkLang.info,
			elements: [
				{
				type: 'vbox',
				id: 'urlOptions',
				children: [
				    {
					type: 'hbox',
					widths: [ '100%' ],
					children: [
						{
						type: 'text',
						id: 'text',
						label: linkLang.text,
						setup: function( data ) {
							if ( data.text ) {
								this.setValue( data.text.text );
								if ( data.text.enabled ) {
									this.enable();
								}
								else {
									this.disable();
								}
							}
							
						},
						commit: function( data ) {
							data.text = {
								text: this.getValue(),
								enabled: this.isEnabled()
							};
						}
					}
					]
				},
					{
					type: 'hbox',
					widths: [ '100%' ],
					children: [
						{
						type: 'text',
						id: 'url',
						label: commonLang.url,
						required: true,
						onLoad: function() {
							this.allowOnChange = true;
						},
						validate: function() {
							if ( (/javascript\:/).test( this.getValue() ) || !urlRegex.test( this.getValue() ) ) {
								alert( commonLang.invalidValue );
								return false;
							}

							var func = CKEDITOR.dialog.validate.notEmpty( linkLang.noUrl );
							return func.apply( this );
						},
						setup: function( data ) {
							if ( data.url )
								this.setValue( data.url.url );
						},
						commit: function( data ) {
							if ( !data.url )
								data.url = {};

							data.url.url = this.getValue();
						}
					}
					]
				},
					{
					type: 'hbox',
					widths: [ '100%' ],
					children: [
						{
						type: 'select',
						id: 'linkTargetType',
						label: commonLang.target,
						'default': '_blank',
						style: 'width : 100%;',
						'items': [
							[ commonLang.targetNew, '_blank' ],
							[ commonLang.targetTop, '_top' ]
						],
						setup: function( data ) {
							if ( data.target )
								this.setValue( data.target.type || '_blank' );
						},
						commit: function( data ) {
							if ( !data.target )
								data.target = {};

							data.target.type = this.getValue();
						}
					}
					]
				},
					{
					type: 'button',
					id: 'browse',
					hidden: 'true',
					filebrowser: 'info:url',
					label: commonLang.browseServer
				}
				]
			}
			]
		}
		],
		onShow: function() {
			var editor = this.getParentEditor(),
				selection = editor.getSelection(),
				element = null;

			// Fill in all the relevant fields if there's already one wixlink selected.
			if ( ( element = plugin.getSelectedLink( editor ) ) && element.hasAttribute( 'href' ) )
				selection.selectElement( element );
			else
				element = null;

			this.setupContent( parseLink.apply( this, [ editor, element ] ) );
		},
		onOk: function() {
			var attributes = {},
				removeAttributes = [],
				data = {},
				me = this,
				editor = this.getParentEditor();

			this.commitContent( data );

			// Compose the URL.
			var urlParsed = ( data.url && data.url.url.match( urlRegex ) ) || false;
				protocol = ( urlParsed && urlParsed[1] ) || 'http://',
				url = ( urlParsed && CKEDITOR.tools.trim( urlParsed[2] ) ) || '';
			attributes[ 'data-cke-saved-href' ] = ( urlParsed && protocol + url ) || '';

			// Popups and target.
			if ( data.target && data.target.type)
				attributes.target = data.target.type;
			else
				removeAttributes.push( 'target' );
			
			var selection = editor.getSelection();

			// Browser need the "href" fro copy/paste wixlink to work. (#6641)
			attributes.href = attributes[ 'data-cke-saved-href' ];

			if ( !this._.selectedElement ) {
				var range = selection.getRanges( 1 )[ 0 ];
				
				if ( data.text && data.text.enabled && data.text.text ) {
					var text = new CKEDITOR.dom.text( data.text.text, editor.document );
					range.deleteContents();
					range.insertNode( text );
					range.selectNodeContents( text );
				}

				// Use link URL as text with a collapsed cursor.
				if ( range.collapsed && (!data.text || !data.text.enabled || !data.text.text ) ) {
					// Short mailto wixlink text view (#5736).
					var text = new CKEDITOR.dom.text( attributes[ 'data-cke-saved-href' ], editor.document );
					range.insertNode( text );
					range.selectNodeContents( text );
				}

				// Apply style.
				var style = new CKEDITOR.style({ element: 'a', attributes: attributes } );
				style.type = CKEDITOR.STYLE_INLINE; // need to override... dunno why.
				style.applyToRange( range );
				range.select();
			} else {
				// We're only editing an existing wixlink, so just overwrite the attributes.
				var element = this._.selectedElement,
					href = element.data( 'cke-saved-href' );

				element.setAttributes( attributes );
				element.removeAttributes( removeAttributes );

				// Update text view when user changes protocol (#4612).
				if ( href == data.text.text || !data.text.text || (data.text.enabled && data.text.text) ) {
					// Short mailto wixlink text view (#5736).
					element.setHtml( href != data.text.text && data.text.enabled && data.text.text ? data.text.text : attributes[ 'data-cke-saved-href' ] );
				}

				selection.selectElement( element );
				delete this._.selectedElement;
			}
		},
		// Inital focus on 'url' field if wixlink is of type URL.
		onFocus: function() {
			this.getContentElement( 'info', 'url' ).select();
		}
	};
});

/**
 * The e-mail address anti-spam protection option. The protection will be
 * applied when creating or modifying e-mail links through the editor interface.
 *
 * Two methods of protection can be choosed:
 *
 * 1. The e-mail parts (name, domain and any other query string) are
 *     assembled into a function call pattern. Such function must be
 *     provided by the developer in the pages that will use the contents.
 * 2. Only the e-mail address is obfuscated into a special string that
 *     has no meaning for humans or spam bots, but which is properly
 *     rendered and accepted by the browser.
 *
 * Both approaches require JavaScript to be enabled.
 *
 *		// href="mailto:tester@ckeditor.com?subject=subject&body=body"
 *		config.emailProtection = '';
 *
 *		// href="<a href=\"javascript:void(location.href=\'mailto:\'+String.fromCharCode(116,101,115,116,101,114,64,99,107,101,100,105,116,111,114,46,99,111,109)+\'?subject=subject&body=body\')\">e-mail</a>"
 *		config.emailProtection = 'encode';
 *
 *		// href="javascript:mt('tester','ckeditor.com','subject','body')"
 *		config.emailProtection = 'mt(NAME,DOMAIN,SUBJECT,BODY)';
 *
 * @since 3.1
 * @cfg {String} [emailProtection='' (empty string = disabled)]
 * @member CKEDITOR.config
 */
