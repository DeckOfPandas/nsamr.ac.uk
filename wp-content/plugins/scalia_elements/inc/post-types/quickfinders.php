<?php

function scalia_quickfinder_item_post_type_init() {
	$labels = array(
		'name'               => __('Quickfinder Items', 'scalia'),
		'singular_name'      => __('Quickfinder Item', 'scalia'),
		'menu_name'          => __('Quickfinders', 'scalia'),
		'name_admin_bar'     => __('Quickfinder Item', 'scalia'),
		'add_new'            => __('Add New', 'scalia'),
		'add_new_item'       => __('Add New Quickfinder Item', 'scalia'),
		'new_item'           => __('New Quickfinder Item', 'scalia'),
		'edit_item'          => __('Edit Quickfinder Item', 'scalia'),
		'view_item'          => __('View Quickfinder Item', 'scalia'),
		'all_items'          => __('All Quickfinder Items', 'scalia'),
		'search_items'       => __('Search Quickfinder Items', 'scalia'),
		'not_found'          => __('No quickfinder items found.', 'scalia'),
		'not_found_in_trash' => __('No quickfinder items found in Trash.', 'scalia')
	);

	$args = array(
		'labels'               => $labels,
		'public'               => false,
		'exclude_from_search'  => true,
		'publicly_queryable'   => false,
		'show_ui'              => true,
		'query_var'            => false,
		'hierarchical'         => false,
		'supports'             => array('title', 'thumbnail', 'page-attributes'),
		'register_meta_box_cb' => 'scalia_quickfinder_items_register_meta_box',
		'taxonomies'           => array('scalia_quickfinders')
	);

	register_post_type('scalia_qf_item', $args);

	$labels = array(
		'name'                       => __('Quickfinders', 'scalia'),
		'singular_name'              => __('Quickfinder', 'scalia'),
		'search_items'               => __('Search Quickfinders', 'scalia'),
		'popular_items'              => __('Popular Quickfinders', 'scalia'),
		'all_items'                  => __('All Quickfinders', 'scalia'),
		'edit_item'                  => __('Edit Quickfinder', 'scalia'),
		'update_item'                => __('Update Quickfinder', 'scalia'),
		'add_new_item'               => __('Add New Quickfinder', 'scalia'),
		'new_item_name'              => __('New Quickfinder Name', 'scalia'),
		'separate_items_with_commas' => __('Separate Quickfinders with commas', 'scalia'),
		'add_or_remove_items'        => __('Add or remove Quickfinders', 'scalia'),
		'choose_from_most_used'      => __('Choose from the most used Quickfinders', 'scalia'),
		'not_found'                  => __('No quickfinders found.', 'scalia'),
		'menu_name'                  => __('Quickfinders', 'scalia'),
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

	register_taxonomy('scalia_quickfinders', 'scalia_qf_item', $args);
}
add_action('init', 'scalia_quickfinder_item_post_type_init');

/* QUICKFINDER ITEM POST META BOX */

function scalia_quickfinder_items_register_meta_box($post) {
	add_meta_box('scalia_quickfinder_item_settings', __('Quickfinder Item Settings', 'scalia'), 'scalia_quickfinder_item_settings_box', 'scalia_qf_item', 'normal', 'high');
}

function scalia_quickfinder_item_settings_box($post) {
	wp_nonce_field('scalia_quickfinder_item_settings_box', 'scalia_quickfinder_item_settings_box_nonce');
	$quickfinder_item_data = scalia_get_sanitize_qf_item_data($post->ID);
	$icon_styles = array('' => __('None', 'scalia'), 'angle-45deg-l' => __('45&deg; Left','scalia'), 'angle-45deg-r' => __('45&deg; Right','scalia'), 'angle-90deg' => __('90&deg;','scalia'));
?>
<p class="meta-options">
	<label for="quickfinder_item_description"><?php _e('Description', 'scalia'); ?>:</label><br />
	<input name="scalia_quickfinder_item_data[description]" type="text" id="quickfinder_item_description" value="<?php echo esc_attr($quickfinder_item_data['description']); ?>" size="60" /><br />
	<br />
	<label for="quickfinder_item_link"><?php _e('Link', 'scalia'); ?>:</label><br />
	<input name="scalia_quickfinder_item_data[link]" type="text" id="quickfinder_item_link" value="<?php echo esc_attr($quickfinder_item_data['link']); ?>" size="60" /><br />
	<br />
	<label for="quickfinder_item_link_target"><?php _e('Link target', 'scalia'); ?>:</label><br />
	<?php scalia_print_select_input(array('_self' => 'Self', '_blank' => 'Blank'), $quickfinder_item_data['link_target'], 'scalia_quickfinder_item_data[link_target]', 'quickfinder_item_link_target'); ?><br />
	<br />
	<label for="quickfinder_item_icon_color"><?php _e('Icon color', 'scalia'); ?>:</label><br />
	<input name="scalia_quickfinder_item_data[icon_color]" type="text" id="quickfinder_item_icon_color" value="<?php echo esc_attr($quickfinder_item_data['icon_color']); ?>" size="60" class="color-select" /><br />
	<br />
	<label for="quickfinder_item_icon_color_2"><?php _e('Icon color 2', 'scalia'); ?>:</label><br />
	<input name="scalia_quickfinder_item_data[icon_color_2]" type="text" id="quickfinder_item_icon_color_2" value="<?php echo esc_attr($quickfinder_item_data['icon_color_2']); ?>" size="60" class="color-select" /><br />
	<br />
	<label for="quickfinder_item_icon_background_color"><?php _e('Icon background color', 'scalia'); ?>:</label><br />
	<input name="scalia_quickfinder_item_data[icon_background_color]" type="text" id="quickfinder_item_icon_background_color" value="<?php echo esc_attr($quickfinder_item_data['icon_background_color']); ?>" size="60" class="color-select" /><br />
	<br />
	<label for="quickfinder_item_icon_border_color"><?php _e('Icon border color', 'scalia'); ?>:</label><br />
	<input name="scalia_quickfinder_item_data[icon_border_color]" type="text" id="quickfinder_item_icon_border_color" value="<?php echo esc_attr($quickfinder_item_data['icon_border_color']); ?>" size="60" class="color-select" /><br />
	<br />
	<label for="quickfinder_item_icon_shape"><?php _e('Icon shape', 'scalia'); ?>:</label><br />
	<?php scalia_print_select_input(array('circle' => 'Circle', 'square' => 'Square'), $quickfinder_item_data['icon_shape'], 'scalia_quickfinder_item_data[icon_shape]', 'quickfinder_item_icon_shape'); ?><br />
	<br />
	<label for="quickfinder_item_icon_size"><?php _e('Icon size', 'scalia'); ?>:</label><br />
	<?php scalia_print_select_input(array('small' => 'Small', 'medium' => 'Medium', 'big' => 'Big'), $quickfinder_item_data['icon_size'], 'scalia_quickfinder_item_data[icon_size]', 'quickfinder_item_icon_size'); ?><br />
	<br />
	<label for="quickfinder_item_icon_style"><?php _e('Icon Style', 'scalia'); ?>:</label><br />
	<?php scalia_print_select_input($icon_styles, $quickfinder_item_data['icon_style'], 'scalia_quickfinder_item_data[icon_style]', 'quickfinder_item_icon_style'); ?><br />

	<label for="quickfinder_item_icon"><?php _e('Icon', 'scalia'); ?>:</label><br />
	<input name="scalia_quickfinder_item_data[icon]" type="text" id="quickfinder_item_icon" value="<?php echo esc_attr($quickfinder_item_data['icon']); ?>" size="60" /><br />
	<?php _e('Enter icon code', 'scalia'); ?>. <a href="<?php echo scalia_user_icons_info_link(); ?>" onclick="tb_show('<?php _e('Icons info', 'codeus'); ?>', this.href+'?TB_iframe=true'); return false;"><?php _e('Show Icon Codes', 'codeus'); ?></a>
</p>
<?php
}

function scalia_quickfinder_item_save_meta_box_data($post_id) {
	if(!isset($_POST['scalia_quickfinder_item_settings_box_nonce'])) {
		return;
	}
	if(!wp_verify_nonce($_POST['scalia_quickfinder_item_settings_box_nonce'], 'scalia_quickfinder_item_settings_box')) {
		return;
	}
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if(isset($_POST['post_type']) && 'scalia_qf_item' == $_POST['post_type']) {
		if(!current_user_can('edit_page', $post_id)) {
			return;
		}
	} else {
		if(!current_user_can('edit_post', $post_id)) {
			return;
		}
	}

	if(!isset($_POST['scalia_quickfinder_item_data']) || !is_array($_POST['scalia_quickfinder_item_data'])) {
		return;
	}

	$quickfinder_item_data = scalia_get_sanitize_qf_item_data(0, $_POST['scalia_quickfinder_item_data']);
	update_post_meta($post_id, 'scalia_quickfinder_item_data', $quickfinder_item_data);
}
add_action('save_post', 'scalia_quickfinder_item_save_meta_box_data');

function scalia_get_sanitize_qf_item_data($post_id = 0, $item_data = array()) {
	$quickfinder_item_data = array(
		'description' => '',
		'link' => '',
		'link_target' => '',
		'icon_color' => '',
		'icon_color_2' => '',
		'icon_background_color' => '',
		'icon_shape' => '',
		'icon_border_color' => '',
		'icon_size' => '',
		'icon_style' => '',
		'icon' => '',
	);
	if(is_array($item_data) && !empty($item_data)) {
		$quickfinder_item_data = array_merge($item_data);
	} elseif($post_id != 0) {
		$quickfinder_item_data = scalia_get_post_data($quickfinder_item_data, 'quickfinder_item', $post_id);
	}
	$quickfinder_item_data['description'] = sanitize_text_field($quickfinder_item_data['description']);
	$quickfinder_item_data['link'] = esc_url($quickfinder_item_data['link']);
	$quickfinder_item_data['link_target'] = scalia_check_array_value(array('_blank', '_self'), $quickfinder_item_data['link_target'], '_self');
	$quickfinder_item_data['icon_color'] = sanitize_text_field($quickfinder_item_data['icon_color']);
	$quickfinder_item_data['icon_color_2'] = sanitize_text_field($quickfinder_item_data['icon_color_2']);
	$quickfinder_item_data['icon_background_color'] = sanitize_text_field($quickfinder_item_data['icon_background_color']);
	$quickfinder_item_data['icon_border_color'] = sanitize_text_field($quickfinder_item_data['icon_border_color']);
	$quickfinder_item_data['icon_shape'] = scalia_check_array_value(array('circle', 'square'), $quickfinder_item_data['icon_shape'], 'circle');
	$quickfinder_item_data['icon_size'] = scalia_check_array_value(array('small', 'medium', 'big'), $quickfinder_item_data['icon_size'], 'big');
	$quickfinder_item_data['icon_style'] = scalia_check_array_value(array('', 'angle-45deg-r', 'angle-45deg-l', 'angle-90deg'), $quickfinder_item_data['icon_style'], '');
	$quickfinder_item_data['icon'] = sanitize_text_field($quickfinder_item_data['icon']);
	return $quickfinder_item_data;
}