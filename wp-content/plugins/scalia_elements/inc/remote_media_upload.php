<?php

/*add_action('admin_menu', 'scalia_remote_upload');
function scalia_remote_upload () {
	add_submenu_page( 'upload.php', 'Remote Upload', 'Remote Upload', 'manage_options', 'scalia-remote-upload', 'scalia_remote_upload_page', '' );
}*/

function scalia_remote_upload_page () {
?>
<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<h2>Remote Upload</h2>
	<form method="POST">
		<?php wp_nonce_field( 'scalia_remote_upload', 'scalia_remote_upload_field' ); ?>
		<input type="text" name="url" />
		<button type="submit"><?php _e('Upload', 'scalia'); ?></button>
	</form>
</div>
<?php
}

add_action('admin_init', 'scalia_remote_upload_submit');
function scalia_remote_upload_submit () {
	if(isset($_REQUEST['scalia_remote_upload_field']) && wp_verify_nonce( $_REQUEST['scalia_remote_upload_field'], 'scalia_remote_upload' ) && !empty($_REQUEST['url'])) {
		$url = $_REQUEST['url'];
		$result = media_sideload_image($url, 0);
	}
}

add_action('post-upload-ui', 'scalia_remote_upload_test');

function scalia_remote_upload_test () {
	wp_enqueue_script('scalia-remote-upload-scripts');
?>
	<h2>Remote Upload</h2>
		<textarea class="urls-list" rows="5" cols="100" style="vertical-align: top;"></textarea>
		<button type="submit" id="scalia-remote-upload-button"><?php _e('Upload', 'scalia'); ?></button>
<?php
}

add_action('admin_enqueue_scripts', 'scalia_remote_upload_enqueue');
function scalia_remote_upload_enqueue () {
	wp_register_script('scalia-remote-upload-scripts', plugins_url( '/../js/remote-upload.js' , __FILE__ ), array('jquery'), false, true);
	wp_localize_script('scalia-remote-upload-scripts', 'scalia_remote_upload_object', array(
		'security' => wp_create_nonce('scalia_remote_upload_ajax_security'),
	));
}

add_action('wp_ajax_scalia_remote_upload_ajax', 'scalia_remote_upload_ajax');
function scalia_remote_upload_ajax () {
	$urls = !empty($_REQUEST['urls']) ? explode("\n", $_REQUEST['urls']) : array();
	foreach($urls as $url) {
		$result = media_sideload_image($url, 0);
	}
	die(-1);
}
