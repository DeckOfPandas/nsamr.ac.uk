<?php
$return_html = $el_class = $width = '';
extract(shortcode_atts(array(
    'el_class' => '',
    'width' => '1/1',
    'css' => ''
), $atts));

$width = scalia_wpb_translateColumnWidthToSpan($width);

$return_html .= "\n\t".'<div class="'.$width.'">';
$return_html .= "\n\t\t".scalia_wpb_js_remove_wpautop($content);
$return_html .= "\n\t".'</div> ';