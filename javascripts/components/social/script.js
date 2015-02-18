/**
 * 
 */
$(document).ready(function(){
	FB.init({
	      appId  : facebook_key,
	      status : true, // check login status
	      cookie : true, // enable cookies to allow the server to access the session
	      xfbml  : true  // parse XFBML
	});
	
	/*-- START -- Login functions*/
	$(".social_networks").delegate("#facebook-login",'click', function(){
		FB.login(function(response) {
			$(".social_networks").loader('show');
		    if (response.authResponse) {
		      FB.api('/me', function(response) {
		        $.ajax({
		            url: 'actions/social.php',
		            type: 'post',
		            data: {
		              uid: response.id,
		              scaction: 'facebook_login'
		            },success: function(ret) {
		            },complete: function(ret){
		        	    window.location.reload();
		            },
		            dataType: 'html'
		        });
		      });
		    } else {
		    }
		}, {scope: 'publish_stream,status_update,manage_pages,offline_access'});
	});

	$(".social-sharing").delegate("#log-to-fb",'click', function(){
		FB.login(function(response) {
		   if (response.authResponse) {
			   checkFacebookStatus();
		   }		    
		 }, {scope: 'publish_stream,status_update,manage_pages,offline_access'});
	});
	
	$(".social_networks").delegate('#twitter-login','click', function(){
		$(".social_networks").loader('show');
	    $.oauthpopup({
	        path: twitter_link,
	        callback: function(cal)
	        {
	        	window.location.reload();
	        }
	    });
	});
	/*-- END -- Login functions*/
	
	/*-- START -- Logout functions*/
	$(".social_networks").delegate("#facebook-logout",'click', function(){
		$(".social_networks").loader('show');
		FB.api($(this).data("fuid")+'/permissions', 'delete', function(response) {
			logoutFacebook();
		});
	});
	
	function logoutFacebook(){
		$.ajax({
			url: 'actions/social.php',
		   	type: 'post',
		   	data: {
			   	scaction: 'facebook_logout'
		   	},success: function(ret) {
	        },complete: function(ret) {
	        	window.location.reload();
		   	},
		   	dataType: 'html'
		});
	}
	
	$(".social_networks").delegate("#twitter-logout",'click', function(){
		$(".social_networks").loader('show');
	    $.ajax({
	    	url: 'actions/social.php',
	    	type: 'post',
	    	data: {
	    		scaction: 'twitter_logout',
	    		href: window.location.href
	    	},complete: function(ret) {
	    		window.location.reload();
	    	},
	       	dataType: 'html'
	    });
	});
	/*-- END -- Logout functions*/
	
	/*-- START -- Status check*/
	function checkFacebookStatus(){
		FB.getLoginStatus(function(response) {
			$(".fb-message").css("display","none");
			if ((response.status === 'connected') && (facebook_uid != "")) {
				setFacebookInfo(response);
				$(".social.facebook").find("label").removeClass("disabled")
				$(".social.facebook").find("input").removeAttr("disabled","disabled");
			} else if ((response.status === 'not_authorized') || (facebook_uid == "")) { // user is NOT AUTHORIZED
				if(facebook_uid != "") { // user delete our application from facebook but ID is still in DB
					logoutFacebook();
				}
				$(".fb-authorize").css("display","block");
			} else { // user is LOGOUT
				$(".fb-login").css("display","block");
				$(".social.facebook").find("label").addClass("disabled")
				$(".social.facebook").find("input").attr("disabled","disabled");
			}
		});
	}
	checkFacebookStatus();
	/*-- END -- Status check*/
	
	function setFacebookInfo(r){
		$(".fb-message").css("display","none");
		var uid = r.authResponse.userID;
		var accessToken = r.authResponse.accessToken;
		$(".fb-checkboxs").empty();	
		FB.api('/'+uid, function(response) {
			$(".fb-checkboxs").append('<span class="fb-page"><input id="fb_user_'+uid+'" class="facebook-checkbox" type="checkbox" name="fb-checkbox[]" value="'+uid+'"><label for="fb_user_'+uid+'" class="facebook-label">'+response.name+'</label></span>');
			FB.api('/'+uid+'/accounts', function(response) {
				$.each(response.data, function(i, v) {
					$(".fb-checkboxs").append('<span class="fb-page"><input id="fb_page_'+(v.id)+'" class="facebook-checkbox" type="checkbox" name="fb-checkbox[]" value="'+(v.id)+'"><label for="fb_page_'+(v.id)+'" class="facebook-label">'+(v.name)+'</label></span>');
				});
			});
		});
	}
	
	/*-- START -- Other code*/
	$(".social-sharing").delegate(".social-share-enable",'click', function(){
		if($(this).parent().parent().find(".disabled").length == 0) {
			$(this).parent().parent().find(".share-settings").toggle();
		}
	});
	
	$(".social-sharing").delegate(".tw-settings textarea","input propertychange",function(){
		var baseNumber = 160;
		var t = $(this).val().toLowerCase();
		var tl = $(this).closest("p.wide").find(".inputbar-wrap input").val();
		if (t.indexOf("!title") >= 0){
			t = t.replace("!title", tl);
			baseNumber -= tl.length-6;
		}
		if (t.indexOf("!url") >= 0){
			t = t.replace("!url", "-_-_-_-_-_-_-_-_-_-_");
			baseNumber -= 16;
		}
		var charLeft = charactersleft(t);
		if(charLeft <= 0) {
			$(this).val($(this).val().substring(0, baseNumber));
			$(this).closest("p.wide").find(".tw-counter").text("0");
		} else {
			$(this).closest("p.wide").find(".tw-counter").text(charLeft);
		}
		//$(this).attr("maxlength",baseNumber);
	});
	/*-- END -- Other code*/
	function charactersleft(tweet) {
	    return 160 - twttrtext.txt.getTweetLength(tweet);
	}
});