<?php

$PORTFOLIO_TYPE_OPTIONS = array('self-link' => __('Portfolio Page', 'scalia'), 'inner-link' => __('Internal Link', 'scalia'), 'outer-link' => __('External Link', 'scalia'), 'full-image' => __('Full-Size Image', 'scalia'), 'youtube' => __('YouTube Video', 'scalia'), 'vimeo' => __('Vimeo Video', 'scalia'), 'self_video' => __('Self-Hosted Video', 'scalia'));

function scalia_portfolio_item_post_type_init() {
	$labels = array(
		'name'               => __('Portfolio Items', 'scalia'),
		'singular_name'      => __('Portfolio Item', 'scalia'),
		'menu_name'          => __('Portfolios', 'scalia'),
		'name_admin_bar'     => __('Portfolio Item', 'scalia'),
		'add_new'            => __('Add New', 'scalia'),
		'add_new_item'       => __('Add New Portfolio Item', 'scalia'),
		'new_item'           => __('New Portfolio Item', 'scalia'),
		'edit_item'          => __('Edit Portfolio Item', 'scalia'),
		'view_item'          => __('View Portfolio Item', 'scalia'),
		'all_items'          => __('All Portfolio Items', 'scalia'),
		'search_items'       => __('Search Portfolio Items', 'scalia'),
		'not_found'          => __('No portfolio items found.', 'scalia'),
		'not_found_in_trash' => __('No portfolio items found in Trash.', 'scalia')
	);

	$args = array(
		'labels'               => $labels,
		'public'               => true,
		'publicly_queryable'   => true,
		'show_ui'              => true,
		'query_var'            => true,
		'hierarchical'         => false,
		'register_meta_box_cb' => 'scalia_portfolio_items_register_meta_box',
		'taxonomies'           => array('scalia_portfolios'),
		'rewrite' => array('slug' => 'portfolios', 'with_front' => false),
		'capability_type' => 'page',
		'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'comments'),
	);

	register_post_type('scalia_pf_item', $args);

	$labels = array(
		'name'                       => __('Portfolios', 'scalia'),
		'singular_name'              => __('Portfolio', 'scalia'),
		'search_items'               => __('Search Portfolios', 'scalia'),
		'popular_items'              => __('Popular Portfolios', 'scalia'),
		'all_items'                  => __('All Portfolios', 'scalia'),
		'edit_item'                  => __('Edit Portfolio', 'scalia'),
		'update_item'                => __('Update Portfolio', 'scalia'),
		'add_new_item'               => __('Add New Portfolio', 'scalia'),
		'new_item_name'              => __('New Portfolio Name', 'scalia'),
		'separate_items_with_commas' => __('Separate Portfolios with commas', 'scalia'),
		'add_or_remove_items'        => __('Add or remove Portfolios', 'scalia'),
		'choose_from_most_used'      => __('Choose from the most used Portfolios', 'scalia'),
		'not_found'                  => __('No portfolios found.', 'scalia'),
		'menu_name'                  => __('Portfolios', 'scalia'),
	);

	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => false,
		'public'                => false,
		'rewrite'               => false,
	);

	register_taxonomy('scalia_portfolios', 'scalia_pf_item', $args);
}
add_action('init', 'scalia_portfolio_item_post_type_init', 5);

/* PORTFOLIO ITEM POST META BOX */

function scalia_portfolio_items_register_meta_box($post) {
	add_meta_box('scalia_portfolio_item_settings', __('Portfolio Item Settings', 'scalia'), 'scalia_portfolio_item_settings_box', 'scalia_pf_item', 'normal', 'high');
}

function print_portfolio_more_type($item_type, $types_index) {
	global $PORTFOLIO_TYPE_OPTIONS;
?>
	<div class="portfolio_item_element_<?php echo $types_index; ?>" id="portfolio_item_type_<?php echo $types_index; ?>_wrapper">
		<label for="portfolio_item_type_<?php echo $types_index; ?>"><?php _e('Type of portfolio item', 'scalia'); ?>:</label><br />
		<?php scalia_print_select_input($PORTFOLIO_TYPE_OPTIONS, $item_type['type'], 'scalia_portfolio_item_data[types]['.$types_index.'][type]', 'portfolio_item_type_'.$types_index); ?>
	</div>

	<div class="portfolio_item_element_<?php echo $types_index; ?>" id="portfolio_item_link_target_<?php echo $types_index; ?>_wrapper">
		<label for="portfolio_item_link_target_<?php echo $types_index; ?>"><?php _e('Link target', 'scalia'); ?>:</label><br />
		<?php scalia_print_select_input(array('_self' => 'Self', '_blank' => 'Blank'), $item_type['link_target'], 'scalia_portfolio_item_data[types]['.$types_index.'][link_target]', 'portfolio_item_link_target_'.$types_index); ?>
	</div>

	<div class="portfolio_item_element_<?php echo $types_index; ?>" id="portfolio_item_link_<?php echo $types_index; ?>_wrapper">
		<label for="portfolio_item_link_<?php echo $types_index; ?>"><?php _e('Link to another page or video ID (for YouTube or Vimeo):', 'scalia'); ?>:</label><br />
		<input name="scalia_portfolio_item_data[types][<?php echo esc_attr($types_index); ?>][link]" type="text" id="portfolio_item_link_<?php echo esc_attr($types_index); ?>" value="<?php echo esc_attr($item_type['link']); ?>" size="60" />
	</div>

	<div class="portfolio_item_element_<?php echo $types_index; ?>" id="portfolio_item_remove_button_<?php echo $types_index; ?>_wrapper">
		<a href="#" onclick="return portfolio_remove_item_type(this);">Remove type</a>
	</div>
	<div class="portfolio_item_element_<?php echo $types_index; ?>"><br /></div>
<?php
}

function scalia_portfolio_item_settings_box($post) {
	global $PORTFOLIO_TYPE_OPTIONS;

	wp_nonce_field('scalia_portfolio_item_settings_box', 'scalia_portfolio_item_settings_box_nonce');
	$portfolio_item_data = scalia_get_sanitize_pf_item_data($post->ID);
	$default_portfolio_type = array('link' => '', 'link_target' => '_self', 'type' => 'self-link');
	if (empty($portfolio_item_data['types']))
		$portfolio_item_data['types'] = array(0 => $default_portfolio_type);
	$types_index = 0;
?>
<p class="meta-options">
	<input name="scalia_portfolio_item_data[highlight]" type="checkbox" id="portfolio_item_highlight" value="1" <?php checked($portfolio_item_data['highlight'], 1); ?> />
	<label for="portfolio_item_highlight"><?php _e('Show as Highlight?', 'scalia'); ?></label>
	<br /><br />

	<label for="portfolio_item_overview_title"><?php _e('Overview title', 'scalia'); ?>:</label><br />
	<input name="scalia_portfolio_item_data[overview_title]" type="text" id="portfolio_item_overview_title" value="<?php echo esc_attr($portfolio_item_data['overview_title']); ?>" size="60" />
	<br /><br />

	<div id="add_portfolio_item_type_template" style="display: none;">
		<?php print_portfolio_more_type($default_portfolio_type, '%INDEX%'); ?>
	</div>

	<div class="portfolio-types">
		<?php
			foreach($portfolio_item_data['types'] as $item_type) {
				print_portfolio_more_type($item_type, $types_index);
				$types_index++;
			}
		?>
	</div>
	<a href="#" onclick="return portfolio_add_item_type(this);">Add one more type</a>
	<script type='text/javascript'>
		init_portfolio_settings();
	</script>
</p>
<?php
}

function scalia_portfolio_item_save_meta_box_data($post_id) {
	if(!isset($_POST['scalia_portfolio_item_settings_box_nonce'])) {
		return;
	}
	if(!wp_verify_nonce($_POST['scalia_portfolio_item_settings_box_nonce'], 'scalia_portfolio_item_settings_box')) {
		return;
	}
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if(isset($_POST['post_type']) && 'scalia_pf_item' == $_POST['post_type']) {
		if(!current_user_can('edit_page', $post_id)) {
			return;
		}
	} else {
		if(!current_user_can('edit_post', $post_id)) {
			return;
		}
	}

	if(!isset($_POST['scalia_portfolio_item_data']) || !is_array($_POST['scalia_portfolio_item_data'])) {
		return;
	}

	$portfolio_item_data = scalia_get_sanitize_pf_item_data(0, $_POST['scalia_portfolio_item_data']);
	update_post_meta($post_id, 'scalia_portfolio_item_data', $portfolio_item_data);
}
add_action('save_post', 'scalia_portfolio_item_save_meta_box_data');

function scalia_get_sanitize_pf_item_data($post_id = 0, $item_data = array()) {
	global $PORTFOLIO_TYPE_OPTIONS;

	$portfolio_item_data = array(
		'highlight' => 0,
		'overview_title' => '',
		'types' => array()
	);
	if(is_array($item_data) && !empty($item_data)) {
		$portfolio_item_data = array_merge($portfolio_item_data, $item_data);
	} elseif($post_id != 0) {
		$portfolio_item_data = scalia_get_post_data($portfolio_item_data, 'portfolio_item', $post_id);
	}
	$portfolio_item_data['highlight'] = $portfolio_item_data['highlight'] ? 1 : 0;
	$portfolio_item_data['overview_title'] = sanitize_text_field($portfolio_item_data['overview_title']);
	if (isset($portfolio_item_data['types']['%INDEX%']))
		unset($portfolio_item_data['types']['%INDEX%']);
	
	$portfolio_item_data_types = array();
	foreach ($portfolio_item_data['types'] as $k => $v) {
		$v['link_target'] = scalia_check_array_value(array('_blank', '_self'), $v['link_target'], '_self');
		$portfolio_type_options = array_keys($PORTFOLIO_TYPE_OPTIONS);
		$v['type'] = scalia_check_array_value($portfolio_type_options, $v['type'], 'self-link');
		if (!in_array($v['type'], array('youtube', 'vimeo')))
			$v['link'] = esc_url($v['link']);

		$portfolio_item_data_types[] = $v;
	}
	$portfolio_item_data['types'] = array_slice($portfolio_item_data_types, 0, 4);
	
	return $portfolio_item_data;
}

add_action('scalia_portfolios_edit_form','scalia_portfolios_form');
add_action('scalia_portfolios_add_form','scalia_portfolios_form');

function scalia_portfolios_form() {
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
}

add_action('scalia_portfolios_edit_form_fields','scalia_portfolios_edit_form_fields');
function scalia_portfolios_edit_form_fields() {
?>
	<tr class="form-field">
		<th valign="top" scope="row"><label for="portfoliosets_icon"><?php _e('Icon', 'scalia'); ?></label></th>
		<td>
			<input class= "icon" type="text" id="portfoliosets_icon" name="portfoliosets_icon" value="<?php echo esc_attr(get_option('portfoliosets_' . $_REQUEST['tag_ID'] . '_icon')); ?>"/><br />
			<span class="description"><?php _e('Enter icon code', 'scalia'); ?>. <a href="<?php echo scalia_user_icons_info_link(); ?>" onclick="tb_show('<?php _e('Icons info', 'scalia'); ?>', this.href+'?TB_iframe=true'); return false;"><?php _e('Show Icon Codes', 'scalia'); ?></a></span>
		</td>
	</tr>
	<tr class="form-field">
		<th valign="top" scope="row"><label for="portfoliosets_order"><?php _e('Order', 'scalia'); ?></label></th>
		<td>
			<input type="text" id="portfoliosets_order" name="portfoliosets_order" value="<?php echo esc_attr(get_option('portfoliosets_' . $_REQUEST['tag_ID'] . '_order', 0)); ?>"/><br />
		</td>
	</tr>
<?php
}

add_action('scalia_portfolios_add_form_fields','scalia_portfolios_add_form_fields');
function scalia_portfolios_add_form_fields() {
?>
	<div class="form-field">
		<label for="portfoliosets_icon"><?php _e('Icon', 'scalia'); ?></label>
		<input class= "icon" type="text" id="portfoliosets_icon" name="portfoliosets_icon"/><br/>
		<?php _e('Enter icon code', 'scalia'); ?>. <a href="<?php echo scalia_user_icons_info_link(); ?>" onclick="tb_show('<?php _e('Icons info', 'scalia'); ?>', this.href+'?TB_iframe=true'); return false;"><?php _e('Show Icon Codes', 'scalia'); ?></a>
	</div>
	<div class="form-field">
		<label for="portfoliosets_order"><?php _e('Order', 'scalia'); ?></label>
		<input class= "icon" type="text" id="portfoliosets_order" name="portfoliosets_order" value="0"/><br/>
	</div>
<?php
}

add_action('create_scalia_portfolios','scalia_portfolios_create');
function scalia_portfolios_create($id) {
	if(isset($_REQUEST['portfoliosets_icon'])) {
		update_option( 'portfoliosets_' . $id . '_icon', $_REQUEST['portfoliosets_icon'] );
	}
	$order = isset($_REQUEST['portfoliosets_order']) ? intval($_REQUEST['portfoliosets_order']) : 0;
	update_option( 'portfoliosets_' . $id . '_order', $order );
}

add_action('edit_scalia_portfolios','scalia_portfolios_update');
function scalia_portfolios_update($id) {
	if(isset($_REQUEST['portfoliosets_icon'])) {
		update_option( 'portfoliosets_' . $id . '_icon', $_REQUEST['portfoliosets_icon'] );
	}
	$order = isset($_REQUEST['portfoliosets_order']) ? intval($_REQUEST['portfoliosets_order']) : 0;
	update_option( 'portfoliosets_' . $id . '_order', $order );
}

add_action('delete_scalia_portfolios','scalia_portfolios_delete');
function scalia_portfolios_delete($id) {
	delete_option( 'portfoliosets_' . $id . '_icon' );
	delete_option( 'portfoliosets_' . $id . '_order' );
}

add_action('wp_ajax_portfolio_load_more', 'portfolio_load_more_callback');
add_action('wp_ajax_nopriv_portfolio_load_more', 'portfolio_load_more_callback');
function portfolio_load_more_callback() {
	$response = array();
	if ( !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'portfolio_ajax-nonce' ) ) {
		$response = array('status' => 'error', 'message' => 'Error verify nonce');
		$response = json_encode($response);
		header( "Content-Type: application/json" );
		echo $response;
		exit;
	}
	$data = isset($_POST['data']) ? $_POST['data'] : array();
	$data['is_ajax'] = true;
	$response = array('status' => 'success');
	ob_start();
	scalia_portfolio($data);
	$response['html'] = trim(preg_replace('/\s\s+/', '', ob_get_clean()));
	$response = json_encode($response);
	header( "Content-Type: application/json" );
	echo $response;
	exit;
}
