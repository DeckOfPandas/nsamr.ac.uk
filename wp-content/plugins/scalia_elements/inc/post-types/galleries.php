<?php

function scalia_galleries_post_type_init() {
	$labels = array(
		'name'               => __('Galleries', 'scalia'),
		'singular_name'      => __('Gallery', 'scalia'),
		'menu_name'          => __('Galleries', 'scalia'),
		'name_admin_bar'     => __('Gallery', 'scalia'),
		'add_new'            => __('Add New', 'scalia'),
		'add_new_item'       => __('Add New Gallery', 'scalia'),
		'new_item'           => __('New Gallery', 'scalia'),
		'edit_item'          => __('Edit Gallery', 'scalia'),
		'view_item'          => __('View Gallery', 'scalia'),
		'all_items'          => __('All Galleries', 'scalia'),
		'search_items'       => __('Search Galleries', 'scalia'),
		'not_found'          => __('No galleries found.', 'scalia'),
		'not_found_in_trash' => __('No galleries found in Trash.', 'scalia')
	);

	$args = array(
		'labels'               => $labels,
		'public'               => false,
		'exclude_from_search'  => true,
		'publicly_queryable'   => false,
		'show_ui'              => true,
		'query_var'            => false,
		'hierarchical'         => false,
		'supports'             => array('title'),
		'register_meta_box_cb' => 'scalia_galleries_register_meta_box',
	);

	register_post_type('scalia_gallery', $args);
}
add_action('init', 'scalia_galleries_post_type_init');

/* GALLERY POST META BOX */

function scalia_galleries_register_meta_box($post) {
	add_meta_box('scalia_gallery_settings', sprintf(__('Gallery Manager (ID = %s)', 'scalia'), (isset($post->ID) ? $post->ID : 0)), 'scalia_gallery_settings_box', 'scalia_gallery', 'normal', 'high');
}

function scalia_gallery_settings_box($post) {
	wp_nonce_field('scalia_gallery_settings_box', 'scalia_gallery_settings_box_nonce');
	if(metadata_exists('post', $post->ID, 'scalia_gallery_images')) {
		$scalia_gallery_images_ids = get_post_meta($post->ID, 'scalia_gallery_images', true);
	} else {
		$attachments_ids = get_posts('post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids');
		$scalia_gallery_images_ids = implode(',', $attachments_ids);
	}
	$attachments_ids = array_filter(explode(',', $scalia_gallery_images_ids));

	echo '<div id="gallery_manager">';
	echo '<input type="hidden" id="scalia_gallery_images" name="scalia_gallery_images" value="' . esc_attr($scalia_gallery_images_ids) . '" />';
	echo '<a id="upload_button" class="button" href="javascript:void(0);" style="font-size: 16px;">' . __('Add images','scalia') . '</a>';

	echo '<ul class="gallery-images">';
	if($attachments_ids) {
		foreach($attachments_ids as $attachment_id) {
			echo '<li class="image" data-attachment_id="' . esc_attr($attachment_id) . '"><a target="_blank" href="' . get_edit_post_link($attachment_id) . '" class="edit">' .wp_get_attachment_image($attachment_id, 'thumbnail') . '</a><a href="javascript:void(0);" class="remove">x</a></li>';
		}
	}
	echo '</ul><br class="clear" />';

	echo '</div>';
?>

<?php
}

function scalia_gallery_save_meta_box_data($post_id) {
	if(!isset($_POST['scalia_gallery_settings_box_nonce'])) {
		return;
	}
	if(!wp_verify_nonce($_POST['scalia_gallery_settings_box_nonce'], 'scalia_gallery_settings_box')) {
		return;
	}
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if(isset($_POST['post_type']) && 'scalia_gallery' == $_POST['post_type']) {
		if(!current_user_can('edit_page', $post_id)) {
			return;
		}
	} else {
		if(!current_user_can('edit_post', $post_id)) {
			return;
		}
	}

	if(!isset($_POST['scalia_gallery_images'])) {
		return;
	}

	update_post_meta($post_id, 'scalia_gallery_images', $_POST['scalia_gallery_images']);
}
add_action('save_post', 'scalia_gallery_save_meta_box_data');
