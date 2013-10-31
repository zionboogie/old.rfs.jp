	// 関数にオプション変数を渡す
	function getTweet(options){
		var options = jQuery.extend({
			screen_name: "",
			target: ""
		}, options);

		jQuery.ajax({
			type:		'POST',
			url:		"http://api.twitter.com/1/statuses/user_timeline.json",
			cache:		false,
			dataType:	'jsonp',
			data:{
				count: "20",
				screen_name: options.screen_name,
				include_rts: "true"
			},
			success: function(json){
	            $.each(json, function(n){
	                // 置換
	                tweetText	= this.text.replace(/((http:|https:)\/\/[\x21-\x26\x28-\x7e]+)/gi, "<a href='$1'>$1</a>");
	                tweetText	= tweetText.replace(/@([a-zA-Z0-9]+)?/gi, "<a href='http://twitter.com/$1' target='_blank'>@$1</a>");
	                tweetText	= tweetText.replace(/#([a-zA-Z0-9]+)?/gi, "<a href='http://twitter.com/#!search?q=$1' target='_blank'>#$1</a>");
	                // HTML整形
	                html	= '<div class="box-twitter-timeline">';
	                html	+= '<p>' + tweetText + '<br />';
	                html	+= '<span class="date">' + getRelativeTime(this.created_at) + '</span></p>';
	                html	+= '</div>';
					jQuery(options.target).append(html);
	            });
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
	        	alert("error"+textStatus+""+errorThrown);
			}
		});
		return this;
	};
	function getRelativeTime(time_value) {
		var values	= time_value.split(' ');
		time_value	= (time_value.indexOf(',') == -1) ? values[1] + " " + values[2] + ", " + values[5] + " " + values[3]
		                                             : values[2] + " " + values[1] + ", " + values[3] + " " + values[4];
		var parsed_date	= Date.parse(time_value);
		var relative_to	= (arguments.length > 1) ? arguments[1] : new Date();
		var delta		= parseInt((relative_to.getTime() - parsed_date)/1000,10);
		delta			= delta + (relative_to.getTimezoneOffset() * 60);
		if(delta < 60) {
			return '［1分以内］';
		} else if(delta < 120) {
			return '［1分前］';
		} else if(delta < (60*60)) {
			return '［' + (parseInt(delta/60, 10)).toString() + '分前］';
		} else if(delta < (120*60)) {
			return '［1時間前］';
		} else if(delta < (24*60*60)) {
			return '［' + (parseInt(delta/3600,10)).toString() + '時間前］';
		} else if(delta < (48*60*60)) {
			return '［1日前］';
		} else {
			return '［' + (parseInt(delta/86400, 10)).toString() + '日前］';
		}
	}


/*
(function(jQuery) {

	// 関数にオプション変数を渡す
	jQuery.fn.getTweet	= function(options){

		var options = jQuery.extend({
			screen_name: "",
			target: ""
		}, options);

		jQuery.ajax({
			type:		'POST',
			url:		"http://api.twitter.com/1/statuses/user_timeline.json",
			cache:		false,
			dataType:	'jsonp',
			data:{
				count: "20",
				screen_name: options.screen_name,
				include_rts: "true"
			},
			success: function(json){
	            $.each(json, function(n){
	                // 置換
	                tweetText	= this.text.replace(/((http:|https:)\/\/[\x21-\x26\x28-\x7e]+)/gi, "<a href='$1'>$1</a>");
	                tweetText	= tweetText.replace(/@([a-zA-Z0-9]+)?/gi, "<a href='http://twitter.com/$1' target='_blank'>@$1</a>");
	                tweetText	= tweetText.replace(/#([a-zA-Z0-9]+)?/gi, "<a href='http://twitter.com/#!search?q=$1' target='_blank'>#$1</a>");
	                // HTML整形
	                html	= '<div class="box-twitter-timeline">';
	                html	+= '<p>' + tweetText + '<br />';
	                html	+= '<span class="date">' + getRelativeTime(this.created_at) + '</span></p>';
	                html	+= '</div>';
					jQuery(options.target).append(html);
	            });
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
	        	alert("error"+textStatus+""+errorThrown);
			}
		});
		return this;

//		return this.each(function() {
//			jQuery(this).css({ color: '#999' });
//		});


	};
	function getRelativeTime(time_value) {
		var values	= time_value.split(' ');
		time_value	= (time_value.indexOf(',') == -1) ? values[1] + " " + values[2] + ", " + values[5] + " " + values[3]
		                                             : values[2] + " " + values[1] + ", " + values[3] + " " + values[4];
		var parsed_date	= Date.parse(time_value);
		var relative_to	= (arguments.length > 1) ? arguments[1] : new Date();
		var delta		= parseInt((relative_to.getTime() - parsed_date)/1000,10);
		delta			= delta + (relative_to.getTimezoneOffset() * 60);
		if(delta < 60) {
			return '［1分以内］';
		} else if(delta < 120) {
			return '［1分前］';
		} else if(delta < (60*60)) {
			return '［' + (parseInt(delta/60, 10)).toString() + '分前］';
		} else if(delta < (120*60)) {
			return '［1時間前］';
		} else if(delta < (24*60*60)) {
			return '［' + (parseInt(delta/3600,10)).toString() + '時間前］';
		} else if(delta < (48*60*60)) {
			return '［1日前］';
		} else {
			return '［' + (parseInt(delta/86400, 10)).toString() + '日前］';
		}
	}

})(jQuery);
*/