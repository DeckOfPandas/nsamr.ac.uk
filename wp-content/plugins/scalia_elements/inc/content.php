<?php

function scalia_slideshow_block($params = array()) {
	if($params['slideshow_type'] == 'LayerSlider') {
		if($params['slider']) {
			echo '<div class="preloader slideshow-preloader"></div><div class="sc-slideshow">';
			echo do_shortcode('[layerslider id="'.$params['slider'].'"]');
			echo '</div>';
		}
	} elseif($params['slideshow_type'] == 'NivoSlider') {
		echo '<div class="preloader slideshow-preloader"></div><div class="sc-slideshow">';
		scalia_nivoslider($params);
		echo '</div>';
	}
}

/* QUICKFINDER BLOCK */

function scalia_quickfinder($params) {
	$params = array_merge(array('quickfinder' => '', 'style' => '', 'connector_color' => '', 'effects_enabled' => ''), $params);
	$args = array(
		'post_type' => 'scalia_qf_item',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['quickfinder'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'scalia_quickfinders',
				'field' => 'slug',
				'terms' => explode(',', $params['quickfinder'])
			)
		);
	}
	$quickfinder_items = new WP_Query($args);

	$quickfinder_style = $params['style'];
	if($params['style'] == 'vertical-1' || $params['style'] == 'vertical-2') {
		$quickfinder_style = 'vertical';
	}
	$quickfinder_item_rotation = 'odd';

	$connector_color = $params['connector_color'];
	if($quickfinder_style == 'vertical' && !$connector_color) {
		$connector_color = '#f1f5f8';
	}

	if($quickfinder_items->have_posts()) {
		wp_enqueue_script('scalia-quickfinders-effects');
		echo '<div class="quickfinder '.($quickfinder_style == 'vertical' ? 'quickfinder-style-vertical quickfinder-style-'.$params['style'] : 'row inline-row inline-row-center').'">';
		while($quickfinder_items->have_posts()) {
			$quickfinder_items->the_post();
			include(locate_template('content-quickfinder-item.php'));
			$quickfinder_item_rotation = $quickfinder_item_rotation == 'odd' ? 'even' : 'odd';
		}
		echo '</div>';
	}
	wp_reset_postdata();
}

function scalia_quickfinder_block($params) {
	echo '<div class="container">';
	scalia_quickfinder($params);
	echo '</div>';
}

/* TEAM BLOCK */

function scalia_team($params) {
	$params = array_merge(array('team' => '', 'style' => 'horizontal', 'columns' => '2'), $params);
	$args = array(
		'post_type' => 'scalia_team_person',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['team'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'scalia_teams',
				'field' => 'slug',
				'terms' => explode(',', $params['team'])
			)
		);
	}
	$persons = new WP_Query($args);

	if($persons->have_posts()) {
		echo '<div class="sc-team row inline-row sc-team-style-'.esc_attr($params['style']).'">';
		while($persons->have_posts()) {
			$persons->the_post();
			include(locate_template('content-team-person.php'));
		}
		echo '</div>';
	}
	wp_reset_postdata();
}

/* GALLERY */

function scalia_gallery($params) {
	$params = array_merge(array('gallery' => 0, 'hover' => 'default', 'layout' => 'fullwidth'), $params);

	if(metadata_exists('post', $params['gallery'], 'scalia_gallery_images')) {
		$scalia_gallery_images_ids = get_post_meta($params['gallery'], 'scalia_gallery_images', true);
	} else {
		$attachments_ids = get_posts('post_parent=' . $params['gallery'] . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids');
		$scalia_gallery_images_ids = implode(',', $attachments_ids);
	}
	$attachments_ids = array_filter(explode(',', $scalia_gallery_images_ids));
?>
<?php if(count($attachments_ids)) : wp_enqueue_script('scalia-gallery'); ?>
	<div class="preloader"></div>
	<div class="sc-gallery sc-gallery-hover-<?php echo esc_attr($params['hover']); ?>">
	<?php foreach($attachments_ids as $attachment_id) : ?>
		<?php
			$item = get_post($attachment_id);
			if($item) {
				$thumb_image_url = wp_get_attachment_image_src($item->ID, 'scalia-post-thumb');
				$preview_image_url = scalia_generate_thumbnail_src($item->ID, 'scalia-gallery-'.esc_attr($params['layout']));
				$full_image_url = wp_get_attachment_image_src($item->ID, 'full');
			}
		?>
		<?php if(!empty($thumb_image_url[0]) && $item) : ?>
			<div class="sc-gallery-item">
				<div class="sc-gallery-item-image">
					<a href="<?php echo $preview_image_url[0]; ?>" data-full-image-url="<?php echo esc_attr($full_image_url[0]); ?>">
						<img src="<?php echo $thumb_image_url[0]; ?>" alt="" class="img-responsive">
					</a>
				</div>
				<div class="sc-gallery-caption">
					<?php if($item->post_excerpt) : ?><div class="sc-gallery-item-title"><?php echo apply_filters('the_excerpt', $item->post_excerpt); ?></div><?php endif; ?>
					<?php if($item->post_content) : ?><div class="sc-gallery-item-description"><?php echo apply_filters('the_content', $item->post_content); ?></div><?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php
}

function scalia_clients($params) {
	$params = array_merge(array('clients_set' => '', 'rows' => '3', 'cols' => '3', 'autoscroll' => '', 'effects_enabled' => false), $params);
	$args = array(
		'post_type' => 'scalia_client',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['clients_set'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'scalia_clients_sets',
				'field' => 'slug',
				'terms' => explode(',', $params['clients_set'])
			)
		);
	}
	$clients_items = new WP_Query($args);

	$rows = ((int)$params['rows']) ? (int)$params['rows'] : 3;
	$cols = ((int)$params['cols']) ? (int)$params['cols'] : 3;

	$items_per_slide = $rows * $cols;
	$params['autoscroll'] = intval($params['autoscroll']);

	if($clients_items->have_posts()) {
		wp_enqueue_script('scalia-clients-grid-carousel');
		echo '<div class="preloader"></div>';
		echo '<div class="sc-clients sc-clients-type-carousel-grid ' . ($params['effects_enabled'] ? 'lazy-loading' : '') . '" ' . ($params['effects_enabled'] ? 'data-ll-item-delay="0"' : '') . ' data-autoscroll="'.esc_attr($params['autoscroll']).'">';
		echo '<div class="sc-clients-slide"><div class="sc-clients-slide-inner clearfix">';
		$i = 0;
		while($clients_items->have_posts()) {
			$clients_items->the_post();
			if($i == $items_per_slide) {
				echo '</div></div><div class="sc-clients-slide"><div class="sc-clients-slide-inner clearfix">';
				$i = 0;
			}
			include(locate_template('content-clients-carousel-grid-item.php'));
			$i++;
		}
		echo '</div></div>';
		echo '</div>';
	}
	wp_reset_postdata();
}

function scalia_testimonialss($params) {
	$params = array_merge(array('testimonials_set' => '', 'fullwidth' => ''), $params);
	$args = array(
		'post_type' => 'scalia_testimonial',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['testimonials_set'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'scalia_testimonials_sets',
				'field' => 'slug',
				'terms' => explode(',', $params['testimonials_set'])
			)
		);
	}
	$testimonials_items = new WP_Query($args);

	if($testimonials_items->have_posts()) {
		wp_enqueue_script('scalia-testimonials-carousel');
		echo '<div class="preloader"></div>';
		echo '<div class="sc-testimonials'.($params['fullwidth'] ? ' fullwidth-block' : '').'">';
		while($testimonials_items->have_posts()) {
			$testimonials_items->the_post();
			get_template_part('content', 'testimonials-carousel-item');
		}
		echo '</div>';
	}
	wp_reset_postdata();
}

function portolios_cmp($term1, $term2) {
	$order1 = get_option('portfoliosets_' . $term1->term_id . '_order', 0);
	$order2 = get_option('portfoliosets_' . $term2->term_id . '_order', 0);
	if($order1 == $order2)
		return 0;
	return $order1 > $order2;
}

// Print Portfolio Block
function scalia_portfolio($params) {
	$params = array_merge(
		array(
			'portfolio' => '',
			'title' => '',
			'layout' => '2x',
			'style' => 'justified',
			'no_gaps' => false,
			'display_titles' => 'page',
			'hover' => 'default',
			'pagination' => 'normal',
			'items_per_page' => '',
			'with_filter' => false,
			'show_info' => false,
			'is_ajax' => false,
			'disable_socials' => false,
			'fullwidth_columns' => '5',
			'effects_enabled' => false
		),
		$params
	);

	if (empty($params['fullwidth_columns']))
		$params['fullwidth_columns'] = 5;

	wp_enqueue_script('scalia-imagesloaded');
	wp_enqueue_script('scalia-isotope');
	wp_enqueue_script('scalia-transform');
	wp_enqueue_script('scalia-portfolio');

	$portfolio_uid = substr( md5(rand()), 0, 7);

	$localize = array_merge(
		array('data' => $params),
		array(
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('portfolio_ajax-nonce')
		)
	);
	wp_localize_script('scalia-portfolio', 'portfolio_ajax_'.$portfolio_uid, $localize);

	$layout_columns_count = -1;
	if($params['layout'] == '1x')
		$layout_columns_count = 1;
	if($params['layout'] == '2x')
		$layout_columns_count = 2;
	if($params['layout'] == '3x')
		$layout_columns_count = 3;
	if($params['layout'] == '4x')
		$layout_columns_count = 4;

	$items_per_page = array();
	$params['items_per_page'] = trim($params['items_per_page']);
	if($params['items_per_page'] != '') {
		$items_per_page = explode(',', $params['items_per_page']);
		foreach ($items_per_page as $k=>$v)
			$items_per_page[$k] = trim($v);
	}
	if (count($items_per_page) == 0)
		$items_per_page = array(8);

	if($params['pagination'] == 'more') {
		$count = 0;
		if(count($items_per_page) > 0)
			$count = $items_per_page[0];
		if(isset($params['more_count'])) {
			$count = intval($params['more_count']);
		}
		if($layout_columns_count == -1)
			$layout_columns_count = 5;
		if($count == 0)
			$count = $layout_columns_count * 2;
		$page = isset($params['more_page']) ? intval($params['more_page']) : 1;
		if($page == 0)
			$page = 1;
		$portfolio_loop = new WP_Query(array(
			'post_type' => 'scalia_pf_item',
			'tax_query' =>$params['portfolio'] ? array(
				array(
					'taxonomy' => 'scalia_portfolios',
					'field' => 'slug',
					'terms' => explode(',', $params['portfolio'])
				)
			) : array(),
			'post_status' => 'publish',
			'orderby' => 'menu_order ID',
			'order' => 'ASC',
			'posts_per_page' => $count,
			'paged' => $page
		));
		if($portfolio_loop->max_num_pages > $page)
			$next_page = $page + 1;
		else
			$next_page = 0;
	} else {
		$page = 1;
		$portfolio_loop = new WP_Query(array(
			'post_type' => 'scalia_pf_item',
			'tax_query' =>$params['portfolio'] ? array(
				array(
					'taxonomy' => 'scalia_portfolios',
					'field' => 'slug',
					'terms' => explode(',', $params['portfolio'])
				)
			) : array(),
			'post_status' => 'publish',
			'orderby' => 'menu_order ID',
			'order' => 'ASC',
			'posts_per_page' => -1,
		));
	}

	$terms = array();

	$portfolio_title = $params['title'] ? $params['title'] : '';
	global $post;
	$portfolio_posttemp = $post;
?>
<?php if($portfolio_loop->have_posts()) : ?>
	<?php
		if($params['portfolio']) {
			$terms = explode(',', $params['portfolio']);
			foreach($terms as $key => $term) {
				$terms[$key] = get_term_by('slug', $term, 'scalia_portfolios');
				if(!$terms[$key]) {
					unset($terms[$key]);
				}
			}
		} else {
			$terms = get_terms('scalia_portfolios', array('hide_empty' => false));
		}
		usort($terms, 'portolios_cmp');
		$terms_set = array();
		foreach ($terms as $term)
			$terms_set[$term->slug] = $term;
	?>

	<?php if(!$params['is_ajax']) : ?>
		<div class="preloader"></div>
		<div class="portfolio-preloader-wrapper">
		<?php if($portfolio_title): ?>
			<h3 class="title"><?php echo $portfolio_title; ?></h3>
		<?php endif; ?>
		<div class="row">
			<div data-per-page="<?php echo intval($items_per_page[0]); ?>" data-portfolio-uid="<?php echo esc_attr($portfolio_uid); ?>" class="portfolio <?php if($params['layout'] == '100%'): ?>fullwidth-columns-<?php echo intval($params['fullwidth_columns']); ?><?php endif; ?> <?php if($params['display_titles'] == 'hover' && $params['layout'] != '1x'): ?>hover-title<?php endif; ?> <?php if($params['style'] == 'masonry' && $params['layout'] != '1x'): ?>portfolio-items-masonry<?php endif; ?> <?php if($layout_columns_count != -1) echo 'columns-'.intval($layout_columns_count); ?> <?php if($params['no_gaps']): ?>without-padding<?php endif; ?> no-padding col-lg-12 col-md-12 col-sm-12 hover-<?php echo esc_attr($params['hover']); ?>" data-hover="<?php echo $params['hover']; ?>" <?php if($params['pagination'] == 'more'): ?>data-next-page="<?php echo esc_attr($next_page); ?>"<?php endif; ?>>
				<?php if($params['with_filter'] && count($terms) > 0): ?>
					<div class="portfilio-top-panel clearfix">
						<?php if(count($items_per_page) > 1): ?>
							<div class="portfolio-count">
								<select>
									<?php foreach($items_per_page as $itp): ?>
										<option value="<?php echo esc_attr($itp); ?>"><?php echo $itp.' '.__('items per page', 'scalia'); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						<?php endif; ?>
						<div class="portfolio-filters <?php if(count($items_per_page) < 2): ?>without-count<?php endif; ?>">
							<a href="#" data-filter="*" class="active all"><span class="icon">&#xe628;</span><?php _e('Show All', 'scalia'); ?></a>
							<?php foreach($terms as $term) : ?>
								<a href="#" data-filter=".<?php echo $term->slug; ?>"><?php if(get_option('portfoliosets_' . $term->term_id . '_icon')) : ?><span class="icon">&#x<?php echo get_option('portfoliosets_' . $term->term_id . '_icon'); ?>;</span><?php endif; ?><?php echo $term->name; ?></a>
							<?php endforeach; ?>
						</div>

						<?php if($params['with_filter'] && count($terms) > 0): ?>
							<div class="portfolio-filters-resp">
								<button class="menu-toggle dl-trigger"><?php _e('Portfolio filters', 'scalia'); ?></button>
								<ul class="dl-menu">
									<li><a href="#" data-filter="*"></span><?php _e('Show All', 'scalia'); ?></a></li>
									<?php foreach($terms as $term) : ?>
										<li><a href="#" data-filter=".<?php echo esc_attr($term->slug); ?>"><?php echo $term->name; ?></a></li>
									<?php endforeach; ?>
								</ul>
							</div>

						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="<?php if($params['layout'] == '100%'): ?>fullwidth-block<?php endif; ?>">
				<div class="portfolio-set col-lg-12 col-md-12 col-sm-12 clearfix">

	<?php else: ?>
		<div data-page="<?php echo $page; ?>" data-next-page="<?php echo $next_page; ?>">
	<?php endif; ?>

					<?php while ($portfolio_loop->have_posts()) : $portfolio_loop->the_post(); ?>
						<?php $slugs = wp_get_object_terms($post->ID, 'scalia_portfolios', array('fields' => 'slugs')); ?>
						<?php include(locate_template('content-portfolio-item.php')); ?>
					<?php endwhile; ?>

	<?php if(!$params['is_ajax']) : ?>
				</div>

				<?php if($params['pagination'] == 'normal'): ?>
					<div class="portfolio-navigator sc-pagination">
					</div>
				<?php endif; ?>
				<?php if($params['pagination'] == 'more' && $next_page > 0): ?>
					<div class="portfolio-load-more col-lg-12">
						<div class="inner col-lg-12">
							<div class="sc-button-with-separator"><div class="sc-button-sep-holder"><div class="sc-button-separator sc-button-separator-double"></div></div><div class="sc-button-sep-button"><div class="centered-box"><button class="sc-button"><?php _e('Load more', 'scalia'); ?></button></div></div><div class="sc-button-sep-holder"><div class="sc-button-separator sc-button-separator-double"></div></div></div>
						</div>
					</div>
				<?php endif; ?>
			</div>
			</div>
		</div>
	</div>
	<?php else: ?>
	</div>
	<?php endif; ?>

<?php else: ?>
	<div data-page="<?php echo esc_attr($page); ?>" data-next-page="<?php echo esc_attr($next_page); ?>"></div>
<?php endif; ?>

<?php $post = $portfolio_posttemp; wp_reset_postdata(); ?>
<?php
}

function scalia_portfolio_block($params = array()) {
	echo '<div class="block-content clearfix">';
	scalia_portfolio_slider($params);
	echo '</div>';
}

// Print Portfolio Slider
function scalia_portfolio_slider($params) {
	$params = array_merge(
		array(
			'portfolio' => '',
			'title' => '',
			'layout' => '3x',
			'no_gaps' => false,
			'display_titles' => 'page',
			'hover' => 'default',
			'show_info' => false,
			'style' => 'justified',
			'is_slider' => true,
			'disable_socials' => false,
			'fullwidth_columns' => '5',
			'effects_enabled' => false
		),
		$params
	);

	if (empty($params['fullwidth_columns']))
		$params['fullwidth_columns'] = 5;

	wp_enqueue_style('scalia-portfolio');


	wp_enqueue_script('scalia-imagesloaded');
	wp_enqueue_script('scalia-transform');
	wp_enqueue_script('scalia-juraSlider');
	wp_enqueue_script('scalia-portfolio');

	$layout_columns_count = -1;
	if($params['layout'] == '3x')
		$layout_columns_count = 3;

	$layout_fullwidth = false;
	if($params['layout'] == '100%')
		$layout_fullwidth = true;

	$portfolio_loop = new WP_Query(array(
		'post_type' => 'scalia_pf_item',
		'tax_query' =>$params['portfolio'] ? array(
			array(
				'taxonomy' => 'scalia_portfolios',
				'field' => 'slug',
				'terms' => explode(',', $params['portfolio'])
			)
		) : array(),
		'post_status' => 'publish',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	));

	$terms = array();

	$portfolio_title = __('Portfolio', 'scalia');
	if($portfolio_set = get_term_by('slug', $params['portfolio'], 'scalia_portfolios')) {
		$portfolio_title = $portfolio_set->name;
	}
	$portfolio_title = $params['title'] ? $params['title'] : $portfolio_title;
	global $post;
	$portfolio_posttemp = $post;

	$classes = array('portfolio', 'portfolio-slider', 'clearfix', 'no-padding', 'col-lg-12', 'col-md-12', 'col-sm-12', 'hover-'.$params['hover']);
	if($layout_fullwidth)
		$classes[] = 'full';
	if($params['display_titles'] == 'hover' && $params['layout'] != '1x')
		$classes[] = 'hover-title';
	if($params['style'] == 'masonry')
		$classes[] = 'portfolio-items-masonry';
	if($layout_columns_count != -1)
		$classes[] = 'columns-'.$layout_columns_count;
	if($params['no_gaps'])
		$classes[] = 'without-padding';
	if($params['layout'] == '100%')
		$classes[] = 'fullwidth-columns-'.$params['fullwidth_columns'];

	if ($params['effects_enabled'])
		$classes[] = 'lazy-loading';

	if ($params['disable_socials'])
		$classes[] = 'disable-socials';
?>
<?php if($portfolio_loop->have_posts()) : ?>
	<div class="preloader"></div>
	<div <?php post_class($classes); ?> <?php if($params['effects_enabled']): ?>data-ll-item-delay="0"<?php endif;?> data-hover="<?php echo esc_attr($params['hover']); ?>">
		<div class="<?php if($layout_fullwidth): ?>fullwidth-block<?php endif; ?>">
			<?php if($params['title']): ?>
				<h3 class="title <?php if($params['effects_enabled']): ?>lazy-loading-item<?php endif;?>" <?php if($params['effects_enabled']): ?>data-ll-effect="fading"<?php endif;?>><?php echo $params['title']; ?></h3>
			<?php endif; ?>
			<div class="portolio-slider-prev">
				<span>&#xe603;</span>
			</div>

			<div class="portolio-slider-next">
				<span>&#xe601;</span>
			</div>

			<?php
				if($params['portfolio']) {
					$terms = explode(',', $params['portfolio']);
					foreach($terms as $key => $term) {
						$terms[$key] = get_term_by('slug', $term, 'scalia_portfolios');
						if(!$terms[$key]) {
							unset($terms[$key]);
						}
					}
				} else {
					$terms = get_terms('scalia_portfolios', array('hide_empty' => false));
				}
				$terms_set = array();
				foreach ($terms as $term)
					$terms_set[$term->slug] = $term;
			?>

			<div class="portolio-slider-content">
				<div class="portolio-slider-center">
					<div class="<?php if($params['layout'] == '100%'): ?>fullwidth-block<?php endif; ?>">
						<div class="portfolio-set col-lg-12 col-md-12 col-sm-12 clearfix">
							<?php while ($portfolio_loop->have_posts()) : $portfolio_loop->the_post(); ?>
								<?php $slugs = wp_get_object_terms($post->ID, 'scalia_portfolios', array('fields' => 'slugs')); ?>
								<?php include(locate_template('content-portfolio-item.php')); ?>
							<?php endwhile; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<?php $post = $portfolio_posttemp; wp_reset_postdata(); ?>
<?php
}

// Print Gallery Block
function scalia_gallery_block($params) {
	$params = array_merge(
		array(
			'ids' => array(),
			'gallery' => '',
			'type' => 'slider',
			'layout' => '3x',
			'style' => 'justified',
			'no_gaps' => false,
			'hover' => 'default',
			'item_style' => '',
			'title' => ''
		),
		$params
	);

	wp_enqueue_style('scalia-gallery');

	wp_enqueue_script('scalia-imagesloaded');
	wp_enqueue_script('scalia-isotope');
	wp_enqueue_script('scalia-transform');
	wp_enqueue_script('scalia-removewhitespace');
	wp_enqueue_script('scalia-collageplus');
	wp_enqueue_script('scalia-gallery');

	$layout_columns_count = -1;
	if($params['layout'] == '2x')
		$layout_columns_count = 2;
	if($params['layout'] == '3x')
		$layout_columns_count = 3;
	if($params['layout'] == '4x')
		$layout_columns_count = 4;

	if(!empty($params['ids'])) {
		$scalia_gallery_images_ids = $params['ids'];
	} else {
		if(metadata_exists('post', $params['gallery'], 'scalia_gallery_images')) {
			$scalia_gallery_images_ids = get_post_meta($params['gallery'], 'scalia_gallery_images', true);
		} else {
			$attachments_ids = get_posts('post_parent=' . $params['gallery'] . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&post_status=publish');
			$scalia_gallery_images_ids = implode(',', $attachments_ids);
		}
		$attachments_ids = array_filter(explode(',', $scalia_gallery_images_ids));
	}
	$attachments_ids = array_filter(explode(',', $scalia_gallery_images_ids));
	$gallery_uid = uniqid();
?>
<div class="preloader"></div>
<div class="gallery-preloader-wrapper">
	<?php if($params['title']): ?>
		<h3 style="text-align: center;"><?php echo $params['title']; ?></h3>
	<?php endif; ?>
	<?php if(count($attachments_ids) > 0) : ?>
	<div class="row">
		<div class="sc-gallery-grid <?php if($params['style'] == 'metro'): ?>metro<?php endif; ?> <?php if($params['type'] == 'slider'): ?>gallery-slider<?php endif; ?> col-lg-12 col-md-12 col-sm-12 <?php if($params['type'] == 'grid' && $params['style'] == 'masonry'): ?>gallery-items-masonry<?php endif; ?> <?php if($params['type'] == 'grid' && $params['no_gaps']): ?>without-padding<?php endif; ?> <?php if($layout_columns_count != -1) echo 'columns-'.$layout_columns_count; ?> hover-<?php echo $params['hover']; ?>" data-hover="<?php echo $params['hover']; ?>">
			<ul class="gallery-set col-lg-12 col-md-12 col-sm-12 clearfix <?php if($params['type'] == 'grid' && $params['layout'] == '100%'): ?>fullwidth-block<?php endif; ?>">
				<?php foreach($attachments_ids as $attachment_id) : ?>
					<?php include(locate_template('content-gallery-item.php')); ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php endif; ?>
</div>
<?php
}

function scalia_news_block($params) {
	echo '<div class="block-content"><div class="container">';
	scalia_newss($params);
	echo '</div></div>';
}

function scalia_newss($params) {
	$params = array_merge(array('news_set' => '', 'effects_enabled' => false), $params);
	$args = array(
		'post_type' => 'scalia_news',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['news_set'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'scalia_news_sets',
				'field' => 'slug',
				'terms' => explode(',', $params['news_set'])
			)
		);
	}

	$news_items = new WP_Query($args);
	if($news_items->have_posts()) {
		wp_enqueue_script('scalia-news-carousel');
		echo '<div class="preloader"></div>';
		echo '<div class="sc-news sc-news-type-carousel clearfix">';
		while($news_items->have_posts()) {
			$news_items->the_post();
			include(locate_template('content-news-carousel-item.php'));
		}
		echo '</div>';
	}
	wp_reset_postdata();
}

function scalia_nivoslider($params = array()) {
	$params = array_merge(array('slideshow' => ''), $params);
	$args = array(
		'post_type' => 'scalia_slide',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['slideshow']) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'scalia_slideshows',
				'field' => 'slug',
				'terms' => explode(',', $params['slideshow'])
			)
		);
	}
	$slides = new WP_Query($args);

	if($slides->have_posts()) {

		wp_enqueue_style('scalia-nivoslider-style');
		wp_enqueue_script('scalia-nivoslider-script');
		wp_enqueue_script('scalia-nivoslider-init-script');

		echo '<div class="preloader"></div>';
		echo '<div class="sc-nivoslider">';
		while($slides->have_posts()) {
			$slides->the_post();
			if(has_post_thumbnail()) {
				$item_data = scalia_get_sanitize_slide_data(get_the_ID());
?>
	<?php if($item_data['link']) : ?>
		<a href="<?php echo esc_url($item_data['link']); ?>" target="<?php echo esc_attr($item_data['link_target']); ?>" class="sc-nivoslider-slide">
	<?php else : ?>
		<div class="sc-nivoslider-slide">
	<?php endif; ?>
	<?php scalia_post_thumbnail('full', false, ''); ?>
	<?php if($item_data['text_position']) : ?>
		<div class="sc-nivoslider-caption" style="display: none;">
			<div class="caption-<?php echo esc_attr($item_data['text_position']); ?>">
				<div class="sc-nivoslider-title"><?php the_title(); ?></div>
				<div class="clearboth"></div>
				<div class="sc-nivoslider-description"><?php the_excerpt(); ?></div>
			</div>
		</div>
	<?php endif; ?>
	<?php if($item_data['link']) : ?>
		</a>
	<?php else : ?>
		</div>
	<?php endif; ?>
<?php
			}
		}
		echo '</div>';
	}
	wp_reset_postdata();
}

if(!function_exists('scalia_video_background')) {
function scalia_video_background($video_type, $video, $aspect_ratio = '16:9', $headerUp = false, $color = '', $opacity = '') {
	$output = '';
	$video_type = scalia_check_array_value(array('', 'youtube', 'vimeo', 'self'), $video_type, '');
	if($video_type && $video) {
		$video_block = '';
		if($video_type == 'youtube' || $video_type == 'vimeo') {
			$link = '';
			if($video_type == 'youtube') {
				$link = '//www.youtube.com/embed/'.$video.'?playlist='.$video.'&autoplay=1&controls=0&loop=1&fs=0&showinfo=0&autohide=1&iv_load_policy=3&rel=0&disablekb=1&wmode=transparent';
			}
			if($video_type == 'vimeo') {
				$link = '//player.vimeo.com/video/'.$video.'?autoplay=1&controls=0&loop=1&title=0&badge=0&byline=0&autopause=0';
			}
			$video_block = '<iframe src="'.esc_url($link).'" frameborder="0"></iframe>';
		} else {
			$video_block = '<video autoplay="autoplay" loop="loop" src="'.$video.'" muted="muted"></video>';
		}
		$overlay_css = '';
		if($color) {
			$overlay_css = 'background-color: '.$color.'; opacity: '.floatval($opacity).';';
		}
		$output = '<div class="sc-video-background" data-aspect-ratio="'.esc_attr($aspect_ratio).'"'.($headerUp ? ' data-headerup="1"' : '').'><div class="sc-video-background-inner">'.$video_block.'</div><div class="sc-video-background-overlay" style="'.$overlay_css.'"></div></div>';
	}
	return $output;
}
}