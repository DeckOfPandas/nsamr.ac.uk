<?php

add_shortcode('sc_fullwidth', 'scalia_fullwidth_shortcode');
add_shortcode('sc_divider', 'scalia_divider_shortcode');
add_shortcode('sc_image', 'scalia_image_shortcode');
add_shortcode('sc_icon_with_title', 'scalia_icon_with_title_shortcode');
add_shortcode('sc_textbox', 'scalia_textbox_shortcode');
add_shortcode('sc_youtube', 'scalia_youtube_shortcode');
add_shortcode('sc_vimeo', 'scalia_vimeo_shortcode');
add_shortcode('sc_dropcap', 'scalia_dropcap_shortcode');
add_shortcode('sc_quote', 'scalia_quote_shortcode');
add_shortcode('sc_video', 'scalia_video_shortcode');
add_shortcode('sc_list', 'scalia_list_shortcode');
add_shortcode('sc_table', 'scalia_table_shortcode');
add_shortcode('sc_icon_with_text', 'scalia_icon_with_text_shortcode');
add_shortcode('sc_alert_box', 'scalia_alert_box_shortcode');
add_shortcode('sc_clients', 'scalia_clients_shortcode');
add_shortcode('sc_diagram', 'scalia_diagram_shortcode');
add_shortcode('sc_skill', 'scalia_skill_shortcode');
add_shortcode('sc_gallery', 'scalia_gallery_shortcode');
add_shortcode('sc_news', 'scalia_news_shortcode');
add_shortcode('sc_quickfinder', 'scalia_quickfinder_shortcode');
add_shortcode('sc_team', 'scalia_team_shortcode');
add_shortcode('sc_pricing_table', 'scalia_pricing_table_shortcode');
add_shortcode('sc_pricing_column', 'scalia_pricing_column_shortcode');
add_shortcode('sc_pricing_price', 'scalia_pricing_price_shortcode');
add_shortcode('sc_pricing_row', 'scalia_pricing_row_shortcode');
add_shortcode('sc_pricing_footer', 'scalia_pricing_footer_shortcode');
add_shortcode('sc_icon', 'scalia_icon_shortcode');
add_shortcode('sc_button', 'scalia_button_shortcode');
add_shortcode('sc_testimonials', 'scalia_testimonials_shortcode');
add_shortcode('sc_map_with_text', 'scalia_map_with_text_shortcode');
add_shortcode('sc_counter', 'scalia_counter_shortcode');
add_shortcode('sc_counter_box', 'scalia_counter_box_shortcode');
add_shortcode('sc_portfolio_slider', 'scalia_portfolio_slider_shortcode');
add_shortcode('sc_portfolio', 'scalia_portfolio_shortcode');
add_shortcode('sc_link', 'scalia_link');

function scalia_fullwidth_shortcode($atts, $content) {
	extract(shortcode_atts(array(
		'color' => '',
		'background_color' => '',
		'background_image' => '',
		'background_style' => '',
		'background_position_horizontal' => 'center',
		'background_position_vertical' => 'top',
		'background_parallax' => '',
		'video_background_type' => '',
		'video_background_src' => '',
		'video_background_acpect_ratio' => '',
		'video_background_overlay_color' => '',
		'video_background_overlay_opacity' => '',
		'padding_top' => '',
		'padding_bottom' => '',
		'padding_left' => '',
		'padding_right' => '',
		'container' => '',
		'styled_marker' => '',
	), $atts, 'sc_fullwidth'));
	$styled_marker = scalia_check_array_value(array('', 'top', 'bottom'), $styled_marker, '');
	$css_style = '';
	if($color) {
		$css_style .= 'color: '.$color.';';
	}
	if($background_color) {
		$css_style .= 'background-color: '.$background_color.';';
	}
	if($background_image = scalia_attachment_url($background_image)) {
		$css_style .= 'background-image: url('.$background_image.');';
	}
	if($background_style == 'cover') {
		$css_style .= 'background-repeat: no-repeat; background-size: cover;';
	}
	if($background_style == 'contain') {
		$css_style .= 'background-repeat: no-repeat; background-size: contain;';
	}
	if($background_style == 'repeat') {
		$css_style .= 'background-repeat: repeat;';
	}
	if($background_style == 'no-repeat') {
		$css_style .= 'background-repeat: no-repeat;';
	}
	$css_style .= 'background-position: '.$background_position_horizontal.' '.$background_position_vertical.';';
	if($background_parallax) {
		$css_style .= 'background-attachment: fixed;';
	}
	$video = scalia_video_background($video_background_type, $video_background_src, $video_background_acpect_ratio, false, $video_background_overlay_color, $video_background_overlay_opacity);
	if($padding_top) {
		$css_style .= 'padding-top: '.$padding_top.'px;';
	}
	if($padding_bottom) {
		$css_style .= 'padding-bottom: '.$padding_bottom.'px;';
	}
	if($padding_left) {
		$css_style .= 'padding-left: '.$padding_left.'px;';
	}
	if($padding_right) {
		$css_style .= 'padding-right: '.$padding_right.'px;';
	}
	$return_html = '<div class="fullwidth-block clearfix'.($styled_marker ? ' styled-marker-'.$styled_marker : '').'" style="'.$css_style.'">'.$video.($container ? '<div class="container">' : '').do_shortcode($content).($container ? '</div>' : '').'</div>';
	return $return_html;
}

function scalia_divider_shortcode($atts) {
	extract(shortcode_atts(array(
		'style' => '',
		'color' => '',
		'margin_top' => '',
		'margin_bottom' => '',
		'fullwidth' => ''
	), $atts, 'sc_divider'));
	$css_style = '';
	if($margin_top) {
		$css_style .= 'margin-top: '.$margin_top.'px;';
	}
	if($margin_bottom) {
		$css_style .= 'margin-bottom: '.$margin_bottom.'px;';
	}
	if($color) {
		$css_style .= 'border-color: '.$color.';';
	}
	$svg = '';
	if($style == 1) {
		$svg = '<svg width="100%" height="1px"><line x1="0" x2="100%" y1="0" y2="0" stroke="'.$color.'" stroke-width="2" stroke-linecap="black" stroke-dasharray="11, 9"/></svg>';
	}
	if($style == 4) {
		$svg = '<svg width="100%" height="8px"><line x1="4" x2="100%" y1="4" y2="4" stroke="'.$color.'" stroke-width="7" stroke-linecap="round" stroke-dasharray="1, 13"/></svg>';
	}
	if($style == 5) {
		$svg = '<svg width="100%" height="6px"><line x1="3" x2="100%" y1="3" y2="3" stroke="'.$color.'" stroke-width="6" stroke-linecap="square" stroke-dasharray="1, 13"/></svg>';
	}
	$return_html = '<div class="clearboth"></div><div class="sc-divider'.($style ? ' sc-divider-style-'.$style : '').($fullwidth ? ' fullwidth-block' : '').'" style="'.$css_style.'">'.$svg.'</div>';
	return $return_html;
}

function scalia_image_shortcode($atts) {
	extract(shortcode_atts(array(
		'width' => '',
		'height' => '',
		'src' => '',
		'alt' => '',
		'style' => 'default',
		'position' => 'below',
		'disable_lightbox'=>''
	), $atts, 'sc_image'));
	$css_style = '';
	$classes = $style;
	if($style != 11 && $style != 12) {
		$classes .= ' rounded-corners';
	}
	if(in_array($style, array(1, 5, 7))) {
		$classes .= ' shadow-box';
	}
	if(substr($width, -1) != "%") {
		$width = intval($width).'px';
	}
	if(substr($height, -1) != "%") {
		$height = intval($height).'px';
	}
	if($width && $width > 0) {
		$css_style .= 'width: '.$width.';';
	}
	if($height && $height > 0) {
		$css_style .= 'height: '.$height.';';
	}
	if($style == '11') {
		$height = $width;
	}
	$return_html = '<div class="sc-image sc-wrapbox sc-wrapbox-style-'.$classes.($position ? ' sc-wrapbox-position-'.$position : '').'" style="'.$css_style.'">'.
		'<div class="sc-wrapbox-inner">'.
		($style == '12' ? '<div class="shadow-wrap">' : '').
		(!$disable_lightbox ? '<a href="'.scalia_attachment_url($src).'" class="fancybox">' : '').
		'<img class="sc-wrapbox-element img-responsive'.($style == '11' ? ' img-circle' : '').'" src="'.scalia_attachment_url($src).'" alt="'.$alt.'"/>'.
		(!$disable_lightbox ? '</a>' : '').
		($style == '12' ? '</div>' : '').
		'</div>'.
		'</div>';
	if($position == 'centered') {
		$return_html = '<div class="centered-box sc-image-centered-box">'.$return_html.'</div>';
	}
	return $return_html;
}

function scalia_aspect_ratio_to_percents($aspect_ratio) {
	if($aspect_ratio) {
		$aspect_ratio = explode(':', $aspect_ratio);
		if(count($aspect_ratio) > 1 && intval($aspect_ratio[0]) > 0 && intval($aspect_ratio[1]) > 0) {
			return round(intval($aspect_ratio[1])/intval($aspect_ratio[0]), 4)*100;
		}
	}
	return 0;
}

function scalia_youtube_shortcode($atts) {
	extract(shortcode_atts(array(
		'width' => '100%',
		'height' => '300',
		'video_id' => '',
		'style' => 'default',
		'position' => 'below',
		'aspect_ratio' => ''
	), $atts, 'sc_youtube'));
	$css_style = '';
	$classes = $style;
	if($style != 11 && $style != 12) {
		$classes .= ' rounded-corners';
	}
	if(in_array($style, array(1, 5, 7))) {
		$classes .= ' shadow-box';
	}
	if(substr($width, -1) != "%") {
		$width = intval($width).'px';
	}
	if(substr($height, -1) != "%") {
		$height = intval($height).'px';
	}
	if($width && $width > 0) {
		$css_style .= 'width: '.$width.';';
	}
	$ratio_style = '';
	if($aspect_percents = scalia_aspect_ratio_to_percents($aspect_ratio)) {
		$ratio_style = 'padding-top: '.$aspect_percents.'%';
	} else {
		if($height && $height > 0) {
			$css_style .= 'height: '.$height.';';
		}
	}
	$return_html = '<div class="sc-youtube sc-wrapbox sc-wrapbox-style-'.$classes.($position ? ' sc-wrapbox-position-'.$position : '').'" style="'.$css_style.'">'.
		'<div class="sc-wrapbox-inner'.($ratio_style ? ' sc-ratio-style' : '').'"'.($ratio_style ? ' style="'.$ratio_style.'"' : '').'>'.
		($style == '12' ? '<div class="shadow-wrap">' : '').
		'<iframe class="sc-wrapbox-element img-responsive" width="'.$width.'" height="'.intval($height).'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="//www.youtube.com/embed/'.$video_id.'?rel=0&amp;wmode=opaque"></iframe>'.
		($style == '12' ? '</div>' : '').
		'</div>'.
		'</div>';
	return $return_html;
}

function scalia_vimeo_shortcode($atts) {
	extract(shortcode_atts(array(
		'width' => '100%',
		'height' => '300',
		'video_id' => '',
		'style' => 'default',
		'position' => 'below',
		'aspect_ratio' => ''
	), $atts, 'sc_vimeo'));
	$css_style = '';
	$classes = $style;
	if($style != 11 && $style != 12) {
		$classes .= ' rounded-corners';
	}
	if(in_array($style, array(1, 5, 7))) {
		$classes .= ' shadow-box';
	}
	if(substr($width, -1) != "%") {
		$width = intval($width).'px';
	}
	if(substr($height, -1) != "%") {
		$height = intval($height).'px';
	}
	if($width && $width > 0) {
		$css_style .= 'width: '.$width.';';
	}
	$ratio_style = '';
	if($aspect_percents = scalia_aspect_ratio_to_percents($aspect_ratio)) {
		$ratio_style = 'padding-top: '.$aspect_percents.'%';
	} else {
		if($height && $height > 0) {
			$css_style .= 'height: '.$height.';';
		}
	}
	$return_html = '<div class="sc-vimeo sc-wrapbox sc-wrapbox-style-'.$classes.($position ? ' sc-wrapbox-position-'.$position : '').'" style="'.$css_style.'">'.
		'<div class="sc-wrapbox-inner'.($ratio_style ? ' sc-ratio-style' : '').'"'.($ratio_style ? ' style="'.$ratio_style.'"' : '').'>'.
		($style == '12' ? '<div class="shadow-wrap">' : '').
		'<iframe class="sc-wrapbox-element img-responsive" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="//player.vimeo.com/video/'.$video_id.'?title=0&amp;byline=0&amp;portrait=0"></iframe>'.
		($style == '12' ? '</div>' : '').
		'</div>'.
		'</div>';
	return $return_html;
}

function scalia_video_shortcode($atts) {
	extract(shortcode_atts(array(
		'width' => '100%',
		'height' => '300',
		'video_src' => '',
		'image_src' => '',
		'style' => 'default',
		'position' => 'below',
		'aspect_ratio' => ''
	), $atts, 'sc_video'));
	$css_style = '';
	$classes = $style;
	if($style != 11 && $style != 12) {
		$classes .= ' rounded-corners';
	}
	if(in_array($style, array(1, 5, 7))) {
		$classes .= ' shadow-box';
	}
	if(substr($width, -1) != "%") {
		$width = intval($width).'px';
	}
	if(substr($height, -1) != "%") {
		$height = intval($height).'px';
	}
	if($width && $width > 0) {
		$css_style .= 'width: '.$width.';';
	}
	$ratio_style = '';
	if($aspect_percents = scalia_aspect_ratio_to_percents($aspect_ratio)) {
		$ratio_style = 'padding-top: '.$aspect_percents.'%';
	} else {
		if($height && $height > 0) {
			$css_style .= 'height: '.$height.';';
		}
	}
	$return_html = '<div class="sc-video sc-wrapbox sc-wrapbox-style-'.$classes.($position ? ' sc-wrapbox-position-'.$position : '').'" style="'.$css_style.'">'.
		'<div class="sc-wrapbox-inner'.($ratio_style ? ' sc-ratio-style' : '').'"'.($ratio_style ? ' style="'.$ratio_style.'"' : '').'>'.
		($style == '12' ? '<div class="shadow-wrap">' : '').
		scalia_selfvideo($video_src, scalia_attachment_url($image_src), '100%', '100%', 'sc-wrapbox-element img-responsive').
		($style == '12' ? '</div>' : '').
		'</div>'.
		'</div>';
	return $return_html;
}

function scalia_textbox_shortcode($atts, $content) {
	extract(shortcode_atts(array(
		'title' => '',
		'icon' => '',
		'title_background_color' => '',
		'title_text_color' => '',
		'content_background_color' => '',
		'content_background_image' => '',
		'content_background_style' => '',
		'content_background_position_horizontal' => 'center',
		'content_background_position_vertical' => 'top',
		'content_text_color' => '',
		'border_color' => '',
		'shadow' => '',
		'centered' => '',
		'no_rounded' => '',
		'effects_enabled' => false
	), $atts, 'sc_textbox'));
	$return_html = '';
	$css_style = '';
	if($title) {
		if($title_background_color) {
			$css_style .= 'background-color: '.$title_background_color.';';
		}
		if($title_text_color) {
			$css_style .= 'color: '.$title_text_color.';';
		}
		if($border_color) {
			$css_style .= 'border-bottom: 1px solid '.$border_color.';';
		}
		$return_html .= '<h5 class="sc-textbox-title" style="'.$css_style.'">'.($icon ? '<span class="sc-textbox-title-icon">&#x'.$icon.';</span>' : '').$title.'</h5>';
	}

	$css_style = '';
	if($content_background_color) {
		$css_style .= 'background-color: '.$content_background_color.';';
	}
	if($content_background_image = scalia_attachment_url($content_background_image)) {
		$css_style .= 'background-image: url('.$content_background_image.');';
	}
	if($content_background_style == 'cover') {
		$css_style .= 'background-repeat: no-repeat; background-size: cover;';
	}
	if($content_background_style == 'contain') {
		$css_style .= 'background-repeat: no-repeat; background-size: contain;';
	}
	if($content_background_style == 'repeat') {
		$css_style .= 'background-repeat: repeat;';
	}
	if($content_background_style == 'no-repeat') {
		$css_style .= 'background-repeat: no-repeat;';
	}
	$css_style .= 'background-position: '.$content_background_position_horizontal.' '.$content_background_position_vertical.';';
	if($content_text_color) {
		$css_style .= 'color: '.$content_text_color.';';
	}
	$return_html .= '<div class="sc-textbox-content" style="'.$css_style.'">'.do_shortcode($content).'</div>';

	$css_style = '';
	if($border_color) {
		$css_style .= 'border: 1px solid '.$border_color.';';
	}
	$return_html = ($effects_enabled ? '<div class="lazy-loading" data-ll-item-delay="0">' : '').'<div class="sc-textbox rounded-corners'.($effects_enabled ? ' lazy-loading-item' : '').($no_rounded ? ' sc-textbox-no-rounded' : '').($shadow ? ' shadow-box' : '').($centered ? ' centered-box' : '').'" '.($effects_enabled ? ' data-ll-effect="fading"' : '').' style="'.$css_style.'">'.$return_html.'</div>'.($effects_enabled ? '</div>' : '');

	return $return_html;
}

function scalia_quote_shortcode($atts, $content) {
	$return_html = '<blockquote><p>'.do_shortcode($content).'</p></blockquote>';
	return $return_html;
}

function scalia_list_shortcode($atts, $content) {
	extract(shortcode_atts(array(
		'type' => '',
		'color' => '',
		'effects_enabled' => false
	), $atts, 'sc_list'));
	$return_html = '<div class="sc-list' . ($effects_enabled ? ' lazy-loading' : '') .($type ? ' sc-list-type-'.$type : '').($color ? ' sc-list-color-'.$color : '').'">'.do_shortcode($content).'</div>';
	return $return_html;
}

function scalia_table_shortcode($atts, $content) {
	extract(shortcode_atts(array(
		'style' => '1',
		'row_headers' => ''
	), $atts, 'sc_table'));
	$return_html = '<div class="sc-table rounded-corners sc-table-responsive shadow-box'.($style ? ' sc-table-style-'.$style : '').($row_headers ? ' row-headers' : '').'">'.do_shortcode($content).'</div>';
	return $return_html;
}

function scalia_quickfinder_shortcode($atts) {
	extract(shortcode_atts(array(
		'quickfinder' => '',
		'style' => '',
		'connector_color' => '',
		'effects_enabled' => ''
	), $atts, 'sc_quickfinder'));
	ob_start();
	scalia_quickfinder(array('quickfinder' => $quickfinder, 'style' => $style, 'connector_color' => $connector_color, 'effects_enabled' => $effects_enabled));
	$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	return $return_html;
}

function scalia_team_shortcode($atts) {
	extract(shortcode_atts(array(
		'team' => '',
		'style' => 'horizontal',
		'columns' => '3'
	), $atts, 'sc_team'));
	$style = scalia_check_array_value(array('horizontal', 'vertical', 'rounded'), $style, 'horizontal');
	if($style == 'horizontal') {
		$columns = scalia_check_array_value(array('1', '2', '3', '4'), $columns, '2');
	} else {
		$columns = scalia_check_array_value(array('1', '2', '3', '4'), $columns, '3');
	}
	ob_start();
	scalia_team(array('team' => $team, 'style' => $style, 'columns' => $columns));
	$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	return $return_html;
}

function scalia_gallery_shortcode($atts) {
	extract(shortcode_atts(array(
		'gallery_gallery' => '',
		'gallery_type' => 'slider',
		'gallery_slider_layout' => 'fullwidth',
		'gallery_layout' => '2x',
		'gallery_style' => 'justified',
		'gallery_no_gaps' => '',
		'gallery_hover' => 'default',
		'gallery_item_style' => '',
		'gallery_title' => '',
	), $atts, 'sc_gallery'));
	$styled_marker = scalia_check_array_value(array('default', 'zooming-blur'), $gallery_hover, 'default');
	$styled_marker = scalia_check_array_value(array('fullwidth', 'sidebar'), $gallery_slider_layout, 'sidebar');
	ob_start();
	if($gallery_type == 'slider') {
		scalia_gallery(array(
			'gallery' => $gallery_gallery,
			'hover' => $gallery_hover,
			'layout' => $gallery_slider_layout,
		));
	} else {
		scalia_gallery_block(array(
			'gallery' => $gallery_gallery,
			'type' => $gallery_type,
			'layout' => $gallery_layout,
			'style' => $gallery_style,
			'no_gaps' => $gallery_no_gaps,
			'hover' => $gallery_hover,
			'item_style' => $gallery_item_style,
			'title' => $gallery_title
		));
	}
	$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	return $return_html;
}

function scalia_pricing_table_shortcode($atts, $content) {
	extract(shortcode_atts(array(
		'style' => '1',
		'button_icon' => 'default',
	), $atts, 'sc_pricing_table'));
	$style = scalia_check_array_value(array('1', '2', '3'), $style, '1');;
	$return_html = '<div class="pricing-table row inline-row inline-row-center pricing-table-style-'.$style.' button-icon-'.$button_icon.'">';
	$return_html.= do_shortcode($content);
	$return_html.= '</div>';
	return $return_html;
}

function scalia_pricing_column_shortcode($atts, $content) {
	extract(shortcode_atts(array(
		'title' => 'Column title',
		'subtitle' => 'Column subtitle',
		'highlighted' => '0',
		'top_choice' => '',
		'label_top_corner' => 0,
	), $atts, 'sc_pricing_column'));
	$return_html = '<div class="pricing-column-wrapper col-md-3 col-sm-4 col-xs-6 inline-column'.($highlighted == '1' ? ' highlighted' : '').'"><div class="pricing-column'.($label_top_corner == '1' ? ' label-top-corner' : '').'"><div class="pricing-title-wrapper"><div class="pricing-title"><div class="title-h3">'.$title.'</div>'.($highlighted && $subtitle != '' ? '<div class="subtitle">'.$subtitle.'</div>' : '').'</div>'.($top_choice ? '<div class="pricing-column-top-choice"><div class="pricing-column-top-choice-text">'.$top_choice.'</div></div>' : '').'</div>';
	$return_html.= do_shortcode($content);
	$return_html.= '</div></div>';
	return $return_html;
}

function scalia_pricing_price_shortcode($atts) {
	extract(shortcode_atts(array(
		'currency' => '',
		'price' => '',
		'time' => '',
		'font_size' => '57',
		'color' => '',
		'background' => ''
	), $atts, 'sc_pricing_price'));
	$background = scalia_attachment_url($background);
	$return_html = '<div class="pricing-price-row'.($background ? ' pricing-price-row-background' : '').'" '.($background ? ' style="background-image: url('.$background.');"' : '').'><div class="pricing-price" style="'.($font_size != '' ? 'font-size: '.$font_size.'px;' : '').($color != '' ? 'color: '.$color.';' : '').'">'.'<div class="pricing-cost">'.$currency.$price.'</div>'.($time != '' ? '<span class="time">'.$time.'</span>' : '').'</div></div>';
	return $return_html;
}

function scalia_pricing_row_shortcode($atts, $content) {
	extract(shortcode_atts(array(
		'strike' => '',
	), $atts, 'sc_pricing_row'));
	$return_html = '<div class="pricing-row '.($strike == '1' ? ' strike' : '').'">'.$content.'</div>';
	return $return_html;
}

function scalia_pricing_footer_shortcode($atts, $content) {
	extract(shortcode_atts(array(
		'href' => '#',
	), $atts, 'sc_pricing_footer'));
	$return_html = '<div class="pricing-footer"><a href="'.$href.'" class="sc-button">'.$content.'</a></div>';
	return $return_html;
}

function scalia_icon_shortcode($atts) {
	extract(shortcode_atts(array(
		'icon' => '',
		'shape' => 'square',
		'style' => '',
		'color' => '',
		'color_2' => '',
		'background_color' => '',
		'border_color' => '',
		'size' => 'small',
		'link' => '',
		'link_target' => '_self',
		'centered' => ''
	), $atts, 'sc_icon'));
	$shape = scalia_check_array_value(array('circle', 'square'), $shape, 'circle');
	$style = scalia_check_array_value(array('', 'angle-45deg-r', 'angle-45deg-l', 'angle-90deg'), $style, '');
	$size = scalia_check_array_value(array('small', 'medium', 'big'), $size, 'small');
	$link = esc_url($link);
	$link_target = scalia_check_array_value(array('_self', '_blank'), $link_target, '_self');
	$css_style_icon = '';
	$css_style_icon_1 = '';
	$css_style_icon_2 = '';
	if($background_color) {
		$css_style_icon .= 'background-color: '.$background_color.';';
		if(!$border_color) {
			$css_style_icon .= 'border: 2px solid '.$background_color.';';
		}
	}
	if($border_color) {
		$css_style_icon .= 'border: 2px solid '.$border_color.';';
	}
	$simple_icon = '';
	if(!($background_color || $border_color)) {
		$simple_icon = ' sc-simple-icon';
	}
	if($color = $color) {
		$css_style_icon_1 = 'color: '.$color.';';
		if(($color_2 = $color_2) && $style) {
			$css_style_icon_2 = 'color: '.$color_2.';';
		} else {
			$css_style_icon_2 = 'color: '.$color.';';
		}
	}
	$return_html = '<div class="sc-icon sc-icon-size-'.$size.' '.$style.' sc-icon-shape-'.$shape.$simple_icon.'" style="'.$css_style_icon.'">'.
		($link ? '<a href="'.$link.'" target="'.$link_target.'">' : '').
		'<span class="sc-icon-half-1" style="'.$css_style_icon_1.'"><span class="back-angle">&#x'.$icon.';</span></span>'.
		'<span class="sc-icon-half-2" style="'.$css_style_icon_2.'"><span class="back-angle">&#x'.$icon.';</span></span>'.
		($link ? '</a>' : '').
		'</div>';
	return ($centered ? '<div class="centered-box">' : '').$return_html.($centered ? '</div>' : '');
}

function scalia_build_icon_shortcode($atts) {
	extract(shortcode_atts(array(
		'icon' => '',
		'icon_shape' => 'square',
		'icon_style' => '',
		'icon_color' => '',
		'icon_color_2' => '',
		'icon_background_color' => '',
		'icon_border_color' => '',
		'icon_size' => 'small',
		'icon_link' => '',
		'icon_link_target' => '_self'
	), $atts, 'sc_icon'));
	$icon_shortcode = '[sc_icon icon="'.$icon.
		'" shape="'.$icon_shape.
		'" style="'.$icon_style.
		'" color="'.$icon_color.
		'" color_2="'.$icon_color_2.
		'" background_color="'.$icon_background_color.
		'" border_color="'.$icon_border_color.
		'" size="'.$icon_size.
		'" link="'.$icon_link.
		'" link_target="'.$icon_link_target.'"]';
	return $icon_shortcode;
}

function scalia_build_icon_with_title_shortcode($atts) {
	extract(shortcode_atts(array(
		'icon' => '',
		'icon_shape' => 'square',
		'icon_style' => '',
		'icon_color' => '',
		'icon_color_2' => '',
		'icon_background_color' => '',
		'icon_border_color' => '',
		'icon_size' => 'small',
		'title' => '',
		'level' => 'h5',
		'title_color' => '',
	), $atts, 'sc_icon'));
	$icon_shortcode = '[sc_icon_with_title icon="'.$icon.
		'" icon_shape="'.$icon_shape.
		'" icon_style="'.$icon_style.
		'" icon_color="'.$icon_color.
		'" icon_color_2="'.$icon_color_2.
		'" icon_background_color="'.$icon_background_color.
		'" icon_border_color="'.$icon_border_color.
		'" icon_size="'.$icon_size.
		'" title="'.$title.
		'" title_color="'.$title_color.
		'" level="'.$level.'"]';
	return $icon_shortcode;
}

function scalia_icon_with_title_shortcode($atts) {
	extract(shortcode_atts(array(
		'icon' => '',
		'icon_shape' => 'square',
		'icon_style' => '',
		'icon_color' => '',
		'icon_color_2' => '',
		'icon_background_color' => '',
		'icon_border_color' => '',
		'icon_size' => 'small',
		'title' => '',
		'level' => 'h1',
		'title_color' => '',
	), $atts, 'sc_icon_with_title'));
	$level = scalia_check_array_value(array('h1','h2','h3','h4','h5','h6'), $level, 'h5');
	$icon_size = scalia_check_array_value(array('small', 'medium', 'big'), $icon_size, 'small');
	$css_style = '';
	if($title_color) {
		$css_style = 'color: '.$title_color.';';
	}
	$return_html = '<div class="sc-icon-with-title-icon">'.do_shortcode(scalia_build_icon_shortcode($atts)).'</div>';
	$return_html .= '<div class="sc-iconed-title"><'.$level.' style="'.$css_style.'">'.$title.'</'.$level.'></div>';
	$return_html = '<div class="sc-icon-with-title sc-icon-with-title-icon-size-'.$icon_size.'">'.$return_html.'</div>';
	return $return_html;
}

function scalia_icon_with_text_shortcode($atts, $content) {
	extract(shortcode_atts(array(
		'icon' => '',
		'icon_shape' => 'square',
		'icon_style' => '',
		'icon_color' => '',
		'icon_color_2' => '',
		'icon_background_color' => '',
		'icon_border_color' => '',
		'icon_size' => 'small',
		'title' => '',
		'level' => 'h1',
		'flow' => '',
		'centered' => '',
		'title_color' => '',
	), $atts, 'sc_icon_with_text'));
	$icon_size = scalia_check_array_value(array('small', 'medium', 'big'), $icon_size, 'small');
	$level = scalia_check_array_value(array('h1','h2','h3','h4','h5','h6'), $level, 'h5');
	if($title) {
		$return_html = do_shortcode(scalia_build_icon_with_title_shortcode($atts));
	} else {
		$return_html = '<div class="sc-icon-with-text-icon">'.do_shortcode(scalia_build_icon_shortcode($atts)).'</div>';
	}
	$return_html .= '<div class="sc-icon-with-text-content">';
	if($title) {
		$return_html .= '<div class="sc-icon-with-text-empty"></div>';
	}
	$return_html .= '<div class="sc-icon-with-text-text">'.do_shortcode($content).'</div></div>';
	$return_html = '<div class="sc-icon-with-text sc-icon-with-text-icon-size-'.$icon_size.($centered ? ' centered-box' : '').($flow ? ' sc-icon-with-text-flow' : '').'">'.$return_html.'<div class="clearboth"></div></div>';
	return $return_html;
}

function scalia_alert_box_shortcode($atts, $content) {
	extract(shortcode_atts(array(
		'icon' => '',
		'image' => '',
		'icon_shape' => 'square',
		'icon_style' => '',
		'icon_color' => '',
		'icon_color_2' => '',
		'icon_background_color' => '',
		'icon_border_color' => '',
		'icon_size' => 'small',
		'button_link' => '#',
		'button_text' => __('More', 'scalia'),
		'button_title' => '',
		'button_link_target' => '_self',
		'button_color' => '',
		'button_background_color' => '',
		'button_2_link' => '#',
		'button_2_text' => '',
		'button_2_title' => '',
		'button_2_link_target' => '_self',
		'button_2_color' => '',
		'button_2_background_color' => '',
		'background_color' => '',
		'background_image' => '',
		'border_color' => '',
		'shadow' => '',
		'centered' => '',
	), $atts, 'sc_alert_box'));
	$return_html = '';
	if($image = scalia_attachment_url($image)) {
		$return_html = '<div class="sc-alert-box-image"><img src="'.$image.'" alt=""'.($icon_shape == 'circle' ? 'class="img-circle"' : '').'></div>';
		$icon_size = 'big';
	} elseif($icon) {
		$return_html = '<div class="sc-icon-with-text-icon">'.do_shortcode(scalia_build_icon_shortcode($atts)).'</div>';
	}
	$css_style = '';
	if($background_color) {
		$css_style .= 'background-color: '.$background_color.';';
	}
	$return_html .= '<div class="sc-icon-with-text-content">'.do_shortcode($content).'</div>';
	$return_html .= '<div class="sc-alert-box-button">'.do_shortcode('[sc_button link="'.$button_link.'" text="'.$button_text.'" title="'.$button_title.'" link_target="'.$button_link_target.'" color="'.$button_color.'" background_color="'.$button_background_color.'" ]');
	if($button_2_text) {
		$return_html .= do_shortcode('[sc_button link="'.$button_2_link.'" text="'.$button_2_text.'" title="'.$button_2_title.'" link_target="'.$button_2_link_target.'" color="'.$button_2_color.'" background_color="'.$button_2_background_color.'" ]');
	}
	$return_html .= '</div>';
	$return_html = '<div class="sc-icon-with-text sc-alert-box clearfix sc-icon-with-text-icon-size-'.$icon_size.($centered ? ' centered-box' : '').'" style="'.$css_style.'">'.$return_html.'<div class="clearboth"></div></div>';
	if($background_color || $background_image || $border_color || $shadow) {
		$return_html = do_shortcode('[sc_textbox content_background_color="'.$background_color.'" content_background_image="'.$background_image.'" border_color="'.$border_color.'" shadow="'.$shadow.'"]'.$return_html.'[/sc_textbox]');
	}
	return $return_html;

}

function scalia_button_shortcode($atts) {
	extract(shortcode_atts(array(
		'link' => '#',
		'text' => __('More', 'scalia'),
		'title' => '',
		'link_target' => '_self',
		'color' => '',
		'background_color' => '',
		'centered' => '',
		'separator_style' => '',
		'effects_enabled' => false
	), $atts, 'sc_button'));
	$link = esc_url($link);
	$link_target = scalia_check_array_value(array('_self', '_blank'), $link_target, '_self');
	$separator_style = scalia_check_array_value(array('', 'double', 'dotted'), $separator_style, '');
	$css_style = '';
	if($color) {
		$css_style .= 'color: '.$color.';';
	}
	if($background_color) {
		$css_style .= 'background-color: '.$background_color.';';
	}
	$return_html = ($centered ? '<div class="centered-box">' : '').($effects_enabled ? '<div class="lazy-loading">' : '').'<a class="sc-button '.($effects_enabled ? 'lazy-loading-item' : '').'" '.($effects_enabled ? 'data-ll-effect="drop-right-without-wrap"' : '').' href="'.$link.'" target="'.$link_target.'" style="'.$css_style.'">'.$text.'</a>'.($effects_enabled ? '</div>' : '').($centered ? '</div>' : '');
	if($separator_style) {
		$sep_color = $background_color ? $background_color : scalia_get_option('button_background_basic_color');
		if($separator_style == 'double') {
			$sep = '<div class="sc-button-sep-holder"><div class="sc-button-separator sc-button-separator-'.$separator_style.'" style="border-color: '.$sep_color.';"></div></div>';
		} else {
			$sep = '<div class="sc-button-sep-holder"><svg width="100%" height="8px"><line x1="4" x2="100%" y1="4" y2="4" stroke="'.$sep_color.'" stroke-width="7" stroke-linecap="round" stroke-dasharray="1, 15"/></svg></div>';
		}
		$return_html = '<div class="sc-button-with-separator">'.$sep.'<div class="sc-button-sep-button">'.$return_html.'</div>'.$sep.'</div>';
	}
	return $return_html;
}

function scalia_dropcap_shortcode($atts) {
	extract(shortcode_atts(array(
		'letter' => '',
		'shape' => 'square',
		'color' => '',
		'style' => 'medium',
		'background_color' => '',
		'border_color' => '',
	), $atts, 'sc_dropcap'));
	$shape = scalia_check_array_value(array('circle', 'square'), $shape, 'circle');
	$style = scalia_check_array_value(array('medium', 'big'), $style, 'medium');
	$css_style = '';
	if($color) {
		$css_style .= 'color: '.$color.';';
	}
	if($background_color) {
		$css_style .= 'background-color: '.$background_color.';';
	}
	if($border_color) {
		$css_style .= 'border: 2px solid '.$border_color.';';
	}
	$letter = function_exists('mb_substr') ? mb_substr($letter,0,1) : substr($letter,0,1);
	$return_html = '<div class="sc-dropcap sc-dropcap-shape-'.$shape.' sc-dropcap-style-'.$style.'"><span class="sc-dropcap-letter" style="'.$css_style.'">'.$letter.'</span></div>';
	return $return_html;
}

function scalia_counter_box_shortcode($atts, $content) {
	extract(shortcode_atts(array(
		'effects_enabled' => false
	), $atts, 'sc_counter_box'));
	wp_enqueue_style('scalia-odometr');
	return '<div class="sc-counter-box row inline-row inline-row-center ' . ($effects_enabled ? 'lazy-loading' : '') . '" ' . ($effects_enabled ? 'data-ll-item-delay="0"' : '') . '>'.do_shortcode($content).'</div>';
}

function scalia_counter_shortcode($atts) {
	extract(shortcode_atts(array(
		'from' => '0',
		'to' => '100',
		'text' => '',
		'icon' => '',
		'icon_shape' => 'square',
		'icon_style' => '',
		'icon_color' => '',
		'icon_color_2' => '',
		'icon_background_color' => '',
		'icon_border_color' => '',
		'icon_size' => 'small',
		'suffix' => ''
	), $atts, 'sc_counter'));
	$return_html = '';
	if($icon) {
		$return_html .= '<div class="sc-counter-icon">'.do_shortcode(scalia_build_icon_shortcode($atts)).'</div>';
	}
	$return_html .= '<div class="sc-counter-number"><div class="sc-counter-odometer" data-to="'.$to.'">'.$from.'</div>'.($suffix ? '<span>'.$suffix.'</span>' : '').'</div>';
	if($text) {
		$return_html .= '<div class="sc-counter-text">'.$text.'</div>';
	}
	return '<div class="sc-counter col-md-3 col-sm-4 col-xs-6 inline-column">'.$return_html.'</div>';
}

function scalia_news_shortcode($atts) {
	extract(shortcode_atts(array(
		'post_types' => '',
		'style' => 'default',
		'categories' => '',
		'post_per_page' => '',
		'post_pagination' => 'normal',
		'ignore_sticky' => '',
		'effects_enabled' => false
	), $atts, 'sc_news'));
	ob_start();
	scalia_blog(array(
		'blog_post_types' => explode(',', $post_types),
		'blog_style' => $style,
		'blog_categories' => $categories,
		'blog_post_per_page' => $post_per_page,
		'blog_pagination' => $post_pagination,
		'blog_ignore_sticky' => $ignore_sticky,
		'effects_enabled' => $effects_enabled
	));
	$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	return $return_html;
}

function scalia_clients_shortcode($atts) {
	extract(shortcode_atts(array(
		'set' => '',
		'style' => 'grid',
		'rows' => '3',
		'cols' => '3',
		'autoscroll' => '',
		'fullwidth' => '',
		'effects_enabled' => false
	), $atts, 'sc_clients'));
	ob_start();
	if($style == 'carousel') {
		scalia_clients_block(array('clients_set' => $set, 'autoscroll' => $autoscroll, 'fullwidth' => $fullwidth, 'effects_enabled' => $effects_enabled));
	} else {
		scalia_clients(array('clients_set' => $set, 'rows' => $rows, 'cols' => $cols, 'autoscroll' => $autoscroll, 'effects_enabled' => $effects_enabled));
	}
	$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	return $return_html;
}

function scalia_testimonials_shortcode($atts) {
	extract(shortcode_atts(array(
		'set' => '',
		'fullwidth' => '',
	), $atts, 'sc_testimonials'));
	ob_start();
	scalia_testimonialss(array('testimonials_set' => $set, 'fullwidth' => $fullwidth));
	$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	return $return_html;
}

function scalia_map_with_text_shortcode($atts, $content) {
	extract(shortcode_atts(array(
		'background_color' => '',
		'color' => '',
		'size' => '',
		'link' => '',
		'grayscale' => '',
		'container' => '',
		'disable_scroll' => '',
		'rounded_corners' => ''
	), $atts, 'sc_map_with_text'));
	$size = str_replace( array( 'px', ' ' ), array( '', '' ), $size );
	$size = $size ? ($size + 46) : '';
	$map = '<div class="sc-map-with-text-map'.($grayscale ? ' grayscale' : '').'">'.do_shortcode('[vc_gmaps'.($link ? ' link="'.$link.'"' : '').' size="'.$size.'" disable_scroll="'.$disable_scroll.'"]').'</div>';
	$css_style = '';
	if($color) {
		$css_style .= 'color: '.$color.';';
	}
	if($background_color) {
		$css_style .= 'background-color: '.$background_color.';';
	}
	$return_html = '<div class="sc-map-with-text'.($rounded_corners ? ' rounded-corners' : '').'"><div class="sc-map-with-text-content" style="'.$css_style.'">'.($container ? '<div class="container">' : '').do_shortcode($content).($container ? '</div>' : '').'</div>'.$map.'</div>';
	return $return_html;
}

function scalia_link($atts) {
	extract(shortcode_atts(array(
		'text' => '',
		'href' => '',
		'class' => '',
		'title' => '',
		'target' => '_self',
	), $atts, 'sc_link'));
	$return_html = '<a';
	if($href) {
		$return_html .= ' href="'.esc_url($href).'"';
	}
	if($class) {
		$return_html .= ' class="'.esc_attr($href).'"';
	}
	if($title) {
		$return_html .= ' title="'.esc_attr($title).'"';
	}
	$target = scalia_check_array_value(array('_self', '_blank'), $target, '_self');
	$return_html .= ' target="'.esc_attr($target).'"';
	$return_html .= '>'.esc_html($text).'</a>';
	return $return_html;
}

function print_filters_for( $hook = '' ) {
	global $wp_filter;
	if( empty( $hook ) || !isset( $wp_filter[$hook] ) )
		return;

	$output = '<pre>';
	$output .= print_r( $wp_filter[$hook],1 );
	$output .= '</pre>';
	return $output;
}

function scalia_run_shortcode($content) {
	global $shortcode_tags;
	$orig_shortcode_tags = $shortcode_tags;
	remove_all_shortcodes();

	/*add_shortcode('sc_fullwidth', 'scalia_fullwidth_shortcode');
	add_shortcode('sc_divider', 'scalia_divider_shortcode');
	add_shortcode('sc_image', 'scalia_image_shortcode');
	add_shortcode('sc_icon_with_title', 'scalia_icon_with_title_shortcode');
	add_shortcode('sc_textbox', 'scalia_textbox_shortcode');*/
	/*add_shortcode('sc_youtube', 'scalia_youtube_shortcode');
	add_shortcode('sc_vimeo', 'scalia_vimeo_shortcode');
	add_shortcode('sc_dropcap', 'scalia_dropcap_shortcode');
	add_shortcode('sc_quote', 'scalia_quote_shortcode');
	add_shortcode('sc_video', 'scalia_video_shortcode');*/
	add_shortcode('sc_list', 'scalia_list_shortcode');
	add_shortcode('sc_table', 'scalia_table_shortcode');
	/*add_shortcode('sc_icon_with_text', 'scalia_icon_with_text_shortcode');
	add_shortcode('sc_alert_box', 'scalia_alert_box_shortcode');
	add_shortcode('sc_clients', 'scalia_clients_shortcode');
	add_shortcode('sc_diagram', 'scalia_diagram_shortcode');
	add_shortcode('sc_skill', 'scalia_skill_shortcode');
	add_shortcode('sc_gallery', 'scalia_gallery_shortcode');
	add_shortcode('sc_news', 'scalia_news_shortcode');
	add_shortcode('sc_portfolio', 'scalia_portfolio_shortcode');
	add_shortcode('sc_quickfinder', 'scalia_quickfinder_shortcode');
	add_shortcode('sc_team', 'scalia_team_shortcode');*/
	add_shortcode('sc_pricing_table', 'scalia_pricing_table_shortcode');
	add_shortcode('sc_pricing_column', 'scalia_pricing_column_shortcode');
	add_shortcode('sc_pricing_price', 'scalia_pricing_price_shortcode');
	add_shortcode('sc_pricing_row', 'scalia_pricing_row_shortcode');
	add_shortcode('sc_pricing_footer', 'scalia_pricing_footer_shortcode');
	/*add_shortcode('sc_icon', 'scalia_icon_shortcode');
	add_shortcode('sc_button', 'scalia_button_shortcode');
	add_shortcode('sc_testimonials', 'scalia_testimonials_shortcode');
	add_shortcode('sc_map_with_text', 'scalia_map_with_text_shortcode');
	add_shortcode('sc_counter', 'scalia_counter_shortcode');
	add_shortcode('sc_counter_box', 'scalia_counter_box_shortcode');*/

	$content = do_shortcode($content);
	$shortcode_tags = $orig_shortcode_tags;
	return $content;
}

if(!scalia_is_plugin_active('js_composer/js_composer.php')) {
	require_once(plugin_dir_path( __FILE__ ) . 'vc_shortcodes/init.php');
}

function scalia_shortcodes() {
	$shortcodes = array(
		'sc_alert_box' => array(
			'name' => __('Alert Box', 'scalia'),
			'base' => 'sc_alert_box',
			'is_container' => true,
			'js_view' => 'VcScAlertBoxView',
			'icon' => 'icon-wpb-call-to-action',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Catch visitors attention with alert box', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('Icon', 'scalia'),
					'param_name' => 'icon',
					'description' => "<a href=\"".scalia_user_icons_info_link()."\" onclick=\"tb_show('".__('Icons info', 'scalia')."', this.href+'?TB_iframe=true'); return false;\">".__('Show Icon Codes', 'scalia').'</a>'
				),
				array(
					'type' => 'attach_image',
					'heading' => __('Image', 'scalia'),
					'param_name' => 'image',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Icon Shape', 'scalia'),
					'param_name' => 'icon_shape',
					'value' => array(__('square', 'scalia') => 'square', __('circle', 'scalia') => 'circle')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Icon Style', 'scalia'),
					'param_name' => 'icon_style',
					'value' => array(__('Default', 'scalia') => '', __('45 degree Right', 'scalia') => 'angle-45deg-r', __('45 degree Left', 'scalia') => 'angle-45deg-l', __('90 degree', 'scalia') => 'angle-90deg')
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Icon Color', 'scalia'),
					'param_name' => 'icon_color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Icon Color 2', 'scalia'),
					'param_name' => 'icon_color_2',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Icon Background Color', 'scalia'),
					'param_name' => 'icon_background_color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Icon Border Color', 'scalia'),
					'param_name' => 'icon_border_color',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Icon Size', 'scalia'),
					'param_name' => 'icon_size',
					'value' => array(__('small', 'scalia') => 'small', __('medium', 'scalia') => 'medium', __('big', 'scalia') => 'big',)
				),
				array(
					'type' => 'textfield',
					'heading' => __('Button Link', 'scalia'),
					'param_name' => 'button_link',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Button Text', 'scalia'),
					'param_name' => 'button_text',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Button Title', 'scalia'),
					'param_name' => 'button_title',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Button Link target', 'scalia'),
					'param_name' => 'button_link_target',
					'value' => array(__('Self', 'scalia') => '_self', __('Blank', 'scalia') => '_blank')
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Button Color', 'scalia'),
					'param_name' => 'button_color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Button Background Color', 'scalia'),
					'param_name' => 'button_background_color',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Button 2 Link', 'scalia'),
					'param_name' => 'button_2_link',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Button 2 Text', 'scalia'),
					'param_name' => 'button_2_text',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Button 2 Title', 'scalia'),
					'param_name' => 'button_2_title',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Button 2 Link target', 'scalia'),
					'param_name' => 'button_2_link_target',
					'value' => array(__('Self', 'scalia') => '_self', __('Blank', 'scalia') => '_blank')
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Button 2 Color', 'scalia'),
					'param_name' => 'button_2_color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Button 2 Background Color', 'scalia'),
					'param_name' => 'button_2_background_color',
				),
				array(
					'type' => 'attach_image',
					'heading' => __('Background Image', 'scalia'),
					'param_name' => 'background_image',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Background Color', 'scalia'),
					'param_name' => 'background_color',
					/*'value' => scalia_get_option('styled_elements_background_color') ? scalia_get_option('styled_elements_background_color') : ''*/
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Border Color', 'scalia'),
					'param_name' => 'border_color',
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Shadow', 'scalia'),
					'param_name' => 'shadow',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Centered', 'scalia'),
					'param_name' => 'centered',
					'value' => array(__('Yes', 'scalia') => '1')
				),
			),
		),

		'sc_button' => array(
			'name' => __('Button', 'scalia'),
			'base' => 'sc_button',
			'icon' => 'icon-wpb-ui-button',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Styled button element', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('Link', 'scalia'),
					'param_name' => 'link',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Text', 'scalia'),
					'param_name' => 'text',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Title', 'scalia'),
					'param_name' => 'title',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Link target', 'scalia'),
					'param_name' => 'link_target',
					'value' => array(__('Self', 'scalia') => '_self', __('Blank', 'scalia') => '_blank')
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Color', 'scalia'),
					'param_name' => 'color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Background Color', 'scalia'),
					'param_name' => 'background_color',
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Centered', 'scalia'),
					'param_name' => 'centered',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Separator Style', 'scalia'),
					'param_name' => 'separator_style',
					'value' => array(__('None', 'scalia') => '', __('Double', 'scalia') => 'double', __('Dotted', 'scalia') => 'dotted')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Lazy loading enabled', 'scalia'),
					'param_name' => 'effects_enabled',
					'value' => array(__('Yes', 'scalia') => '1')
				),
			),
		),

		'sc_clients' => array(
			'name' => __('Clients', 'scalia'),
			'base' => 'sc_clients',
			'icon' => 'scalia-icon-wpb-ui-clients',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Clients overview inside content', 'scalia'),
			'params' => array(

				array(
					'type' => 'textfield',
					'heading' => __('Set', 'scalia'),
					'param_name' => 'set',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Style', 'scalia'),
					'param_name' => 'style',
					'value' => array(__('Grid', 'scalia') => 'grid', __('Carousel', 'scalia') => 'carousel')
				),
				array(
					'type' => 'textfield',
					'heading' => __('Autoscroll', 'scalia'),
					'param_name' => 'autoscroll',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Rows', 'scalia'),
					'param_name' => 'rows',
					'value' => '3',
					'dependency' => array(
						'element' => 'style',
						'value' => array('grid')
					),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Cols', 'scalia'),
					'param_name' => 'cols',
					'value' => '3',
					'dependency' => array(
						'element' => 'style',
						'value' => array('grid')
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Fullwidth', 'scalia'),
					'param_name' => 'fullwidth',
					'value' => array(__('Yes', 'scalia') => '1'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('carousel')
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Lazy loading enabled', 'scalia'),
					'param_name' => 'effects_enabled',
					'value' => array(__('Yes', 'scalia') => '1')
				),
			),
		),

		'sc_counter' => array(
			'name' => __('Counter', 'scalia'),
			'base' => 'sc_counter',
			'as_child' => array('only' => 'sc_counter_box'),
			'content_element' => true,
			'icon' => 'scalia-icon-wpb-ui-counter',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Counter', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('From', 'scalia'),
					'param_name' => 'from',
				),
				array(
					'type' => 'textfield',
					'heading' => __('To', 'scalia'),
					'param_name' => 'to',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Text', 'scalia'),
					'param_name' => 'text',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Icon', 'scalia'),
					'param_name' => 'icon',
					'description' => "<a href=\"".scalia_user_icons_info_link()."\" onclick=\"tb_show('".__('Icons info', 'scalia')."', this.href+'?TB_iframe=true'); return false;\">".__('Show Icon Codes', 'scalia').'</a>'
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Icon Shape', 'scalia'),
					'param_name' => 'icon_shape',
					'value' => array(__('square', 'scalia') => 'square', __('circle', 'scalia') => 'circle')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Icon Style', 'scalia'),
					'param_name' => 'icon_style',
					'value' => array(__('Default', 'scalia') => '', __('45 degree Right', 'scalia') => 'angle-45deg-r', __('45 degree Left', 'scalia') => 'angle-45deg-l', __('90 degree', 'scalia') => 'angle-90deg')
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Icon Color', 'scalia'),
					'param_name' => 'icon_color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Icon Color 2', 'scalia'),
					'param_name' => 'icon_color_2',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Icon Background Color', 'scalia'),
					'param_name' => 'icon_background_color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Icon Border Color', 'scalia'),
					'param_name' => 'icon_border_color',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Icon Size', 'scalia'),
					'param_name' => 'icon_size',
					'value' => array(__('small', 'scalia') => 'small', __('medium', 'scalia') => 'medium', __('big', 'scalia') => 'big',)
				),
				array(
					'type' => 'textfield',
					'heading' => __('Suffix', 'scalia'),
					'param_name' => 'suffix',
				),
			),
		),

		'sc_counter_box' => array(
			'name' => __('Counter box', 'scalia'),
			'base' => 'sc_counter_box',
			'is_container' => true,
			'js_view' => 'VcScCounterBoxView',
			'show_settings_on_create' => false,
			'as_parent' => array('only' => 'sc_counter'),
			'icon' => 'scalia-icon-wpb-ui-counter-box',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Counter box', 'scalia'),
			'params' => array(
				array(
					'type' => 'checkbox',
					'heading' => __('Lazy loading enabled', 'scalia'),
					'param_name' => 'effects_enabled',
					'value' => array(__('Yes', 'scalia') => '1')
				),
			),
		),

		'sc_diagram' => array(
			'name' => __('Diagram', 'scalia'),
			'base' => 'sc_diagram',
			'icon' => 'icon-wpb-graph',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Styled diagrams and graphs', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('title', 'scalia'),
					'param_name' => 'title',
				),
				array(
					'type' => 'textfield',
					'heading' => __('summary', 'scalia'),
					'param_name' => 'summary',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('type', 'scalia'),
					'param_name' => 'type',
					'value' => array(__('line', 'scalia') => 'line', __('circle', 'scalia') => 'circle')
				),
				array(
					'type' => 'textarea',
					'heading' => __('Content', 'scalia'),
					'param_name' => 'content',
					'value' => '[sc_skill title="Skill1" amount="70" color="#ff0000"]'."\n".
						'[sc_skill title="Skill2" amount="70" color="#ffff00"]'."\n".
						'[sc_skill title="Skill3" amount="70" color="#ff00ff"]'."\n".
						'[sc_skill title="Skill4" amount="70" color="#f0f0f0"]'
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Lazy loading enabled', 'scalia'),
					'param_name' => 'effects_enabled',
					'value' => array(__('Yes', 'scalia') => '1')
				),
			),
		),

		'sc_divider' => array(
			'name' => __('Divider', 'scalia'),
			'base' => 'sc_divider',
			'icon' => 'icon-wpb-ui-separator',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Horizontal separator in different styles', 'scalia'),
			'params' => array(
				array(
					'type' => 'dropdown',
					'heading' => __('Style', 'scalia'),
					'param_name' => 'style',
					'value' => array(
						__('1px', 'scalia') => '',
						__('stroked', 'scalia') => 1,
						__('3px', 'scalia') => 2,
						__('7px', 'scalia') => 3,
						__('dotted', 'scalia') => 4,
						__('dashed', 'scalia') => 5,
						__('zigzag', 'scalia') => 6,
						__('wave', 'scalia') => 7
					)
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Color', 'scalia'),
					'param_name' => 'color',
					/*'value' => scalia_get_option('divider_default_color') ? scalia_get_option('divider_default_color') : ''*/
				),
				array(
					'type' => 'textfield',
					'heading' => __('Margin top', 'scalia'),
					'param_name' => 'margin_top',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Margin bottom', 'scalia'),
					'param_name' => 'margin_bottom',
					/*'value' => '27'*/
				),
			),
		),

		'sc_dropcap' => array(
			'name' => __('Dropcap', 'scalia'),
			'base' => 'sc_dropcap',
			'icon' => 'scalia-icon-wpb-ui-dropcap',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Dropcap symbol for text content', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('letter', 'scalia'),
					'param_name' => 'letter',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Shape', 'scalia'),
					'param_name' => 'shape',
					'value' => array(__('square', 'scalia') => 'square', __('circle', 'scalia') => 'circle')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Style', 'scalia'),
					'param_name' => 'style',
					'value' => array(__('Medium', 'scalia') => 'medium', __('Big', 'scalia') => 'big')
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Color', 'scalia'),
					'param_name' => 'color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Background Color', 'scalia'),
					'param_name' => 'background_color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Border Color', 'scalia'),
					'param_name' => 'border_color',
				),
			),
		),

		'sc_fullwidth' => array(
			'name' => __('Fullwidth', 'scalia'),
			'base' => 'sc_fullwidth',
			'is_container' => true,
			'js_view' => 'VcScFullwidthView',
			'icon' => 'scalia-icon-wpb-ui-fullwidth',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Fullwidth', 'scalia'),
			'params' => array(
				array(
					'type' => 'colorpicker',
					'heading' => __('Color', 'scalia'),
					'param_name' => 'color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Background Color', 'scalia'),
					'param_name' => 'background_color',
					/*'value' => scalia_get_option('styled_elements_background_color') ? scalia_get_option('styled_elements_background_color') : ''*/
				),
				array(
					'type' => 'attach_image',
					'heading' => __('Background image', 'scalia'),
					'param_name' => 'background_image',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Background style', 'scalia'),
					'param_name' => 'background_style',
					'value' => array(
						__('Default', 'scalia') => '',
						__('Cover', 'scalia') => 'cover',
						__('Contain', 'scalia') => 'contain',
						__('No Repeat', 'scalia') => 'no-repeat',
						__('Repeat', 'scalia') => 'repeat'
					)
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Background horizontal position', 'scalia'),
					'param_name' => 'background_position_horizontal',
					'value' => array(
						__('Center', 'scalia') => 'center',
						__('Left', 'scalia') => 'left',
						__('Right', 'scalia') => 'right'
					)
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Background vertical position', 'scalia'),
					'param_name' => 'background_position_vertical',
					'value' => array(
						__('Top', 'scalia') => 'top',
						__('Center', 'scalia') => 'center',
						__('Bottom', 'scalia') => 'bottom'
					)
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Parallax', 'scalia'),
					'param_name' => 'background_parallax',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Background video type', 'scalia'),
					'param_name' => 'video_background_type',
					'value' => array(
						__('None', 'scalia') => '',
						__('YouTube', 'scalia') => 'youtube',
						__('Vimeo', 'scalia') => 'vimeo',
						__('Self', 'scalia') => 'self'
					)
				),
				array(
					'type' => 'textfield',
					'heading' => __('Video id (YouTube or Vimeo) or src', 'scalia'),
					'param_name' => 'video_background_src',
					'value' => ''
				),
				array(
					'type' => 'textfield',
					'heading' => __('Video Aspect ratio (16:9, 16:10, 4:3...)', 'scalia'),
					'param_name' => 'video_background_acpect_ratio',
					'value' => '16:9'
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Background video overlay color', 'scalia'),
					'param_name' => 'video_background_overlay_color',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Background video overlay opacity (0 - 1)', 'scalia'),
					'param_name' => 'video_background_overlay_opacity',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Padding top', 'scalia'),
					'param_name' => 'padding_top',
					/*'value' => '20'*/
				),
				array(
					'type' => 'textfield',
					'heading' => __('Padding bottom', 'scalia'),
					'param_name' => 'padding_bottom',
					/*'value' => '20'*/
				),
				array(
					'type' => 'textfield',
					'heading' => __('Padding left', 'scalia'),
					'param_name' => 'padding_left',
					/*'value' => '25'*/
				),
				array(
					'type' => 'textfield',
					'heading' => __('Padding right', 'scalia'),
					'param_name' => 'padding_right',
					/*'value' => '25'*/
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Container', 'scalia'),
					'param_name' => 'container',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Styled Marker', 'scalia'),
					'param_name' => 'styled_marker',
					'value' => array(__('None', 'scalia') => '', __('Top', 'scalia') => 'top', __('Bottom', 'scalia') => 'bottom')
				),
			),
		),

		'sc_icon' => array(
			'name' => __('Icon', 'scalia'),
			'base' => 'sc_icon',
			'icon' => 'scalia-icon-wpb-ui-icon',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Customizable Font Icon', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('Icon', 'scalia'),
					'param_name' => 'icon',
					'description' => "<a href=\"".scalia_user_icons_info_link()."\" onclick=\"tb_show('".__('Icons info', 'scalia')."', this.href+'?TB_iframe=true'); return false;\">".__('Show Icon Codes', 'scalia').'</a>'
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Shape', 'scalia'),
					'param_name' => 'shape',
					'value' => array(__('square', 'scalia') => 'square', __('circle', 'scalia') => 'circle')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Style', 'scalia'),
					'param_name' => 'style',
					'value' => array(__('Default', 'scalia') => '', __('45 degree Right', 'scalia') => 'angle-45deg-r', __('45 degree Left', 'scalia') => 'angle-45deg-l', __('90 degree', 'scalia') => 'angle-90deg')
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Color', 'scalia'),
					'param_name' => 'color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Color 2', 'scalia'),
					'param_name' => 'color_2',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Background Color', 'scalia'),
					'param_name' => 'background_color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Border Color', 'scalia'),
					'param_name' => 'border_color',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Size', 'scalia'),
					'param_name' => 'size',
					'value' => array(__('small', 'scalia') => 'small', __('medium', 'scalia') => 'medium', __('big', 'scalia') => 'big',)
				),
				array(
					'type' => 'textfield',
					'heading' => __('Link', 'scalia'),
					'param_name' => 'link',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Link target', 'scalia'),
					'param_name' => 'link_target',
					'value' => array(__('Self', 'scalia') => '_self', __('Blank', 'scalia') => '_blank')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Centered', 'scalia'),
					'param_name' => 'centered',
					'value' => array(__('Yes', 'scalia') => '1')
				),
			),
		),

		'sc_icon_with_text' => array(
			'name' => __('Icon with text', 'scalia'),
			'base' => 'sc_icon_with_text',
			'is_container' => true,
			'js_view' => 'VcScIconWithTextView',
			'icon' => 'scalia-icon-wpb-ui-icon_with_text',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Font Icon with aligned text content', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('Icon', 'scalia'),
					'param_name' => 'icon',
					'description' => "<a href=\"".scalia_user_icons_info_link()."\" onclick=\"tb_show('".__('Icons info', 'scalia')."', this.href+'?TB_iframe=true'); return false;\">".__('Show Icon Codes', 'scalia').'</a>'
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Shape', 'scalia'),
					'param_name' => 'icon_shape',
					'value' => array(__('square', 'scalia') => 'square', __('circle', 'scalia') => 'circle')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Icon style', 'scalia'),
					'param_name' => 'icon_style',
					'value' => array(__('Default', 'scalia') => '', __('45 degree Right', 'scalia') => 'angle-45deg-r', __('45 degree Left', 'scalia') => 'angle-45deg-l', __('90 degree', 'scalia') => 'angle-90deg')
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('color', 'scalia'),
					'param_name' => 'icon_color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('color 2', 'scalia'),
					'param_name' => 'icon_color_2',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Background Color', 'scalia'),
					'param_name' => 'icon_background_color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Border Color', 'scalia'),
					'param_name' => 'icon_border_color',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Size', 'scalia'),
					'param_name' => 'icon_size',
					'value' => array(__('small', 'scalia') => 'small', __('medium', 'scalia') => 'medium', __('big', 'scalia') => 'big',)
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Flow', 'scalia'),
					'param_name' => 'flow',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Centered', 'scalia'),
					'param_name' => 'centered',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'textfield',
					'heading' => __('Title', 'scalia'),
					'param_name' => 'title',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Title color', 'scalia'),
					'param_name' => 'title_color',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Level', 'scalia'),
					'param_name' => 'level',
					'value' => array(__('h1', 'scalia') => 'h1', __('h2', 'scalia') => 'h2', __('h3', 'scalia') => 'h3', __('h4', 'scalia') => 'h4', __('h5', 'scalia') => 'h5', __('h6', 'scalia') => 'h6')
				),
			),
		),

		'sc_icon_with_title' => array(
			'name' => __('Icon with Title', 'scalia'),
			'base' => 'sc_icon_with_title',
			'icon' => 'scalia-icon-wpb-ui-iconed_title',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Title with customizable font icon', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('Icon', 'scalia'),
					'param_name' => 'icon',
					'description' => "<a href=\"".scalia_user_icons_info_link()."\" onclick=\"tb_show('".__('Icons info', 'scalia')."', this.href+'?TB_iframe=true'); return false;\">".__('Show Icon Codes', 'scalia').'</a>'
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Shape', 'scalia'),
					'param_name' => 'icon_shape',
					'value' => array(__('square', 'scalia') => 'square', __('circle', 'scalia') => 'circle')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Style', 'scalia'),
					'param_name' => 'icon_style',
					'value' => array(__('Default', 'scalia') => '', __('45 degree Right', 'scalia') => 'angle-45deg-r', __('45 degree Left', 'scalia') => 'angle-45deg-l', __('90 degree', 'scalia') => 'angle-90deg')
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Color', 'scalia'),
					'param_name' => 'icon_color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Color 2', 'scalia'),
					'param_name' => 'icon_color_2',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Background Color', 'scalia'),
					'param_name' => 'icon_background_color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Border Color', 'scalia'),
					'param_name' => 'icon_border_color',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Size', 'scalia'),
					'param_name' => 'icon_size',
					'value' => array(__('small', 'scalia') => 'small', __('medium', 'scalia') => 'medium', __('big', 'scalia') => 'big',)
				),
				array(
					'type' => 'textfield',
					'heading' => __('Title', 'scalia'),
					'param_name' => 'title',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Title color', 'scalia'),
					'param_name' => 'title_color',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Level', 'scalia'),
					'param_name' => 'level',
					'value' => array(__('h1', 'scalia') => 'h1', __('h2', 'scalia') => 'h2', __('h3', 'scalia') => 'h3', __('h4', 'scalia') => 'h4', __('h5', 'scalia') => 'h5', __('h6', 'scalia') => 'h6')
				),
			),
		),

		'sc_map_with_text' => array(
			'name' => __('Map with Text', 'scalia'),
			'base' => 'sc_map_with_text',
			'is_container' => true,
			'js_view' => 'VcScMapWithTextView',
			'icon' => 'scalia-icon-wpb-ui-map-with-text',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Map with Text', 'scalia'),
			'params' => array(
				array(
					'type' => 'colorpicker',
					'heading' => __('Background color', 'scalia'),
					'param_name' => 'background_color',
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Map height', 'js_composer' ),
					'param_name' => 'size',
					'admin_label' => true,
					'description' => __( 'Enter map height in pixels. Example: 200 or leave it empty to make map responsive.', 'js_composer' )
				),
				array(
					'type' => 'textarea_safe',
					'heading' => __( 'Map embed iframe', 'js_composer' ),
					'param_name' => 'link',
					'description' => sprintf( __( 'Visit <a href="%s" target="_blank">Google maps</a> to create your map. 1) Find location 2) Click "Share" and make sure map is public on the web 3) Click folder icon to reveal "Embed on my site" link 4) Copy iframe code and paste it here.', 'js_composer' ), 'https://www.google.com/maps/d/')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Grayscale', 'scalia'),
					'param_name' => 'grayscale',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Container', 'scalia'),
					'param_name' => 'container',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Deactivate Map Zoom By Scrolling', 'scalia'),
					'param_name' => 'disable_scroll',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Rounded Corners', 'scalia'),
					'param_name' => 'rounded_corners',
					'value' => array(__('Yes', 'scalia') => '1')
				),
			),
		),

		'sc_news' => array(
			'name' => __('News & Blog', 'scalia'),
			'base' => 'sc_news',
			'icon' => 'scalia-icon-wpb-ui-news',
			'category' => __('Scalia', 'scalia'),
			'description' => __('News List', 'scalia'),
			'params' => array(
				array(
					'type' => 'checkbox',
					'heading' => __('Post types', 'scalia'),
					'param_name' => 'post_types',
					'value' => array(__('Post', 'scalia') => 'post', __('News', 'scalia') => 'scalia_news')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Style', 'scalia'),
					'param_name' => 'style',
					'value' => array(
						__('Default', 'scalia') => 'default',
						__('Timeline', 'scalia') => 'timeline',
						__('Masonry 3x', 'scalia') => '3x',
						__('Masonry 4x', 'scalia') => '4x',
						__('100% width', 'scalia') => '100%',
						__('News Grid Carousel', 'scalia') => 'grid_carousel',
						__('Styled List 1', 'scalia') => 'styled_list1',
						__('Styled List 2', 'scalia') => 'styled_list2',
					)
				),
				array(
					'type' => 'textfield',
					'heading' => __('Categories', 'scalia'),
					'param_name' => 'categories',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Post per page', 'scalia'),
					'param_name' => 'post_per_page',
					'value' => '5'
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Pagination', 'scalia'),
					'param_name' => 'post_pagination',
					'value' => array(
						__('Normal', 'scalia') => 'normal',
						__('Load More', 'scalia') => 'more',
						__('Disable pagination', 'scalia') => 'disable',
					)
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Ignore Sticky Posts', 'scalia'),
					'param_name' => 'ignore_sticky',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Lazy loading enabled', 'scalia'),
					'param_name' => 'effects_enabled',
					'value' => array(__('Yes', 'scalia') => '1')
				),
			),
		),

		'sc_portfolio' => array(
			'name' => __('Portfolio', 'scalia'),
			'base' => 'sc_portfolio',
			'icon' => 'scalia-icon-wpb-ui-portfolio',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Portfolio overview inside content', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('Portfolio Set Slug', 'scalia'),
					'description' => __('Choose a set by entering portfolio set slug', 'scalia'),
					'param_name' => 'portfolios',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Layout', 'scalia'),
					'param_name' => 'portfolio_layout',
					'value' => array(__('2x columns', 'scalia') => '2x', __('3x columns', 'scalia') => '3x', __('4x columns', 'scalia') => '4x', __('100% width', 'scalia') => '100%', __('1x column list', 'scalia') => '1x')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Style', 'scalia'),
					'param_name' => 'portfolio_style',
					'value' => array(__('Justified Grid', 'scalia') => 'justified', __('Masonry Grid ', 'scalia') => 'masonry')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Columns 100% Width (1920x Screen)', 'scalia'),
					'param_name' => 'portfolio_fullwidth_columns',
					'value' => array(__('4 Columns', 'scalia') => '4', __('5 Columns', 'scalia') => '5')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('No Gaps', 'scalia'),
					'param_name' => 'portfolio_no_gaps',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Display Titles', 'scalia'),
					'param_name' => 'portfolio_display_titles',
					'value' => array(__('On Page', 'scalia') => 'page', __('On Hover', 'scalia') => 'hover')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Hover Type', 'scalia'),
					'param_name' => 'portfolio_hover',
					'value' => array(__('Default', 'scalia') => 'default', __('Zooming Blur', 'scalia') => 'zooming-blur', __('Horizontal Sliding', 'scalia') => 'horizontal-sliding', __('Vertical Sliding', 'scalia') => 'vertical-sliding')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Pagination', 'scalia'),
					'param_name' => 'portfolio_pagination',
					'value' => array(__('Normal', 'scalia') => 'normal', __('Load More ', 'scalia') => 'more')
				),
				array(
					'type' => 'textfield',
					'heading' => __('Items per page', 'scalia'),
					'param_name' => 'portfolio_items_per_page',
					'value' => '8,16,24'
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Show Date & Sets', 'scalia'),
					'param_name' => 'portfolio_show_info',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Disable sharing buttons', 'scalia'),
					'param_name' => 'portfolio_disable_socials',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Activate Filter', 'scalia'),
					'param_name' => 'portfolio_with_filter',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'textfield',
					'heading' => __('Title', 'scalia'),
					'param_name' => 'portfolio_title'
				),
			),
		),

		'sc_portfolio_slider' => array(
			'name' => __('Portfolio slider', 'scalia'),
			'base' => 'sc_portfolio_slider',
			'icon' => 'scalia-icon-wpb-ui-portfolio',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Portfolio slider inside content', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('Title', 'scalia'),
					'param_name' => 'portfolio_title',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Portfolios', 'scalia'),
					'param_name' => 'portfolios',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Layout', 'scalia'),
					'param_name' => 'portfolio_layout',
					'value' => array(__('3x columns', 'scalia') => '3x', __('100% width', 'scalia') => '100%')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Columns 100% Width (1920x Screen)', 'scalia'),
					'param_name' => 'portfolio_fullwidth_columns',
					'value' => array(__('4 Columns', 'scalia') => '4', __('5 Columns', 'scalia') => '5')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('No Gaps', 'scalia'),
					'param_name' => 'portfolio_no_gaps',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Display Titles', 'scalia'),
					'param_name' => 'portfolio_display_titles',
					'value' => array(__('On Page', 'scalia') => 'page', __('On Hover', 'scalia') => 'hover')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Hover Type', 'scalia'),
					'param_name' => 'portfolio_hover',
					'value' => array(__('Default', 'scalia') => 'default', __('Zooming Blur', 'scalia') => 'zooming-blur', __('Horizontal Sliding', 'scalia') => 'horizontal-sliding', __('Vertical Sliding', 'scalia') => 'vertical-sliding')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Show Date & Sets', 'scalia'),
					'param_name' => 'portfolio_show_info',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Disable sharing buttons', 'scalia'),
					'param_name' => 'portfolio_disable_socials',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Lazy loading enabled', 'scalia'),
					'param_name' => 'effects_enabled',
					'value' => array(__('Yes', 'scalia') => '1')
				),
			),
		),

		'sc_pricing_column' => array(
			'name' => __('Pricing Table Column', 'scalia'),
			'base' => 'sc_pricing_column',
			'icon' => 'scalia-icon-wpb-ui-pricing-column',
			'as_parent' => array('only' => 'sc_pricing_price, sc_pricing_row, sc_pricing_footer'),
			'as_child' => array('only' => 'sc_pricing_table'),
			'category' => __('Scalia', 'scalia'),
			'is_container' => true,
			'js_view' => 'VcScPricingColumnView',
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('Column title', 'scalia'),
					'param_name' => 'title',
					'value' => 'Column title'
				),
				array(
					'type' => 'textfield',
					'heading' => __('Column subtitle', 'scalia'),
					'param_name' => 'subtitle',
					'value' => 'Column subtitle'
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Highlighted', 'scalia'),
					'param_name' => 'highlighted',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'textfield',
					'heading' => __('Top Choice', 'scalia'),
					'param_name' => 'top_choice',
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Top Corner Label Position', 'scalia'),
					'param_name' => 'label_top_corner',
					'value' => array(__('Yes', 'scalia') => '1')
				),
			),
		),

		'sc_pricing_price' => array(
			'name' => __('Pricing Table Column Price', 'scalia'),
			'base' => 'sc_pricing_price',
			'icon' => 'scalia-icon-wpb-ui-pricing-price',
			'as_child' => array('only' => 'sc_pricing_column'),
			'category' => __('Scalia', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('Currency', 'scalia'),
					'param_name' => 'currency',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Price', 'scalia'),
					'param_name' => 'price',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Time', 'scalia'),
					'param_name' => 'time',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Font Size', 'scalia'),
					'param_name' => 'font_size',
					'value' => 57
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Color', 'scalia'),
					'param_name' => 'color',
				),
				array(
					'type' => 'attach_image',
					'heading' => __('Background', 'scalia'),
					'param_name' => 'background',
				),
			),
		),

		'sc_pricing_row' => array(
			'name' => __('Pricing Table Column Row', 'scalia'),
			'base' => 'sc_pricing_row',
			'icon' => 'scalia-icon-wpb-ui-pricing-row',
			'as_child' => array('only' => 'sc_pricing_column'),
			'category' => __('Scalia', 'scalia'),
			'params' => array(
				array(
					'type' => 'textarea_html',
					'heading' => __('Content', 'scalia'),
					'param_name' => 'content',
					'value' => '#'
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Strike', 'scalia'),
					'param_name' => 'strike',
					'value' => array(__('Yes', 'scalia') => '1')
				),
			),
		),

		'sc_pricing_footer' => array(
			'name' => __('Pricing Table Column Footer', 'scalia'),
			'base' => 'sc_pricing_footer',
			'icon' => 'scalia-icon-wpb-ui-pricing-footer',
			'as_child' => array('only' => 'sc_pricing_column'),
			'category' => __('Scalia', 'scalia'),
			'params' => array(
				array(
					'type' => 'textarea_html',
					'heading' => __('Content', 'scalia'),
					'param_name' => 'content',
					'value' => '#'
				),
				array(
					'type' => 'textfield',
					'heading' => __('Link', 'scalia'),
					'param_name' => 'href',
					'value' => '#'
				),
			),
		),

		'sc_pricing_table' => array(
			'name' => __('Pricing table', 'scalia'),
			'base' => 'sc_pricing_table',
			'icon' => 'scalia-icon-wpb-ui-pricing-table',
			'is_container' => true,
			'js_view' => 'VcScPricingTableView',
			'as_parent' => array('only' => 'sc_pricing_column'),
			'category' => __('Scalia', 'scalia'),
			'description' => __('Styled pricing table', 'scalia'),
			'params' => array(
				array(
					'type' => 'dropdown',
					'heading' => __('Style', 'scalia'),
					'param_name' => 'style',
					'value' => array(__('Style 1', 'scalia') => '1', __('Style 2', 'scalia') => '2', __('Style 3', 'scalia') => '3')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Button icon', 'scalia'),
					'param_name' => 'button_icon',
					'value' => array(__('Default', 'scalia') => 'default', __('Price', 'scalia') => 'price')
				),
/*				array(
					'type' => 'textarea',
					'heading' => __('Content', 'scalia'),
					'param_name' => 'content',
					'value' => "\n[sc_pricing_column title=\"Column title\"]\n[sc_pricing_price currency=\"$\" price=\"9.99\" time=\"Per Month\"][/sc_pricing_price]\n[sc_pricing_row]Feature 1[/sc_pricing_row]\n[sc_pricing_footer href=\"#\"]Order[/sc_pricing_footer]\n[/sc_pricing_column]"
				),*/
			),
		),

		'sc_quickfinder' => array(
			'name' => __('Quickfinder', 'scalia'),
			'base' => 'sc_quickfinder',
			'icon' => 'scalia-icon-wpb-ui-quickfinder',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Quickfinder overviews inside content', 'scalia'),
			'params' => array(
				array(
					'type' => 'dropdown',
					'heading' => __('Style', 'scalia'),
					'param_name' => 'style',
					'value' => array(__('Grid View', 'scalia') => 'default', __('Vertical Style 1', 'scalia') => 'vertical-1', __('Vertical Style 2', 'scalia') => 'vertical-2')
				),
				array(
					'type' => 'textfield',
					'heading' => __('Quickfinder', 'scalia'),
					'param_name' => 'quickfinder',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Connector Color', 'scalia'),
					'param_name' => 'connector_color',
					'dependency' => array(
						'element' => 'style',
						'value' => array('vertical-1', 'vertical-2')
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Lazy loading enabled', 'scalia'),
					'param_name' => 'effects_enabled',
					'value' => array(__('Yes', 'scalia') => '1')
				),
			),
		),

		'sc_quote' => array(
			'name' => __('Quoted text', 'scalia'),
			'base' => 'sc_quote',
			'icon' => 'scalia-icon-wpb-ui-quote',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Quoted text content', 'scalia'),
			'params' => array(
				array(
					'type' => 'textarea_html',
					'heading' => __('Content', 'scalia'),
					'param_name' => 'content',
				),
			),
		),

		'sc_video' => array(
			'name' => __('Self-Hosted Video ', 'scalia'),
			'base' => 'sc_video',
			'icon' => 'scalia-icon-wpb-ui-video',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Video content', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('Width', 'scalia'),
					'param_name' => 'width',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Height', 'scalia'),
					'param_name' => 'height',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Video Aspect ratio (16:9, 16:10, 4:3...)', 'scalia'),
					'param_name' => 'aspect_ratio',
					'value' => '16:9'
				),
				array(
					'type' => 'textfield',
					'heading' => __('Video URL in mp4 or flv format', 'scalia'),
					'param_name' => 'video_src',
				),
				array(
					'type' => 'attach_image',
					'heading' => __('Poster Image', 'scalia'),
					'param_name' => 'image_src',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Border Style', 'scalia'),
					'param_name' => 'style',
					'value' => array(
						__('no border', 'scalia') => 'default',
						__('1px & shadow', 'scalia') => '1',
						__('4px', 'scalia') => '2',
						__('10px', 'scalia') => '3',
						__('10px outlined', 'scalia') => '4',
						__('20px outlined & flat shadow', 'scalia') => '5',
						__('20px outlined & soft shadow', 'scalia') => '6',
						__('30px combined & flat shadow', 'scalia') => '7',
						__('30px combined', 'scalia') => '8',
						__('30px combined inverted', 'scalia') => '9',
						__('dashed', 'scalia') => '10',
						__('round image', 'scalia') => '11',
						__('soft corner', 'scalia') => '12'
					)
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Position', 'scalia'),
					'param_name' => 'position',
					'value' => array(__('below', 'scalia') => 'below', __('left', 'scalia') => 'left', __('right', 'scalia') => 'right')
				),
			),
		),

		'sc_gallery' => array(
			'name' => __('Styled Gallery', 'scalia'),
			'base' => 'sc_gallery',
			'icon' => 'icon-wpb-images-stack',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Image gallery in different styles', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('Gallery Post ID', 'scalia'),
					'description' => __('Enter the gallery ID', 'scalia'),
					'param_name' => 'gallery_gallery'
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Gallery Type', 'scalia'),
					'param_name' => 'gallery_type',
					'description' => __('Choose gallery type', 'scalia'),
					'value' => array(__('Slider', 'scalia') => 'slider', __('Grid', 'scalia') => 'grid')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Layout', 'scalia'),
					'param_name' => 'gallery_slider_layout',
					'value' => array(__('fullwidth', 'scalia') => 'fullwidth', __('Sidebar', 'scalia') => 'sidebar'),
					'dependency' => array(
						'element' => 'gallery_type',
						'value' => array('slider')
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Layout', 'scalia'),
					'param_name' => 'gallery_layout',
					'description' => __('Choose gallery layout', 'scalia'),
					'value' => array(__('2x columns', 'scalia') => '2x', __('3x columns', 'scalia') => '3x', __('4x columns', 'scalia') => '4x', __('100% width', 'scalia') => '100%'),
					'dependency' => array(
						'element' => 'gallery_type',
						'value' => array('grid')
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Style', 'scalia'),
					'param_name' => 'gallery_style',
					'description' => __('Choose gallery style', 'scalia'),
					'value' => array(__('Justified Grid', 'scalia') => 'justified', __('Masonry Grid', 'scalia') => 'masonry', __('Metro Style', 'scalia') => 'metro'),
					'dependency' => array(
						'element' => 'gallery_type',
						'value' => array('grid')
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('No Gaps', 'scalia'),
					'param_name' => 'gallery_no_gaps',
					'value' => array(__('Yes', 'scalia') => '1'),
					'dependency' => array(
						'element' => 'gallery_type',
						'value' => array('grid')
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Hover Type', 'scalia'),
					'param_name' => 'gallery_hover',
					'value' => array(__('Default', 'scalia') => 'default', __('Zooming Blur', 'scalia') => 'zooming-blur')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Border Style', 'scalia'),
					'param_name' => 'gallery_item_style',
					'value' => array(
						__('Default', 'scalia') => '',
						__('1px & shadow', 'scalia') => '1',
						__('4px', 'scalia') => '2',
						__('10px', 'scalia') => '3',
						__('10px outlined', 'scalia') => '4',
						__('20px outlined & flat shadow', 'scalia') => '5',
						__('20px outlined & soft shadow', 'scalia') => '6',
						__('30px combined & flat shadow', 'scalia') => '7',
						__('30px combined', 'scalia') => '8',
						__('30px combined inverted', 'scalia') => '9',
						__('dashed', 'scalia') => '10',
						__('round image', 'scalia') => '11',
						__('soft corner', 'scalia') => '12'
					),
					'dependency' => array(
						'element' => 'gallery_type',
						'value' => array('grid')
					),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Title', 'scalia'),
					'param_name' => 'gallery_title',
					'dependency' => array(
						'element' => 'gallery_type',
						'value' => array('grid')
					),
				),
			),
		),

		'sc_image' => array(
			'name' => __('Styled Image', 'scalia'),
			'base' => 'sc_image',
			'icon' => 'icon-wpb-single-image',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Image in different styles', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('Width', 'scalia'),
					'param_name' => 'width',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Height', 'scalia'),
					'param_name' => 'height',
				),
				array(
					'type' => 'attach_image',
					'heading' => __('Src', 'scalia'),
					'param_name' => 'src',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Alt text', 'scalia'),
					'param_name' => 'alt',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Border Style', 'scalia'),
					'param_name' => 'style',
					'value' => array(
						__('no border', 'scalia') => 'default',
						__('1px & shadow', 'scalia') => '1',
						__('4px', 'scalia') => '2',
						__('10px', 'scalia') => '3',
						__('10px outlined', 'scalia') => '4',
						__('20px outlined & flat shadow', 'scalia') => '5',
						__('20px outlined & soft shadow', 'scalia') => '6',
						__('30px combined & flat shadow', 'scalia') => '7',
						__('30px combined', 'scalia') => '8',
						__('30px combined inverted', 'scalia') => '9',
						__('dashed', 'scalia') => '10',
						__('round image', 'scalia') => '11',
						__('soft corner', 'scalia') => '12'
					)
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Position', 'scalia'),
					'param_name' => 'position',
					'value' => array(__('below', 'scalia') => 'below', __('centered', 'scalia') => 'centered', __('left', 'scalia') => 'left', __('right', 'scalia') => 'right')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Disable lightbox', 'scalia'),
					'param_name' => 'disable_lightbox',
					'value' => array(__('Yes', 'scalia') => '1')
				),
			),
		),

		'sc_list' => array(
			'name' => __('Styled List', 'scalia'),
			'base' => 'sc_list',
			'icon' => 'scalia-icon-wpb-ui-list',
			'category' => __('Scalia', 'scalia'),
			'description' => __('List in different styles', 'scalia'),
			'params' => array(
				array(
					'type' => 'dropdown',
					'heading' => __('Type', 'scalia'),
					'param_name' => 'type',
					'value' => array(__('Default', 'scalia') => '', __('Check', 'scalia') => 'check', __('Minus', 'scalia') => 'minus', __('Arrow', 'scalia') => 'arrow', __('Disc', 'scalia') => 'disc', __('Square', 'scalia') => 'square', __('Cross', 'scalia') => 'cross', __('Star', 'scalia') => 'star', __('Pin', 'scalia') => 'pin')
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Color', 'scalia'),
					'param_name' => 'color',
					'value' => array(
						__('Turquoise', 'scalia') => '',
						__('Light Blue', 'scalia') => '1',
						__('Grey', 'scalia') => '2',
						__('Wine Red', 'scalia') => '3',
						__('Mustard Yellow', 'scalia') => '4',
						__('Light Green', 'scalia') => '5',
						__('Green Grey', 'scalia') => '6',
						__('Light Red', 'scalia') => '7',
						__('Light Grey ', 'scalia') => '8')
				),
				array(
					'type' => 'textarea_html',
					'heading' => __('Content', 'scalia'),
					'param_name' => 'content',
					'value' => '<ul>'."\n".'<li>'.__('Element 1', 'codeus').'</li>'."\n".'<li>'.__('Element 2', 'codeus').'</li>'."\n".'<li>'.__('Element 3', 'codeus').'</li>'."\n".'</ul>'
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Lazy loading enabled', 'scalia'),
					'param_name' => 'effects_enabled',
					'value' => array(__('Yes', 'scalia') => '1')
				),
			),
		),

		'sc_table' => array(
			'name' => __('Table', 'scalia'),
			'base' => 'sc_table',
			'icon' => 'scalia-icon-wpb-ui-table',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Styled table content', 'scalia'),
			'params' => array(
				array(
					'type' => 'dropdown',
					'heading' => __('Style', 'scalia'),
					'param_name' => 'style',
					'value' => array(__('style-1', 'scalia') => '1', __('style-2', 'scalia') => '2')
				),
				array(
					'type' => 'textarea_html',
					'heading' => __('Content', 'scalia'),
					'param_name' => 'content',
					'value' => '<table style="width: 100%;">'."\n".
						'<thead><tr><th>'.__('Title 1', 'codeus').'</th><th>'.__('Title 2', 'codeus').'</th><th>'.__('Title 3', 'codeus').'</th></tr></thead>'."\n".
						'<tbody>'."\n".
						'<tr><td><b>'.__('Content 1 1', 'codeus').'</b></td><td>'.__('Content 1 2', 'codeus').'</td><td>'.__('Content 1 3', 'codeus').'</td></tr>'."\n".
						'<tr><td><b>'.__('Content 2 1', 'codeus').'</b></td><td>'.__('Content 2 2', 'codeus').'</td><td>'.__('Content 2 3', 'codeus').'</td></tr>'."\n".
						'<tr><td><b>'.__('Content 3 1', 'codeus').'</b></td><td>'.__('Content 3 2', 'codeus').'</td><td>'.__('Content 3 3', 'codeus').'</td></tr>'."\n".
						'</tbody></table>',
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Row Headers For Responsive', 'scalia'),
					'param_name' => 'row_headers',
					'value' => array(__('Yes', 'scalia') => '1')
				),
			),
		),

		'sc_team' => array(
			'name' => __('Team', 'scalia'),
			'base' => 'sc_team',
			'icon' => 'scalia-icon-wpb-ui-team',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Team overview inside content', 'scalia'),
			'params' => array(
				array(
					'type' => 'dropdown',
					'heading' => __('Style', 'scalia'),
					'param_name' => 'style',
					'value' => array(__('Horizontal', 'scalia') => 'horizontal', __('Vertical', 'scalia') => 'vertical', __('Rounded', 'scalia') => 'rounded')
				),
				array(
					'type' => 'textfield',
					'heading' => __('team', 'scalia'),
					'param_name' => 'team',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Columns', 'scalia'),
					'param_name' => 'columns',
					'value' => array(3, 1, 2, 4)
				),
			),
		),

		'sc_testimonials' => array(
			'name' => __('Testimonials', 'scalia'),
			'base' => 'sc_testimonials',
			'icon' => 'scalia-icon-wpb-ui-testimonials',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Testimonials', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('set', 'scalia'),
					'param_name' => 'set',
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Fullwidth', 'scalia'),
					'param_name' => 'fullwidth',
					'value' => array(__('Yes', 'scalia') => '1')
				)
			),
		),

		'sc_textbox' => array(
			'name' => __('Textbox', 'scalia'),
			'base' => 'sc_textbox',
			'is_container' => true,
			'js_view' => 'VcScTextboxView',
			'icon' => 'icon-wpb-layer-shape-text',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Customizable block of text', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('Title', 'scalia'),
					'param_name' => 'title',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Icon', 'scalia'),
					'param_name' => 'icon',
					'description' => "<a href=\"".scalia_user_icons_info_link()."\" onclick=\"tb_show('".__('Icons info', 'scalia')."', this.href+'?TB_iframe=true'); return false;\">".__('Show Icon Codes', 'scalia').'</a>'
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Title Background Color', 'scalia'),
					'param_name' => 'title_background_color',
					/*'value' => scalia_get_option('styled_elements_background_color') ? scalia_get_option('styled_elements_background_color') : ''*/
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Title Text Color', 'scalia'),
					'param_name' => 'title_text_color',
				),
				array(
					'type' => 'attach_image',
					'heading' => __('Content Background Image', 'scalia'),
					'param_name' => 'content_background_image',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Background style', 'scalia'),
					'param_name' => 'content_background_style',
					'value' => array(
						__('Default', 'scalia') => '',
						__('Cover', 'scalia') => 'cover',
						__('Contain', 'scalia') => 'contain',
						__('No Repeat', 'scalia') => 'no-repeat',
						__('Repeat', 'scalia') => 'repeat'
					)
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Background horizontal position', 'scalia'),
					'param_name' => 'content_background_position_horizontal',
					'value' => array(
						__('Center', 'scalia') => 'center',
						__('Left', 'scalia') => 'left',
						__('Right', 'scalia') => 'right'
					)
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Background vertical position', 'scalia'),
					'param_name' => 'content_background_position_vertical',
					'value' => array(
						__('Top', 'scalia') => 'top',
						__('Center', 'scalia') => 'center',
						__('Bottom', 'scalia') => 'bottom'
					)
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Content Background Color', 'scalia'),
					'param_name' => 'content_background_color',
					/*'value' => scalia_get_option('styled_elements_background_color') ? scalia_get_option('styled_elements_background_color') : ''*/
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Content Text Color', 'scalia'),
					'param_name' => 'content_text_color',
				),
				array(
					'type' => 'colorpicker',
					'heading' => __('Border Color', 'scalia'),
					'param_name' => 'border_color',
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Shadow', 'scalia'),
					'param_name' => 'shadow',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Centered', 'scalia'),
					'param_name' => 'centered',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('No rounded corners', 'scalia'),
					'param_name' => 'no_rounded',
					'value' => array(__('Yes', 'scalia') => '1')
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Lazy loading enabled', 'scalia'),
					'param_name' => 'effects_enabled',
					'value' => array(__('Yes', 'scalia') => '1')
				),
			),
		),

		'sc_youtube' => array(
			'name' => __('Youtube', 'scalia'),
			'base' => 'sc_youtube',
			'icon' => 'scalia-icon-wpb-ui-youtube',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Youtube video content', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('Width', 'scalia'),
					'param_name' => 'width',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Height', 'scalia'),
					'param_name' => 'height',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Video Aspect ratio (16:9, 16:10, 4:3...)', 'scalia'),
					'param_name' => 'aspect_ratio',
					'value' => '16:9'
				),
				array(
					'type' => 'textfield',
					'heading' => __('Video_id', 'scalia'),
					'param_name' => 'video_id',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Border Style', 'scalia'),
					'param_name' => 'style',
					'value' => array(
						__('no border', 'scalia') => 'default',
						__('1px & shadow', 'scalia') => '1',
						__('4px', 'scalia') => '2',
						__('10px', 'scalia') => '3',
						__('10px outlined', 'scalia') => '4',
						__('20px outlined & flat shadow', 'scalia') => '5',
						__('20px outlined & soft shadow', 'scalia') => '6',
						__('30px combined & flat shadow', 'scalia') => '7',
						__('30px combined', 'scalia') => '8',
						__('30px combined inverted', 'scalia') => '9',
						__('dashed', 'scalia') => '10',
						__('round image', 'scalia') => '11',
						__('soft corner', 'scalia') => '12'
					)
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Position', 'scalia'),
					'param_name' => 'position',
					'value' => array(__('below', 'scalia') => 'below', __('left', 'scalia') => 'left', __('right', 'scalia') => 'right')
				),
			),
		),

		'sc_vimeo' => array(
			'name' => __('Vimeo', 'scalia'),
			'base' => 'sc_vimeo',
			'icon' => 'scalia-icon-wpb-ui-vimeo',
			'category' => __('Scalia', 'scalia'),
			'description' => __('Vimeo video content', 'scalia'),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __('Width', 'scalia'),
					'param_name' => 'width',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Height', 'scalia'),
					'param_name' => 'height',
				),
				array(
					'type' => 'textfield',
					'heading' => __('Video Aspect ratio (16:9, 16:10, 4:3...)', 'scalia'),
					'param_name' => 'aspect_ratio',
					'value' => '16:9'
				),
				array(
					'type' => 'textfield',
					'heading' => __('Video id', 'scalia'),
					'param_name' => 'video_id',
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Border Style', 'scalia'),
					'param_name' => 'style',
					'value' => array(
						__('no border', 'scalia') => 'default',
						__('1px & shadow', 'scalia') => '1',
						__('4px', 'scalia') => '2',
						__('10px', 'scalia') => '3',
						__('10px outlined', 'scalia') => '4',
						__('20px outlined & flat shadow', 'scalia') => '5',
						__('20px outlined & soft shadow', 'scalia') => '6',
						__('30px combined & flat shadow', 'scalia') => '7',
						__('30px combined', 'scalia') => '8',
						__('30px combined inverted', 'scalia') => '9',
						__('dashed', 'scalia') => '10',
						__('round image', 'scalia') => '11',
						__('soft corner', 'scalia') => '12'
					)
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Position', 'scalia'),
					'param_name' => 'position',
					'value' => array(__('below', 'scalia') => 'below', __('left', 'scalia') => 'left', __('right', 'scalia') => 'right')
				),
			),
		),

	);
	return apply_filters('scalia_shortcodes_array', $shortcodes);
}

function scalia_VC_init() {
	if(scalia_is_plugin_active('js_composer/js_composer.php')) {
		global $vc_manager;
		remove_filter('the_excerpt', array($vc_manager->vc(), 'excerptFilter'));
		add_action('admin_print_scripts-post.php', 'scalia_printScriptsMessages');
		add_action('admin_print_scripts-post-new.php', 'scalia_printScriptsMessages');
		$shortcodes = scalia_shortcodes();
		foreach($shortcodes as $shortcode) {
			vc_map($shortcode);
		}
		$vc_layout_sub_controls = array(
			array( 'link_post', __( 'Link to post', 'js_composer' ) ),
			array( 'no_link', __( 'No link', 'js_composer' ) ),
			array( 'link_image', __( 'Link to bigger image', 'js_composer' ) )
		);
		$target_arr = array(
			__( 'Same window', 'js_composer' ) => '_self',
			__( 'New window', 'js_composer' ) => "_blank"
		);
		vc_map( array(
			'name' => __( 'Posts Grid', 'js_composer' ),
			'base' => 'vc_posts_grid',
			'icon' => 'icon-wpb-application-icon-large',
			'description' => __( 'Posts in grid view', 'js_composer' ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Widget title', 'js_composer' ),
					'param_name' => 'title',
					'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' )
				),
				array(
					'type' => 'loop',
					'heading' => __( 'Grids content', 'js_composer' ),
					'param_name' => 'loop',
					'settings' => array(
						'size' => array( 'hidden' => false, 'value' => 10 ),
						'order_by' => array( 'value' => 'date' ),
					),
					'description' => __( 'Create WordPress loop, to populate content from your site.', 'js_composer' )
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Columns count', 'js_composer' ),
					'param_name' => 'grid_columns_count',
					'value' => array( 6, 4, 3, 2, 1 ),
					'std' => 3,
					'admin_label' => true,
					'description' => __( 'Select columns count.', 'js_composer' )
				),
				array(
					'type' => 'sorted_list',
					'heading' => __( 'Teaser layout', 'js_composer' ),
					'param_name' => 'grid_layout',
					'description' => __( 'Control teasers look. Enable blocks and place them in desired order. Note: This setting can be overrriden on post to post basis.', 'js_composer' ),
					'value' => 'title,image,text',
					'options' => array(
						array( 'image', __( 'Thumbnail', 'js_composer' ), $vc_layout_sub_controls ),
						array( 'title', __( 'Title', 'js_composer' ), $vc_layout_sub_controls ),
						array(
							'text',
							__( 'Text', 'js_composer' ),
							array(
								array( 'excerpt', __( 'Teaser/Excerpt', 'js_composer' ) ),
								array( 'text', __( 'Full content', 'js_composer' ) )
							)
						),
						array( 'link', __( 'Read more link', 'js_composer' ) )
					)
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Link target', 'js_composer' ),
					'param_name' => 'grid_link_target',
					'value' => $target_arr,
					// 'dependency' => array(
					//     'element' => 'grid_link',
					//     'value' => array( 'link_post', 'link_image_post' )
					// )
				),
				array(
					'type' => 'checkbox',
					'heading' => __( 'Show filter', 'js_composer' ),
					'param_name' => 'filter',
					'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					'description' => __( 'Select to add animated category filter to your posts grid.', 'js_composer' )
				),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Layout mode', 'js_composer' ),
					'param_name' => 'grid_layout_mode',
					'value' => array(
						__( 'Fit rows', 'js_composer' ) => 'fitRows',
						__( 'Masonry', 'js_composer' ) => 'masonry'
					),
					'description' => __( 'Teaser layout template.', 'js_composer' )
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Thumbnail size', 'js_composer' ),
					'param_name' => 'grid_thumb_size',
					'description' => __( 'Enter thumbnail size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height) . ', 'js_composer' )
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Extra class name', 'js_composer' ),
					'param_name' => 'el_class',
					'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
				)
			)
			// 'html_template' => dirname(__DIR__).'/composer/shortcodes_templates/vc_posts_grid.php'
		) );
		vc_add_param('vc_tabs', array(
			'type' => 'dropdown',
			'heading' => __('Style', 'scalia'),
			'param_name' => 'style',
			'value' => array(1, 2),
		));
		vc_add_param('vc_tour', array(
			'type' => 'dropdown',
			'heading' => __('Style', 'scalia'),
			'param_name' => 'style',
			'value' => array(1, 2),
		));
		vc_add_param('vc_tabs', array(
			'type' => 'textfield',
			'heading' => __('ID', 'scalia'),
			'param_name' => 'id',
		));
		vc_add_param('vc_tour', array(
			'type' => 'textfield',
			'heading' => __('ID', 'scalia'),
			'param_name' => 'id',
		));
		vc_add_param('vc_posts_grid', array(
			'type' => 'checkbox',
			'heading' => __('Disable scalia style', 'scalia'),
			'param_name' => 'disable_scalia_style',
			'value' => array(__('Yes', 'scalia') => '1')
		));
		vc_add_param('vc_posts_grid', array(
			'type' => 'checkbox',
			'heading' => __('Lazy loading enabled', 'scalia'),
			'param_name' => 'effects_enabled',
			'value' => array(__('Yes', 'scalia') => '1')
		));
		vc_add_param('vc_column_inner', array(
			'type' => 'column_offset',
			'heading' => __('Responsiveness', 'js_composer'),
			'param_name' => 'offset',
			'group' => __( 'Width & Responsiveness', 'js_composer' ),
			'description' => __('Adjust column for different screen sizes. Control width, offset and visibility settings.', 'js_composer') 
		));
		vc_add_param('vc_gmaps', array(
			'type' => 'checkbox',
			'heading' => __('Deactivate Map Zoom By Scrolling', 'scalia'),
			'param_name' => 'disable_scroll',
			'value' => array(__('Yes', 'scalia') => '1')
		));
		vc_add_param('vc_gmaps', array(
			'type' => 'dropdown',
			'heading' => __('Style', 'scalia'),
			'param_name' => 'style',
			'value' => array(
				__('no style', 'scalia') => '',
				__('no border', 'scalia') => 'default',
				__('1px & shadow', 'scalia') => '1',
				__('4px', 'scalia') => '2',
				__('10px', 'scalia') => '3',
				__('10px outlined', 'scalia') => '4',
				__('20px outlined & flat shadow', 'scalia') => '5',
				__('20px outlined & soft shadow', 'scalia') => '6',
				__('30px combined & flat shadow', 'scalia') => '7',
				__('30px combined', 'scalia') => '8',
				__('30px combined inverted', 'scalia') => '9',
				__('dashed', 'scalia') => '10',
				__('round image', 'scalia') => '11',
				__('soft corner', 'scalia') => '12'
			)
		));
		vc_add_param('vc_accordion', array(
			'type' => 'checkbox',
			'heading' => __('Lazy loading enabled', 'scalia'),
			'param_name' => 'effects_enabled',
			'value' => array(__('Yes', 'scalia') => '1')
		));
		vc_remove_element('vc_carousel');
		vc_remove_element('vc_button');
		vc_remove_element('vc_cta_button');
		vc_remove_element('vc_video');
		vc_remove_element('vc_flickr');
		if($vc_manager->mode() != 'admin_frontend_editor' && $vc_manager->mode() != 'admin_page' && $vc_manager->mode() != 'page_editable') {
			add_filter('the_content', 'scalia_run_shortcode', 7);
			add_filter('scalia_print_shortcodes', 'scalia_run_shortcode', 7);
			add_filter('widget_text', 'scalia_run_shortcode', 7);
			add_filter('the_excerpt', 'scalia_run_shortcode', 7);
		}
	} else {
		add_filter('the_content', 'scalia_run_shortcode', 7);
		add_filter('scalia_print_shortcodes', 'scalia_run_shortcode', 7);
		add_filter('widget_text', 'scalia_run_shortcode', 7);
		add_filter('the_excerpt', 'scalia_run_shortcode', 7);
	}
}
add_action('init', 'scalia_VC_init', 11);

function scalia_update_vc_shortcodes_params() {
	$param = WPBMap::getParam('vc_gmaps', 'link');
	$param['description'] = sprintf( __( 'Visit <a href="%s" target="_blank">Google maps</a> to create your map. 1) Find location 2) Click "Share" and make sure map is public on the web 3) Click folder icon to reveal "Embed on my site" link 4) Copy iframe code and paste it here.', 'js_composer' ), 'https://www.google.com/maps/d/');
	vc_update_shortcode_param('vc_gmaps', $param);
}
add_action('vc_after_init', 'scalia_update_vc_shortcodes_params');

if(class_exists('WPBakeryShortCodesContainer')) {
	class WPBakeryShortCode_sc_alert_box extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_sc_fullwidth extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_sc_map_with_text extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_sc_icon_with_text extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_sc_textbox extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_sc_counter_box extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_sc_pricing_table extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_sc_pricing_column extends WPBakeryShortCodesContainer {}
}

function scalia_js_remove_wpautop($content, $autop = false) {
	if(scalia_is_plugin_active('js_composer/js_composer.php')) {
		return wpb_js_remove_wpautop($content, $autop);
	}
	return $content;
}

function scalia_portfolio_slider_shortcode($atts) {
	extract(shortcode_atts(array(
		'portfolios' => '',
		'portfolio_title' => '',
		'portfolio_layout' => '3x',
		'portfolio_no_gaps' => '',
		'portfolio_display_titles' => 'page',
		'portfolio_hover' => 'default',
		'portfolio_show_info' => '',
		'portfolio_disable_socials' => '',
		'portfolio_fullwidth_columns' => '4',
		'effects_enabled' => false
	), $atts, 'sc_portfolio_slider'));
	if(scalia_is_plugin_active('js_composer/js_composer.php')) {
		global $vc_manager;
		if($vc_manager->mode() == 'admin_frontend_editor' || $vc_manager->mode() == 'admin_page' || $vc_manager->mode() == 'page_editable') {
			return '<div class="portfolio-slider-shortcode-dummy"></div>';
		}
	}
	ob_start();
	scalia_portfolio_slider(array(
		'portfolio' => $portfolios,
		'title' => $portfolio_title,
		'layout' => $portfolio_layout,
		'no_gaps' => $portfolio_no_gaps,
		'display_titles' => $portfolio_display_titles,
		'hover' => $portfolio_hover,
		'show_info' => $portfolio_show_info,
		'disable_socials' => $portfolio_disable_socials,
		'fullwidth_columns' => $portfolio_fullwidth_columns,
		'effects_enabled' => $effects_enabled,
		)
	);
	$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	return $return_html;
}

function scalia_portfolio_shortcode($atts) {
	extract(shortcode_atts(array(
		'portfolios' => '',
		'portfolio_layout' => '2x',
		'portfolio_style' => 'justified',
		'portfolio_no_gaps' => '',
		'portfolio_display_titles' => 'page',
		'portfolio_hover' => 'default',
		'portfolio_pagination' => 'normal',
		'portfolio_items_per_page' => '',
		'portfolio_show_info' => '',
		'portfolio_with_filter' => '',
		'portfolio_title' => '',
		'portfolio_disable_socials' => '',
		'portfolio_fullwidth_columns' => '4'
	), $atts, 'sc_portfolio'));
	if(scalia_is_plugin_active('js_composer/js_composer.php')) {
		global $vc_manager;
		if($vc_manager->mode() == 'admin_frontend_editor' || $vc_manager->mode() == 'admin_page' || $vc_manager->mode() == 'page_editable') {
			return '<div class="portfolio-shortcode-dummy"></div>';
		}
	}
	ob_start();
	scalia_portfolio(array(
		'portfolio' => $portfolios,
		'title' => $portfolio_title,
		'layout' => $portfolio_layout,
		'style' => $portfolio_style,
		'no_gaps' => $portfolio_no_gaps,
		'display_titles' => $portfolio_display_titles,
		'hover' => $portfolio_hover,
		'pagination' => $portfolio_pagination,
		'items_per_page' => $portfolio_items_per_page,
		'with_filter' => $portfolio_with_filter,
		'show_info' => $portfolio_show_info,
		'disable_socials' => $portfolio_disable_socials,
		'fullwidth_columns' => $portfolio_fullwidth_columns,
	));
	$return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	return $return_html;
}

/*
function sc_counter_box_vc_controls() {
?>
<script type="text/html" id="vc_controls-template-sc-counter-box">
	<div class="vc_controls-container">
		<div class="vc_controls-out-tl">
			<div class="vc_element element-{{ tag }}">
				<a class="vc_control-btn vc_element-name vc_element-move"
					 title="<?php printf( __( 'Drag to move %s', 'js_composer' ), '{{ name }}' ) ?>"><span
					class="vc_btn-content">{{ name }}</span></a>
				<a class="vc_control-btn vc_control-btn-prepend" href="#"
					 title="<?php printf( __( 'Prepend to %s', 'js_composer' ), '{{ name }}' ) ?>"><span
					class="vc_btn-content"><span class="icon"></span></span></a>
				<a class="vc_control-btn vc_control-btn-clone" href="#"
					 title="<?php printf( __( 'Clone %s', 'js_composer' ), '{{ name }}' ) ?>"><span
					class="vc_btn-content"><span class="icon"></span></span></a>
				<a class="vc_control-btn vc_control-btn-delete" href="#"
					 title="<?php printf( __( 'Delete %s', 'js_composer' ), '{{ name }}' ) ?>"><span
					class="vc_btn-content"><span class="icon"></span></span></a>
			</div>
		</div>
		<div class="vc_controls-bc">
			<a class="vc_control-btn vc_control-btn-append" href="#"
				 title="<?php printf( __( 'Append to %s', 'js_composer' ), '{{ name }}' ) ?>"><span
				class="vc_btn-content"><span class="icon"></span></span></a>
		</div>
	</div>
	<!-- end vc_controls-column -->
</script>
<?php
}
add_action('vc_frontend_editor_render_template', 'sc_counter_box_vc_controls');
*/

/*function scalia_custom_css_classes_for_vc_column($class_string, $tag) {
	if(!scalia_get_option('')) {
		global $vc_manager;
		if($vc_manager->mode() != 'admin_frontend_editor' && $vc_manager->mode() != 'admin_page' && $vc_manager->mode() != 'page_editable') {
			if($tag == 'vc_column' || $tag == 'vc_column_inner') {
				$class_string = preg_replace_callback('/vc_col-sm-(\d{1,2})/', 'scalia_vc_column_replace_classes', $class_string);
			}
		}
	}
	return $class_string;
}
add_filter('vc_shortcodes_css_class', 'scalia_custom_css_classes_for_vc_column', 10, 2);

function scalia_vc_column_replace_classes($matches) {
	$css_class = 'vc_col-md-'.$matches[1];
	if($matches[1] > 6) {
		$css_class .= ' vc_col-xs-12';
	}
	if($matches[1] < 7 && $matches[1] > 3) {
		$css_class .= ' vc_col-sm-6 vc_col-xs-12';
	}
	if($matches[1] == 3) {
		$css_class .= ' vc_col-xs-6';
	}
	if($matches[1] == 2) {
		$css_class .= ' vc_col-xs-3';
	}
	if($matches[1] == 1) {
		$css_class .= ' vc_col-sm-2 vc_col-xs-3';
	}
	return $css_class;
}*/


function scalia_printScriptsMessages() {
	if(in_array( get_post_type(), vc_editor_post_types())) {
		wp_enqueue_script('scalia_js_composer_js_custom_views');
	}
}

function scalia_add_tta_tabs_tour_styles() {
	$param = WPBMap::getParam( 'vc_tta_tabs', 'style' );
	if(is_array($param['value'])) {
		$param['value'][__( 'Scalia Style 1', 'scalia' )] = 'scalia-style-1';
		$param['value'][__( 'Scalia Style 2', 'scalia' )] = 'scalia-style-2';
		vc_update_shortcode_param( 'vc_tta_tabs', $param );
	}
	$param = WPBMap::getParam( 'vc_tta_tour', 'style' );
	if(is_array($param['value'])) {
		$param['value'][__( 'Scalia Style 1', 'scalia' )] = 'scalia-style-1';
		$param['value'][__( 'Scalia Style 2', 'scalia' )] = 'scalia-style-2';
		vc_update_shortcode_param( 'vc_tta_tour', $param );
	}
}
add_action( 'vc_after_init', 'scalia_add_tta_tabs_tour_styles' );

function scalia_add_vc_column_text_effects() {
	$param = WPBMap::getParam( 'vc_column_text', 'css_animation' );
	if($param['type'] == 'dropdown') {
		$param['value'][__( 'Fade', 'scalia' )] = 'fade';
		vc_update_shortcode_param( 'vc_column_text', $param );
	}
}
add_action( 'vc_after_init', 'scalia_add_vc_column_text_effects' );

add_action( 'vc_before_init', 'scalia_disable_vc_updater' );
function scalia_disable_vc_updater() {
	global $vc_manager;
	$vc_manager->disableUpdater(true);
}