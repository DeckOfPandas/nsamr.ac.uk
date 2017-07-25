<?php

function scalia_selfvideo($file = '', $image = '', $width = 400, $height = 300, $class = '') {
	$id = 'selfvideo-'.time().rand();
	ob_start();
?>
<?php if(!scalia_selfvideoerror()) : ?>
	<div id="<?php echo $id; ?>" class="<?php echo $class; ?>"></div>
	<script type="text/javascript">
		(function($) {
			$(function() {
				jwplayer('<?php echo $id; ?>').setup({
					file: '<?php echo $file; ?>',
					height: '<?php echo str_replace('px','',$height); ?>',
					<?php if($image) : ?>image: "<?php echo $image; ?>",<?php endif; ?>
					width: '<?php echo str_replace('px','',$width); ?>'
				});
			});
		})(jQuery);
	</script>
<?php else : ?>
	<?php echo scalia_selfvideoerror(); ?>
<?php endif; ?>
<?php
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}

function scalia_selfvideoerror() {
	require_once(ABSPATH . 'wp-admin/includes/plugin.php');
	if(is_plugin_active('jw-player-plugin-for-wordpress/jwplayermodule.php')) {
		return false;
	} elseif(is_plugin_inactive('jw-player-plugin-for-wordpress/jwplayermodule.php')) {
		return sprintf(__('Plugin "JW Player for Flash & HTML5 Video" is not active. Use this <a href="%s">link</a> to activate it.', 'scalia'), admin_url('plugins.php'));
	} else {
		return sprintf(__('Plugin "JW Player for Flash & HTML5 Video" is not installed. Use this <a href="%s">link</a> to install and activate it.'), admin_url('plugin-install.php?tab=search&s=jw-player-plugin-for-wordpress'));
	}
}
