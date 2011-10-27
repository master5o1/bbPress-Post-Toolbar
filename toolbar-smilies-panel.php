<?php

// Add panel entry to toolbar:
add_filter( 'bbp_5o1_toolbar_add_items' , array('bbp_5o1_smilies_panel', 'panel_entry'), 3 );

if ( get_option('bbp_5o1_toolbar_use_custom_smilies') ) {
	add_filter( 'smilies_src', array('bbp_5o1_smilies_panel', 'switch_url'), 0, 3 );
	if ( file_exists(WP_CONTENT_DIR . '/smilies/package-config.php') )
		require_once(WP_CONTENT_DIR . '/smilies/package-config.php');
	elseif ( file_exists(dirname(__FILE__) . '/smilies/package-config.php') )
		require_once(dirname(__FILE__) . '/smilies/package-config.php');
}

class bbp_5o1_smilies_panel {

	function switch_url($link, $img, $url) {
		if ( file_exists(WP_CONTENT_DIR . '/smilies/package-config.php') )
			return content_url( '/smilies/' . $img );
		elseif ( file_exists(dirname(__FILE__) . '/smilies/package-config.php') )
			return plugins_url( '/smilies/' . $img, __FILE__ );
		return $link;
	}

	function panel_entry($items) {
		global $wpsmiliestrans;
		if ( get_option( 'use_smilies' ) ) {
			$item['action'] = 'switch_panel';
			$item['inside_anchor'] = str_replace( "class='wp-smiley' ", 'title="Smilies"', convert_smilies(':)') );
			$item['data'] = "";
			// Allow duplicate codes to be parsed but not have buttons for those duplicates.
			$smilies_array = array_flip($wpsmiliestrans);
			foreach ($smilies_array as $name => $code) {
				$js = "insert_smiley('" . addslashes($code) . "');";
				$item['data'] .= '<a class="smiley" onclick="' . $js . '">' . str_replace("class='wp-smiley' ", 'title="' . $code . '" ', convert_smilies($code)) . '</a>';
			}
			$items[] = $item;
		}
		return $items;
	}
}

?>