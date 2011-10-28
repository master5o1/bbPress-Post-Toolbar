<?php

// Add panel entry to toolbar:
add_filter( 'bbp_5o1_toolbar_add_items' , array('bbp_5o1_pastebin_panel', 'panel_entry'), 2 );

// Add shortcodes to bbPress replies text:
add_filter( 'bbp_get_reply_content', array('bbp_5o1_pastebin_panel', 'add_pastebin_shortcode'), 0);

// Add shortcodes to WordPress pages/posts/etc:
add_shortcode( 'paste', array ( 'bbp_5o1_pastebin_panel', 'pastebin_shortcode' ) );

class bbp_5o1_pastebin_panel {

	function panel_entry($items) {
		$item['action'] = 'switch_panel';
		$item['inside_anchor'] = '<img src="' . plugins_url( '/images', __FILE__ ) . '/pastebin.ico" title="Pastebin" alt="Pastebin" />';
		// $item['inside_anchor'] = 'Pastebin';

		$paste_provider['Gist'] = "https://gist.github.com/";
		$paste_provider['Pastebin'] = "http://pastebin.com/";

		foreach ($paste_provider as $key => $value) {
			$paste_providers .= '<a href="' . $value . '" title="' . $key . '">' . $key . '</a> ';
		}
		$online_paste_url = __( 'Online Paste URL:', 'bbp_5o1_toolbar' );
		$supported = __( 'Supported Paste providers:', 'bbp_5o1_toolbar' );
		$item['data'] = <<<HTML
<div style="width: 310px; display: inline-block;"><span>${online_paste_url}</span><br />
<input style="display:inline-block;width:300px;" type="text" id="paste_url" value="" /></div>
<a class="toolbar-apply" style="margin-top: 1.4em;" onclick="insert_panel('paste');">Apply Link</a>
<p style="font-size: x-small;"><span>${supported} ${paste_providers}</span><br /></p>
HTML;
		$items[] = $item;
		return $items;
	}

	function add_pastebin_shortcode($content) {
		$shortcode_tags['paste'] = array ( 'bbp_5o1_pastebin_panel', 'pastebin_shortcode' );

		if (empty($shortcode_tags) || !is_array($shortcode_tags))
			return $content;
		$tagnames = array_keys($shortcode_tags);
		$tagregexp = join( '|', array_map('preg_quote', $tagnames) );
		$pattern = '(.?)\[('.$tagregexp.')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)';
		return preg_replace_callback('/'.$pattern.'/s', 'do_shortcode_tag', $content);
	}

	function pastebin_shortcode( $atts = null, $content = null ) {
		$host = parse_url($content, PHP_URL_HOST);
		// Gist
		if ( $host == "gist.github.com" ) {
			return bbp_5o1_pastebin_panel::gist( $atts, $content );
		}
		if ( $host == "pastebin.com" || $host == "www.pastebin.com" ) {
			return bbp_5o1_pastebin_panel::pastebin( $atts, $content );
		}
		return $content;
	}

	function gist( $atts = null, $content = null ) {
		$code = parse_url( $content, PHP_URL_PATH );
		return '<script src="https://gist.github.com' . $code . '.js"></script>';
	}

	function pastebin ( $atts = null, $content = null ) {
		$code = parse_url( $content, PHP_URL_PATH );
		$code = str_replace('/', '', $code);
		// return '<iframe src="http://pastebin.com/embed_iframe.php?i=' . $code . '" style="border:none;width:100%;height:400px;"></iframe>';
		return '<script src="http://pastebin.com/embed_js.php?i=' . $code . '"></script>';
	}
}

?>