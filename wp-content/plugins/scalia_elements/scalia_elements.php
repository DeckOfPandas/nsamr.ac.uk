<?php
/*
Plugin Name: Scalia Theme Elements
Plugin URI: http://codex-themes.com/scalia/
Author: Codex Themes
Version: 1.4.5
Author URI: http://codex-themes.com/scalia/
TextDomain: scalia
DomainPath: /languages
*/

add_action( 'plugins_loaded', 'scalia_load_textdomain' );
function scalia_load_textdomain() {
	load_plugin_textdomain( 'scalia', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

if(!function_exists('scalia_is_plugin_active')) {
	function scalia_is_plugin_active($plugin) {
		include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		return is_plugin_active($plugin);
	}
}

if(!function_exists('scalia_user_icons_info_link')) {
function scalia_user_icons_info_link() {
	return esc_url(apply_filters('scalia_user_icons_info_link', get_template_directory_uri().'/fonts/user-icons-list.html'));
}
}

/* Get theme option*/
if(!function_exists('scalia_get_option')) {
function scalia_get_option($name, $default = false, $ml_full = false) {
	$options = get_option('scalia_theme_options');
	if(isset($options[$name])) {
		$ml_options = array('home_content', 'footer_html', 'contacts_address', 'contacts_phone', 'contacts_fax', 'contacts_email', 'contacts_website');
		if(in_array($name, $ml_options) && is_array($options[$name]) && !$ml_full) {
			if(defined('ICL_LANGUAGE_CODE')) {
				global $sitepress;
				if(isset($options[$name][ICL_LANGUAGE_CODE])) {
					$options[$name] = $options[$name][ICL_LANGUAGE_CODE];
				} elseif($sitepress->get_default_language() && isset($options[$name][$sitepress->get_default_language()])) {
					$options[$name] = $options[$name][$sitepress->get_default_language()];
				} else {
					$options[$name] = '';
				}
			}else {
				$options[$name] = reset($options[$name]);
			}
		}
		return apply_filters('scalia_option_'.$name, $options[$name]);
	}
	return apply_filters('scalia_option_'.$name, $default);
}
}

require_once(plugin_dir_path( __FILE__ ) . 'inc/video/video.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/content.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/remote_media_upload.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/diagram.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/additional.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/post-types/init.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/shortcodes/init.php');
require_once(plugin_dir_path( __FILE__ ) . 'inc/widgets/init.php');