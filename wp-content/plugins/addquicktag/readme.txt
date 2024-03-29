=== AddQuicktag ===
Contributors: Bueltge, inpsyde
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6069955
Tags: quicktag, editor, tinymce, add buttons, button, buttons, visual editor
Requires at least: 3.0
Tested up to: 4.1
Stable tag: trunk

This plugin makes it easy to add Quicktags to the html - and visual-editor.

== Description ==
This plugin makes it easy to add Quicktags to the html - and visual-editor. It is possible to export your Quicktags as a JSON - file that can be imported in other installations of the plugin. 

WP-AddQuicktag for WordPress is originally created by [Roel Meurders](http://roel.meurders.nl/ "Roel Meurders"). The versions in the Repo of AddQuicktag are newer versions, this is a complete rewrite of version 2.0.0 with more functionalities.

The plugin can add configurable custom quicktags to the editor of every post type, including custom post types from other sources. You may choose a post type for which a quicktag shall show up in the editor.
If this should not work perfectly well for you, you may also use the hooks inside the plugin. See the examples and hint inside the tab "[Other Notes](http://wordpress.org/extend/plugins/addquicktag/other_notes/)".

= Bugs, technical hints or contribute =
Please give me feedback, contribute and file technical bugs on [GitHub Repo](https://github.com/bueltge/AddQuicktag).

**Made by [Inpsyde](http://inpsyde.com) &middot; We love WordPress**

Have a look at the premium plugins in our [market](http://marketpress.com).

== Installation ==
= Requirements =
* WordPress version 3.0 and later (see _Compatible up to_)

= Installation =
1. Unpack the download-package
1. Upload the files to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress or for the Network, if you will use in Multisite for all Sites
1. Got to 'Settings' menu and configure the plugin

**Versions before WordPress 3.0**

If you wish to use this plugin with an older version of WordPress, please use an older version of this plugin, older than version 2.0.0 - you find old versions in the [repo](http://wordpress.org/extend/plugins/addquicktag/download/). Anyway, I will not support this version. The plugin version 2.0.0 and newer are rewrites, that use all new possibilties of the WordPress Core that are not available in WordPress versions before 3.x.


== Screenshots ==
1. Settings area in WordPress 4.0-alpha
2. HTML Editor with new Quicktags
3. Visual editor with new Quicktags


== Other Notes ==
= Acknowledgements =
**Thanks to**

* German Translation (de_DE) by [myself](http://bueltge.de) ;)
* French translation (fr_FR) by [Jean-Michel MEYER](http://www.li-an.fr/blog)
* Japanese translation (ja) by [Yuuichi](http://www.u-1.net/2011/12/29/2498/)
* Traditional Chinese (zh_TW) by [Zeng pppdog](http://pppdog.me/)
* Slovak translation by [Branco](http://webhostinggeeks.com/user-reviews/)
* Brazilian Portuguese translation by [Aluízio Leye Larangeira](http://www.aluizioll.com.br/category/traducao/)
* Turkish translation by [Turk3005](http://wordpress.org/support/profile/turk3005), see also his [seetings tutorial video](https://yadi.sk/i/l6YwDpa_V3FaV)
* Ukranian translation by [Michael Yunat](http://getvoip.com/blog)

= Hook for custom post types =
The plugin adds the quicktag by default to post types/ID `post`, `page` and `comment`. To use the plugin for other post types also you may use a filter; see the following example or an example plugin in the [Gist 1595155](https://gist.github.com/1595155).

	// add custom function to filter hook 'addquicktag_post_types'
	add_filter( 'addquicktag_post_types', 'my_addquicktag_post_types' );
	/**
	 * Return array $post_types with custom post types
	 *
	 * @param   $post_type Array
	 * @return  $post_type Array
	 */
	function my_addquicktag_post_types( $post_types ) {

		$post_types[] = 'edit-comments';

		return $post_types;
	}


= Hook for custom pages =
It is possible also to filter the pages inside the backend. By default the scripts include the pages `post.php`, `comment.php`. The following example changes this for an another page.

	add_filter( 'addquicktag_pages', 'my_addquicktag_pages' );
	/**
	 * Return array $page with custom page strings
	 *
	 * @param   $page Array
	 * @return  $page Array
	 */
	function my_addquicktag_pages( $page ) {

		$page[] = 'edit-comments.php';

		return $page;
	}

See this Gist as an example for how to add the Quicktags to the editor of comments: [Gist: 3076698](https://gist.github.com/3076698).
If you want the Quicktags of this plugin to work on the Quickedit of comments as well, remove the `.example`-part of `addquicktag_quickedit_comment.php.example` filename. The file is a stand alone helper plugin for Add Quicktag. You'll need to activate this file (plugin) separately in 'Manage Plugins'.


= Hook for custom buttons =
It is possible to add custom buttons to the editor, if the plugin is active.

The following example adds buttons. The params inside the array are the same as in the settings of the plugin.

	if ( class_exists( 'Add_Quicktag' ) ) :
	add_filter( 'addquicktag_buttons', 'my_addquicktag_buttons' );

	function my_addquicktag_buttons( $buttons ) {

	    $buttons[] = array(
	        'text'          => 'Permalink',
	        'title'         => '',
	        'start'         => '[permalink]',
	        'end'           => '[/permalink]',
	        'access'        => '',
	        'order'         => 1,
	        'visual'        => 1,
	        'post'          => 0,
	        'page'          => 1,
	        'comment'       => 0,
	        'edit-comments' => 0
	    );
	    $buttons[] = array(
	        'text'          => 'Button',
	        'title'         => '',
	        'start'         => '<span class="border blue">',
	        'end'           => '</span>',
	        'access'        => '',
	        'order'         => 2,
	        'visual'        => 1,
	        'post'          => 0,
	        'page'          => 1,
	        'comment'       => 0,
	        'edit-comments' => 0
	    );

	    return $buttons;
	}
	endif;


= License =
Good news, this plugin is free for everyone! Since it's released under the GPL, you can use it free of charge on your personal or commercial blog. But if you enjoy this plugin, you may consider to thank me and leave a [small donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6069955 "Paypal Donate link") for the time I've spent writing and supporting this plugin. And I really don't want to know how many hours of my life this plugin has already eaten ;)

= Translations =
The plugin comes with various translations, please refer to the [WordPress Codex](http://codex.wordpress.org/Installing_WordPress_in_Your_Language "Installing WordPress in Your Language") for more information on activating the translation. If you want to help to translate the plugin to your language, please have a look at the .pot file which contains all defintions and may be used with a [gettext](http://www.gnu.org/software/gettext/) editor like [Poedit](http://www.poedit.net/) (Windows) or the plugin [Localization](http://wordpress.org/extend/plugins/codestyling-localization/) for WordPress.


== Changelog ==
= 2.4.1 (2015-01-19) =
* Bugfix for save label of each button; change sanitizing

= 2.4.0 (12/18/2014) =
* Add traditional Chinese (zh_TW) langugae files
* Fix filter topic to use tags with attributes [issue #30](https://github.com/bueltge/AddQuicktag/issues/30)
* Update grammer topics [Issue #29](https://github.com/bueltge/AddQuicktag/issues/29)

= 2.3.3 (06/26/2014) =
* Fix PHP notice on different installs
* Update french, turkish and german language files
* Update description, hint on the settings page
* Order setting works now also on the visual drop down menu
* Update readme
* Update screenshots for the new version, possibilities

= 2.3.2 (06/20/2014) =
* Bugfix Javascript to add quicktags

= 2.3.1 (06/19/2014) =
* Fix JavaScript error on code_buttons

= 2.3.0 (06/19/2014) =
* Allow Quicktags on edit-comment and quick-edit screen on default
* Add possibilty to remove core quicktags from each post type
* Add possibility to add enhanced code buttons to mask code and format
* Rewrite the Im-Export function (now works simple with json)
* Rewrite the german language file

= 2.3.0-RC1 (05/22/2014) =
* Use on default all post types with active UI, incl. Custom and Defaul Post types
* New settings UI to easier add quicktags to each post type
* Add Widget area, now it is possible to use Quicktags on widgets with WP editor
* Add brazilian translation
* Add turkish translation
* Add possibility to remove default quicktags
* Changes on settings style, check in MP6 design, WP 3.8
* Add ukranian translation
* Add solution to remove core quicktag, Beta Status
* Fix TinyMCE Select Box in WordPress 3.9*

= 2.2.2 (02/09/2013) =
* Add Filter Hook for custom button, see [issue #9](https://github.com/bueltge/AddQuicktag/issues/9)
* Small check for undefined var on settings page

= 2.2.1 (13/11/2012) =
* Fix for im/export
* Add toggle checkboxes for each type

= 2.2.0 =
* Add checkboxes for different post type, use also filter for custom post type
* Change script on HTML editor, only include buttons, there have an active checkbox on options for his post type
* Add more data in JSON
* Fix for custom post types; works now also on settings page
* Change function to add button in html editor --> `QTags.addButton()`
* Update im/export function for use with custmo post type
* Fix settings page on network for WP 3.5

= 2.1.0 =
* Add fix, see [Forum thread 'array_multisort error'](http://wordpress.org/support/topic/plugin-addquicktag-array_multisort-error#post-2920394)
* See Quicktag button in visual editor, onbly if an button is actove for visual
* Change hooks for include scripts
* Add filter for page in backend
* Add edit comments to use quicktags

= 2.0.4 =
* Add fix for use older settings from previous versions
* Unicode fix for upload XML file

= 2.0.3 =
* Add Filter 'addquicktag_post_types' for use the plugin also on custom post types
* Update readme and add an example for this filter; also an Gist for use faster

= 2.0.2 =
* change hook for including styles and scripts for compatibility in WP 3.4

= 2.0.1 =
* Bugfix on JS for WP smaller 3.3; use quickbuttons clean on html-editor with core-buttons

= 2.0.0 =
* complete redesign, new code from the first line
* add function for add quicktags on html and visual editor
* works also on Multisite Network
* new settings page
* add fallback in JS to use this new version also in WordPress smaller 3.3

= v1.6.5 (02/02/2011) =
* changes for admin-hints
* kill php warnings on debug-mode

= v1.6.4 (12/22/2010) =
* small changes for depreaced WP functions

= v1.6.3 (16/06/2009) =
* Add belarussian language file, thanks to Fat Cow

Find out about older changes on the [the official website](http://bueltge.de/wp-addquicktags-de-plugin/120/#historie "AddQuicktag")!
