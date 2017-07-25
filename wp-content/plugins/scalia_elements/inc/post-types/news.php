<?php


function scalia_news_post_type_init() {
	$labels = array(
		'name'               => __('News', 'scalia'),
		'singular_name'      => __('News', 'scalia'),
		'menu_name'          => __('News', 'scalia'),
		'name_admin_bar'     => __('News', 'scalia'),
		'add_new'            => __('Add New', 'scalia'),
		'add_new_item'       => __('Add New News', 'scalia'),
		'new_item'           => __('New News', 'scalia'),
		'edit_item'          => __('Edit News', 'scalia'),
		'view_item'          => __('View News', 'scalia'),
		'all_items'          => __('All News', 'scalia'),
		'search_items'       => __('Search News', 'scalia'),
		'not_found'          => __('No news found.', 'scalia'),
		'not_found_in_trash' => __('No news found in Trash.', 'scalia')
	);


	$args = array(
		'labels'               => $labels,
		'public'               => true,
		'publicly_queryable'   => true,
		'show_ui'              => true,
		'query_var'            => true,
		'hierarchical'         => false,
		'has_archive' => true,
		'register_meta_box_cb' => 'scalia_post_items_register_meta_box',
		'taxonomies'           => array('scalia_news_sets'),
		'rewrite' => array('slug' => 'news', 'with_front' => false),
		'capability_type' => 'page',
		'supports'             => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'comments'),
	);

	register_post_type('scalia_news', $args);
	$labels = array(
		'name'                       => __('News Sets', 'scalia'),
		'singular_name'              => __('News Set', 'scalia'),
		'search_items'               => __('Search News Sets', 'scalia'),
		'popular_items'              => __('Popular News Sets', 'scalia'),
		'all_items'                  => __('All News Sets', 'scalia'),
		'edit_item'                  => __('Edit News Set', 'scalia'),
		'update_item'                => __('Update News Set', 'scalia'),
		'add_new_item'               => __('Add New News Set', 'scalia'),
		'new_item_name'              => __('New News Set Name', 'scalia'),
		'separate_items_with_commas' => __('Separate News Sets with commas', 'scalia'),
		'add_or_remove_items'        => __('Add or remove News Sets', 'scalia'),
		'choose_from_most_used'      => __('Choose from the most used News Sets', 'scalia'),
		'not_found'                  => __('No news sets found.', 'scalia'),
		'menu_name'                  => __('News Sets', 'scalia'),
	);

	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array('slug' => 'news_sets'),
	);

	register_taxonomy('scalia_news_sets', 'scalia_news', $args);

}
add_action('init', 'scalia_news_post_type_init', 5);

function scalia_post_items_register_meta_box($post) {
	add_meta_box('scalia_news_item_settings', __('News Item Settings', 'scalia'), 'scalia_post_item_settings_box', 'scalia_news', 'normal', 'high');
}

add_action('wp_ajax_blog_load_more', 'blog_load_more_callback');
add_action('wp_ajax_nopriv_blog_load_more', 'blog_load_more_callback');
function blog_load_more_callback() {
	$response = array();
	if ( !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'blog_ajax-nonce' ) ) {
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
	scalia_blog($data);
	$response['html'] = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
	$response = json_encode($response);
	header( "Content-Type: application/json" );
	echo $response;
	exit;
}
