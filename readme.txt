=== bbPress Post Toolbar ===
Contributors: master5o1
Donate link: http://master5o1.com/donate/
Tags: bbPress, bbPress 2.0, toolbar, youtube, images, smilies, smileys, emoticons, 5o1
Requires at least: WordPress 3.1+ and bbPress 2.0+
Tested up to: 3.2.1
Stable tag: 0.7.5

Post toolbar for bbPress 2.0.

== Description ==

Post toolbar for bbPress 2.0.

* Toolbar is automatically shown, though it can be set to manual insertion.
* Enables embedding of images in a bbPress post (turn it on in the settings).
* Users can upload images directly to the site (Valums' [Ajax Upload](http://valums.com/ajax-upload/) script)
* Allows &lt;span style=""&gt; in a bbPress posts.
* Embeds online videos from multiple providers (Dailymotion, LiveLeak, Megavideo, Metacafe, Vimeo, YouTube,) using [video]http://...[video]
* Also provides a [youtube]http://...[/youtube] shortcode.
* Embeds online pastebins from multiple providers (GitHub's Gist, Pastebin.com) using [paste]http://...[/paste]
* Toolbar items all pluggable, defaults can be turned off and replaced by custom ones.
* Default item set is formatting, smilies, and videos.
* Custom CSS styling of the bar.
* Custom button ordering.

An example of the toolbar is on the [forum](http://master5o1.com/contact/) on my website.

I would prefer it if support and feedback is placed on my [forum](http://master5o1.com/forum/wordpress-plugins/bbpress-post-toolbar/) because I'll check my website more often than WordPress' plugin forums.
I have a GitHub repository for this project [here](https://github.com/master5o1/bbPress-Post-Toolbar).
Bug reports, feature requests and other issues can be reported through the GitHub [Issues system](https://github.com/master5o1/bbPress-Post-Toolbar/issues).
Any [donations](http://master5o1.com/donate/) will be gratefully accepted. ;)

== Installation ==

1. Make sure you have bbPress 2.0 (or better) plugin activated.
1. Upload `bbpress-post-toolbar` folder to the `/wp-content/plugins/` directory
1. Move or copy the `smilies` folder to `/wp-content/` directory. (So it doesn't get changed on upgrades).
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure the options in the bbPress Post Toolbar settings page.

If you choose to set the bar to manual insertion rather than automatic, then you will need to add this to your theme file, or where ever you might want to show the bar:

* `<?php do_action('bbp_post_toolbar_insertion'); ?>`


It is a good idea to copy or move the smilies directory included with this plugin to the wp-content directory.

== Frequently Asked Questions ==

= Can images be set a maximum width? =

Easily.  In fact, I found that bbPress already does this in its default theme (and thus compatability theming will also).  Try putting the following into your theme's css:

`#content .bbp-topic-content img,
#content .bbp-reply-content img {
	max-width: 100%;
}`

= Could you explain how to use customised smilies? =

I have included a simple set of smilies with this plugin in the `bbpress-post-toolbar/smilies/` directory.  Changing the files will obviously change what the smilies look like.  But edit the package-config.php file inside this directory to change the code binding to a particular image.

I recommend that this folder is either copied or moved to the `/wp-content/` directory so that any customised smilies that you have added are not lost on an upgrade to this plugin.

== Screenshots ==

1. Code box showing an example of how to post the code box.
1. Online Video panel opened.
1. Toolbar options showing the Drag 'n Drop button ordering.

== Changelog ==

= 0.7.5 =
* Allowed for duplicate smiley codes but not so that each duplicate code has its own entry on the panel. [feature request](http://master5o1.com/topic/id-like-to-submit-a-feature/)
* Example: :) and (: both translate to smile.png but smile.png only has one entry on the toolbar smilies panel.
* Fixed minor bug in video panel where 'Online Video Url::' had two colons.
* Removed :'( from the smilies translation package and added :cry: ;( ;-( etc as replacement.
* Replaced the screenshots with newer ones.
* Created toolbar-pastebin-panel.php to handle the [paste][/paste] shortcode.  Starting with GitHub's Gist and Pastebin.com.
* Adjusted the [code] design so that it works better in all browsers.
* Prevented the bar from being displayed if the web browser is MSIE and less than version 8.0.

= 0.7.0 =
* Made images be anchored to their image file also.  As requested in [this topic](http://master5o1.com/topic/image-hyperlinks/) on my forum.
* Built custom CSS ability.  Uses the default toolbar.css if there is not custom CSS available.  Custom CSS replaces toolbar.css and is run before the default/extended button css.
* Custom button ordering.  Drag and drop using jQuery's Sortable.

= 0.6.1 =
* Fixed the 5MB issue in toolbar-images-panel.php.  It now uses your site's PHP settings to determine your max upload filesize.
* Something else, but I've forgotten because I did it a week ago and didn't write it down.  Main reason for releasing this update is so that the fix above is applied and released to all before I go skiing for a week.

= 0.6.0 =
* Adjusted the toolbar HTML and CSS to fix it so that if the buttons are too wide for the toolbar, the overflow into a second layor still looks nice.
* LiveLeak video support; Also RedTube but that's not listed on the video panel because it's pr0ns.
* Added a [code][/code] shortcode (Turned off).  Tempted to hook it into the <code></code> html tag.  It displays line numbers and holds true with indentation.
* The add_shortcode() and add_filter() for the [code][/code] is commented out in toolbar-format.php.  Uncomment them to use [code][/code].
* bbPress 2.0-RC3 brings better action hooks for the bar placement.  This means that I don't have to use bbp_template_notices and attempt to delete all but one placed bar.
* That is: The bar is printed _exactly_ where I want it.  If it doesn't get printed at all or where you want it, turn on manual insertion and put it where it should be.
* Added Megavideo support to the video shortcode.
* I believe 0.5.5 had a bug where the only metacafe video that was being displayed was hard coded.  This has been fixed.
* Video dimensions can be customised: [video width=450 height=286]http://...[/video].  Defaults are 450x286.
* Reworked the formatting section quite heavily.
* Updated the color chooser to look a bit nicer.  Added three new colours (Orange, Indigo and Violet).
* Minor changes to font size panel.
* Added the font face panel with 8 fonts.
* Minor change to Links panel.  If text is highlighted in the post textarea then clicking on the links panel button will prompt for the URL and use the highlighted text as the link text.
* Changed the buttons to use a JavaScript stack.  This means that clicking once on Bold will give you `<strong>`, clicking again will give `</strong>`.  Attempted to produce relatively correct HTML using the stack.
* Unclosed tag indicator on the button that relates to the unclosed tag.  Also a close tags `</>` button.  Closing open tags is done on submit.
* Minor adjustments to `toolbar.css`

= 0.5.8 =
* Wow, my testing sux, obviously.  Oh well.
* Fixed a bug where the image was being uploaded to ./2011/08imagename.jpg instead of ./2011/08/imagename.jpg.
* Also made the unique filename be a counter of how many occurances of that filename is in the folder: image.jpg exists so named image-1.jpg, image-2.jpg, etc.  Much like WP does usually for it's stuff.
* Cudos to mvaginger for [bringing this bug to my attention](http://wordpress.org/support/topic/i-am-sorry-but-still-buggy).

= 0.5.7 =
* Reverted back to option-checks rather than included 'child' plugins.
* Minor restyle of the options page.

= 0.5.6 =
* When going to the options page for the first time, the default set of toolbar items will be activated.
* Activated items are: Toolbar Formatting, Toolbar Smilies Panel, Toolbar Video Panel.

= 0.5.5 =
* Extracted the buttons and panels into four sub-plugins: video, smilies, formatting & images.
* Above allowed for ordering of the buttons (by those categories); default ordering is formatting, images, video, smilies.
* Added [video][/video] shortcode to replace the [youtube] one.  This is because I added more providers.
* Video providers are Youtube, Dailymotion, Vimeo and Metacafe.
* Removed the allow images option because it is implied when the images sub-plugin is activated.

= 0.5.1 =
* Allowed image uploading to be optional, that is, while posting images is still allowable, uploading them is not.
* Got around to enqueing the style and scripts (fileuploader.css/.js & toolbar.css/.js).
* Fixed various URLs and directory paths to use WP's functions or constants (ie: content_url(), site_url(), plugins_url(), WP_CONTENT_DIR, etc).

= 0.5.0 =
* Added image uploading using Valums' [Ajax Upload](http://valums.com/ajax-upload/) script.
* Image uploading is turned on when allowing image posting is turned on.
* Anonymous (unregistered users) can upload images if allowed in the settings (off by default).

= 0.4.0 =
* Allowing the insertion of the bar set to manual, use `<?php do_action('bbp_post_toolbar_insertion'); ?>` in your theme file where ever you want the bar to appear.
* Allowed the Help panel to be customised.

= 0.3.3 =
* Programmatically determined what the plugin version is so that I can't forget to update the version in the About panel, etc.

= 0.3.2 =
* Added `/languages/bbpress-post-toolbar.pot` file to the plugin for translations to be done.
* Adding __() and _e() to allow for translations.
* Haha, turns out I forgot the version info in about plugin again. -_-

= 0.3.1 =
* Changing the plugin header to try and get the Active Versions pie 
chart working.

= 0.3.0 =
* Reorganised the plugin options page and added some notes about each option.  Suggestion to move `/smilies/`, etc.
* Allowed the option to have the master5o1 credit be linked back to my website.  Default = not linked.
* Made smilies directory preference be `wp-content/smilies/`, then fall back to `wp-content/plugins/bbpress-post-toolbar/smilies/`, then fall back to WordPress' default set.

= 0.2.1 =
* Accidentally forgot to increase the version that was displayed in the About panel on the toolbar.

= 0.2.0 =
* Add Button API is actually usable now.
* Allowed custom javascript functions to be run through the Add Button api so that adding a button is actually doable.

= 0.1.0 =
* First release.

== Upgrade Notice ==

= 0.5.6 =
You may want to manually deactivate and activate the plugin (and perhaps sub-plugins).

= 0.5.5 =
This release changes a lot: You will need to activate at least one of the sub-plugins to see the buttons.  I suggest at the minimum the formatting one.

= 0.5.0 =
This release brings in AJAX uploading of images to the Images panel.  I have used Valums' [Ajax Upload](http://valums.com/ajax-upload/) script; the default styling from his demo was retained while I familiarise myself with the script.  I intend to change the styling and visually integrate the script with the other parts of the panel.

More information on my website: http://master5o1.com/2011/08/10/post-toolbar-version-0-5-0/

= 0.3.3 =
You can actually ignore this update, it's just me getting some minor things done.

= 0.3.2 =
Added translation .pot file so that people can have custom translations.

== To Do ==

* Go to sleep.
* Clean some things.
* Other things that I can't figure out yet.
* Relax and have a cup of hot chocolate.

== Custom Buttons ==

The following is about standard push buttons, not panel opening buttons.  To see how a panel opening button works just view the bbpress-post-toolbar.php source.

Adding custom buttons to the toolbar is done using by making a plugin and adding a filter to hook into the button.
My example below is how I added the Spoiler button to the toolbar, which is [my modification](https://github.com/master5o1/wordpress-tiny-spoiler) of the [Tiny Spoiler](http://wordpress.org/extend/plugins/tiny-spoiler/) plugin.

Note: This isn't my only modification to Tiny Spoiler.  I had to also build a function to parse the `[spoiler]` shortcode inside a bbPress post.

`function bbp_5o1_spoiler_add_to_toolbar($items) {
	$javascript = <<<JS
function(){ insert_shortcode('spoiler') }
JS;
	$items[] = array( 'action' => 'api_item',
		'inside_anchor' => '<img src="'. site_url() . '/wp-content/plugins/tiny-spoiler/spoiler_btn.png" title="Spoiler" alt="Spoiler" />',
		'data' => $javascript);
	return $items;
}
add_filter( 'bbp_5o1_toolbar_add_items' , 'bbp_5o1_spoiler_add_to_toolbar' );`

= Available JavaScript Functions =

Really, just look inside toolbar.js

* Insert an HTML tag: `insert_data('tag')`
* (returns <tag></tag>, potentially wrapped around text)
* Insert a shortcode tag: `insert_shortcode('tag')`
* (returns [tag][/tag], potentially wrapped around text)
* Insert a smiley: `insert_smiley(':)')`
* Insert a color: `insert_color('red')`
* Insert a size: `insert_size('5pt')`
* `testText(tag_s, tag_e)` can be used to try to wrap a start- and end-tag around selected text.  If there is text selected then the tag will be applied at the end of the post content wrapped around a single space.

= Available Action/Filter hookers =

These are filters or actions that I have made to make buttons populate the bar:

* apply_filters( 'bbp_5o1_toolbar_add_items', array() );
* do_action( 'bbp_5o1_toolbar_css' );
* ...