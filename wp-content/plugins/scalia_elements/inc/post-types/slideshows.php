<?php

function scalia_slides_post_type_init() {
	$labels = array(
		'name'               => __('Slides', 'scalia'),
		'singular_name'      => __('Slideshow Slide', 'scalia'),
		'menu_name'          => __('NivoSlider', 'scalia'),
		'name_admin_bar'     => __('Slideshow Slide', 'scalia'),
		'add_new'            => __('Add New', 'scalia'),
		'add_new_item'       => __('Add New Slide', 'scalia'),
		'new_item'           => __('New Slide', 'scalia'),
		'edit_item'          => __('Edit Slide', 'scalia'),
		'view_item'          => __('View Slide', 'scalia'),
		'all_items'          => __('All Slides', 'scalia'),
		'search_items'       => __('Search Slides', 'scalia'),
		'not_found'          => __('No slides found.', 'scalia'),
		'not_found_in_trash' => __('No slides found in Trash.', 'scalia')
	);

	$args = array(
		'labels'               => $labels,
		'public'               => false,
		'exclude_from_search'  => true,
		'publicly_queryable'   => false,
		'show_ui'              => true,
		'query_var'            => false,
		'hierarchical'         => false,
		'supports'             => array('title', 'thumbnail', 'excerpt', 'page-attributes'),
		'register_meta_box_cb' => 'scalia_slides_register_meta_box',
		'taxonomies'           => array('scalia_slideshows')
	);

	register_post_type('scalia_slide', $args);

	$labels = array(
		'name'                       => __('Slideshows', 'scalia'),
		'singular_name'              => __('Slideshow', 'scalia'),
		'search_items'               => __('Search Slideshows', 'scalia'),
		'popular_items'              => __('Popular Slideshows', 'scalia'),
		'all_items'                  => __('All Slideshows', 'scalia'),
		'edit_item'                  => __('Edit Slideshow', 'scalia'),
		'update_item'                => __('Update Slideshow', 'scalia'),
		'add_new_item'               => __('Add New Slideshow', 'scalia'),
		'new_item_name'              => __('New Slideshow Name', 'scalia'),
		'separate_items_with_commas' => __('Separate Slideshows with commas', 'scalia'),
		'add_or_remove_items'        => __('Add or remove Slideshows', 'scalia'),
		'choose_from_most_used'      => __('Choose from the most used Slideshows', 'scalia'),
		'not_found'                  => __('No slideshows found.', 'scalia'),
		'menu_name'                  => __('Slideshows', 'scalia'),
	);

	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'public'                => false,
		'query_var'             => false,
		'rewrite'               => false,
	);

	register_taxonomy('scalia_slideshows', 'scalia_slide', $args);
}
add_action('init', 'scalia_slides_post_type_init');

/* SLIDE POST META BOX */

function scalia_slides_register_meta_box($post) {
	remove_meta_box('postimagediv', 'scalia_slide', 'side');
	add_meta_box('postimagediv', __('Slide Image', 'scalia'), 'post_thumbnail_meta_box', 'scalia_slide', 'normal', 'high');
	add_meta_box('scalia_slide_settings', __('Slide Settings', 'scalia'), 'scalia_slide_settings_box', 'scalia_slide', 'normal', 'high');
}

function scalia_slide_settings_box($post) {
	wp_nonce_field('scalia_slide_settings_box', 'scalia_slide_settings_box_nonce');
	$slide_data = scalia_get_sanitize_slide_data($post->ID);
	$slide_text_position_options = array('' => __('None', 'codeus'), 'left' => __('Left', 'codeus'), 'right' => __('Right', 'codeus'));
?>
<p class="meta-options">
	<label for="slide_link"><?php _e('Link', 'codeus'); ?>:</label><br />
	<input name="scalia_slide_data[link]" type="text" id="slide_link" value="<?php echo esc_attr($slide_data['link']); ?>" size="60" /><br />
	<br />
	<label for="slide_link_target"><?php _e('Link target', 'scalia'); ?>:</label><br />
	<?php scalia_print_select_input(array('_self' => 'Self', '_blank' => 'Blank'), $slide_data['link_target'], 'scalia_slide_data[link_target]', 'slide_link_target'); ?><br />
	<br />
	<label for="slide_text_position"><?php _e('Caption position', 'scalia'); ?>:</label><br />
	<?php scalia_print_select_input($slide_text_position_options, $slide_data['text_position'], 'scalia_slide_data[text_position]', 'slide_text_position'); ?>
</p>
<?php
}

function scalia_slide_save_meta_box_data($post_id) {
	if(!isset($_POST['scalia_slide_settings_box_nonce'])) {
		return;
	}
	if(!wp_verify_nonce($_POST['scalia_slide_settings_box_nonce'], 'scalia_slide_settings_box')) {
		return;
	}
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if(isset($_POST['post_type']) && 'scalia_slide' == $_POST['post_type']) {
		if(!current_user_can('edit_page', $post_id)) {
			return;
		}
	} else {
		if(!current_user_can('edit_post', $post_id)) {
			return;
		}
	}

	if(!isset($_POST['scalia_slide_data']) || !is_array($_POST['scalia_slide_data'])) {
		return;
	}

	$slide_data = scalia_get_sanitize_slide_data(0, $_POST['scalia_slide_data']);
	update_post_meta($post_id, 'scalia_slide_data', $slide_data);
}
add_action('save_post', 'scalia_slide_save_meta_box_data');

function scalia_get_sanitize_slide_data($post_id = 0, $item_data = array()) {
	$slide_data = array(
		'link' => '',
		'link_target' => '',
		'text_position' => ''
	);
	if(is_array($item_data) && !empty($item_data)) {
		$slide_data = array_merge($item_data);
	} elseif($post_id != 0) {
		$slide_data = scalia_get_post_data($slide_data, 'slide', $post_id);
	}
	$slide_data['link'] = esc_url($slide_data['link']);
	$slide_data['link_target'] = scalia_check_array_value(array('_blank', '_self'), $slide_data['link_target'], '_self');
	$slide_data['text_position'] = scalia_check_array_value(array('', 'left', 'right'), $slide_data['text_position'], '');
	return $slide_data;
}