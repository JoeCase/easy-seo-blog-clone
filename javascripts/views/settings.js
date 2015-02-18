 /**
 * Init the settings class and construct it
 * @constructor
 */
function Settings() {
    // Set the properties
    this.baseUrl = window.document.url;
}

Settings.prototype = {

    /**
     * Allows initialization
     */
    init: function() {
    	// init layouts
        var layout = $('form input:radio').filter(':checked').parents('.option');
        layout.addClass('checked').siblings().removeClass('checked');
        layout.find('.layout_slider').slider('enable');
        layout.siblings().find('.layout_slider').slider('disable');
    	
    	// add to all submit input/buttons attribute clicked
    	$('body').delegate("form *[type=submit]", 'click', function() {
    	    $("*[type=submit]", $(this).parents("form")).removeAttr("clicked");
    	    $(this).attr("clicked", "true");
    	});
    	
        return this;
    },

    /**
     * The current base URL
     * @type String
     */
    baseUrl: null,

    /**
     * Method for submitting the settings form via AJAX
     */
    submitForm: function($form, successCallback) {
    	if (typeof($form) == 'undefined') {
    		$form = $('#options_form');
    	}
    	$form.loader('show');
        
    	
    	var $btn = $form.find("*[type=submit][clicked=true]");
        var data = $form.serialize() + '&' + $btn.attr('name') + '=' + $btn.val() + '&ajax=true';

        // include disabled fields
        $form.find('input[disabled]').each(function() {
            data = data + '&' + $(this).attr('name') + '=' + $(this).val();
        });
        
        this.ajaxSubmit(data, 'POST', $form.attr('action'), function(data) {
        	$form.loader('hide');
        	if (typeof(successCallback) == 'function') {
        		successCallback(data);
        	}
        	$form.trigger('saved');
        	Wix.Settings.refreshApp();
        });
        
        return false;
    },

    /**
     * Submits the given data using AJAX
     * @param data - Serialized object of the form data
     * @param method - String contains the method type
     * @param url - URL for the ajax call
     */
    ajaxSubmit: function(data, method, url, successCallback) {
        $.ajax({
            url: url,
            type: method,
            dataType: 'json',
            data: data,
            success: successCallback
        });
        
        return this;
    },

    /**
     * Configures a list of elements as ColorPicker elements
     * @param elements
     * @param options
     */
    configureColorPicker: function(elements, options) {
        var elements = $(elements);
        $(elements).each(function() {
        	var $prepend = $('<span class="color-selector-wrap"><a rel="popover" class="color-selector default"></a></span>');
        	$(this).before($prepend).addClass('hidden');
        	var tmp_options = $.extend( { startWithColor: $(this).val() }, options );
        	$prepend.children().ColorPicker(tmp_options).on('colorChanged', function(event, data) {
        		$(this).parent().next().val(data.selected_color).change();
        	});
        });
        
        return this;
    },
    
    /**
     * Disable ColorPicker functionaity fot a list of elements
     * @param elements
     * @param options
     */
    disableColorPicker: function(elements, options) {
        var elements = $(elements);
        $(elements).each(function() {
        	$(this).prev().find('.color-selector').ColorPicker('enabled', false);
        });
        
        return this;
    },

    /**
     * Attach to provide hide/show functionality. Reveals the element clicked on and hides the rest.
     */
    switchBoxes: function() {
        $(this).addClass('opened').children('.style_settings_list').slideDown('slow');
        $(this).siblings().removeClass('opened').children('.style_settings_list').slideUp('slow');
        
        return this;
    },

    /**
     * Configures a jQuery UI slider element
     * @param container
     * @param input
     * @param options
     */
    configureSlider: function(container, input, options) {
        // if no options supplied, provide default ones
        if (options == null || options == undefined) {
            options = {
                range: "min",
                min: 1,
                max: 6
            }
        }

        // make sure the input updates accordingly
        options.value = input.val();
        options.slide = function(event, ui) {
            $(input).val(ui.value);
        }

        // submit the form when needed
        options.stop = function() {
            $(input).parents('form').submit();
        }
        
        $(input).attr('disabled', 'disabled');

        // turn provided elements into jQuery UI elements
        $(container).width(80).slider(options);
        
        return this;
    },

    /**
     * Configures a jQuery UI slider element specifically designed for opacity elements
     * @param container
     * @param input
     * @param options
     */
    configureOpacitySlider: function(container, input, options) {
        if (options == null || options == undefined) {
            options = {
                range: "min"
            }
        }

        options.min = 0;
        options.max = 1.0;
        options.step = 0.1;

        return this.configureSlider(container, input, options);
    },    

    /**
     * binds a checkbox with a slider so the slider will be disabled when the checkbox is not checked
     * @param checkbox
     * @param slider
     */
    bindCheckboxWithSlider: function(checkbox, slider) {
        checkbox = $(checkbox);
        slider = $(slider);

        // bind a listener
        $(checkbox).on('change', function() {
            check(checkbox, slider);
        });

        // make the first call
        check(checkbox, slider);

        /**
         * Sub-function to reduce duplication
         * @param checkbox
         * @param slider
         */
        function check(checkbox, slider) {
            // weather or not the checkbox is checked
            var checked = checkbox.is(':checked');

            // enable or disable the slider depending on the checkbox
            if (checked === true) {
                slider.slider('enable');
            } else {
                slider.slider('disable');
            }
        }
    },

    /**
     * handles switching between layouts
     * @return {*}
     */
    switchLayouts: function() {
        var layout = $(this).parents('.option');
        layout.addClass('checked').siblings().removeClass('checked');
        layout.find('.layout_slider').slider('enable');
        layout.siblings().find('.layout_slider').slider('disable');
        return this;
    }
}
