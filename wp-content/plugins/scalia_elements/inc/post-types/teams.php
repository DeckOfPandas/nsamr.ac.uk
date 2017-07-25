<?php

function scalia_team_person_post_type_init() {
	$labels = array(
		'name'               => __('Team Persons', 'scalia'),
		'singular_name'      => __('Team Person', 'scalia'),
		'menu_name'          => __('Teams', 'scalia'),
		'name_admin_bar'     => __('Team Person', 'scalia'),
		'add_new'            => __('Add New', 'scalia'),
		'add_new_item'       => __('Add New Person', 'scalia'),
		'new_item'           => __('New Person', 'scalia'),
		'edit_item'          => __('Edit Person', 'scalia'),
		'view_item'          => __('View Person', 'scalia'),
		'all_items'          => __('All Persons', 'scalia'),
		'search_items'       => __('Search Persons', 'scalia'),
		'not_found'          => __('No persons found.', 'scalia'),
		'not_found_in_trash' => __('No persons found in Trash.', 'scalia')
	);

	$args = array(
		'labels'               => $labels,
		'public'               => false,
		'exclude_from_search'  => true,
		'publicly_queryable'   => false,
		'show_ui'              => true,
		'query_var'            => false,
		'hierarchical'         => false,
		'supports'             => array('title', 'editor', 'thumbnail', 'page-attributes'),
		'register_meta_box_cb' => 'scalia_team_persons_register_meta_box',
		'taxonomies'           => array('scalia_teams')
	);

	register_post_type('scalia_team_person', $args);

	$labels = array(
		'name'                       => __('Teams', 'scalia'),
		'singular_name'              => __('Team', 'scalia'),
		'search_items'               => __('Search Teams', 'scalia'),
		'popular_items'              => __('Popular Teams', 'scalia'),
		'all_items'                  => __('All Teams', 'scalia'),
		'edit_item'                  => __('Edit Team', 'scalia'),
		'update_item'                => __('Update Team', 'scalia'),
		'add_new_item'               => __('Add New Team', 'scalia'),
		'new_item_name'              => __('New Team Name', 'scalia'),
		'separate_items_with_commas' => __('Separate Teams with commas', 'scalia'),
		'add_or_remove_items'        => __('Add or remove Teams', 'scalia'),
		'choose_from_most_used'      => __('Choose from the most used Teams', 'scalia'),
		'not_found'                  => __('No teams found.', 'scalia'),
		'menu_name'                  => __('Teams', 'scalia'),
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

	register_taxonomy('scalia_teams', 'scalia_team_person', $args);
}
add_action('init', 'scalia_team_person_post_type_init');

/* PERSON POST META BOX */

function scalia_team_persons_register_meta_box($post) {
	add_meta_box('scalia_team_person_settings', __('Person Settings', 'scalia'), 'scalia_team_person_settings_box', 'scalia_team_person', 'normal', 'high');
}

function scalia_team_person_settings_box($post) {
	wp_nonce_field('scalia_team_person_settings_box', 'scalia_team_person_settings_box_nonce');
	$person_data = scalia_get_sanitize_team_person_data($post->ID);
?>
<p class="meta-options">
	<label for="person_name"><?php _e('Name', 'scalia'); ?>:</label><br />
	<input name="scalia_team_person_data[name]" type="text" id="person_name" value="<?php echo esc_attr($person_data['name']); ?>" size="60" /><br />
	<br />
	<label for="person_position"><?php _e('Position', 'scalia'); ?>:</label><br />
	<input name="scalia_team_person_data[position]" type="text" id="person_position" value="<?php echo esc_attr($person_data['position']); ?>" size="60" /><br />
	<br />
	<label for="person_phone"><?php _e('Phone', 'scalia'); ?>:</label><br />
	<input name="scalia_team_person_data[phone]" type="text" id="person_phone" value="<?php echo esc_attr($person_data['phone']); ?>" size="60" /><br />
	<br />
	<label for="person_email"><?php _e('Email', 'scalia'); ?>:</label><br />
	<input name="scalia_team_person_data[email]" type="email" id="person_email" value="<?php echo esc_attr($person_data['email']); ?>" size="60" /><br />
	<br />
	<input name="scalia_team_person_data[hide_email]" type="checkbox" id="person_hide_email" value="1" <?php checked($person_data['hide_email'], 1); ?> />
	<label for="person_hide_email"><?php _e('Hide Email', 'scalia'); ?></label><br />
	<br />
	<label for="person_link"><?php _e('Link', 'scalia'); ?>:</label><br />
	<input name="scalia_team_person_data[link]" type="text" id="person_link" value="<?php echo esc_attr($person_data['link']); ?>" size="60" /><br />
	<br />
	<label for="person_link_target"><?php _e('Link target', 'scalia'); ?>:</label><br />
	<?php scalia_print_select_input(array('_self' => 'Self', '_blank' => 'Blank'), $person_data['link_target'], 'scalia_team_person_data[link_target]', 'person_link_target'); ?>
</p>
<?php
}

function scalia_team_person_save_meta_box_data($post_id) {
	if(!isset($_POST['scalia_team_person_settings_box_nonce'])) {
		return;
	}
	if(!wp_verify_nonce($_POST['scalia_team_person_settings_box_nonce'], 'scalia_team_person_settings_box')) {
		return;
	}
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if(isset($_POST['post_type']) && 'scalia_team_person' == $_POST['post_type']) {
		if(!current_user_can('edit_page', $post_id)) {
			return;
		}
	} else {
		if(!current_user_can('edit_post', $post_id)) {
			return;
		}
	}

	if(!isset($_POST['scalia_team_person_data']) || !is_array($_POST['scalia_team_person_data'])) {
		return;
	}

	$person_data = scalia_get_sanitize_team_person_data(0, $_POST['scalia_team_person_data']);
	$person_data['link_target'] = scalia_check_array_value(array('_blank', '_self'), $person_data['link_target'], '_self');

	update_post_meta($post_id, 'scalia_team_person_data', $person_data);
}
add_action('save_post', 'scalia_team_person_save_meta_box_data');

function scalia_get_sanitize_team_person_data($post_id = 0, $item_data = array()) {
	$person_data = array(
		'name' => '',
		'position' => '',
		'phone' => '',
		'email' => '',
		'hide_email' => false,
		'link' => '',
		'link_target' => ''
	);
	if(is_array($item_data) && !empty($item_data)) {
		$person_data = array_merge($item_data);
	} elseif($post_id != 0) {
		$person_data = scalia_get_post_data($person_data, 'team_person', $post_id);
	}
	$person_data['name'] = sanitize_text_field($person_data['name']);
	$person_data['position'] = sanitize_text_field($person_data['position']);
	$person_data['phone'] = sanitize_text_field($person_data['phone']);
	$person_data['email'] = sanitize_email($person_data['email']);
	$person_data['hide_email'] = $person_data['hide_email'] ? 1 : 0;
	$person_data['link'] = esc_url($person_data['link']);
	$person_data['link_target'] = scalia_check_array_value(array('_blank', '_self'), $person_data['link_target'], '_self');
	return $person_data;
}