// Global variables
post_text_init = false;
post_image_init = false;
post_video_init = false;
post_link_init = false;

last_image_url = '';
last_video_url = '';


$(document).ready(function() {
    // refresh the app, move it to user redirect
    Wix.Settings.refreshApp();

	expandPostText();
	$('.accordion').accordion({
        header: ".box > h3",
        heightStyle: "content",
        activate: function( event, ui ) {
        	$('.post-area').mCustomScrollbar('update');
        	$('.recent-posts').mCustomScrollbar('update');
        }
    });
	
	$('.page').mCustomScrollbar({
		scrollInertia:0,
		mouseWheelPixels:50,
		advanced: {
		    updateOnContentResize: true,
		    autoScrollOnFocus: false
		}
	});
	
	
    // ---------------------
    // initialize components
    // ---------------------
	
	var settings = new Settings();
    
    // activate color picker elements
    settings.configureColorPicker('#Settings_feedBgColor, #Settings_postsBgColor, #Settings_borderColor, #Settings_textColor, #Settings_linksColor, #Settings_titleColor, #Settings_tagsFontColor, #Settings_dateFontColor, #Settings_paginationFontColor, #Settings_btfFontColor, #Settings_dbtFontColor, #Settings_sidebarDividerColor, #Settings_sidebarTitleFontColor, #Settings_sidebarRecentPostsFontColor, #Settings_sidebarTagsFontColor, #Settings_readMoreFontColor');

    // activate slider elements
    settings.configureSlider($('#gridColumns_slider'), $('#Settings_gridColumns'), {range: "min",min: 1,max: 4});
    settings.configureSlider($('#gridRows_slider'), $('#Settings_gridRows'), {range: "min", min: 1, max: 20});
    settings.configureSlider($('#pinterestColumns_slider'), $('#Settings_pinterestColumns'), {range: "min",min: 1,max: 4});
    settings.configureSlider($('#pinterestRows_slider'), $('#Settings_pinterestRows'), {range: "min", min: 1, max: 20});
    settings.configureSlider($('#lastPostsCount_slider'), $('#Settings_lastPostsCount'), {range: "min", min: 1, max: 20});
    settings.configureSlider($('#borderThickness_slider'), $('#Settings_borderThickness'), {range: "min", min: 0, max: 5});
    settings.configureSlider($('#textFontSize_slider'), $('#Settings_textFontSize'), {range: "min", min: 8, max: 30});
    settings.configureSlider($('#linksFontSize_slider'), $('#Settings_linksFontSize'), {range: "min", min: 8, max: 30});
    settings.configureSlider($('#titleFontSize_slider'), $('#Settings_titleFontSize'), {range: "min", min: 8, max: 50});
    settings.configureSlider($('#tagsFontSize_slider'), $('#Settings_tagsFontSize'), {range: "min", min: 8, max: 30});
    settings.configureSlider($('#dateFontSize_slider'), $('#Settings_dateFontSize'), {range: "min", min: 8, max: 30});
    settings.configureSlider($('#paginationFontSize_slider'), $('#Settings_paginationFontSize'), {range: "min", min: 8, max: 30});
    settings.configureSlider($('#btfFontSize_slider'), $('#Settings_btfFontSize'), {range: "min", min: 8, max: 30});
    settings.configureSlider($('#dbtFontSize_slider'), $('#Settings_dbtFontSize'), {range: "min", min: 8, max: 30});
    settings.configureSlider($('#textSingleFontSize_slider'), $('#Settings_textSingleFontSize'), {range: "min", min: 8, max: 30});
    settings.configureSlider($('#linksSingleFontSize_slider'), $('#Settings_linksSingleFontSize'), {range: "min", min: 8, max: 30});
    settings.configureSlider($('#titleSingleFontSize_slider'), $('#Settings_titleSingleFontSize'), {range: "min", min: 8, max: 50});
    settings.configureSlider($('#tagsSingleFontSize_slider'), $('#Settings_tagsSingleFontSize'), {range: "min", min: 8, max: 30});
    settings.configureSlider($('#dateSingleFontSize_slider'), $('#Settings_dateSingleFontSize'), {range: "min", min: 8, max: 30});
    settings.configureSlider($('#paginationSingleFontSize_slider'), $('#Settings_paginationSingleFontSize'), {range: "min", min: 8, max: 30});
    settings.configureSlider($('#btfSingleFontSize_slider'), $('#Settings_btfSingleFontSize'), {range: "min", min: 8, max: 30});
    settings.configureSlider($('#paginationNumbersCount_slider'), $('#Settings_paginationNumbersCount'), {range: "min", min: 3, max: 15, step: 2});
    settings.configureSlider($('#sidebarDividerThicknes_slider'), $('#Settings_sidebarDividerThicknes'), {range: "min", min: 1, max: 10});
    settings.configureSlider($('#recentPostsCount_slider'), $('#Settings_recentPostsCount'), {range: "min", min: 1, max: 10});
    settings.configureSlider($('#sidebarTitleFontSize_slider'), $('#Settings_sidebarTitleFontSize'), {range: "min", min: 8, max: 30});
    settings.configureSlider($('#sidebarRecentPostsFontSize_slider'), $('#Settings_sidebarRecentPostsFontSize'), {range: "min", min: 8, max: 20});
    settings.configureSlider($('#sidebarTagsFontSize_slider'), $('#Settings_sidebarTagsFontSize'), {range: "min", min: 8, max: 20});
    settings.configureSlider($('#readMoreFontSize_slider'), $('#Settings_readMoreFontSize'), {range: "min", min: 8, max: 20});
    settings.configureSlider($('#mobilePostsCount_slider'), $('#Settings_mobilePostsCount'), {range: "min", min: 1, max: 7, step: 1});
    
    // opacity sliders
    settings.configureOpacitySlider($('#feedBgOpacityValue_slider'), $('#Settings_feedBgOpacityValue'));
    settings.configureOpacitySlider($('#postsBgOpacityValue_slider'), $('#Settings_postsBgOpacityValue'));
    settings.configureOpacitySlider($('#sidebarDividerColorOpacityValue_slider'), $('#Settings_sidebarDividerColorOpacityValue'));

    // bind checkboxes with sliders so the sliders will be disabled when the checkboxes are not checked
    settings.bindCheckboxWithSlider($('#Settings_feedBgOpacity'), $('#feedBgOpacityValue_slider'));
    settings.bindCheckboxWithSlider($('#Settings_postsBgOpacity'), $('#postsBgOpacityValue_slider'));
    settings.bindCheckboxWithSlider($('#Settings_paginationShowNumbers'), $('#paginationNumbersCount_slider'));
    settings.bindCheckboxWithSlider($('#Settings_sidebarDividerColorOpacity'), $('#sidebarDividerColorOpacityValue_slider'));
    
    // ---------------------
    // initialize actions
    // ---------------------

    // bind to form submission event
    $('#options_form').on('submit', function() {
        settings.submitForm();
        return false;
    });

    // submit the settings form on every input change
    $('#options_form').delegate('input,select,textarea', 'change', function() {
        $('#options_form').submit();
    });

    // takes care of enabling and disabling layout fields, depending on the currently selected layout
    $('form input:radio').on('click', settings.switchLayouts);
    
    initializeManagePosts($('.recent-posts'));

	// -----------------------
	// Text posting
	// -----------------------
	
	$('.js-post-text-action').click(function(event) {
		event.preventDefault();
		collapseAll('.post-text', expandPostText);
		$(this).closest('p').find('*').removeClass('active');
		$(this).addClass('active');
	});

	// -----------------------
	// Image posting
	// -----------------------
	
	$('.js-post-image-action').click(function(event) {
		event.preventDefault();
		collapseAll('.post-image', expandPostImage);
		$(this).closest('p').find('*').removeClass('active');
		$(this).addClass('active');
	});

	// -----------------------
	// Video posting
	// -----------------------
	
	$('.js-post-video-action').click(function(event) {
		event.preventDefault();
		collapseAll('.post-video', expandPostVideo);
		$(this).closest('p').find('*').removeClass('active');
		$(this).addClass('active');
	});

	// -----------------------
	// Link posting
	// -----------------------
	
	$('.js-post-link-action').click(function(event) {
		event.preventDefault();
		collapseAll('.post-link', expandPostLink);
		$(this).closest('p').find('*').removeClass('active');
		$(this).addClass('active');
	});

	// Tiles interaction
	
	$('.tile').hover(function(){
		$(this).find('.foreground').stop().animate({'margin-top': '-80%'}, 'fast');
	}, function() {
		$(this).find('.foreground').stop().animate({'margin-top': '10%'}, 'fast');
	});
	
	// Initialize settings
	settings.init();
});

// ===================== //
// Common blog functions //
// ===================== //

function invokeCallback(callback) {
	if (typeof(callback) != 'undefined' && callback) {
		callback();
	}
}
	
function collapseAll(exclude, callback) {
	$('.post-area form').parent().not('.hidden').not(exclude).addClass('hidden');
	$('.iconbutton').removeClass('active');
	invokeCallback(callback);
}

function clearForm($form) {
	$form.find('*[name=id]').val('').change();
	$form.find('*[name=title]').val('').change();
	$form.find('*[name=url]').val('').change();
	CKEDITOR.instances[$form.find('*[name=content]').attr('id')].setData( '' );
	$form.find('*[name=tags]').importTags('');
}

//==================== //
//Post text functions  //
//==================== //

function initliazePostTextForm($form, cancelCallbackFn) {
	// initialize JavaScript components
	// editor
	var $textarea = $form.find('textarea');
	$textarea.attr('id', $textarea.attr('id') + Date.now());
	//CKEDITOR.replace($textarea.attr('id'));
	inicializeEditor($textarea.attr('id'));

	// tags
	var $tags = $form.find('input[name=tags]');
	$tags.tagsInput(tagsInputOptions);
	$tags.importTags($tags.val());

	// initialize Premium features
	if (typeof(initliazePostTextFormPremium) == 'function') {
		initliazePostTextFormPremium($form);
	}
	
	// initialize behaviour
	$form.find('.js-post-text-cancel').click(function() {
		cancelCallbackFn($form);
	});
	
	// recognize clicked submit buttons
	$form.find("*[type=submit]").click(function() {
		$(this).parents("form").find("*[type=submit]").removeAttr("clicked");
	    $(this).attr("clicked", "true");
	});
	
	// validations
	$form.validate({
		rules: {
			'title' : {required: true, maxlength: 255}
		}
	});
	
	initializeDatePicker($form);
}

function expandPostText() {
	if (!post_text_init) {
		initliazePostTextForm($('.post-text form'), cleanPostTextForm);
		post_text_init = true;
	}

	$('.post-text').removeClass('hidden');
	$('.iconbutton.text').addClass('active');
}

function cleanPostTextForm($form) {
	if (typeof($form) == 'undefined') {
		$form = $('.post-text form');
	}
	clearForm($form);
}

// ==================== //
// Post image functions //
// ==================== //

function initliazePostImageForm($form, cancelCallbackFn) {
    // initialize JavaScript components
	// editor
	var $textarea = $form.find('textarea');
	$textarea.attr('id', $textarea.attr('id') + Date.now());
	//CKEDITOR.replace($textarea.attr('id'));
	inicializeEditor($textarea.attr('id'));

	// tags
	var $tags = $form.find('input[name=tags]');
	$tags.tagsInput(tagsInputOptions);

	// initialize Premium features
	if (typeof(initliazePostImageFormPremium) == 'function') {
		initliazePostImageFormPremium($form);
	}
	
	// initialize behaviour
	$form.find('.js-image-gallery').click(function() {
		Wix.Settings.openMediaDialog(Wix.Settings.MediaType.IMAGE, false, function(data) {
			$form.find('input[name=url]').val(Wix.Utils.Media.getImageUrl(data.relativeUri)).change();
		});
	});
	
	$form.find('.image-source').load(function() {
		$(this).parent().removeClass('hidden');
	});

	$form.find('input[name=url]').on('change keyup paste', function() {
		var url = $(this).val();
		// set new URL and hide image if URL changed
		if (url != last_image_url) {
			$form.find('.image-source').attr('src', url).parent().addClass('hidden');
		}
		last_image_url = url;
	});
	if ($form.find('input[name=url]').val() != '') {
		last_image_url = '';
		$form.find('input[name=url]').change();
	}
	
	$form.find('.image-remove').click(function() {
		removeImage($form);
	});
	
	$form.find('.js-post-image-cancel').click(function() {
		cancelCallbackFn($form);
	});
		
	// recognize clicked submit buttons
	$form.find("*[type=submit]").click(function() {
		$(this).parents("form").find("*[type=submit]").removeAttr("clicked");
	    $(this).attr("clicked", "true");
	});
	
	// validations
	$form.validate({
		rules: {
			'title' : {required: true, maxlength: 255},
			'url' :  {maxlength: 2000},
		}
	});
	
	initializeDatePicker($form);
}

function expandPostImage() {
	if (!post_image_init) {
		initliazePostImageForm($('.post-image form'), cleanPostImageForm);
		post_image_init = true;
	}
	
	$('.post-image').removeClass('hidden');
	$('.iconbutton.image').addClass('active');
}

function cleanPostImageForm($form) {
	if (typeof($form) == 'undefined') {
		$form = $('.post-image form');
	}
	clearForm($form);
}

function removeImage($form) {
	if (typeof($form) == 'undefined') {
		$form = $('.post-image form');
	}
	$form.find('input[name=url]').val('').change();
}

// ==================== //
// Post video functions //
// ==================== //

function initliazePostVideoForm($form, cancelCallbackFn) {
	// initialize JavaScript components
	// editor
	var $textarea = $form.find('textarea');
	$textarea.attr('id', $textarea.attr('id') + Date.now());
	//CKEDITOR.replace($textarea.attr('id'));
	inicializeEditor($textarea.attr('id'));

	// tags
	var $tags = $form.find('input[name=tags]');
	$tags.tagsInput(tagsInputOptions);
	$tags.importTags($tags.val());

	// initialize Premium features
	if (typeof(initliazePostVideoFormPremium) == 'function') {
		initliazePostVideoFormPremium($form);
	}

	$form.find('input[name=url]').on('change keyup paste', function(event) {
		var url = $(this).val();
		if (url != last_video_url) {
			var embedded = getEmbeddedPlayer(url, 500, 300);
			if (embedded) {
				$form.find(".video-preview").html(embedded).removeClass('hidden');
			}
			else {
				$form.find(".video-preview").addClass('hidden').html('');
			}
		}
		last_video_url = url;
	});
	if ($form.find('input[name=url]').val() != '') {
		last_video_url = '';
		$form.find('input[name=url]').change();
	}

	$form.find('.js-post-video-cancel').click(function() {
		cancelCallbackFn($form);
	});

	// recognize clicked submit buttons
	$form.find("*[type=submit]").click(function() {
		$(this).parents("form").find("*[type=submit]").removeAttr("clicked");
	    $(this).attr("clicked", "true");
	});
	
	// validations
	$form.validate({
		rules: {
			'title' : {required: true, maxlength: 255},
			'url' :  {maxlength: 2000},
		}
	});
	
	initializeDatePicker($form);
}

function expandPostVideo() {
	if (!post_video_init) {
		initliazePostVideoForm($('.post-video form'), cleanPostVideoForm);
		post_video_init = true;
	}
	
	$('.post-video').removeClass('hidden');
	$('.iconbutton.video').addClass('active');
}

function cleanPostVideoForm($form) {
	if (typeof($form) == 'undefined') {
		$form = $('.post-video form');
	}
	clearForm($form);
}

// ==================== //
// Post link functions  //
// ==================== //

function initliazePostLinkForm($form, cancelCallbackFn) {
	// initialize JavaScript components
	// editor
	var $textarea = $form.find('textarea');
	$textarea.attr('id', $textarea.attr('id') + Date.now());
	//CKEDITOR.replace($textarea.attr('id'));
	inicializeEditor($textarea.attr('id'));

	// tags
	var $tags = $form.find('input[name=tags]');
	$tags.tagsInput(tagsInputOptions);
	$tags.importTags($tags.val());

	// initialize Premium features
	if (typeof(initliazePostLinkFormPremium) == 'function') {
		initliazePostLinkFormPremium($form);
	}
	
	// initialize behaviour
	$form.find('.js-post-link-cancel').click(function() {
		cancelCallbackFn($form);
	});

	// recognize clicked submit buttons
	$form.find("*[type=submit]").click(function() {
		$(this).parents("form").find("*[type=submit]").removeAttr("clicked");
	    $(this).attr("clicked", "true");
	});
	
	// validations
	$form.validate({
		rules: {
			'title' : {required: true, maxlength: 255},
			'url' :  {maxlength: 2000},
		}
	});
	
	initializeDatePicker($form);
}

function expandPostLink() {
	if (!post_link_init) {
		initliazePostLinkForm($('.post-link form'), cleanPostLinkForm);
		post_link_init = true;
	}
	
	$('.post-link').removeClass('hidden');
	$('.iconbutton.link').addClass('active');
}

function cleanPostLinkForm($form) {
	if (typeof($form) == 'undefined') {
		$form = $('.post-link form');
	}
	clearForm($form);
}

//==================== //
// Editing functions   //
//==================== //

function initializeManagePosts($posts) {
	$posts.find('.write-a-post').click(function(event) {
		event.preventDefault();
		$('.post-area').prev().click();
		return false;
	});
	
	// Ajaxify publication switch
	$posts.find('.change_publish').on('click', function(event) {
		event.preventDefault();
		var $anchor = $(this);
		$anchor.loader('show');
		
		$.get($(this).attr('href'), {'ajax':true}, function(data) {
			$anchor.loader('hide');
			data = $.parseJSON(data);
			if (data.published) {
				$anchor.removeClass('checked');
			}
			else {
				$anchor.addClass('checked');
			}
			$anchor.attr('href', data.url);
			Wix.Settings.refreshApp();
		});
	});
	
	$posts.find('.edit').click(function(event) {
		event.preventDefault();
		
		$(this).loader('show');
		
		var $formContainer = $('<div></div>');
		var $tile = $(this).parents('.tile')
		$tile.append($formContainer)
		
		$formContainer.load($(this).attr('href'), {'ajax':'true'}, function() {
			var $form = $(this).find('form').css({'visibility' : 'hidden', 'opacity' : 0});
			// capiralize class name
			var className = $form.attr('class');
			className = className.charAt(0).toUpperCase() + className.substring(1);
			
			// get method for initialization
			if (typeof(window['initliazePost'+className+'Form']) == 'function') {
				window['initliazePost'+className+'Form']($form, function($form) {
					// cancel event
					$form.animate({'opacity' : 0}, 'normal', function() {
						$tile.animate({'height' : 38}, 'normal', function() {
							$tile.find('*').css('max-height', '');
							$tile.find('.manage-buttons').removeClass('hidden');
							$form.remove();
						});
						$tile.find('.date').animate({'right' : 190}, 'normal');
					});
				});
				
				// submit event
				$form.submit(function() {
					if ($form.valid()) {
						var $textarea = $form.find('*[name=content]');
						var settings = new Settings();
						$textarea.val(CKEDITOR.instances[$textarea.attr('id')].getData());
						settings.submitForm($(this), function(data) {
							$form.animate({'opacity' : 0}, 'normal', function() {
								$tile.animate({'height' : 38}, 'normal', function() {
									$tile.find('*').css('max-height', '');
									$tile.find('.manage-buttons').removeClass('hidden');
								});
								$tile.find('h3').text($form.find('input[name=title]').val());
								$tile.find('p.date').text($.datepicker.formatDate('yy-mm-dd', new Date($form.find('input[name=postdate]').val())));
								// $tile.find('a.preview').attr('href', $form.find('input[name=url]').val());
								// $tile.find('span.preview img').attr('src', $form.find('input[name=url]').val());
								$form.remove();
								$tile.find('.date').animate({'right' : 190}, 'normal');
							});
						});
					}
					return false;
				});
				
				$tile.find('.manage-buttons').addClass('hidden');
				$tile.find('*').css('max-height', '100%');
				
				$(this).loader('hide');
				$tile.animate({'height' : $form.outerHeight()+38}, 'normal', function() {
					$tile.css('height', 'auto');
					$form.css('visibility', '').animate({'opacity' : 1}, 'normal');
					// focus first input field
					$form.find('input[type=text]').first().focus();
				});
				$tile.find('.date').animate({'right' : 0}, 'normal');
			}
			else {
				$(this).loader('hide', {message : ''});
			}
		});
		
		return false;
	});
	
	$posts.find('.delete').inlineConfirmation({
		text: __delete_question,
		confirm: __delete_confirmation,
		cancel: __delete_cancelation,
		hideOriginalAction: false,
		confirmCallback: deletePost
	});
	
	// Ajaxify .js-more-posts button
	$posts.find('.js-more-posts').click(function() {
		event.preventDefault();
		
		var $btn = $(this);
		var $tile = $btn.closest('.tile');
		var $recent = $btn.closest('#recent-posts');
	
		$btn.loader('show');
		
		$.get($btn.attr('href'), { 'ajax' : true }, function(data) {			
			$recent.append(initializeManagePosts($(data)));
			$btn.loader('hide');
			$tile.remove();
		});
		
		return false;
	});
	
	return $posts;
};

function deletePost() {
	$(this).loader('show');
	
	var $tile = $(this).closest('.tile');
	
	$.get($tile.find('.delete').attr('href'), {'ajax':true}, function() {
		$tile.animate({'opacity' : 0, 'height' : 0}, 'normal', function() {
			$tile.remove();
		});
	});
	Wix.Settings.refreshApp();
}

function inicializeEditor($id){
	var editor = CKEDITOR.replace($id);
	editor.once('contentDom', function(event) {		
		for (var i=0; i<fonts.length; i++) {
			if((fonts[i] != null) || (fonts[i] != "")) {
				event.editor.document.appendStyleSheet(fonts[i]);
			}
		}		
	});	
}

function initializeDatePicker($form) {
	if(($form.find("[name=postdate]").val()+"") == ""){
		$form.find("[name=postdate]").appendDtpicker({
			"locale": "en",
			"minuteInterval": 15,
		});		
	} else {
		$form.find("[name=postdate]").appendDtpicker({
			"locale": "en",
			"minuteInterval": 15,
			"current": $form.find("[name=postdate]").val()+"",
		});
	}		
}