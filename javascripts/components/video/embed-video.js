function getEmbeddedPlayer(url, width, height){
	var output = '';
	var youtubeUrl = url.match(/watch(.*)v=([a-zA-Z0-9\-_]+)/);
	var youtuUrl = url.match(/youtu.be\/([a-zA-Z0-9\-_]+)/); 
	var vimeoUrl = url.match(/^(http:|https:)\/\/(www\.)?vimeo\.com\/(clip\:)?(\d+).*$/);
	if( youtubeUrl){
		output = '<iframe width="'+width+'" height="'+height+'" src="http://www.youtube.com/embed/'+youtubeUrl[youtubeUrl.length-1]+'?rel=0&wmode=transparent" frameborder="0" allowfullscreen>';
	} else if( youtuUrl ){
		output = '<iframe width="'+width+'" height="'+height+'" src="http://www.youtube.com/embed/'+youtuUrl[1]+'?rel=0&wmode=transparent" frameborder="0" allowfullscreen>';
	} else if(vimeoUrl){
		output =  '<iframe src="http://player.vimeo.com/video/'+vimeoUrl[4]+'" width="'+width+'" height="'+height+'" frameborder="0"></iframe>';
	}else{
		output = false;
	}
	return output;
}
