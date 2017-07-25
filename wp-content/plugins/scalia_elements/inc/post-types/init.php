<?php

require_once(plugin_dir_path( __FILE__ ) . 'clients.php');
require_once(plugin_dir_path( __FILE__ ) . 'galleries.php');
require_once(plugin_dir_path( __FILE__ ) . 'news.php');
require_once(plugin_dir_path( __FILE__ ) . 'portfolios.php');
require_once(plugin_dir_path( __FILE__ ) . 'quickfinders.php');
require_once(plugin_dir_path( __FILE__ ) . 'teams.php');
require_once(plugin_dir_path( __FILE__ ) . 'testimonials.php');
require_once(plugin_dir_path( __FILE__ ) . 'slideshows.php');

function scalia_rewrite_flush() {
	scalia_news_post_type_init();
	scalia_portfolio_item_post_type_init();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'scalia_rewrite_flush' );