/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.dialog.add( 'wixmail', function( editor ) {
	var plugin = CKEDITOR.plugins.wixlink;
	
	// Loads the parameters in a selected wixlink to the wixlink dialog fields.
	var javascriptProtocolRegex = /^javascript:/,
		emailAddressRegex = /^\s*([^@\s]+@[^\s\.]+\.[^\s]+)\s*/,
		emailRegex = /^mailto:([^?]+)(?:\?(.+))?$/,
		emailSubjectRegex = /subject=([^;?:@&=$,\/]*)/,
		emailBodyRegex = /body=([^;?:@&=$,\/]*)/,
		encodedEmailLinkRegex = /^javascript:void\(location\.href='mailto:'\+String\.fromCharCode\(([^)]+)\)(?:\+'(.*)')?\)$/,
		functionCallProtectedEmailLinkRegex = /^javascript:([^(]+)\(([^)]+)\)$/;

	var parseLink = function( editor, element ) {
			var href = ( element && ( element.data( 'cke-saved-href' ) || element.getAttribute( 'href' ) ) ) || '',
				range = ( editor && editor.getSelection() && editor.getSelection().getRanges(1)[0] ) || null,
				javascriptMatch, emailMatch, urlMatch,
				retval = {};

			if ( ( javascriptMatch = href.match( javascriptProtocolRegex ) ) ) {
				if ( emailProtection == 'encode' ) {
					href = href.replace( encodedEmailLinkRegex, function( match, protectedAddress, rest ) {
						return 'mailto:' +
							String.fromCharCode.apply( String, protectedAddress.split( ',' ) ) +
							( rest && unescapeSingleQuote( rest ) );
					});
				}
				// Protected email wixlink as function call.
				else if ( emailProtection ) {
					href.replace( functionCallProtectedEmailLinkRegex, function( match, funcName, funcArgs ) {
						if ( funcName == compiledProtectionFunction.name ) {
							retval.type = 'email';
							var email = retval.email = {};

							var paramRegex = /[^,\s]+/g,
								paramQuoteRegex = /(^')|('$)/g,
								paramsMatch = funcArgs.match( paramRegex ),
								paramsMatchLength = paramsMatch.length,
								paramName, paramVal;

							for ( var i = 0; i < paramsMatchLength; i++ ) {
								paramVal = decodeURIComponent( unescapeSingleQuote( paramsMatch[ i ].replace( paramQuoteRegex, '' ) ) );
								paramName = compiledProtectionFunction.params[ i ].toLowerCase();
								email[ paramName ] = paramVal;
							}
							email.address = [ email.name, email.domain ].join( '@' );
						}
					});
				}
			}

			if ( !retval.type ) {
				// Protected email link as encoded string.
				if ( ( emailMatch = href.match( emailRegex ) ) ) {
					var subjectMatch = href.match( emailSubjectRegex ),
						bodyMatch = href.match( emailBodyRegex );

					retval.type = 'email';
					var email = ( retval.email = {} );
					email.address = emailMatch[ 1 ];
					subjectMatch && ( email.subject = decodeURIComponent( subjectMatch[ 1 ] ) );
					bodyMatch && ( email.body = decodeURIComponent( bodyMatch[ 1 ] ) );
				}
				// urlRegex matches empty strings, so need to check for href as well.
				else {
					retval.type = 'email';
					retval.email = {};
				}
			}
			
			if ( !retval.email.address && range && !range.collapsed ) {
				// check selected text
				var selectedText = editor.getSelection().getSelectedText();
				var mailParsed = selectedText.match( emailAddressRegex ) || false;
				if ( mailParsed ) {
					retval.email.address = mailParsed[0];
				}
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

	var emailProtection = editor.config.emailProtection || '';

	// Compile the protection function pattern.
	if ( emailProtection && emailProtection != 'encode' ) {
		var compiledProtectionFunction = {};

		emailProtection.replace( /^([^(]+)\(([^)]+)\)$/, function( match, funcName, params ) {
			compiledProtectionFunction.name = funcName;
			compiledProtectionFunction.params = [];
			params.replace( /[^,\s]+/g, function( param ) {
				compiledProtectionFunction.params.push( param );
			});
		});
	}

	function protectEmailLinkAsFunction( email ) {
		var retval,
			name = compiledProtectionFunction.name,
			params = compiledProtectionFunction.params,
			paramName, paramValue;

		retval = [ name, '(' ];
		for ( var i = 0; i < params.length; i++ ) {
			paramName = params[ i ].toLowerCase();
			paramValue = email[ paramName ];

			i > 0 && retval.push( ',' );
			retval.push( '\'', paramValue ? escapeSingleQuote( encodeURIComponent( email[ paramName ] ) ) : '', '\'' );
		}
		retval.push( ')' );
		return retval.join( '' );
	}

	function protectEmailAddressAsEncodedString( address ) {
		var charCode,
			length = address.length,
			encodedChars = [];
		for ( var i = 0; i < length; i++ ) {
			charCode = address.charCodeAt( i );
			encodedChars.push( charCode );
		}
		return 'String.fromCharCode(' + encodedChars.join( ',' ) + ')';
	}

	function getLinkClass( ele ) {
		return ele.getAttribute( 'class' );
	}

	var commonLang = editor.lang.common,
		linkLang = editor.lang.wixlink;

	return {
		title: linkLang.mailtitle,
		minWidth: 350,
		minHeight: 230,
		contents: [
			{
			id: 'info',
			label: linkLang.mainlinfo,
			title: linkLang.mainlinfo,
			elements: [
				{
				type: 'vbox',
				id: 'emailOptions',
				padding: 1,
				children: [
					{
					type: 'text',
					id: 'emailAddress',
					label: linkLang.emailAddress,
					required: true,
					validate: function() {
						var func = CKEDITOR.dialog.validate.notEmpty( linkLang.noEmail );
						return func.apply( this );
					},
					setup: function( data ) {
						if ( data.email )
							this.setValue( data.email.address );

						this.select();
					},
					commit: function( data ) {
						if ( !data.email )
							data.email = {};

						data.email.address = this.getValue();
					}
				},
					{
					type: 'text',
					id: 'emailSubject',
					label: linkLang.emailSubject,
					setup: function( data ) {
						if ( data.email )
							this.setValue( data.email.subject );
					},
					commit: function( data ) {
						if ( !data.email )
							data.email = {};

						data.email.subject = this.getValue();
					}
				},
					{
					type: 'textarea',
					id: 'emailBody',
					label: linkLang.emailBody,
					rows: 3,
					'default': '',
					setup: function( data ) {
						if ( data.email )
							this.setValue( data.email.body );
					},
					commit: function( data ) {
						if ( !data.email )
							data.email = {};

						data.email.body = this.getValue();
					}
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
			var linkHref,
				email = data.email,
				address = email.address;

			switch ( emailProtection ) {
				case '':
				case 'encode':
					{
						var subject = encodeURIComponent( email.subject || '' ),
							body = encodeURIComponent( email.body || '' );

						// Build the e-mail parameters first.
						var argList = [];
						subject && argList.push( 'subject=' + subject );
						body && argList.push( 'body=' + body );
						argList = argList.length ? '?' + argList.join( '&' ) : '';

						if ( emailProtection == 'encode' ) {
							linkHref = [ 'javascript:void(location.href=\'mailto:\'+', protectEmailAddressAsEncodedString( address ) ];
							// parameters are optional.
							argList && linkHref.push( '+\'', escapeSingleQuote( argList ), '\'' );

							linkHref.push( ')' );
						} else
							linkHref = [ 'mailto:', address, argList ];

						break;
					}
				default:
					{
						// Separating name and domain.
						var nameAndDomain = address.split( '@', 2 );
						email.name = nameAndDomain[ 0 ];
						email.domain = nameAndDomain[ 1 ];

						linkHref = [ 'javascript:', protectEmailLinkAsFunction( email ) ];
					}
			}

			attributes[ 'data-cke-saved-href' ] = linkHref.join( '' );

			var selection = editor.getSelection();

			// Browser need the "href" fro copy/paste wixlink to work. (#6641)
			attributes.href = attributes[ 'data-cke-saved-href' ];

			if ( !this._.selectedElement ) {
				var range = selection.getRanges( 1 )[ 0 ],
					selectedText = selection.getSelectedText();

				// Use email adress as text with a collapsed cursor OR if email address was taken from selection -> replace it by current
				if ( range.collapsed || emailAddressRegex.test( selectedText )) {
					// Short mailto wixlink text view (#5736).
					var text = new CKEDITOR.dom.text( data.email.address, editor.document );
					range.deleteContents();
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
					href = element.data( 'cke-saved-href' ),
					textView = element.getHtml();

				element.setAttributes( attributes );
				element.removeAttributes( removeAttributes );

				// Update text view when user changes protocol (#4612).
				if ( emailAddressRegex.test( textView ) ) {
					// Short mailto wixlink text view (#5736).
					element.setHtml(data.email.address);
				}

				selection.selectElement( element );
				delete this._.selectedElement;
			}
		},
		// Inital focus on 'url' field if wixlink is of type URL.
		onFocus: function() {
			this.getContentElement( 'info', 'emailAddress' ).select();
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
