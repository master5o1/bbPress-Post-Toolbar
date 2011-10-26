<?php

// Add panel entry to toolbar:
add_filter( 'bbp_5o1_toolbar_add_items' , array('bbp_5o1_toolbar_format', 'entry'), 0 );
add_filter( 'bbp_5o1_toolbar_add_items' , array('bbp_5o1_toolbar_format', 'close_tags_entry'), 999 );
add_action( 'bbp_head', array('bbp_5o1_toolbar_format', 'color_style') );


// add_filter( 'bbp_get_reply_content', array('bbp_5o1_toolbar_format', 'add_code_shortcode'), -999 );
// add_shortcode( 'code', array('bbp_5o1_toolbar_format', 'do_code') );

class bbp_5o1_toolbar_format {

	function add_code_shortcode($content) {
		$shortcode_tags['code'] = array('bbp_5o1_toolbar_format', 'do_code');
		if (empty($shortcode_tags) || !is_array($shortcode_tags))
			return $content;
		$tagnames = array_keys($shortcode_tags);
		$tagregexp = join( '|', array_map('preg_quote', $tagnames) );
		$pattern = '(.?)\[('.$tagregexp.')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)';
		// This allows it to pick up the HTML <code> tag.
		//$content = str_replace( array('<code>', '</code>'), array('[code]', '[/code]'), $content);
		return preg_replace_callback('/'.$pattern.'/s', 'do_shortcode_tag',  $content);
	}
	
	function do_code( $atts = null, $content = null ) {
		extract(shortcode_atts(array(
			'title' => 'arbitrary',
		), $atts));
		$count = substr_count( $content, "\n" );
		$numbers = '';
		for ($i = 0; $i <= $count; $i++) {
			$numbers .= $i+1 . ".\n";
		}
		$id = 'code-line-'.date('U').'-'.rand(1,999);
		$numid = str_replace('line', 'num', $id);
		$content = str_replace( array('<', '>', '[', ']'), array('&lt;', '&gt;', '&#91;', '&#93;'), $content );
		$content = str_replace(array("\t","  "), array("&nbsp;&nbsp;", "&nbsp;&nbsp;"), $content);
		$js = 'document.getElementById(\'' . $numid . '\').scrollTop = this.scrollTop; this.scrollTop =  document.getElementById(\'' . $numid . '\').scrollTop;';
		return '<div class="code-main"><div class="code-title"><span>&nbsp;<strong>Code:</strong> '.$title.' </span><span style="float: right;">(<a onclick="fnSelect(\'' . $id . '\');">select</a>)</span></div><div class="code-num" id="' . $numid . '">' . $numbers . '</div><div class="code-line" onscroll="'.$js.'" id="' . $id . '">' . $content . '</div><div style="clear:both;"></div></div>';
	}
	
	function code_style() {
		return <<<STYLE
div.code-title {
	width: 99.25%;
	font-family: monospace;
	margin: 0;
	padding: 0;
	background-color: #e5e5e5;
	border: solid 1px #e5e5e5;
	border-bottom: none;
}

div.code-num {
	text-align: right;
	width: 7%;
	float: left;
	margin: 0 0 1.42em;
	padding: 0.25em 0 1.0em;
	display: inline-block;
	white-space: nowrap;
	font-family: monospace;
	border-bottom: solid 1px #e5e5e5;
	background-color: #e5e5e5;
	overflow: hidden;
}

div.code-line {
	width: 92.5%;
	float: left;
	display: inline-block;
	margin: 0 0 1.42em;
	padding: 0.25em 0 1.0em;
	border: solid 1px #e5e5e5;
	border-top: none;
	background-color: #f9f9f9;
	white-space: nowrap;
	font-family: monospace;
	overflow: hidden;
}

div.code-num,
div.code-line {
	max-height: 400px;
}

div.code-main {
	margin: 5px 0;
	padding: 0;
}

div.code-main:hover .code-num {
}

div.code-main:hover .code-line {
	overflow-y: auto;
	overflow-x: scroll;
	margin: 0;
}
STYLE;
	}
	
	function close_tags_entry($items) {
		$items[] = array( 'action' => 'api_item',
			'inside_anchor' => '<small title="Close HTML Tags">&lt;/&gt;</small>',
			'data' => "function(stack){closeTags(stack);}");
		return $items;
	}

	function entry($items) {
	
		$items[] = array( 'action' => 'api_item',
						 'inside_anchor' => '<img src="' . plugins_url( '/images/bold.png', __FILE__ ) . '" title="Bold" alt="Bold" />',
						 'data' => "function(stack){insertHTML(stack, 'strong', []);}");
		$items[] = array( 'action' => 'api_item',
						 'inside_anchor' => '<img src="' . plugins_url( '/images/italic.png', __FILE__ ) . '" title="Italics" alt="Italics" />',
						 'data' => "function(stack){insertHTML(stack, 'em', []);}");
		$items[] = array( 'action' => 'api_item',
						 'inside_anchor' => '<img src="' . plugins_url( '/images/underline.png', __FILE__ ) . '" title="Underline" alt="Underline" />',
						 'data' => "function(stack){insertHTML(stack, 'span', [['style', 'text-decoration:underline;']]);}");
		$items[] = array( 'action' => 'api_item',
						 'inside_anchor' => '<img src="' . plugins_url( '/images/strikethrough.png', __FILE__ ) . '" title="Strike through" alt="Strike through" />',
						 'data' => "function(stack){insertHTML(stack, 'span', [['style', 'text-decoration:line-through;']]);}");
		if ( get_option('bbp_5o1_toolbar_use_textalign') ) {
			$items[] = array( 'action' => 'api_item',
							 'inside_anchor' => '<img src="' . plugins_url( '/images/fontleft.png', __FILE__ ) . '" title="Left Align" alt="Left Align" />',
							 'data' => "function(stack){insertHTML(stack, 'span', [['style', 'text-align:left;']]);}");
			$items[] = array( 'action' => 'api_item',
							 'inside_anchor' => '<img src="' . plugins_url( '/images/fontcenter.png', __FILE__ ) . '" title="Center Align" alt="Center Align" />',
							 'data' => "function(stack){insertHTML(stack, 'span', [['style', 'text-align:center;']]);}");
			$items[] = array( 'action' => 'api_item',
							 'inside_anchor' => '<img src="' . plugins_url( '/images/fontjustify.png', __FILE__ ) . '" title="Justified Align" alt="Justified Align" />',
							 'data' => "function(stack){insertHTML(stack, 'span', [['style', 'text-align:justify;']]);}");
			$items[] = array( 'action' => 'api_item',
							 'inside_anchor' => '<img src="' . plugins_url( '/images/fontright.png', __FILE__ ) . '" title="Right Align" alt="Right Align" />',
							 'data' => "function(stack){insertHTML(stack, 'span', [['style', 'text-align:right;']]);}");
		}
		$items[] = array( 'action' => 'api_item',
						  'inside_anchor' => '<img src="' . plugins_url( '/images/quote.png', __FILE__ ) . '" title="Quote" alt="Quote" />',
						  'data' => "function(stack){insertHTML(stack, 'blockquote', []);}");
		$items[] = array( 'action' => 'api_item',
						  'inside_anchor' => '<img src="' . plugins_url( '/images/code.png', __FILE__ ) . '" title="Code" alt="Code" />',
						  'data' => "function(stack){insertHTML(stack, 'code', []);}");
		$items[] = array( 'action' => 'switch_panel',
						 'inside_anchor' => '<img src="' . plugins_url( '/images/fontcolor.png', __FILE__ ) . '" title="Color" alt="Color" />',
						 'panel' => 'color',
						 'data' => bbp_5o1_toolbar_format::color_formatting());
		$items[] = array( 'action' => 'switch_panel',
						 'inside_anchor' => '<img src="' . plugins_url( '/images/font.png', __FILE__ ) . '" title="Size" alt="Size" />',
						 'panel' => 'size',
						 'data' => bbp_5o1_toolbar_format::size_formatting());
		$items[] = array( 'action' => 'switch_panel',
						 'inside_anchor' => '<img src="' . plugins_url( '/images/font.png', __FILE__ ) . '" title="Font Face" alt="Font" />',
						 'panel' => 'font',
						 'data' => bbp_5o1_toolbar_format::font_formatting());
		$items[] = array( 'action' => 'switch_panel',
						 'inside_anchor' => '<img src="' . plugins_url( '/images/link.png', __FILE__ ) . '" title="Link" alt="Link" />',
						 'panel' => 'links',
						 'data' => '<div style="width: 310px; display: inline-block;"><span>Link URL:</span><br />
<input style="display:inline-block;width:300px;" type="text" id="link_url" value="" /></div>
<div style="width: 310px; display: inline-block;"><span>Link Name: (optional)</span><br />
<input style="display:inline-block;width:300px;" type="text" id="link_name" value="" /></div>
<a class="toolbar-apply" style="margin-top: 1.4em;" onclick="insert_panel(\'link\');">Apply Link</a>');
		return $items;
	}
	
	function font_formatting() {
		$fonts[] = "Arial";
		$fonts[] = "'Comic Sans MS'";
		$fonts[] = "Courier";
		$fonts[] = "Georgia";
		$fonts[] = "Helvetica";
		$fonts[] = "'Times New Roman'";
		$fonts[] = "Ubuntu";
		$fonts[] = "Verdana";

		$html = '';
		foreach ($fonts as $font) {
			$html .= '<a title="' . $font . '" onclick="insert_font(\'' . addslashes($font) . '\');" style="cursor: pointer; display: inline-block; margin:0 0.5em;font-family:' . $font . '; font-size: 1.4em;">' . $font . '</a> ';
		}
		return '<div style="text-align: center;">' . $html . '</div>';
	}
	
	function colors() {
		$colors[] = 'Red';
		$colors[] = 'Green';
		$colors[] = 'Blue';
		$colors[] = 'Cyan';
		$colors[] = 'Magenta';
		$colors[] = 'Yellow';
		$colors[] = 'Black';
		$colors[] = 'White';
		$colors[] = 'Grey';
		$colors[] = 'Orange';
		$colors[] = 'Indigo';
		$colors[] = 'Violet';
		return $colors;
	}
	
	function color_formatting() {
		$colors = bbp_5o1_toolbar_format::colors();
		$html = '';
		foreach ($colors as $color) {
			$html .= '<span title="' . $color . '" onclick="insert_color(\'' . strtolower($color) . '\');" class="color-choice" style="background:' . strtolower($color) . ';"></span>';
		}
		$html .= '<span title="' . $color . '" onclick="insert_color(\'' . strtolower($color) . '\');" class="color-choice-no" style="background:' . strtolower($color) . ';"></span>';
		$chooser = '<div style="background:' . strtolower($color) . ';" class="color-chooser">' . $html . "</div>";
		return "<strong>Pick a color, any color... as long as it's black.</strong>" . $chooser;
	}
	
	function color_style() {
		$colors = bbp_5o1_toolbar_format::colors();
	 ?>
<style type="text/css">/*<![CDATA[*/
#post-toolbar .panel .color-choice,
#post-toolbar .panel .color-choice-no {
	width: <?php echo ( (1/(count($colors)+1))*100 ); ?>%;
}

#post-toolbar .panel .color-choice:hover {
	width: <?php echo 2*( (1/(count($colors)+1))*100 ); ?>%;
}

<?php echo bbp_5o1_toolbar_format::code_style(); ?>

/*]]>*/</style>
	 <?php
	}
	
	function size_formatting() {
		$sizes[] = "xx-small";
		$sizes[] = "x-small";
		$sizes[] = "small";
		$sizes[] = "medium";
		$sizes[] = "large";
		$sizes[] = "x-large";
		$sizes[] = "xx-large";
		foreach ($sizes as $size) {
			$html .= '<a class="size" onclick="insert_size(\'' . $size . '\');" style="font-size:' . $size . ';">' . $size . '</a>';
		}
		return '<div style="line-height: 50px;text-align:center;">' . $html . '</div>';
	}
	
}

?>