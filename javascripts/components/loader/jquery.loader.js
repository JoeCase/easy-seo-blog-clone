;(function( $ ){
	var methods = {
		show : function( options ) {
			options = methods._findLoaderWrap(this, options);
			var $loaderElem = options.loaderParentElement;
			
			// show loader
			$loaderElem.addClass(options.visibleClass);
		    	
			// add overlay into loader wrapper
			var $overlay = $('<div class="' + options.overlayClass + '"></div>');
			var $loaderImg = $('<div class="' + options.loaderClass + '"></div>').appendTo($overlay);
			$loaderElem.append($overlay);
		},
		hide : function( options ) {
			options = methods._findLoaderWrap(this, options);
			var $loaderElem = options.loaderParentElement;
			$loaderElem.children('.'+options.overlayClass).remove();
			$loaderElem.removeClass(options.visibleClass);
		},
		_findLoaderWrap : function( $this, options ) { 
			var loaderClass = (options && options.hasOwnProperty('loader')) ? options.loader : 'loader';
			var options = $.extend({
				'loaderClass'  : loaderClass,
				'visibleClass' : loaderClass + '-visible',
				'wrapperClass' : loaderClass + '-wrap',
				'overlayClass' : loaderClass + '-overlay',
			}, options);
		    	
			var $loaderElem = $this;
			if (!$this.is('.'+options.wrapperClass)) {
				$loaderElem = $this.closest('.'+options.wrapperClass + ', body');
    		}
			options.loaderParentElement = $loaderElem;
			return options
		}
	};

	$.fn.loader = function(method) {

		// Method calling logic
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.show.apply(this, arguments);
		} else {
			$.error('Method ' + method + ' does not exist on jQuery.loader');
		}
	};
})( jQuery );
