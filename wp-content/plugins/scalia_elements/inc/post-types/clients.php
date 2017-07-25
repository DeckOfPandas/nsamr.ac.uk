<?php

function scalia_clients_post_type_init() {
	$labels = array(
		'name'               => __('Clients', 'scalia'),
		'singular_name'      => __('Client', 'scalia'),
		'menu_name'          => __('Clients', 'scalia'),
		'name_admin_bar'     => __('Client', 'scalia'),
		'add_new'            => __('Add New', 'scalia'),
		'add_new_item'       => __('Add New Client', 'scalia'),
		'new_item'           => __('New Client', 'scalia'),
		'edit_item'          => __('Edit Client', 'scalia'),
		'view_item'          => __('View Client', 'scalia'),
		'all_items'          => __('All Clients', 'scalia'),
		'search_items'       => __('Search Clients', 'scalia'),
		'not_found'          => __('No clients found.', 'scalia'),
		'not_found_in_trash' => __('No clients found in Trash.', 'scalia')
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
		'register_meta_box_cb' => 'scalia_clients_register_meta_box',
		'taxonomies'           => array('scalia_clients_sets')
	);

	register_post_type('scalia_client', $args);

	$labels = array(
		'name'                       => __('Clients Sets', 'scalia'),
		'singular_name'              => __('Clients Set', 'scalia'),
		'search_items'               => __('Search Clients Sets', 'scalia'),
		'popular_items'              => __('Popular Clients Sets', 'scalia'),
		'all_items'                  => __('All Clients Sets', 'scalia'),
		'edit_item'                  => __('Edit Clients Set', 'scalia'),
		'update_item'                => __('Update Clients Set', 'scalia'),
		'add_new_item'               => __('Add New Clients Set', 'scalia'),
		'new_item_name'              => __('New Clients Set Name', 'scalia'),
		'separate_items_with_commas' => __('Separate Clients Sets with commas', 'scalia'),
		'add_or_remove_items'        => __('Add or remove Clients Sets', 'scalia'),
		'choose_from_most_used'      => __('Choose from the most used Clients Sets', 'scalia'),
		'not_found'                  => __('No clients sets found.', 'scalia'),
		'menu_name'                  => __('Clients Sets', 'scalia'),
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

	register_taxonomy('scalia_clients_sets', 'scalia_client', $args);
}
add_action('init', 'scalia_clients_post_type_init');

/* CLIENT POST META BOX */

function scalia_clients_register_meta_box($post) {
	remove_meta_box('postimagediv', 'scalia_client', 'side');
	add_meta_box('postimagediv', __('Client Image', 'scalia'), 'post_thumbnail_meta_box', 'scalia_client', 'normal', 'high');
	add_meta_box('scalia_client_settings', __('Client Settings', 'scalia'), 'scalia_client_settings_box', 'scalia_client', 'normal', 'high');
}

function scalia_client_settings_box($post) {
	wp_nonce_field('scalia_client_settings_box', 'scalia_client_settings_box_nonce');
	$client_data = scalia_get_sanitize_client_data($post->ID);
?>
<p class="meta-options">
	<label for="client_description"><?php _e('Description', 'scalia'); ?>:</label><br />
	<input name="scalia_client_data[description]" type="text" id="client_description" value="<?php echo esc_attr($client_data['description']); ?>" size="60" /><br />
	<br />
	<label for="client_link"><?php _e('Link', 'scalia'); ?>:</label><br />
	<input name="scalia_client_data[link]" type="text" id="client_link" value="<?php echo esc_attr($client_data['link']); ?>" size="60" /><br />
	<br />
	<label for="client_link_target"><?php _e('Link target', 'scalia'); ?>:</label><br />
	<?php scalia_print_select_input(array('_blank' => 'Blank', '_self' => 'Self'), $client_data['link_target'], 'scalia_client_data[link_target]', 'client_link_target'); ?>
</p>
<?php
}

function scalia_client_save_meta_box_data($post_id) {
	if(!isset($_POST['scalia_client_settings_box_nonce'])) {
		return;
	}
	if(!wp_verify_nonce($_POST['scalia_client_settings_box_nonce'], 'scalia_client_settings_box')) {
		return;
	}
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if(isset($_POST['post_type']) && 'scalia_client' == $_POST['post_type']) {
		if(!current_user_can('edit_page', $post_id)) {
			return;
		}
	} else {
		if(!current_user_can('edit_post', $post_id)) {
			return;
		}
	}

	if(!isset($_POST['scalia_client_data']) || !is_array($_POST['scalia_client_data'])) {
		return;
	}

	$client_data = scalia_get_sanitize_client_data(0, $_POST['scalia_client_data']);
	update_post_meta($post_id, 'scalia_client_data', $client_data);
}
add_action('save_post', 'scalia_client_save_meta_box_data');

function scalia_get_sanitize_client_data($post_id = 0, $item_data = array()) {
	$client_data = array(
		'description' => '',
		'link' => '',
		'link_target' => ''
	);
	if(is_array($item_data) && !empty($item_data)) {
		$client_data = array_merge($item_data);
	} elseif($post_id != 0) {
		$client_data = scalia_get_post_data($client_data, 'client', $post_id);
	}
	$client_data['description'] = sanitize_text_field($client_data['description']);
	$client_data['link'] = esc_url($client_data['link']);
	$client_data['link_target'] = scalia_check_array_value(array('_blank', '_self'), $client_data['link_target'], '_blank');
	return $client_data;
}

/* CLIENTS SET OPTIONS */

function scalia_clients_sets_add_form_fields_callback() {
?>
	<div class="form-field">
		<label for="clients_set_icon"><?php _e('Icon', 'scalia'); ?></label>
		<input class= "icon" type="text" id="clients_set_icon" name="clients_set_icon"/><br/>
		<?php _e('Enter icon code', 'scalia'); ?>. <a href="<?php echo scalia_user_icons_info_link(); ?>" onclick="tb_show('<?php _e('Icons info', 'scalia'); ?>', this.href+'?TB_iframe=true'); return false;"><?php _e('Show Icon Codes', 'scalia'); ?></a>
	</div>
<?php
}
add_action('scalia_clients_sets_add_form_fields','scalia_clients_sets_add_form_fields_callback');

function scalia_clients_sets_edit_form_fields_callback() {
?>
	<tr class="form-field">
		<th valign="top" scope="row"><label for="clients_set_icon"><?php _e('Icon', 'scalia'); ?></label></th>
		<td>
			<input class= "icon" type="text" id="clients_set_icon" name="clients_set_icon" value="<?php echo esc_attr(get_option('scalia_clients_set_' . $_REQUEST['tag_ID'] . '_icon')); ?>"/><br />
			<span class="description"><?php _e('Enter icon code', 'scalia'); ?>. <a href="<?php echo scalia_user_icons_info_link(); ?>" onclick="tb_show('<?php _e('Icons info', 'scalia'); ?>', this.href+'?TB_iframe=true'); return false;"><?php _e('Show Icon Codes', 'scalia'); ?></a></span>
		</td>
	</tr>
<?php
}
add_action('scalia_clients_sets_edit_form_fields','scalia_clients_sets_edit_form_fields_callback');

function scalia_clients_sets_create_callback($id) {
	if(isset($_REQUEST['clients_set_icon'])) {
		update_option('scalia_clients_set_' . $id . '_icon', $_REQUEST['clients_set_icon']);
	}
}
add_action('create_scalia_clients_sets','scalia_clients_sets_create_callback');

function scalia_clients_sets_update_callback($id) {
	if(isset($_REQUEST['clients_set_icon'])) {
		update_option('scalia_clients_set_' . $id . '_icon', $_REQUEST['clients_set_icon']);
	}
}
add_action('edit_scalia_clients_sets','scalia_clients_sets_update_callback');

function scalia_clients_sets_delete_callback($id) {
	delete_option('scalia_clients_set_' . $id . '_icon');
}
add_action('delete_scalia_clients_sets','scalia_clients_sets_delete_callback');