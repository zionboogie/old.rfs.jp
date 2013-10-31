window.google_analytics_uacct = "UA-2874244-2";

document.write('<script src="/wp-content/themes/yoko/common/js/syntaxhighlighter/scripts/shCore.js"></script>');
document.write('<script src="/wp-content/themes/yoko/common/js/syntaxhighlighter/scripts/shBrushAS3.js"></script>');
document.write('<script src="/wp-content/themes/yoko/common/js/syntaxhighlighter/scripts/shBrushBash.js"></script>');
document.write('<script src="/wp-content/themes/yoko/common/js/syntaxhighlighter/scripts/shBrushCss.js"></script>');
document.write('<script src="/wp-content/themes/yoko/common/js/syntaxhighlighter/scripts/shBrushJScript.js"></script>');
document.write('<script src="/wp-content/themes/yoko/common/js/syntaxhighlighter/scripts/shBrushPerl.js"></script>');
document.write('<script src="/wp-content/themes/yoko/common/js/syntaxhighlighter/scripts/shBrushPhp.js"></script>');
document.write('<script src="/wp-content/themes/yoko/common/js/syntaxhighlighter/scripts/shBrushSql.js"></script>');
document.write('<script src="/wp-content/themes/yoko/common/js/syntaxhighlighter/scripts/shBrushPlain.js"></script>');
document.write('<script src="/wp-content/themes/yoko/common/js/syntaxhighlighter/scripts/shBrushXml.js"></script>');

document.write('<link href="/wp-content/themes/yoko/common/js/syntaxhighlighter/styles/shCore.css" rel="stylesheet"/>');
document.write('<link href="/wp-content/themes/yoko/common/js/syntaxhighlighter/styles/shThemeDefault.css" rel="stylesheet"/>');

// プラグイン
jQuery.fn.topLink = function(settings) {
	settings = jQuery.extend({
		min: 1,
		fadeSpeed: 200
	}, settings);
	return this.each(function() {
		var el = $(this);
		el.hide();
		$(window).scroll(function() {
			if($(window).scrollTop() >= settings.min){
				el.fadeIn(settings.fadeSpeed);
			} else {
				el.fadeOut(settings.fadeSpeed);
			}
		});
	});
};

