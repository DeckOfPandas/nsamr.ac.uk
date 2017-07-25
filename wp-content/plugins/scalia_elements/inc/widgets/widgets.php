<?php
function scalia_widgets_init() {
	if (!is_blog_installed())
		return;

	register_widget('Scalia_Widget_Picturebox');
	register_widget('Scalia_Widget_Tweets');
	register_widget('Scalia_Widget_Popular_Posts');
	register_widget('Scalia_Widget_Testimonial');
	register_widget('Scalia_Widget_news');
	register_widget('Scalia_Widget_Teams');
	register_widget('Scalia_Widget_Recent_Posts');
	register_widget('Scalia_Widget_Flickr');
	register_widget('Scalia_Widget_Submenu');
	register_widget('Scalia_Widget_Facebook');
	register_widget('Scalia_Widget_ProjectInfo');
	register_widget('Scalia_Widget_Contats');
}

add_action('widgets_init', 'scalia_widgets_init');

/* Scalia_Widget_Picturebox */

class Scalia_Widget_Picturebox extends WP_Widget {

	function __construct() {
		$widget_ops = array('Picturebox' => 'widget_picturebox', 'description' => __('Picturebox', 'scalia'));
		parent::__construct('Picturebox', __('Picturebox', 'scalia'), $widget_ops);
	}


	function widget($args, $instance) {
		$widget_data = array_merge(array(
			'image' => '',
			'title' => '',
			'text' => '',
			'link' => ''
		), $instance);

		extract($args);
		echo $before_widget;

		if($widget_data['title']) {
			echo $before_title . $widget_data['title'] . $after_title;
		}

		if($widget_data['image']) {
			?>
			<div class="sc-picturebox rounded-corners shadow-box bordered-box">
				<div class="sc-picturebox-image">
					<a href="<?php echo $widget_data['link'] ? esc_url($widget_data['link']) : esc_url($widget_data['image']); ?>">
						<img class="img-responsive" src="<?php echo esc_url($widget_data['image']); ?>" alt="<?php echo esc_attr($widget_data['title']); ?>" />
					</a>
				</div>
				<?php if($widget_data['text']) : ?>
				<div class="sc-picturebox-text"><p><?php echo nl2br($widget_data['text']); ?></p></div>
				<?php endif; ?>
			</div>
			<?php
		}

		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['image'] = strip_tags($new_instance['image']);
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text'] = $new_instance['text'];
		$instance['link'] = strip_tags($new_instance['link']);
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args((array)$instance, array('image' => '', 'title' => '', 'text' => '', 'link' => ''));
		$image = esc_url($instance['image']);
		$title = strip_tags($instance['title']);
		$text = $instance['text'];
		$link = esc_url($instance['link']);
		?>

		<p><label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image link', 'scalia'); ?>: <input
					class="widefat picture-select" id="<?php echo $this->get_field_id('image'); ?>"
					name="<?php echo $this->get_field_name('image'); ?>" type="text"
					value="<?php echo esc_attr($image); ?>"/></label></p>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'scalia'); ?>: <input
					class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
					name="<?php echo $this->get_field_name('title'); ?>" type="text"
					value="<?php echo esc_attr($title); ?>"/></label></p>
		<p><label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Description', 'scalia'); ?>: <textarea
					class="widefat" id="<?php echo $this->get_field_id('text'); ?>"
					name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_attr($text); ?></textarea></label></p>
		<p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link', 'scalia'); ?>: <input
					class="widefat" id="<?php echo $this->get_field_id('link'); ?>"
					name="<?php echo $this->get_field_name('link'); ?>" type="text"
					value="<?php echo esc_attr($link); ?>"/></label></p>
	<?php
	}
}

/* POPULAR POSTS */

class Scalia_Widget_Popular_Posts extends WP_Widget {
	function __construct() {
		$widget_ops = array('classname' => 'Custom_Popular_Posts', 'description' => __('The popular posts with thumbnails', 'scalia'));
		parent::__construct('Custom_Popular_Posts', __('Custom Popular Posts', 'scalia'), $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$widget_data = array_merge(array(
			'items' => '',
			'title' => '',
		), $instance);
		if (!is_numeric($widget_data['items'])) {
			$widget_data['items'] = 3;
		}
		if (empty($widget_data['title'])) {
			$widget_data['title'] = __('Popular Posts', 'Scalia');
		}
		if (!empty($widget_data['items'])) {
			echo $before_title . $widget_data['title'] . $after_title;
			pp_posts('popular', $widget_data['items'], TRUE);
		}
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['items'] = strip_tags($new_instance['items']);

		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args((array)$instance, array('title' => '', 'items' => ''));
		$title = strip_tags($instance['title']);
		$items = strip_tags($instance['items']);

		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'Scalia'); ?>: <input
					class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
					name="<?php echo $this->get_field_name('title'); ?>" type="text"
					value="<?php echo esc_attr($title); ?>"/></label></p>
		<p><label for="<?php echo $this->get_field_id('items'); ?>"><?php _e('Items (default 3)', 'Scalia'); ?>: <input
					class="widefat" id="<?php echo $this->get_field_id('items'); ?>"
					name="<?php echo $this->get_field_name('items'); ?>" type="text"
					value="<?php echo esc_attr($items); ?>"/></label></p>

	<?php
	}
}

function pp_posts($sort = 'recent', $items = 3, $echo = TRUE, $categories = array()) {
	$return_html = '';
	if ($sort == 'recent') {
		$category = '';
		if (!in_array(0, $categories))
			$category = '&category='.implode(',', $categories);
		$posts = get_posts('numberposts=' . $items . '&order=DESC&orderby=date&post_type=post&post_status=publish'.$category);
	} else {
		global $wpdb;
		$query = "SELECT ID, post_title, post_content, post_date FROM {$wpdb->prefix}posts WHERE post_type = 'post' AND post_status= 'publish' ORDER BY comment_count DESC LIMIT 0, %d";
		$posts = $wpdb->get_results($wpdb->prepare($query, $items));
	}
	if (!empty($posts)) {
		$return_html .= '<ul class="posts blog styled">';
		foreach ($posts as $post) {
			$image_thumb = '';
			if (has_post_thumbnail($post->ID, 'scalia-post-thumb')) {
				$image_id = get_post_thumbnail_id($post->ID);
				$image_thumb = wp_get_attachment_image_src($image_id, 'scalia-post-thumb', true);
			}
			$return_html .= '<li class="clearfix sc-pp-posts">';
			if (!empty($image_thumb)) {
				$return_html .= '<div class="sc-pp-posts-image"><a href="' . get_permalink($post->ID) . '"><img src="' . $image_thumb[0] . '" alt="' . $post->post_title .'"/></a></div>';
			} else {
				$return_html .= '<div class="sc-pp-posts-image"><a href="' . get_permalink($post->ID) . '"><span class="sc-dummy"></span></a></div>';
			}
			$return_html .= '<div class="sc-pp-posts-text"> <div class="sc-pp-posts-item"><a href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a></div>';
			$return_html .= '<div class="sc-pp-posts-date">' . get_the_date( '', $post->ID )  . '</div></div></li>';
		}

		$return_html .= '</ul>';
	}
	if ($echo) {
		echo $return_html;
	} else {
		return $return_html;
	}
}


/* Scalia_Widget_Recent_Posts */

class Scalia_Widget_Recent_Posts extends WP_Widget {
	function Scalia_Widget_Recent_Posts() {
		$widget_ops = array('classname' => 'Custom_Recent_Posts', 'description' => __('The recent posts with thumbnails', 'scalia'));
		parent::__construct('Custom_Recent_Posts', __('Custom Recent Posts', 'scalia'), $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$widget_data = array_merge(array(
			'items' => '',
			'title' => '',
			'categories' => array()
		), $instance);

		if (!is_numeric($widget_data['items'])) {
			$widget_data['items'] = 3;
		}

		if (empty($widget_data['title'])) {
			$widget_data['title'] = __('Recent Posts', 'Scalia');
		}
		if (!$widget_data['categories'])
			$widget_data['categories'] = array();
		if (!empty($widget_data['items'])) {
			echo $before_title . $widget_data['title'] . $after_title;
			pp_posts('recent', $widget_data['items'], TRUE, $widget_data['categories']);
		}
		echo $after_widget;

	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['items'] = strip_tags($new_instance['items']);
		$instance['categories'] = $new_instance['categories'];

		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args((array)$instance, array('title' => '', 'items' => '', 'categories' => array() ));
		$title = strip_tags($instance['title']);
		$items = strip_tags($instance['items']);

		$category_terms = get_categories(array('hide_empty' => false));
		$categories = array('0' => __('All Items', 'scalia'));
		foreach($category_terms as $term) {
			$categories[$term->term_id] = $term->name;
		}
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'scalia'); ?>: <input
					class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
					name="<?php echo $this->get_field_name('title'); ?>" type="text"
					value="<?php echo esc_attr($title); ?>"/></label></p>
		<p><label for="<?php echo $this->get_field_id('items'); ?>"><?php _e('Items (default 3)', 'scalia'); ?>: <input
					class="widefat" id="<?php echo $this->get_field_id('items'); ?>"
					name="<?php echo $this->get_field_name('items'); ?>" type="text"
					value="<?php echo esc_attr($items); ?>"/></label></p>
		<p><label><?php _e('Select Categories', 'scalia') ?>:</label><br />

		<?php scalia_print_checkboxes($categories, $instance['categories'], $this->get_field_name('categories').'[]', $this->get_field_id('categories'), '<br/>'); ?></p>
	<?php
	}
}


/* Scalia_Widget_Tweets */

class Scalia_Widget_Tweets extends WP_Widget {
	function __construct() {
		$widget_ops = array('Tweets' => 'widget_Tweets', 'description' => __('Tweets', 'scalia'));
		parent::__construct('Tweets', __('Tweets', 'scalia'), $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$widget_data = array_merge(array(
			'title' => '',
			'consumer_key' => '',
			'consumer_secret' => '',
			'access_token' => '',
			'access_token_secret' => '',
			'twitter_id' => '',
			'count' => '',
		), $instance);
		echo $before_widget;
		if ($widget_data['title']) {
			echo $before_title . $widget_data['title'] . $after_title;
		}
		if ($widget_data['twitter_id'] && $widget_data['consumer_key'] && $widget_data['consumer_secret'] && $widget_data['access_token'] && $widget_data['access_token_secret'] && $widget_data['count']) {
			$transName = 'list_tweets_' . $args['widget_id'];
			$cacheTime = 10;
			delete_transient($transName);
			if (false === ($twitterData = get_transient($transName))) {
				// require the twitter auth class
				require_once (plugin_dir_path( __FILE__ ) . '../twitteroauth/twitteroauth.php');
				$twitterConnection = new TwitterOAuth(
					$widget_data['consumer_key'], // Consumer Key
					$widget_data['consumer_secret'], // Consumer secret
					$widget_data['access_token'], // Access token
					$widget_data['access_token_secret'] // Access token secret
				);
				$twitterData = $twitterConnection->get(
					'statuses/user_timeline',
					array(
						'screen_name' => $widget_data['twitter_id'],
						'count' => $widget_data['count'],
						'exclude_replies' => false
					)
				);
				if ($twitterConnection->http_code != 200) {
					$twitterData = get_transient($transName);
				}
				// Save our new transient.
				set_transient($transName, $twitterData, 60 * $cacheTime);
			};
			$twitter = get_transient($transName);
			if ($twitter && is_array($twitter)) {
				?>
				<div class="twitter-box rounded-corners shadow-box">
					<div class="twitter-holder">
						<div class="b">
							<div class="tweets-container bordered-box rounded-corners" id="tweets_<?php echo $args['widget_id']; ?>">
								<ul id="jtwt" class="styled">
									<?php foreach ($twitter as $tweet): ?>
										<li class="jtwt_tweet">
											<p class="jtwt_tweet_text icon-twitter">
												<?php
												$latestTweet = $tweet->text;
												$latestTweet = preg_replace('/http:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '&nbsp;<a href="http://$1" target="_blank">http://$1</a>&nbsp;', $latestTweet);
												$latestTweet = preg_replace('/@([a-z0-9_]+)/i', '&nbsp;<a href="http://twitter.com/$1" target="_blank">@$1</a>&nbsp;', $latestTweet);
												echo $latestTweet;
												?>
											</p>
											<?php
											$twitterTime = strtotime($tweet->created_at);
											$timeAgo = $this->ago($twitterTime);
											?>
											<div class="jtwt_date"><?php echo $timeAgo; ?></div>

										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
					</div>
					<span class="arrow"></span>
				</div>
			<?php
			}
		}
		echo $after_widget;
	}

	function ago($time) {
		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		$lengths = array("60", "60", "24", "7", "4.35", "12", "10");
		$now = time();
		$difference = $now - $time;
		$tense = "ago";
		for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
			$difference /= $lengths[$j];
		}
		$difference = round($difference);

		if ($difference != 1) {
			$periods[$j] .= "s";
		}
		return "$difference $periods[$j] ago ";
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['consumer_key'] = $new_instance['consumer_key'];
		$instance['consumer_secret'] = $new_instance['consumer_secret'];
		$instance['access_token'] = $new_instance['access_token'];
		$instance['access_token_secret'] = $new_instance['access_token_secret'];
		$instance['twitter_id'] = $new_instance['twitter_id'];
		$instance['count'] = $new_instance['count'];
		return $instance;
	}

	function form($instance) {
		$defaults = array(
			'title' => 'Recent Tweets',
			'twitter_id' => '',
			'count' => 3,
			'consumer_key' => '',
			'consumer_secret' => '',
			'access_token' => '',
			'access_token_secret' => ''
		);
		$instance = wp_parse_args((array)$instance, $defaults); ?>
		<p><?php printf(__('<a href="%s">Find or Create your Twitter App</a>', 'Scalia'), 'http://dev.twitter.com/apps'); ?></p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
				   name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('consumer_key'); ?>">Consumer Key:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('consumer_key'); ?>"
				   name="<?php echo $this->get_field_name('consumer_key'); ?>"
				   value="<?php echo $instance['consumer_key']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('consumer_secret'); ?>">Consumer Secret:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('consumer_secret'); ?>"
				   name="<?php echo $this->get_field_name('consumer_secret'); ?>"
				   value="<?php echo $instance['consumer_secret']; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('access_token'); ?>">Access Token:</label>
			<input class="widefat"" id="<?php echo $this->get_field_id('access_token'); ?>"
			name="<?php echo $this->get_field_name('access_token'); ?>"
			value="<?php echo $instance['access_token']; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('access_token_secret'); ?>">Access Token Secret:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('access_token_secret'); ?>"
				   name="<?php echo $this->get_field_name('access_token_secret'); ?>"
				   value="<?php echo $instance['access_token_secret']; ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('twitter_id'); ?>">Twitter ID:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('twitter_id'); ?>"
				   name="<?php echo $this->get_field_name('twitter_id'); ?>"
				   value="<?php echo $instance['twitter_id']; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('count'); ?>">Number of Tweets:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('count'); ?>"
				   name="<?php echo $this->get_field_name('count'); ?>" value="<?php echo $instance['count']; ?>"/>
		</p>

	<?php
	}
}


/* Testimonials */

class Scalia_Widget_Testimonial extends WP_Widget {
	function __construct() {
		$widget_ops = array('classname' => 'scalia-widget-testimonial', 'description' => __('List of testimonials', 'scalia'));
		parent::__construct('scalia_testimonials', __('Testimonial', 'scalia'), $widget_ops);
	}
	function widget($args, $instance) {
		extract($args);
		echo $before_widget;
		$widget_data = array_merge(array(
			'style' => '',
			'title' => '',
			'testimonials_set' => '',
			'autoscroll_testimonials' => $autoscroll_testimonials = isset($instance['autoscroll_testimonials']) ? $instance['autoscroll_testimonials'] : 5000,
			'effects_enabled' => false
		), $instance);
		$params = array("style" =>  $widget_data['style'], "testimonials_set" => $widget_data['testimonials_set'], "autoscroll_testimonials" => $widget_data['autoscroll_testimonials'], "effects_enabled" => $widget_data['effects_enabled']);
		if (!empty($widget_data['title'])) {
            echo ($params['effects_enabled'] ? '<div class="lazy-loading" data-ll-item-delay="0"><div class="lazy-loading-item" data-ll-effect="drop-top" data-ll-step="0.5">' : '').$before_title . $widget_data['title']. $after_title.($params['effects_enabled'] ? '</div></div>' : '');
		}
		echo $params['effects_enabled'] ? '<div class="lazy-loading" data-ll-item-delay="0"><div class="lazy-loading-item" data-ll-effect="drop-right-unwrap" data-ll-offset="0.5" data-ll-item-delay="400">' : '';
		scalia_testimonials($params);
		echo $params['effects_enabled'] ? '</div></div>' : '';
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['style'] = $new_instance['style'];
		$instance['testimonials_set'] = $new_instance['testimonials_set'];
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['autoscroll_testimonials'] = strip_tags($new_instance['autoscroll_testimonials']);
		$instance['effects_enabled'] = (bool) $new_instance['effects_enabled'];
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args((array)$instance, array('style' => '', 'testimonials_set' => '', 'title' => '', 'autoscroll_testimonials' => '', 'effects_enabled' => false));
		$testimonials_sets = array('' => __('All Testimonials', 'scalia'));
		$styles = array('0' => __('Style 1', 'scalia'), '1' => __('Style 2', 'scalia'));
		$testimonials_sets_terms = get_terms('scalia_testimonials_sets', array('hide_empty' => false));
		$title = strip_tags($instance['title']);
		$autoscroll_testimonials = strip_tags($instance['autoscroll_testimonials']);
		foreach ($testimonials_sets_terms as $term) {
			$testimonials_sets[$term->slug] = $term->name;
		}
		$effects_enabled = (bool) $instance['effects_enabled'];
		?>
		<p>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'scalia'); ?>: <input
					class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
					name="<?php echo $this->get_field_name('title'); ?>" type="text"
					value="<?php echo esc_attr($title); ?>"/></label></p>

		<p><label for="<?php echo $this->get_field_id('autoscroll_testimonials'); ?>"><?php _e('Autoscroll Speed', 'scalia'); ?>: <input
					class="widefat" id="<?php echo $this->get_field_id('autoscroll_testimonials'); ?>"
					name="<?php echo $this->get_field_name('autoscroll_testimonials'); ?>" type="text"
					value="<?php echo esc_attr($autoscroll_testimonials); ?>"/></label></p>

		<label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Widget Style', 'scalia'); ?>
			:</label><br/>
		<?php scalia_print_select_input($styles, $instance['style'], $this->get_field_name('style'), $this->get_field_id('style')) ?>
		<br/>
		<label
			for="<?php echo $this->get_field_id('testimonials_set'); ?>"><?php _e('Testimonials Set', 'scalia'); ?>
			:</label><br/>
		<?php scalia_print_select_input($testimonials_sets, $instance['testimonials_set'], $this->get_field_name('testimonials_set'), $this->get_field_id('testimonials_set')) ?>
		<br/>
		</p>

		<p>
			<input type="checkbox" name="<?php echo $this->get_field_name('effects_enabled'); ?>" id="<?php echo $this->get_field_id('effects_enabled'); ?>" value="1" <?php checked($effects_enabled, 1); ?> />
			<label for="<?php echo $this->get_field_id('effects_enabled'); ?>"><?php _e('Lazy loading enabled', 'scalia'); ?></label>
		</p>
	<?php
	}
}

function scalia_testimonials($params) {
	wp_enqueue_script('scalia-widgets');
	$params = array_merge(array('style' => '', 'testimonials_set' => '',  'autoscroll_testimonials' => '', 'effects_enabled' => false), $params);
	$args = array(
		'post_type' => 'scalia_testimonial',
		'orderby' => 'menu_order ID',
		'order' => 'DESC'
	);
	if ($params['testimonials_set'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'scalia_testimonials_sets',
				'field' => 'slug',
				'terms' => $params['testimonials_set']
			)
		);
	}
	$testimonials = new WP_Query($args);
	if ($testimonials->have_posts()) {
		if ($params['style'] == 0) {
			echo '<div class="widget-testimonials testimonials-style-1-block "><div  data-autoscroll="'.$params['autoscroll_testimonials'].'" class="testimonials-carousel-style-1 testimonials-style-1">';
			$link_start = '';
			$link_end = '';
			while ($testimonials->have_posts()) {
				$testimonials->the_post();
				$item_data = scalia_get_sanitize_testimonial_data(get_the_ID());
				$image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'scalia-post-thumb');
				if($item_data['link']) {
					$link_start = '<a href="'.$item_data['link'].'" target="'.$item_data['link_target'].'">';
					$link_end = '</a>';
				}
				?>
				<div class="testimonials-style-1-item rounded-corners">
					<div class="testimonials-style-1-image"> <?php echo  $link_start; ?> <?php if($params['effects_enabled']): ?><span class="lazy-loading-item" style="display: block;" data-ll-item-delay="0" data-ll-effect="clip"><?php endif; ?> <?php  scalia_post_thumbnail('scalia-post-thumb'); ?> <?php if($params['effects_enabled']): ?></span><?php endif; ?> <?php echo $link_end; ?> </div>
					<div class="testimonials-style-1-text sc-testimonial-text">	<?php the_content(); ?></div>
					<div class="testimonials-style-1-name sc-testimonial-name"><?php  echo  $item_data['name']  ?></div>
					<?php if ($item_data['position']) : ?><div class="testimonials-style-1-post sc-testimonial-position"> <?php echo $item_data['position']  ?></div><?php endif; ?>
					<?php if ($item_data['company']) : ?><div class="testimonials-style-1-post sc-testimonial-company">  <?php echo  $item_data['company'] ?></div><?php endif; ?>
					<div class="testimonials-style-1-teg">&#xe60b;</div>
				</div>
				<?php
			}
			echo '</div><div class="triangle-with-shadow"></div></div>';
		}
		if ($params['style'] == 1) {
			echo '<div class="testimonials testimonials-style-2"><div class="testimonials-style-2"><div data-autoscroll="'.$params['autoscroll_testimonials'].'"  class="testimonials-carousel-style-2" >';
			$link_start = '';
			$link_end = '';
			while ($testimonials->have_posts()) {
				$testimonials->the_post();
				$item_data = scalia_get_sanitize_testimonial_data(get_the_ID());
				$image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'scalia-post-thumb');
				if($item_data['link']) {
					$link_start = '<a href="'.$item_data['link'].'" target="'.$item_data['link_target'].'">';
					$link_end = '</a>';
				}
				?>
				<div class="testimonials-style-2-item">
					<div class="testimonials-style-2-text sc-testimonial-text bordered-box shadow-box"> <?php the_content(); ?>
						<div class="testimonials-style-2-teg">&#xe60b;</div>
					</div>
					<div class="triangle-with-shadow trangle-style-2"></div>
					<div class="testimonials-style-2-bg">
						<div class="testimonials-style-2-image"> <?php echo $link_start ?> <?php if($params['effects_enabled']): ?><span class="lazy-loading-item" style="display: block;" data-ll-item-delay="0" data-ll-effect="clip"><?php endif; ?> <span> <?php scalia_post_thumbnail('scalia-post-thumb');?> </span> <?php if($params['effects_enabled']): ?></span><?php endif; ?> <?php echo $link_end;?> </div>
							<div class="testimonials-style-2-name sc-testimonial-name">
							 	<?php  echo ($item_data['name']); ?>
 							</div>
						<div class="testimonials-style-2-post sc-testimonial-position"><?php echo $item_data['position']  ?></div>
						<div class="testimonials-style-2-post sc-testimonial-position"><?php echo $item_data['company'] ?></div>
					</div>
				</div>
				<?php
			}
			echo '</div></div></div>';
		}
	}

	wp_reset_postdata();
}


/* news */

class Scalia_Widget_news extends WP_Widget {
	function __construct() {
		$widget_ops = array('classname' => 'scalia_news', 'description' => __('scalia news', 'scalia'));
		parent::__construct('scalia_news_list', __('News', 'scalia'), $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		echo $before_widget;
		$widget_data = array_merge(array(
			'title' => '',
			'count' => '',
			'news_set' => ''
		), $instance);
		$params = array("count" => $widget_data['count'], "news_set" => $widget_data['news_set'] );
		if (intval ($params['count']) == 0) {
			$params['count'] = 3;
		}
		if (!empty($widget_data['title'])) {
			echo $before_title . $widget_data['title'] . $after_title;
		}
		scalia_news_list($params);
		echo $after_widget;
	}
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['news_set'] = $new_instance['news_set'];
		$instance['count'] = $new_instance['count'];
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	function form($instance)
	{
		$instance = wp_parse_args((array)$instance, array('count' => '', 'news_set' => '', 'title' => ''));
		$count = strip_tags($instance['count']);
		$news_sets = array('' => __('All News', 'scalia'));
		$news_sets_terms = get_terms('scalia_news_sets', array('hide_empty' => false));
		$title = strip_tags($instance['title']);
		foreach ($news_sets_terms as $term) {
			$news_sets[$term->slug] = $term->name;
		}
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'scalia'); ?>: <input
					class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
					name="<?php echo $this->get_field_name('title'); ?>" type="text"
					value="<?php echo esc_attr($title); ?>"/></label></p>
		<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Count', 'scalia'); ?>: <input
					class="widefat" id="<?php echo $this->get_field_id('count'); ?>"
					name="<?php echo $this->get_field_name('count'); ?>" type="text"
					value="<?php echo esc_attr($count); ?>"/></label></p>
		<label
			for="<?php echo $this->get_field_id('news_set'); ?>"><?php _e('News sets ', 'scalia'); ?>
			:</label><br/>
		<?php scalia_print_select_input($news_sets, $instance['news_set'], $this->get_field_name('news_set'), $this->get_field_id('news_set')) ?>
		<br/>
	<?php
	}
}

function scalia_news_list($params) {
	$params = array_merge(array('count' => '', 'news_set' => ''), $params);
	$args = array(
		'post_type' => 'scalia_news',
		'posts_per_page' => $params['count'],
		'orderby' => 'date',
		'order' => 'DESC'
	);
	if ($params['news_set'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'scalia_news_sets',
				'field' => 'slug',
				'terms' => $params['news_set']
			)
		);
	}
	$loop = new WP_Query($args);
	echo '<ul class="posts styled">';
	while ($loop->have_posts()) : $loop->the_post();
		?>
		<li class="sc-latest-news">
			<div class="sc-latest-news-image">
				<a href='<?php the_permalink() ?>'> <?php scalia_post_thumbnail('scalia-post-thumb', true, '') ?> </a>
			</div>
			<div class="sc-latest-news-title">
				<a href='<?php the_permalink() ?>'>  <?php the_title(); ?></a>
				<span> <?php echo get_the_date()?> </span>
			</div>
		</li>
		<?php
	endwhile;
	echo '</ul>';
	wp_reset_postdata();
}

/* Flickr */

class Scalia_Widget_Flickr extends WP_Widget {
	function Scalia_Widget_Flickr() {
		$widget_ops = array('classname' => 'Custom_Flickr', 'description' => __('Display your recent Flickr photos', 'scalia') );
		parent::__construct('Custom_Flickr', __('Flickr', 'scalia'), $widget_ops);
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		echo $before_widget;

		$widget_data = array_merge(array(
			'flickr_id' => empty($instance['flickr_id']) ? ' ' : apply_filters('widget_title', $instance['flickr_id']),
			'title' => '',
			'items' => '',
		), $instance);
		if(!is_numeric($widget_data['items']))
		{
            $widget_data['items'] = 9;
		}
		if(empty($widget_data['title']))
		{
			$widget_data['title'] = __('Photostream', 'Scalia');
		}

		if(!empty($widget_data['items']) && !empty($widget_data['flickr_id']))
		{
			$photos_arr = get_flickr(array('type' => 'user', 'id' => $widget_data['flickr_id'], 'items' =>$widget_data['items'] ));

			if(!empty($photos_arr))
			{
				echo $before_title.$widget_data['title'].$after_title;
				echo '<div class="flickr clearfix">';
				$num = 0;
				foreach($photos_arr as $photo) {
					echo '<div class="flickr-item position-'.($num % 3).'">';
					echo '<a href="'.esc_url($photo['url']).'" title="'.esc_attr($photo['title']).'" class="fancy"><img src="'.esc_url($photo['thumb_url']).'" alt="" /></a>';
					echo '</div>';
					$num++;
				}
				echo '</div>';
			}
		}
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['items'] = strip_tags($new_instance['items']);
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['flickr_id'] = strip_tags($new_instance['flickr_id']);
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'items' => '', 'flickr_id' => '', 'title' => '') );
		$items = strip_tags($instance['items']);
		$flickr_id = strip_tags($instance['flickr_id']);
		$title = strip_tags($instance['title']);
		?>
		<p><label for="<?php echo $this->get_field_id('flickr_id'); ?>"><?php printf(__('Flickr ID <a href="%s">Find your Flickr ID here</a>', 'Scalia'), 'http://idgettr.com/'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('flickr_id'); ?>" name="<?php echo $this->get_field_name('flickr_id'); ?>" type="text" value="<?php echo esc_attr($flickr_id); ?>" /></label></p>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'Scalia'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>

		<p><label for="<?php echo $this->get_field_id('items'); ?>"><?php _e('Items (default 9)', 'Scalia'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('items'); ?>" name="<?php echo $this->get_field_name('items'); ?>" type="text" value="<?php echo esc_attr($items); ?>" /></label></p>
	<?php
	}
}

function get_flickr($settings) {
	if (!function_exists('MagpieRSS')) {
		// Check if another plugin is using RSS, may not work
		include_once (ABSPATH . WPINC . '/class-simplepie.php');
	}

	if(!isset($settings['items']) || empty($settings['items']))
	{
		$settings['items'] = 9;
	}

	// get the feeds
	if ($settings['type'] == "user") { $rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?id=' . $settings['id'] . '&tags=' . (isset($settings['tags']) ? $settings['tags'] : ''). '&per_page='.$settings['items'].'&format=rss_200'; }
	elseif ($settings['type'] == "favorite") { $rss_url = 'http://api.flickr.com/services/feeds/photos_faves.gne?id=' . $settings['id'] . '&format=rss_200'; }
	elseif ($settings['type'] == "set") { $rss_url = 'http://api.flickr.com/services/feeds/photoset.gne?set=' . $settings['set'] . '&nsid=' . $settings['id'] . '&format=rss_200'; }
	elseif ($settings['type'] == "group") { $rss_url = 'http://api.flickr.com/services/feeds/groups_pool.gne?id=' . $settings['id'] . '&format=rss_200'; }
	elseif ($settings['type'] == "public" || $settings['type'] == "community") { $rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?tags=' . $settings['tags'] . '&format=rss_200'; }
	else {
		print '<strong>'.__('No "type" parameter has been setup. Check your settings, or provide the parameter as an argument.', 'Scalia').'</strong>';
		die();
	}
	# get rss file

	$feed = new SimplePie();
	$feed->set_feed_url($rss_url);
	$feed->set_cache_location(get_template_directory().'/cache/');
	$feed->init();
	$feed->handle_content_type();

	$photos_arr = array();

	foreach ($feed->get_items() as $key => $item)
	{
		$enclosure = $item->get_enclosure();
		$img = image_from_description($item->get_description());
		$thumb_url = select_image($img, 0);
		$large_url = select_image($img, 4);

		$photos_arr[] = array(
			'title' => $enclosure->get_title(),
			'thumb_url' => $thumb_url,
			'url' => $large_url,
		);

		$current = intval($key+1);

		if($current == $settings['items'])
		{
			break;
		}
	}

	return $photos_arr;
}

function image_from_description($data) {
	preg_match_all('/<img src="([^"]*)"([^>]*)>/i', $data, $matches);
	return $matches[1][0];
}

function select_image($img, $size) {
	$img = explode('/', $img);
	$filename = array_pop($img);

	// The sizes listed here are the ones Flickr provides by default.  Pass the array index in the

	// 0 for square, 1 for thumb, 2 for small, etc.
	$s = array(
		'_s.', // square
		'_t.', // thumb
		'_m.', // small
		'.',   // medium
		'_b.'  // large
	);

	$img[] = preg_replace('/(_(s|t|m|b))?\./i', $s[$size], $filename);
	return implode('/', $img);
}


/*Submenu*/

class Scalia_Widget_Submenu extends WP_Widget {
	function __construct()
	{
		$widget_ops = array('classname' => 'widget_submenu', 'description' => __('Submenu', 'scalia'));
		parent::__construct('Submenu', __('Submenu', 'scalia'), $widget_ops);
	}

	function widget($args, $instance) {
		// Get menu
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;
		extract($args);
		$title = $instance['title'];
		if ( !$nav_menu )
			return;
		echo $args['before_widget'];
		if (!empty($title)) {
			echo $before_title . $title . $after_title;
		}
		wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu, 'walker' => new Submenu_Walker_Nav_Menu, 'items_wrap' => '<ul class="styled">%3$s</ul>') );
		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
		$title = strip_tags($instance['title']);
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
		if ( !$menus ) {
			echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.', 'Scalia'), admin_url('nav-menus.php') ) .'</p>';
			return;
		}
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'scalia'); ?>: <input
					class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
					name="<?php echo $this->get_field_name('title'); ?>" type="text"
					value="<?php echo esc_attr($title); ?>"/></label></p>
		<p>
			<label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu', 'Scalia'); ?>:</label>
			<select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
				<?php
				foreach ( $menus as $menu ) {
					echo '<option value="' . $menu->term_id . '"'
						. selected( $nav_menu, $menu->term_id, false )
						. '>'. $menu->name . '</option>';
				}
				?>
			</select>
		</p>
	<?php
	}
}

add_filter('wp_nav_menu_items', 'scalia_wp_nav_menu_items_before', 9, 2);
function scalia_wp_nav_menu_items_before($items, $args) {
	if(is_object($args->walker) && get_class($args->walker) == 'Submenu_Walker_Nav_Menu') {
		return $items.'@#@';
	}
	return $items;
}

add_filter('wp_nav_menu_items', 'scalia_wp_nav_menu_items_after', 11, 2);
function scalia_wp_nav_menu_items_after($items, $args) {
	if(is_object($args->walker) && get_class($args->walker) == 'Submenu_Walker_Nav_Menu') {
		return substr($items, 0, strpos($items, "@#@"));
	}
	return $items;
}

class Submenu_Walker_Nav_Menu extends Walker_Nav_Menu {

	var $current_tree_ids = array();

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ($depth == 0)
			return;

		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"styled\">\n";
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ($depth == 0)
			return;

		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		if ($depth == 0 && ($item->current_item_ancestor || $item->current)) {
			$this->current_tree_ids[] = $item->ID;
		}

		if (!in_array($item->menu_item_parent, $this->current_tree_ids))
			return;

		$this->current_tree_ids[] = $item->ID;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		if($depth != 0) {
			$output .= $indent . '<li' . $id . $value . $class_names .'>';
		}

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		if($depth != 0) {
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}

	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if (!in_array($item->menu_item_parent, $this->current_tree_ids))
			return;

		$output .= "</li>\n";
	}
}


/* FACEBOOK */

class Scalia_Widget_Facebook extends WP_Widget {
	function __construct()
	{
		$widget_ops = array('classname' => 'Facebook', 'description' => __('Facebook', 'scalia'));
		parent::__construct('Facebook', __('Facebook', 'scalia'), $widget_ops);
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		echo $before_widget;
		$fb_page_url = $instance['fb_page_url'];
		$title = $instance['title'];
		$widget_data = array_merge(array(
			'fb_page_url' => '',
			'title' => '',

		), $instance);
		if(!empty($fb_page_url)){
			echo $before_title;
			if($widget_data['title']) {
				echo $widget_data['title'];
			} else {
				echo __('Find us on Facebook', 'scalia');
			}
			echo $after_title;
			?>
			<div class="rounded-corners shadow-box bordered-box"><iframe src="//www.facebook.com/plugins/likebox.php?href=<?php echo urlencode($widget_data['fb_page_url']); ?>&amp;width=285&amp;height=258&amp;show_faces=true&amp;colorscheme=light&amp;stream=false&amp;show_border=false&amp;header=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:250px; height:250px; margin:  -10px 0 -10px -10px; vertical-align: top;" allowTransparency="true"></iframe></div>
		<?php
		}

		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['fb_page_url'] = strip_tags($new_instance['fb_page_url']);
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'fb_page_url' => '', 'title' => '') );
		$fb_page_url = strip_tags($instance['fb_page_url']);
		$title = strip_tags($instance['title']);

		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'Scalia'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('fb_page_url'); ?>"><?php _e('Facebook Page URL', 'Scalia'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('fb_page_url'); ?>" name="<?php echo $this->get_field_name('fb_page_url'); ?>" type="text" value="<?php echo esc_attr($fb_page_url); ?>" /></label></p>
	<?php
	}
}

function scalia_widget_categories_args($cat_args) {
	$cat_args['walker'] = new Scalia_Walker_Category;
	return $cat_args;
}
add_filter('widget_categories_args', 'scalia_widget_categories_args');

class Scalia_Walker_Category extends Walker_Category {

	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		extract($args);

		$cat_name = esc_attr( $category->name );

		/** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters( 'list_cats', $cat_name, $category );

		$link = '<a href="' . esc_url( get_term_link($category) ) . '" ';
		if ( $use_desc_for_title == 0 || empty($category->description) ) {
			$link .= 'title="' . esc_attr( sprintf(__( 'View all posts filed under %s', 'scalia' ), $cat_name) ) . '"';
		} else {
			$link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
		}

		$link .= '>';
		$link .= $cat_name . '</a>';

		if ( !empty($feed_image) || !empty($feed) ) {
			$link .= ' ';

			if ( empty($feed_image) )
				$link .= '(';
			$link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $feed_type ) ) . '"';
			if ( empty($feed) ) {
				$alt = ' alt="' . sprintf(__( 'Feed for all posts filed under %s', 'scalia' ), $cat_name ) . '"';
			} else {
				$title = ' title="' . $feed . '"';
				$alt = ' alt="' . $feed . '"';
				$name = $feed;
				$link .= $title;
			}

			$link .= '>';

			if ( empty($feed_image) )
				$link .= $name;
			else
				$link .= "<img src='$feed_image'$alt$title" . ' />';

			$link .= '</a>';

			if ( empty($feed_image) )
				$link .= ')';
		}

		if ( !empty($show_count) )
			$link .= ' (' . number_format_i18n( $category->count ) . ')';

		if ( 'list' == $args['style'] ) {
			$category_children_ids = array();
			foreach(get_categories(array('child_of' => $category->term_id)) as $category_child) {
				$category_children_ids[] = $category_child->term_id;
			}
			$output .= "\t<li";
			$class = 'cat-item cat-item-' . $category->term_id;
			if ( !empty($current_category) ) {
				$_current_category = get_term( $current_category, $category->taxonomy );
				if ( $category->term_id == $current_category )
					$class .=  ' current-cat';
				elseif ( $category->term_id == $_current_category->parent )
					$class .=  ' current-cat-parent';
				elseif(in_array($current_category, $category_children_ids)) {
					$class .=  ' current-cat-ancestor';
				}
				if($args['has_children']) {
					$class .=  ' cat-parent';
				}
			}
			$output .=  ' class="' . $class . '"';
			$output .= ">$link\n";
		} else {
			$output .= "\t$link<br />\n";
		}
	}

}

/*ProjectInfo*/

class Scalia_Widget_ProjectInfo extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'project_info', 'description' => __( 'Project Info / Contact Info / Custom Iconed Fields', 'scalia') );
		parent::__construct('project_info', __('Project Info / Contact Info / Custom Iconed Fields', 'scalia'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$widget_data = array_merge(array(
			'title' => apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base ),
			'fields' => '',
			'button_url' => '',
			'style' => ''
		), $instance);
		if(!($button_text = $instance['button_text'])) {
			$button_text = __('Project Preview', 'Scalia');
		}

		echo $before_widget;
		if ( $widget_data['title'] )
			echo $before_title. $widget_data['title'] .$after_title;
		if ($widget_data['style'] == 0) {
			echo '<div class="project_info-item-style-1">';
		}
		if ($widget_data['style'] == 1) {
			echo '<div class="project_info-item-style-2">';
		}
		foreach($widget_data['fields'] as $field) : ?>
			<?php if($field['title']) : ?>
				<div class="project_info-item<?php if($field['icon']) echo ' iconed'; ?><?php if($widget_data['style'] == 0) echo ' rounded-corners bordered-box'; ?>">
					<div class="title<?php if($widget_data['style'] == 0) echo ' styled-subtitle'; ?>">
						<?php if($field['icon']) : ?><span class="icon">&#x<?php echo $field['icon']; ?>;</span><?php endif; ?>
						<span class="project_info-item-title"> <?php  echo $field['title']; ?> </span>
					</div>
					<div class="value"><?php echo $field['value']; ?></div>
				</div>
			<?php endif; ?>
		<?php endforeach;
		echo '</div>'
		?>
		<?php if($widget_data['button_url']) : ?>
			<a class="sc-button"  style="color: #ffffff;" target="_self" href="<?php echo $widget_data['button_url'] ?>"><?php echo $button_text; ?></a>
		<?php endif; ?>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => '', 'fields' => array(), 'button_url' => '', 'button_text' => '' ));
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['fields'] = $new_instance['fields'];
		$instance['button_url'] = $new_instance['button_url'];
		$instance['button_text'] = $new_instance['button_text'];
		$instance['style'] = $new_instance['style'];
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'fields' => array(), 'button_url' => '', 'button_text' => '', 'style' => '' ) );
		$title = $instance['title'];
		$fields = $instance['fields'];
		$button_url = $instance['button_url'];
		$button_text = $instance['button_text'];
		$style = array('0' => __('Style 1', 'scalia'), '1' => __('Style 2', 'scalia'));

		?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'Scalia'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<h3>Fields:</h3>
		<?php scalia_print_select_input($style, $instance['style'], $this->get_field_name('style'), $this->get_field_id('style')) ?>

		<?php for( $i=0; $i < 10; $i++ ) : ?>
			<h4>Field #<?php echo ($i+1); ?>:</h4>
			<p><label for="<?php echo $this->get_field_id('fields_' . $i . '_title'); ?>"><?php _e('Title', 'Scalia'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('fields_' . $i . '_title'); ?>" name="<?php echo $this->get_field_name('fields') . '[' . $i . '][title]'; ?>" type="text" value="<?php echo isset($fields[$i]) ? $fields[$i]['title'] : ''; ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('fields_' . $i . '_value'); ?>"><?php _e('Value', 'Scalia'); ?>: <textarea class="widefat" rows="3" cols="20" id="<?php echo $this->get_field_id('fields_' . $i . '_value'); ?>" name="<?php echo $this->get_field_name('fields') . '[' . $i . '][value]'; ?>"><?php echo isset($fields[$i]) ? $fields[$i]['value'] : ''; ?></textarea></label></p>
			<p>
				<label for="<?php echo $this->get_field_id('fields_' . $i . '_icon'); ?>"><?php _e('Icon', 'Scalia'); ?>: <input class="widefat icon" id="<?php echo $this->get_field_id('fields_' . $i . '_icon'); ?>" name="<?php echo $this->get_field_name('fields') . '[' . $i . '][icon]'; ?>" type="text" value="<?php echo isset($fields[$i]) ? $fields[$i]['icon'] : ''; ?>" /></label><br/>
				<?php _e('Enter icon code', 'Scalia'); ?>. <a href="<?php echo scalia_user_icons_info_link(); ?>" onclick="tb_show('<?php _e('Icons info', 'Scalia'); ?>', this.href+'?TB_iframe=true'); return false;"><?php _e('Show Icon Codes', 'Scalia'); ?></a>
			</p>
		<?php endfor; ?>
		<p>&nbsp;</p>
		<p><label for="<?php echo $this->get_field_id('button_url'); ?>"><?php _e('Button URL', 'Scalia'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('button_url'); ?>" name="<?php echo $this->get_field_name('button_url'); ?>" type="text" value="<?php echo $button_url; ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e('Button Text', 'Scalia'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo $button_text; ?>" /></label></p>
	<?php

	}

}


class Scalia_Widget_Teams extends WP_Widget {
	function __construct() {
		$widget_ops = array('classname' => 'Teams', 'description' => __('Teams', 'scalia'));
		parent::__construct('Teams', __('Teams', 'scalia'), $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$widget_data = array_merge(array(
			'teams' => '',
			'title' => '',
            'person_data_email' => '',
            'teams_animation_speed' => '',
			'effects_enabled' => false
		), $instance);
		if (!is_numeric($widget_data['teams_animation_speed'])) {
			$widget_data['teams_animation_speed'] = 5000;
		}
		$params = array("teams" => $widget_data['teams'], "teams_animation_speed" => $widget_data['teams_animation_speed'], "effects_enabled" => $widget_data['effects_enabled'], "person_data_email" => $widget_data['person_data_email']);
		if (!empty($widget_data['title'])) {
			echo ($params['effects_enabled'] ? '<div class="lazy-loading" data-ll-item-delay="0"><div class="lazy-loading-item" data-ll-effect="drop-top" data-ll-step="0.5">' : '').$before_title . $widget_data['title']. $after_title.($params['effects_enabled'] ? '</div></div>' : '');
		}
		echo $params['effects_enabled'] ? '<div class="lazy-loading" data-ll-item-delay="0"><div class="lazy-loading-item" data-ll-effect="drop-right-unwrap" data-ll-offset="0.5" data-ll-item-delay="400">' : '';
		scalia_teams($params);


		echo $params['effects_enabled'] ? '</div></div>' : '';
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['teams'] = $new_instance['teams'];
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['teams_animation_speed'] = strip_tags($new_instance['teams_animation_speed']);
		$instance['effects_enabled'] = (bool) $new_instance['effects_enabled'];
        $instance['person_data_email'] = (bool)  $new_instance['person_data_email'];


        return $instance;
	}

	function form($instance) {

		$instance = wp_parse_args((array)$instance, array('teams' => '', 'title' => '', 'teams_animation_speed' => '', 'effects_enabled' => false, 'person_data_email' => ''));
		$team = $instance['teams'];
		$team_terms = get_terms('scalia_teams', array('hide_empty' => false));
		$title = strip_tags($instance['title']);
		$teams_animation_speed = strip_tags($instance['teams_animation_speed']);
		$person_data_email =(bool)$instance['person_data_email'];
		$scalia_teams = array();
		foreach ($team_terms as $term) {
			$scalia_teams[$term->slug] = $term->name;
		}
		$effects_enabled = (bool) $instance['effects_enabled'];

		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'scalia'); ?>: <input
					class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
					name="<?php echo $this->get_field_name('title'); ?>" type="text"
					value="<?php echo esc_attr($title); ?>"/></label></p>

		<p><label for="<?php echo $this->get_field_id('teams_animation_speed'); ?>"><?php _e('Autoscroll Speed', 'scalia'); ?>: <input
					class="widefat" id="<?php echo $this->get_field_id('teams_animation_speed'); ?>"
					name="<?php echo $this->get_field_name('teams_animation_speed'); ?>" type="text"
					value="<?php echo esc_attr($teams_animation_speed); ?>"/></label></p>

		<label
			for="<?php echo $this->get_field_id('team'); ?>"><?php _e('Team Set', 'scalia'); ?>
			:</label>
		<br/>



		<?php
		scalia_print_checkboxes($scalia_teams, $team, $this->get_field_name('teams').'[]');
		?>
		<br/>

		<p>
			<input type="checkbox" name="<?php echo $this->get_field_name('effects_enabled'); ?>" id="<?php echo $this->get_field_id('effects_enabled'); ?>" value="1" <?php checked($effects_enabled, 1); ?> />
			<label for="<?php echo $this->get_field_id('effects_enabled'); ?>"><?php _e('Lazy loading enabled', 'scalia'); ?></label>
		</p>
        <p>
            <input type="checkbox" name="<?php echo $this->get_field_name('person_data_email'); ?>" id="<?php echo $this->get_field_id('person_data_email'); ?>" value="1" <?php checked($person_data_email, 1); ?> />
            <label for="<?php echo $this->get_field_id('person_data_email'); ?>"><?php _e('Hide Email', 'scalia'); ?></label>
        </p>


	<?php
	}
}


function scalia_teams($params) {
	wp_enqueue_script('scalia-widgets');
	$params = array_merge(array('teams' => '','teams_animation_speed' => '' ,'person_data_email' => ''), $params);
	$args = array(
		'post_type' => 'scalia_team_person',
		'orderby' => 'menu_order ID',
		'order' => 'DESC'
	);
	if ($params['teams'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'scalia_teams',
				'field' => 'slug',
				'terms' => $params['teams']
			)
		);
	}
	$loop = new WP_Query($args);

	echo '<div class="widget-teams rounded-corners shadow-box bordered-box">';
	echo '<div data-autoscroll="'.$params['teams_animation_speed'].'" class="sc-teams-items-carousel">';
	echo '<div class="sc-teams-items">';
	$link_start = '';
	$link_end = '';

	while ($loop->have_posts()) : $loop->the_post();
		$item_data = scalia_get_sanitize_team_person_data(get_the_ID());
		if($item_data['link']) {
			$link_start = '<a href="'.$item_data['link'].'" target="'.$item_data['link_target'].'">';
			$link_end = '</a>';
		}
		?>
		<div class="sc-teams-item rounded-corners">
			<span class="sc-teams-image"><?php echo $link_start ?> <?php if($params['effects_enabled']): ?><span class="lazy-loading-item" style="display: block;" data-ll-item-delay="0" data-ll-effect="clip"><?php endif; ?> <?php scalia_post_thumbnail('scalia-post-thumb'); ?> <?php if($params['effects_enabled']): ?></span><?php endif; ?> <?php echo $link_end; ?> </span>
			<div class="sc-teams-name"><?php echo  $item_data['name']?></div>
			<div class="sc-teams-position"><?php echo$item_data['position']?></div>
			<div class="sc-teams-phone"> <?php echo $item_data['phone']?></div>
            <?php
            $email_link = scalia_get_data($item_data, 'email', '', '<div class="team-person-email"><a href="mailto:', '">'.$item_data['email'].'</a></div>');
            if($item_data['hide_email']) {
                $email = explode('@', $item_data['email']);
                if(count($email) == 2) {
                    $email_link = '<div class="team-person-email"><a href="#" class="hidden-email" data-name="'.$email[0].'" data-domain="'.$email[1].'">'.__('Send Message', 'scalia').'</a></div>';
                }
            }
            echo $email_link;
            ?>
		</div>
		<?php
	endwhile;
	echo '</div></div></div>';
	wp_reset_postdata();
}


/* Scalia_Widget_Contacts */

class Scalia_Widget_Contats extends WP_Widget {
	function __construct() {
		$widget_ops = array('Cotnacts' => 'widget_contacts', 'description' => __('Contacts', 'scalia'));
		parent::__construct('Contacts', __('Contacts', 'scalia'), $widget_ops);
	}

	function widget($args, $instance) {

		extract($args);
		$widget_data = array_merge(array(
			'title' => '',
		), $instance);
		echo $before_widget;
		if (!empty($widget_data['title'])) {
			echo $before_title . $widget_data['title'] . $after_title;
		}
		echo  scalia_contacts();
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args((array)$instance, array('title' => ''));
		$title = strip_tags($instance['title']);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'scalia'); ?>: <input
					class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
					name="<?php echo $this->get_field_name('title'); ?>" type="text"
					value="<?php echo esc_attr($title); ?>"/></label></p>
	<?php
	}
}
