<?php
$return_html = $el_class = $bg_image = $bg_color = $bg_image_repeat = $font_color = $padding = $margin_bottom = $css = '';
extract(shortcode_atts(array(
    'el_class'        => '',
    'bg_image'        => '',
    'bg_color'        => '',
    'bg_image_repeat' => '',
    'font_color'      => '',
    'padding'         => '',
    'margin_bottom'   => '',
    'css' => ''
), $atts));

$style = scalia_buildStyle($bg_image, $bg_color, $bg_image_repeat, $font_color, $padding, $margin_bottom);

$return_html .= '<div class="row"'.$style.'>';
$return_html .= scalia_wpb_js_remove_wpautop($content);
$return_html .= '</div>';