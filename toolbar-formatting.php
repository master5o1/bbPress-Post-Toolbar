<?php

// Add panel entry to toolbar:
add_filter( 'bbp_5o1_toolbar_add_items' , array('bbp_5o1_toolbar_formatting', 'entry'), 0 );

class bbp_5o1_toolbar_formatting {

	function entry($items) {
		$items[] = array( 'action' => 'insert_data',
						 'inside_anchor' => '<img src="' . plugins_url( '/images', __FILE__ ) . '/bold.png" title="Bold" alt="Bold" />',
						 'data' => 'strong');
		$items[] = array( 'action' => 'insert_data',
						 'inside_anchor' => '<img src="' . plugins_url( '/images', __FILE__ ) . '/italic.png" title="Italics" alt="Italics" />',
						 'data' => 'em');
		$items[] = array( 'action' => 'insert_data',
						 'inside_anchor' => '<img src="' . plugins_url( '/images', __FILE__ ) . '/underline.png" title="Underline" alt="Underline" />',
						 'data' => 'underline');
		$items[] = array( 'action' => 'insert_data',
						 'inside_anchor' => '<img src="' . plugins_url( '/images', __FILE__ ) . '/strikethrough.png" title="Strike through" alt="Strike through" />',
						 'data' => 'strike');
		if ( get_option('bbp_5o1_toolbar_use_textalign') ) {
			$items[] = array( 'action' => 'insert_data',
							 'inside_anchor' => '<img src="' . plugins_url( '/images', __FILE__ ) . '/fontleft.png" title="Left Align" alt="Left Align" />',
							 'data' => 'fontleft');
			$items[] = array( 'action' => 'insert_data',
							 'inside_anchor' => '<img src="' . plugins_url( '/images', __FILE__ ) . '/fontcenter.png" title="Center Align" alt="Center Align" />',
							 'data' => 'fontcenter');
			$items[] = array( 'action' => 'insert_data',
							 'inside_anchor' => '<img src="' . plugins_url( '/images', __FILE__ ) . '/fontjustify.png" title="Justified Align" alt="Justified Align" />',
							 'data' => 'fontjustify');
			$items[] = array( 'action' => 'insert_data',
							 'inside_anchor' => '<img src="' . plugins_url( '/images', __FILE__ ) . '/fontright.png" title="Right Align" alt="Right Align" />',
							 'data' => 'fontright');
		}
		$items[] = array( 'action' => 'insert_data',
						  'inside_anchor' => '<img src="' . plugins_url( '/images', __FILE__ ) . '/quote.png" title="Quote" alt="Quote" />',
						  'data' => 'blockquote');
		$items[] = array( 'action' => 'insert_data',
						  'inside_anchor' => '<img src="' . plugins_url( '/images', __FILE__ ) . '/code.png" title="Code" alt="Code" />',
						  'data' => 'code');
		$items[] = array( 'action' => 'switch_panel',
						 'inside_anchor' => '<img src="' . plugins_url( '/images', __FILE__ ) . '/fontcolor.png" title="Color" alt="Color" />',
						 'data' => bbp_5o1_toolbar_formatting::color_formatting());
		$items[] = array( 'action' => 'switch_panel',
						 'inside_anchor' => '<img src="' . plugins_url( '/images', __FILE__ ) . '/font.png" title="Size" alt="Size" />',
						 'data' => bbp_5o1_toolbar_formatting::size_formatting());
		$items[] = array( 'action' => 'switch_panel',
						 'inside_anchor' => '<img src="' . plugins_url( '/images', __FILE__ ) . '/link.png" title="Link" alt="Link" />',
						 'data' => '<div style="width: 310px; display: inline-block;"><span>Link URL:</span><br />
<input style="display:inline-block;width:300px;" type="text" id="link_url" value="" /></div>
<div style="width: 310px; display: inline-block;"><span>Link Name: (optional)</span><br />
<input style="display:inline-block;width:300px;" type="text" id="link_name" value="" /></div>
<a class="toolbar-apply" style="margin-top: 1.4em;" onclick="insert_panel(\'link\');">Apply Link</a>
<p style="font-size: x-small;">Hint: Paste the link URL into the <em>Link URL</em> text box, then select text and hit <a onclick="insert_panel(\'link\');">Apply Link</a> to use the selected text as the link name.</p>');
		return $items;
	}
	
	function color_formatting() {
		return '<span title="Red" onclick="insert_color(\'red\');" style="background:red;width:50px;height:50px;display:inline-block;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;cursor:pointer;"></span>
<span title="Green" onclick="insert_color(\'green\');" style="cursor:pointer;background:green;width:50px;height:50px;display:inline-block;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;cursor:pointer;"></span>
<span title="Blue" onclick="insert_color(\'blue\');" style="cursor:pointer;background:blue;width:50px;height:50px;display:inline-block;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;cursor:pointer;"></span>
<span title="Yellow" onclick="insert_color(\'yellow\');" style="cursor:pointer;background:yellow;width:50px;height:50px;display:inline-block;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;cursor:pointer;"></span>
<span title="Magenta" onclick="insert_color(\'magenta\');" style="cursor:pointer;background:magenta;width:50px;height:50px;display:inline-block;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;cursor:pointer;"></span>
<span title="Cyan" onclick="insert_color(\'cyan\');" style="cursor:pointer;background:cyan;width:50px;height:50px;display:inline-block;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;cursor:pointer;"></span>
<span title="Black" onclick="insert_color(\'black\');" style="cursor:pointer;background:black;width:50px;height:50px;display:inline-block;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;cursor:pointer;"></span>
<span title="White" onclick="insert_color(\'white\');" style="cursor:pointer;background:white;width:50px;height:50px;display:inline-block;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;cursor:pointer;"></span>
<span title="Grey" onclick="insert_color(\'grey\');" style="cursor:pointer;background:grey;width:50px;height:50px;display:inline-block;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;cursor:pointer;"></span>';
	}
	
	function size_formatting() {
		return '<div style="line-height: 50px;"><a class="size" onclick="insert_size(\'xx-small\');" style="font-size:xx-small;">xx-small</a>
<a class="size" onclick="insert_size(\'x-small\');" style="font-size:x-small;">x-small</a>
<a class="size" onclick="insert_size(\'small\');" style="font-size:small;">small</a>
<a class="size" onclick="insert_size(\'medium\');" style="font-size:medium;">medium</a>
<a class="size" onclick="insert_size(\'large\');" style="font-size:large;">large</a>
<a class="size" onclick="insert_size(\'x-large\');" style="font-size:x-large;">x-large</a>
<a class="size" onclick="insert_size(\'xx-large\');" style="font-size:xx-large;">xx-large</a></div>';
	}
	
}

?>