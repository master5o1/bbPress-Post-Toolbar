<?php
/**
 Plugin Name: bbPress Post Toolbar
 Plugin URI: http://wordpress.org/extend/plugins/bbpress-post-toolbar/
 Description: A toolbar for bbPress that can be extended by other plugins.
 Version: 0.7.5
 Author: Jason Schwarzenberger
 Author URI: http://master5o1.com/
*/
/*  Copyright 2011  Jason Schwarzenberger  (email : jason@master5o1.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// WordPress Actions & Filters:
add_action( 'init', array('bbp_5o1_toolbar', 'plugin_do_options') );
add_action('admin_menu', array('bbp_5o1_toolbar', 'admin_add_config_link') );
add_filter( 'plugin_action_links', array('bbp_5o1_toolbar', 'admin_add_settings_link') , 10, 2 );

add_action( 'wp_footer' , array('bbp_5o1_toolbar', 'post_form_toolbar_footer_script') );

// bbPress 2.0 Actions & Filters:
add_action( 'bbp_init' , array('bbp_5o1_toolbar', 'script_and_style') );
if ( !get_option( 'bbp_5o1_toolbar_manual_insertion' ) ) {
	$bbp_5o1_toolbar_hook_to_use = 1; // Possible values are: 0, 1, 2, 3.
	switch ($bbp_5o1_toolbar_hook_to_use) {
		case 0:
			// This is the best case at the moment because of the default theme avatar placement.
			add_action( 'bbp_theme_before_reply_form_notices' , array('bbp_5o1_toolbar', 'post_form_toolbar_bar') );
			add_action( 'bbp_theme_before_topic_form_notices' , array('bbp_5o1_toolbar', 'post_form_toolbar_bar') );
			define( 'BBP_5o1_DELETE_EXCESS_TOOLBARS', false );
			break;
		case 1:
			// Avatar makes it look heaps yuck if I use these two.
			add_action( 'bbp_theme_before_reply_form_content' , array('bbp_5o1_toolbar', 'post_form_toolbar_bar') );
			add_action( 'bbp_theme_before_topic_form_content' , array('bbp_5o1_toolbar', 'post_form_toolbar_bar') );
			define( 'BBP_5o1_DELETE_EXCESS_TOOLBARS', false );
			break;
		case 2:
			// Don't like it being _after_ the text area.
			add_action( 'bbp_theme_after_reply_form_content' , array('bbp_5o1_toolbar', 'post_form_toolbar_bar') );
			add_action( 'bbp_theme_after_topic_form_content' , array('bbp_5o1_toolbar', 'post_form_toolbar_bar') );
			define( 'BBP_5o1_DELETE_EXCESS_TOOLBARS', false );
			break;
		case 3:
			// If the above add_actions don't work for you, make the if statement `if ( false ) {` instead of true.
			// This will revert it to the OLD method of printing the bar wherever bbp_template_notices are and then
			// attempting to delete the excess toolbars.
			define( 'BBP_5o1_DELETE_EXCESS_TOOLBARS', true );
			add_action( 'bbp_template_notices' , array('bbp_5o1_toolbar', 'post_form_toolbar_bar') );
			break;
	}
} else {
	add_action( 'bbp_post_toolbar_insertion', array('bbp_5o1_toolbar','post_form_toolbar_bar') );
}
// Components:
if ( get_option( 'bbp_5o1_toolbar_use_formatting' ) )
	require_once( dirname(__FILE__) . '/toolbar-format.php' );
if ( get_option( 'bbp_5o1_toolbar_use_youtube' ) )
	require_once( dirname(__FILE__) . '/toolbar-video-panel.php' );
if ( get_option( 'bbp_5o1_toolbar_use_smilies' ) )
	require_once( dirname(__FILE__) . '/toolbar-smilies-panel.php' );
if ( get_option( 'bbp_5o1_toolbar_use_images' ) )
	require_once( dirname(__FILE__) . '/toolbar-images-panel.php' );
if ( get_option( 'bbp_5o1_toolbar_use_pastebin' ) )
	require_once( dirname(__FILE__) . '/toolbar-pastebin-panel.php' );

// Plugin Activation/Deactivation Hooks:
register_activation_hook(__FILE__, array('bbp_5o1_toolbar', 'plugin_activation') );
register_deactivation_hook(__FILE__, array('bbp_5o1_toolbar', 'plugin_deactivation') );

load_plugin_textdomain('bbp_5o1_toolbar', false, basename( dirname( __FILE__ ) ) . '/languages' );

add_filter('query_vars',array('bbp_5o1_toolbar','css_trigger'));
add_action('template_redirect', array('bbp_5o1_toolbar','css_trigger_check'));

add_filter( 'bbp_5o1_toolbar_add_items' , array('bbp_5o1_toolbar', 'button_ordering'), 999);
add_action( 'admin_init' , array('bbp_5o1_toolbar', 'button_ordering_script') );
add_action( 'admin_head' , array('bbp_5o1_toolbar', 'button_ordering_style') );

// Plugin class:
class bbp_5o1_toolbar {

	function version() {
		$plugin_data = get_file_data( __FILE__, array( 'Version' => 'Version' ), 'plugin' );
		return $plugin_data['Version'];
	}

	function plugin_activation() {
		// Components:
		add_option( 'bbp_5o1_toolbar_use_youtube', true, '', 'yes' );
		add_option( 'bbp_5o1_toolbar_use_formatting', true, '', 'yes' );
		add_option( 'bbp_5o1_toolbar_use_smilies', true, '', 'yes' );
		add_option( 'bbp_5o1_toolbar_use_images', false, '', 'yes' );
		add_option( 'bbp_5o1_toolbar_use_pastebin', false, '', 'yes' );

		add_option( 'bbp_5o1_toolbar_use_custom_smilies', false, '', 'yes' );
		add_option( 'bbp_5o1_toolbar_use_textalign', false, '', 'yes' );
		add_option( 'bbp_5o1_toolbar_show_credit', false, '', 'yes' );
		add_option( 'bbp_5o1_toolbar_custom_help', false, '', 'yes' );
		add_option( 'bbp_5o1_toolbar_manual_insertion', false, '', 'yes' );
		add_option( 'bbp_5o1_toolbar_allow_anonymous_image_uploads', false, '', 'yes' );
		add_option( 'bbp_5o1_toolbar_allow_image_uploads', true, '', 'yes' );
	}

	function plugin_deactivation() {
		delete_option( 'bbp_5o1_toolbar_use_youtube' );
		delete_option( 'bbp_5o1_toolbar_use_formatting' );
		delete_option( 'bbp_5o1_toolbar_use_smilies' );
		delete_option( 'bbp_5o1_toolbar_use_images' );
		delete_option( 'bbp_5o1_toolbar_use_pastebin' );

		delete_option( 'bbp_5o1_toolbar_use_custom_smilies' );
		delete_option( 'bbp_5o1_toolbar_use_textalign' );
		delete_option( 'bbp_5o1_toolbar_show_credit' );
		delete_option( 'bbp_5o1_toolbar_custom_help' );
		delete_option( 'bbp_5o1_toolbar_manual_insertion' );
		delete_option( 'bbp_5o1_toolbar_allow_anonymous_image_uploads' );
		delete_option( 'bbp_5o1_toolbar_allow_image_uploads' );
	}

	function admin_add_settings_link( $links, $file ) {
		if ( 'bbpress-post-toolbar/bbpress-post-toolbar.php' != $file )
			return $links;
		$settings_link = '<a href="' . admin_url( 'plugins.php?page=bbpress-post-toolbar' ) . '">' . __( 'Settings', 'bbp_5o1_toolbar') . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	function plugin_options_page() {
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}

		// Components:
		$use_youtube = false;
		if ( get_option( 'bbp_5o1_toolbar_use_youtube' ) )
			$use_youtube = true;
		$use_smilies = false;
		if ( get_option( 'bbp_5o1_toolbar_use_smilies' ) )
			$use_smilies = true;
		$use_formatting = false;
		if ( get_option( 'bbp_5o1_toolbar_use_formatting' ) )
			$use_formatting = true;
		$use_images = false;
		if ( get_option( 'bbp_5o1_toolbar_use_images' ) )
			$use_images = true;
		$use_pastebin = false;
		if ( get_option( 'bbp_5o1_toolbar_use_pastebin' ) )
			$use_pastebin = true;

		$custom_smilies = false;
		if ( get_option('bbp_5o1_toolbar_use_custom_smilies') )
			$custom_smilies = true;
		$textalign = false;
		if ( get_option('bbp_5o1_toolbar_use_textalign') )
			$textalign = true;
		$credit = false;
		if ( get_option('bbp_5o1_toolbar_show_credit') )
			$credit = true;
		$manual = false;
		if ( get_option( 'bbp_5o1_toolbar_manual_insertion' ) )
			$manual = true;
		$anonymous_image_uploads = false;
		if ( get_option( 'bbp_5o1_toolbar_allow_anonymous_image_uploads' ) )
			$anonymous_image_uploads = true;
		$image_uploads = false;
		if ( get_option( 'bbp_5o1_toolbar_allow_image_uploads' ) )
			$image_uploads = true;
		?>
		<div class="wrap">
			<div style="max-width: 700px;">
				<h2>bbPress Post Toolbar</h2>
				<?php _e('Plugin Version', 'bbp_5o1_toolbar'); ?> <?php echo bbp_5o1_toolbar::version(); ?><br />
				<?php _e('If you enjoy this plugin, please consider making a <a href="http://master5o1.com/donate/">donation</a> to master5o1 as a token of thanks.', 'bbp_5o1_toolbar'); ?>
				<h3><?php _e('Options', 'bbp_5o1_toolbar'); ?></h3>
				<form method="post" action="">
					<p>
					<?php bbp_5o1_toolbar::button_ordering_admin(); ?>
					</p>
					<p>
						<strong><?php _e('Show video panel?', 'bbp_5o1_toolbar'); ?></strong><br /><br />
						<span style="margin: 0 50px;">
						<label style="display: inline-block; width: 150px;"><input name="bbp_5o1_toolbar_use_youtube" type="radio" value="1" <?php print (($use_youtube) ? 'checked="checked"' : '' ) ?> /> <?php _e('Yes (default)', 'bbp_5o1_toolbar'); ?></label>
						<label><input name="bbp_5o1_toolbar_use_youtube" type="radio" value="0" <?php print ((!$use_youtube) ? 'checked="checked"' : '' ) ?> /> <?php _e('No', 'bbp_5o1_toolbar'); ?></label>
						</span>
					</p>
					<p>
						<strong><?php _e('Show pastebin panel?', 'bbp_5o1_toolbar'); ?></strong><br /><br />
						<span style="margin: 0 50px;">
							<label style="display: inline-block; width: 150px;"><input name="bbp_5o1_toolbar_use_pastebin" type="radio" value="1" <?php print (($use_pastebin) ? 'checked="checked"' : '' ) ?> /> <?php _e('Yes', 'bbp_5o1_toolbar'); ?></label>
							<label><input name="bbp_5o1_toolbar_use_pastebin" type="radio" value="0" <?php print ((!$use_pastebin) ?
	'checked="checked"' : '' ) ?> /> <?php _e('No (default)', 'bbp_5o1_toolbar'); ?></label>
						</span>
					</p>
					<p>
						<strong><?php _e('Show smilies panel?', 'bbp_5o1_toolbar'); ?></strong><br /><br />
						<span style="margin: 0 50px;">
						<label style="display: inline-block; width: 150px;"><input name="bbp_5o1_toolbar_use_smilies" type="radio" value="1" <?php print (($use_smilies) ? 'checked="checked"' : '' ) ?> /> <?php _e('Yes (default)', 'bbp_5o1_toolbar'); ?></label>
						<label><input name="bbp_5o1_toolbar_use_smilies" type="radio" value="0" <?php print ((!$use_smilies) ? 'checked="checked"' : '' ) ?> /> <?php _e('No', 'bbp_5o1_toolbar'); ?></label>
						</span>
					</p>
					<?php if ( $use_smilies ) : ?>
					<div style="margin: 0 0 10px 51px; padding: 0 10px 10px 10px; border: solid 10px #eee; border-top: solid 1px #eee; border-right: solid 1px #eee;">
						<p>
							<strong><?php _e('Use customised smilies?', 'bbp_5o1_toolbar'); ?></strong><br /><br />
							<span style="margin: 0 50px;">
							<label style="display: inline-block; width: 150px;"><input name="bbp_5o1_toolbar_use_custom_smilies" type="radio" value="1" <?php print (($custom_smilies) ? 'checked="checked"' : '' ) ?> /> <?php _e('Yes', 'bbp_5o1_toolbar'); ?></label>
							<label><input name="bbp_5o1_toolbar_use_custom_smilies" type="radio" value="0" <?php print ((!$custom_smilies) ? 'checked="checked"' : '' ) ?> /> <?php _e('No (default)', 'bbp_5o1_toolbar'); ?></label>
							</span><br />
							<div style="margin: 0 50px;"><small><?php printf( __('Note: It is recommended that the %s directory is copied or moved to the %s directory.  This is to prevent any custom smilies that you may have added from being lost on an upgrade to this plugin.', 'bbp_5o1_toolbar'), '<code><small>' . dirname(__FILE__) . '/smilies/</small></code>', '<code><small>' . WP_CONTENT_DIR . '/smilies/</small></code>'); ?></small></div>
						</p>
					</div>
					<?php endif; ?>
					<p>
						<strong><?php _e('Show formatting buttons?', 'bbp_5o1_toolbar'); ?></strong><br /><br />
						<span style="margin: 0 50px;">
						<label style="display: inline-block; width: 150px;"><input name="bbp_5o1_toolbar_use_formatting" type="radio" value="1" <?php print (($use_formatting) ? 'checked="checked"' : '' ) ?> /> <?php _e('Yes (default)', 'bbp_5o1_toolbar'); ?></label>
						<label><input name="bbp_5o1_toolbar_use_formatting" type="radio" value="0" <?php print ((!$use_formatting) ?
'checked="checked"' : '' ) ?> /> <?php _e('No', 'bbp_5o1_toolbar'); ?></label>
						</span>
					</p>
					<?php if ( $use_formatting ) : ?>
					<div style="margin: 0 0 10px 51px; padding: 0 10px 10px 10px; border: solid 10px #eee; border-top: solid 1px #eee; border-right: solid 1px #eee;">
						<p>
							<strong><?php _e('Show text-alignment buttons?', 'bbp_5o1_toolbar'); ?></strong><br /><br />
							<span style="margin: 0 50px;">
							<label style="display: inline-block; width: 150px;"><input name="bbp_5o1_toolbar_use_textalign" type="radio" value="1" <?php print (($textalign) ? 'checked="checked"' : '' ) ?> /> <?php _e('Yes', 'bbp_5o1_toolbar'); ?></label>
							<label><input name="bbp_5o1_toolbar_use_textalign" type="radio" value="0" <?php print ((!$textalign) ?
	'checked="checked"' : '' ) ?> /> <?php _e('No (default)', 'bbp_5o1_toolbar'); ?></label>
							</span>
						</p>
					</div>
					<?php endif; ?>
					<p>
						<strong><?php _e('Allow images to be posted?', 'bbp_5o1_toolbar'); ?></strong><br /><br />
						<span style="margin: 0 50px;">
							<label style="display: inline-block; width: 150px;"><input name="bbp_5o1_toolbar_use_images" type="radio" value="1" <?php print (($use_images) ? 'checked="checked"' : '' ) ?> /> <?php _e('Yes', 'bbp_5o1_toolbar'); ?></label>
							<label><input name="bbp_5o1_toolbar_use_images" type="radio" value="0" <?php print ((!$use_images) ?
	'checked="checked"' : '' ) ?> /> <?php _e('No (default)', 'bbp_5o1_toolbar'); ?></label>
						</span>
					</p>
					<?php if ( $use_images ) : ?>
					<div style="margin: 0 0 10px 51px; padding: 0 10px 10px 10px; border: solid 10px #eee; border-top: solid 1px #eee; border-right: solid 1px #eee;">
						<p>
							<strong><?php _e('Allow users to upload images?', 'bbp_5o1_toolbar'); ?></strong><br /><br />
							<span style="margin: 0 50px;">
							<label style="display: inline-block; width: 150px;"><input name="bbp_5o1_toolbar_allow_image_uploads" type="radio" value="1" <?php print (($image_uploads) ? 'checked="checked"' : '' ) ?> /> <?php _e('Yes', 'bbp_5o1_toolbar'); ?></label>
							<label><input name="bbp_5o1_toolbar_allow_image_uploads" type="radio" value="0" <?php print ((!$image_uploads) ?
	'checked="checked"' : '' ) ?> /> <?php _e('No (default)', 'bbp_5o1_toolbar'); ?></label>
							</span><br />
							<div style="margin: 0 50px;">
								<span><small><?php _e('Upload Directory', 'bbp_5o1_toolbar'); ?>: </small><code><small><?php $directory = wp_upload_dir(); print $directory['path'].'/'; ?></small></code></span><br />
							</div>
						</p>
						<?php if ( $image_uploads ) : ?>
						<div style="margin: 0 0 10px 51px; padding: 0 10px 10px 10px; border: solid 10px #eee; border-top: solid 1px #eee; border-right: solid 1px #eee;">
							<p>
								<strong><?php _e('Allow unregistered users to upload images?', 'bbp_5o1_toolbar'); ?></strong><br /><br />
								<span style="margin: 0 50px;">
								<label style="display: inline-block; width: 150px;"><input name="bbp_5o1_toolbar_allow_anonymous_image_uploads" type="radio" value="1" <?php print (($anonymous_image_uploads) ? 'checked="checked"' : '' ) ?> /> <?php _e('Yes', 'bbp_5o1_toolbar'); ?></label>
								<label><input name="bbp_5o1_toolbar_allow_anonymous_image_uploads" type="radio" value="0" <?php print ((!$anonymous_image_uploads) ?
		'checked="checked"' : '' ) ?> /> <?php _e('No (default)', 'bbp_5o1_toolbar'); ?></label>
								</span><br />
							</p>
						</div>
						<?php endif; ?>
					</div>
					<?php endif; ?>
					<p>
						<strong><?php _e('Customised toolbar styling (CSS)'); ?></strong></br /><br />
						<textarea name="bbp_5o1_toolbar_custom_css" style="margin: 0 50px; min-height: 100px; min-width: 400px;"><?php echo get_option('bbp_5o1_toolbar_custom_css'); ?></textarea>
						<div style="margin: 0 50px;">
							<small><?php printf( __('Clear the text area to revert to the default css (%s).', 'bbp_5o1_toolbar'), '<a href="' . plugins_url('/includes/toolbar.css', __file__) . '">toolbar.css</a>' ); ?></small>
						</div>
					</p>
					<p>
						<strong><?php _e('Customised help panel message.'); ?></strong></br /><br />
						<textarea name="bbp_5o1_toolbar_custom_help" style="margin: 0 50px; min-height: 100px; min-width: 400px;"><?php echo get_option('bbp_5o1_toolbar_custom_help'); ?></textarea>
						<div style="margin: 0 50px;">
							<small><?php _e('Clear the text area to revert to the default help panel message.', 'bbp_5o1_toolbar'); ?></small>
						</div>
					</p>
					<p>
						<strong><?php _e('Allow manual insertion of the bar?', 'bbp_5o1_toolbar'); ?></strong><br /><br />
						<span style="margin: 0 50px;">
						<label style="display: inline-block; width: 150px;"><input name="bbp_5o1_toolbar_manual_insertion" type="radio" value="1" <?php print (($manual) ? 'checked="checked"' : '' ) ?> /> <?php _e('Yes', 'bbp_5o1_toolbar'); ?></label>
						<label><input name="bbp_5o1_toolbar_manual_insertion" type="radio" value="0" <?php print ((!$manual) ?
'checked="checked"' : '' ) ?> /> <?php _e('No (default)', 'bbp_5o1_toolbar'); ?></label>
						</span><br />
						<div style="margin: 0 50px;"><small><?php _e("Note: Manual bar insertion requires that you use <code>&lt;?php do_action( 'bbp_post_toolbar_insertion' ); ?&gt;</code> in your theme files, or wherever you desire the bar to appear.", 'bbp_5o1_toolbar'); ?></small></div>
					</p>
					<p>
						<strong><?php _e('Link to master5o1&#39;s website in the About panel as a credit to the plugin developer?', 'bbp_5o1_toolbar'); ?></strong><br /><br />
						<span style="margin: 0 50px;">
						<label style="display: inline-block; width: 150px;"><input name="bbp_5o1_toolbar_show_credit" type="radio" value="1" <?php print (($credit) ? 'checked="checked"' : '' ) ?> /> <?php _e('Yes', 'bbp_5o1_toolbar'); ?></label>
						<label><input name="bbp_5o1_toolbar_show_credit" type="radio" value="0" <?php print ((!$credit) ?
'checked="checked"' : '' ) ?> /> <?php _e('No (default)', 'bbp_5o1_toolbar'); ?></label>
						</span><br />
						<div style="margin: 0 50px;">
							<span><small><strong><?php _e('Example', 'bbp_5o1_toolbar'); ?>:</strong> <?php printf( __('Version %s by %s.', 'bbp_5o1_toolbar'), bbp_5o1_toolbar::version(), '<a href="http://master5o1.com/" title="master5o1&#39;s website">master5o1</a>' ); ?></small></span><br />
							<span><small><strong><?php _e('Default', 'bbp_5o1_toolbar'); ?>:</strong> <?php printf( __('Version %s by %s.', 'bbp_5o1_toolbar'), bbp_5o1_toolbar::version(), 'master5o1' ); ?></small></span>
						</div>
					</p>
					<input type="hidden" name="bbpress-post-toolbar" value="bbpress-post-toolbar" />
					<input type="submit" value="Submit" />
				</form>
			</div>
		</div>
		<?php
	}

	function plugin_do_options() {
		if ( !is_admin() || !current_user_can('manage_options') )
			return;
		if (isset($_POST['bbpress-post-toolbar']) && $_POST['bbpress-post-toolbar'] == "bbpress-post-toolbar") {

			update_option('bbp_5o1_toolbar_sort_order', $_POST['bbp_5o1_toolbar_sort_order']);


			// Components:
			if ($_POST['bbp_5o1_toolbar_use_youtube'] == 1)
				update_option('bbp_5o1_toolbar_use_youtube', true);
			elseif ($_POST['bbp_5o1_toolbar_use_youtube'] == 0)
				update_option('bbp_5o1_toolbar_use_youtube', false);

			if ($_POST['bbp_5o1_toolbar_use_formatting'] == 1)
				update_option('bbp_5o1_toolbar_use_formatting', true);
			elseif ($_POST['bbp_5o1_toolbar_use_formatting'] == 0)
				update_option('bbp_5o1_toolbar_use_formatting', false);

			if ($_POST['bbp_5o1_toolbar_use_images'] == 1)
				update_option('bbp_5o1_toolbar_use_images', true);
			elseif ($_POST['bbp_5o1_toolbar_use_images'] == 0)
				update_option('bbp_5o1_toolbar_use_images', false);

			if ($_POST['bbp_5o1_toolbar_use_smilies'] == 1)
				update_option('bbp_5o1_toolbar_use_smilies', true);
			elseif ($_POST['bbp_5o1_toolbar_use_smilies'] == 0)
				update_option('bbp_5o1_toolbar_use_smilies', false);

			if ($_POST['bbp_5o1_toolbar_use_pastebin'] == 1)
				update_option('bbp_5o1_toolbar_use_pastebin', true);
			elseif ($_POST['bbp_5o1_toolbar_use_pastebin'] == 0)
				update_option('bbp_5o1_toolbar_use_pastebin', false);


			if ($_POST['bbp_5o1_toolbar_use_beta'] == 1)
				update_option('bbp_5o1_toolbar_use_beta', true);
			elseif ($_POST['bbp_5o1_toolbar_use_beta'] == 0)
				update_option('bbp_5o1_toolbar_use_beta', false);


			if ($_POST['bbp_5o1_toolbar_use_custom_smilies'] == 1)
				update_option('bbp_5o1_toolbar_use_custom_smilies', true);
			elseif ($_POST['bbp_5o1_toolbar_use_custom_smilies'] == 0)
				update_option('bbp_5o1_toolbar_use_custom_smilies', false);

			if ($_POST['bbp_5o1_toolbar_use_textalign'] == 1)
				update_option('bbp_5o1_toolbar_use_textalign', true);
			elseif ($_POST['bbp_5o1_toolbar_use_textalign'] == 0)
				update_option('bbp_5o1_toolbar_use_textalign', false);

			if ($_POST['bbp_5o1_toolbar_show_credit'] == 1)
				update_option('bbp_5o1_toolbar_show_credit', true);
			elseif ($_POST['bbp_5o1_toolbar_show_credit'] == 0)
				update_option('bbp_5o1_toolbar_show_credit', false);

			update_option('bbp_5o1_toolbar_custom_help', $_POST['bbp_5o1_toolbar_custom_help']);
			update_option('bbp_5o1_toolbar_custom_css', $_POST['bbp_5o1_toolbar_custom_css']);

			if ($_POST['bbp_5o1_toolbar_manual_insertion'] == 1)
				update_option('bbp_5o1_toolbar_manual_insertion', true);
			elseif ($_POST['bbp_5o1_toolbar_manual_insertion'] == 0)
				update_option('bbp_5o1_toolbar_manual_insertion', false);

			if ($_POST['bbp_5o1_toolbar_allow_anonymous_image_uploads'] == 1)
				update_option('bbp_5o1_toolbar_allow_anonymous_image_uploads', true);
			elseif ($_POST['bbp_5o1_toolbar_allow_anonymous_image_uploads'] == 0)
				update_option('bbp_5o1_toolbar_allow_anonymous_image_uploads', false);

			if ($_POST['bbp_5o1_toolbar_allow_image_uploads'] == 1)
				update_option('bbp_5o1_toolbar_allow_image_uploads', true);
			elseif ($_POST['bbp_5o1_toolbar_allow_image_uploads'] == 0)
				update_option('bbp_5o1_toolbar_allow_image_uploads', false);

		}
	}

	function admin_add_config_link() {
		if ( function_exists('add_submenu_page') )
			add_submenu_page('plugins.php', __('bbPress Post Toolbar Options', 'bbp_5o1_toolbar'), __('bbPress Post Toolbar', 'bbp_5o1_toolbar'), 'manage_options', 'bbpress-post-toolbar', array('bbp_5o1_toolbar','plugin_options_page') );
	}

	function post_form_toolbar_bar($param = null) {
		global $wpsmiliestrans;

		// Let's not output the bar if the browser is MSIE 7 or older.
		$agent = explode(' ', $_SERVER['HTTP_USER_AGENT']);
		$browser = $agent[2];
		$version = str_replace(';',' ',$agent[3]);
		if ($browser == 'MSIE' && intval($version,10) < 8 && intval($version,10) != 0) {
			echo '<div class="bbp-template-notice warning">bbPress Post Toolbar does not support your browser version.</div>';
			return $param;
		}

		$items = apply_filters( 'bbp_5o1_toolbar_add_items' , array() );
		if (count($items) == 0) {
			$items[] = array( 'action' => 'switch_panel',
			 'inside_anchor' => 'Empty Toolbar',
			 'data' => "You've got an empty toolbar.  Activate the sub-plugins, such as the formatting one.");
		}
		?>
		<div id="post-toolbar">
			<ul id="buttons" style="list-style-type: none;"><?php
				?><li class="right-button"><a onclick="switch_panel('post-toolbar-item-help');" title="<?php _e( 'Help', 'bbp_5o1_toolbar'); ?>"><small><small><?php _e( 'Help', 'bbp_5o1_toolbar'); ?></small></small></a></li><?php
			?><?php
				$i = 0;
				foreach ($items as $item) :
					if ($item['action'] == 'api_item') :
						?><li><a onclick="do_button(this, {action : '<?php echo $item['action']; ?>', panel : 'post-toolbar-item-<?php print $i; ?>'}, <?php echo $item['data']; ?>)"><?php echo $item['inside_anchor']; ?></a></li><?php
					else:
						?><li><a onclick="do_button(this, { action : '<?php echo $item['action']; ?>', panel : 'post-toolbar-item-<?php print $i; ?>' }, function(){ return '<?php if ($item['action'] == 'insert_data' || $item['action'] == 'insert_shortcode') { echo $item['data']; } elseif (isset($item['panel'])) {echo $item['panel'];} ?>';})"><?php echo $item['inside_anchor']; ?></a></li><?php
					endif;
					$i++;
				endforeach;
			  ?><?php
			?></ul>
			<?php
			$i = 0;
			foreach ($items as $item) :
				if ($item['action'] == 'switch_panel') :
					?><div id="post-toolbar-item-<?php print $i; ?>" class="panel"><?php print $item['data']; ?></div><?php
				endif;
				$i++;
			endforeach;
			?><div id="post-toolbar-item-help" class="panel">
				<h4 style="display: inline-block;">bbPress Post Toolbar <?php _e('Help', 'bbp_5o1_toolbar'); ?></h4><span style="line-height: 16px; margin: auto 5px;">&mdash; <a onclick="switch_panel('post-toolbar-item-about');" style="cursor: pointer;"><?php _e('About', 'bbp_5o1_toolbar'); ?></a></span>
				<div>
			<?php if ( ! get_option('bbp_5o1_toolbar_custom_help') ) : ?>
				<p><?php _e("This toolbar allows simple click-to-add HTML elements.", 'bbp_5o1_toolbar'); ?></p>
				<p><?php _e("For the options that are simple buttons (e.g. bold, italics), one can select text and then click the button to apply the tag around the selected text.", 'bbp_5o1_toolbar'); ?></p>
				<p><?php _e("For the options at open panels (e.g. link), open the panel first, add the url to the text box (if link), then hit Apply Link.  If it's font sizing or colors, then select the text and click the size you want, e.g., xx-small.", 'bbp_5o1_toolbar'); ?></p>
			<?php else: echo get_option('bbp_5o1_toolbar_custom_help'); endif; ?>
				</div>
			</div>
			<div id="post-toolbar-item-about" class="panel">
				<h4 style="display: inline-block;"><?php _e('About', 'bbp_5o1_toolbar'); ?> bbPress Post Toolbar</h4><span style="line-height: 16px; margin: auto 5px;">&mdash; <a onclick="switch_panel('post-toolbar-item-help');" style="cursor: pointer;"><?php _e('Help', 'bbp_5o1_toolbar'); ?></a></span>
				<p><?php _e("This toolbar allows simple click-to-add HTML elements.", 'bbp_5o1_toolbar'); ?></p>
				<span><?php printf( __('Version %s by %s.', 'bbp_5o1_toolbar'), bbp_5o1_toolbar::version(), ((get_option('bbp_5o1_toolbar_show_credit') ? '<a href="http://master5o1.com/" title="master5o1&#39;s website">master5o1</a>' : 'master5o1') ) ); ?></span>
			</div>
		</div>
		<?php
		return $param;
	}

	function post_form_toolbar_footer_script() {
		?>
		<script type="text/javascript"><!--
			addCloseTagsToSubmit();
			<?php if ( ! get_option( 'bbp_5o1_toolbar_manual_insertion' ) ) :
				if ( BBP_5o1_DELETE_EXCESS_TOOLBARS ) :
					// Deleting the bar is not necessary now that we have those nicely placed action hooks.
					echo "delete_post_toolbar();";
				endif;
			endif; ?>
		//--></script>
		<?php
	}

	function script_and_style() {
		wp_register_script( 'bbp_5o1_post_toolbar_script', plugins_url('includes/toolbar.js', __FILE__) );
		//wp_register_style( 'bbp_5o1_post_toolbar_style', plugins_url('includes/toolbar.css', __FILE__) );
		wp_register_style( 'bbp_5o1_post_toolbar_style', site_url('/?bbp_5o1_toolbar_css') );

		wp_enqueue_script( 'bbp_5o1_post_toolbar_script' );
		wp_enqueue_style( 'bbp_5o1_post_toolbar_style' );
	}

	function css_trigger($vars) {
		$vars[] = 'bbp_5o1_toolbar_css';
		return $vars;
	}

	function css_trigger_check() {
		if ( !isset($_GET['bbp_5o1_toolbar_css']) ) { return; }
		header("Content-Type: text/css");
		if ( ! get_option('bbp_5o1_toolbar_custom_css') ) {
			echo "/* begin default CSS: */\n";
			echo file_get_contents( dirname(__FILE__) . '/includes/toolbar.css' );
			echo "\n";
			echo "/* end default CSS: */\n";
		} else {
			echo "/* begin custom CSS: */\n";
			echo get_option('bbp_5o1_toolbar_custom_css');
			echo "\n";
			echo "/* end custom CSS: */\n";
		}
		echo "\n\n";
		echo "/* begin toolbar buttons/extensions CSS: */\n";
		do_action( 'bbp_5o1_toolbar_css' );
		echo "\n";
		echo "/* end toolbar buttons/extensions CSS: */\n";
		exit;
	}

	function button_ordering( $items ) {
		if ( ! get_option( 'bbp_5o1_toolbar_sort_order' ) )
			return $items;
		$order_str = trim(get_option( 'bbp_5o1_toolbar_sort_order' ), ' ,');
		$sort_order = explode(',', $order_str);
		$items_hashes = array_flip($sort_order);

		$new_items = array();
		foreach ($items_hashes as $hash => $item) {
			if ($hash != md5('')) {
				$new_items[$hash] = -1;
			}
		}

		foreach ($items as $item) {
			$hash = md5( $item['action'] . $item['inside_anchor'] );
			if ($hash != md5('')) {
				$new_items[$hash] = $item;
			}
		}

		$items = array();
		$hashes = '';
		foreach ($new_items as $hash => $item) {
			if ($hash != md5('')) {
				$hashes .= $hash . ',';
				$items[] = $item;
			}
		}
		update_option('bbp_5o1_toolbar_sort_order', trim($hashes, ' ,'));

		return $items;
	}

	function button_ordering_style() {
		echo <<<HTML
<style type="text/css">
	#sortable { list-style-type: none; margin: 0 0 10px; padding: 0; width: 100%; background-color: #eaeaea; border: solid 1px #e5e5e5;}
	#sortable li { text-align: center; vertical-align: middle; cursor: pointer; margin: 4px; padding: 3px 0 2px; min-width: 25px; height: 20px; display: inline-block; background-color: #f5f5f5; border: solid 1px #bbb; }
</style>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery( "#sortable" ).sortable({
			stop: function(event, ui) {
				document.getElementById('sort-order-hashes').value = '';
				for (i = 0; i < jQuery('#sortable').sortable('toArray').length; i++) {
					document.getElementById('sort-order-hashes').value += jQuery('#sortable').sortable('toArray')[i];
					if (i < jQuery('#sortable').sortable('toArray').length-1) {
						document.getElementById('sort-order-hashes').value += ',';
					}
				}
			}
		});
		jQuery( "#sortable" ).disableSelection();
	});
</script>
HTML;
	}

	function button_ordering_script() {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-draggable' );
	}

	function button_ordering_admin() {
		$items = apply_filters( 'bbp_5o1_toolbar_add_items' , array() );
		if (count($items) == 0) {
			return;
		}
		echo '<strong>' . __('Button ordering', 'bbp_5o1_toolbar') . '</strong> (drag n drop)' . "\n";
		?>
		<div style="margin: 0"><small><?php _e('Note: This is probably buggy on several cases.', 'bbp_5o1_toolbar'); ?></small></div>
		<?php
		echo '<ul id="sortable">';
		$hashes = '';
		foreach ($items as $item) {
			$hash = md5( $item['action'] . $item['inside_anchor']);
			$hashes .= $hash . ',';
			echo '<li id="' . $hash . '" class="ui-state-default">' . $item['inside_anchor'] . '</li>';
		}

		echo '</ul>' . "\n";
		echo '<input type="hidden" name="bbp_5o1_toolbar_sort_order" id="sort-order-hashes" value="' . trim($hashes, ' ,') . '" />' . "\n";
		echo '<input type="submit" onclick="document.getElementById(\'sort-order-hashes\').value=\'\';" value="Reset custom ordering" />' . "\n";
	}
}

// Extend kses to allow <span>
if ( !CUSTOM_TAGS ) {
	$allowedtags['span'] = array(
			'style' => array());
}
?>
