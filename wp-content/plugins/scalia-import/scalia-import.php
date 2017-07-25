<?php
/*
Plugin Name: Scalia Import
Plugin URI: http://codex-themes.com/scalia/
Author: Codex Themes
Version: 1.0.2
Author URI: http://codex-themes.com/scalia/
*/


if(!function_exists('scalia_is_plugin_active')) {
	function scalia_is_plugin_active($plugin) {
		include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		return is_plugin_active($plugin);
	}
}

add_action('admin_menu', 'scalia_import_submenu_page');
function scalia_import_submenu_page() {
	add_menu_page( 'Scalia Import', 'Scalia Import', 'manage_options', 'scalia-import-submenu-page', 'scalia_import_page', '', 81 );
}

function scalia_import_page() {
?>
<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<h2>Scalia Import</h2>
	<?php if(scalia_is_plugin_active('wordpress-importer/wordpress-importer.php')) : ?>
		<p><?php printf(__('It seems that Wordpress Import Plugin is active. Please deactivate Wordpress Import Plugin on <a href="%s">plugins page</a> to proceed with import of Scalia\'s main demo content.'), admin_url('plugins.php')); ?></p>
	<?php elseif(get_stylesheet() != 'scalia') : ?>
		<p><?php _e('Your current activated theme in not Scalia main parent theme. Please note, that this import works only with Scalia main parent theme. Please activate Scalia main parent theme before proceeding with import.'); ?></p>
	<?php elseif(!scalia_is_plugin_active('scalia_elements/scalia_elements.php')) : ?>
		<p><?php printf(__('Plugin "Scalia Theme Elements" is not active.')); ?></p>
	<?php else : ?>
		<div class="scalia-import-output"></div>
	<?php endif; ?>
</div>
<?php
}

function scalia_import_enqueue($hook) {
	if($hook == 'toplevel_page_scalia-import-submenu-page') {
		wp_enqueue_script('scalia-import-scripts', plugins_url( '/js/si-scripts.js' , __FILE__ ), array('jquery'), false, true);
		wp_enqueue_style('scalia-import-css', plugins_url( '/css/si-styles.css' , __FILE__ ));
	}
}
add_action('admin_enqueue_scripts', 'scalia_import_enqueue');

add_action('wp_ajax_scalia_import_files_list', 'scalia_import_files_list');
function scalia_import_files_list () {
	$dir_content = array_values(array_diff(scandir(plugin_dir_path( __FILE__ ) . '/content'), array('..', '.')));
	echo json_encode(array('status' => 1, 'message' => 'Import is running. Please wait...', 'files_list' => $dir_content));
	die(-1);
}

add_action('wp_ajax_scalia_import_file', 'scalia_import_file');
function scalia_import_file () {
	if(!empty($_REQUEST['filename']) && file_exists(plugin_dir_path( __FILE__ ) . '/content/'.$_REQUEST['filename'])) {
		if (! defined('WP_LOAD_IMPORTERS')) define('WP_LOAD_IMPORTERS', true);
		require_once(plugin_dir_path( __FILE__ ) . '/inc/wordpress-importer.php');
		ob_start();
		$wp_import = new WP_Import();
		$wp_import->fetch_attachments = true;
		$wp_import->import(plugin_dir_path( __FILE__ ) . '/content/'.$_REQUEST['filename']);
		$messages = ob_get_clean();
		echo json_encode(array('status' => 1, 'message' => 'Done. <!-- '.$messages.' -->'));
	}
	die(-1);
}