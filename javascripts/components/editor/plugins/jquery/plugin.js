CKEDITOR.plugins.add( 'jquery', {
	init: function( editor ) {
		editor.on('contentDom', function(ev) {
			var editor = ev.editor, window = editor.document.getWindow().$;
			
			editor.jQueryDocument = editor.document.$;
			editor.jQuery = editor.$ = function( selector, context ) {
				// The jQuery object is actually just the init constructor 'enhanced'
				return new jQuery.fn.init( selector, context, jQuery( editor.jQueryDocument ) );
			};
			
			for (m in jQuery) {
				editor.jQuery[m] = jQuery[m];
			}
		});
	}
});
