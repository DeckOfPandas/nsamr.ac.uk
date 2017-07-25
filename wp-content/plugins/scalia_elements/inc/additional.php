<?php
function sclalia_tag_cloud_args($args){
	$args['smallest'] = 12;
	$args['largest'] = 30;
	$args['unit'] = 'px';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'sclalia_tag_cloud_args');
add_filter( 'woocommerce_product_tag_cloud_widget_args', 'sclalia_tag_cloud_args');

function scalia_clients_block($params) {
	$params = array_merge(array('clients_set' => '', 'autoscroll' => '', 'fullwidth' => '', 'effects_enabled' => false), $params);
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
				'terms' => $params['clients_set']
			)
		);
	}
	$clients_items = new WP_Query($args);
	$params['autoscroll'] = intval($params['autoscroll']);
	if($clients_items->have_posts()) {
		wp_enqueue_script('scalia-clients-grid-carousel');
		$clients_title = '';
		$clients_description = '';

		if($clients_set = get_term_by('slug', $params['clients_set'], 'scalia_clients_sets')) {
			$clients_title = $clients_set->name;
			$clients_description = $clients_set->description;
		}

 		?>
		<?php if($params['fullwidth']) : ?><div class="fullwidth-block"><?php endif; ?>
			<div class="sc-client-set-title">
				<?php if($params['fullwidth'] || $clients_title || $clients_description) : ?><div class="container"><?php endif; ?>
				<?php if ($clients_title) : ?><div class="clients_title"><h3><?php $clients_title ?> </h3></div><?php endif; ?>
				<?php if ($clients_description) : ?><div class="clients_description"><?php $clients_description ?> </div> <?php endif; ?>
				<?php if($params['fullwidth'] || $clients_title || $clients_description) : ?></div><?php endif; ?>
			</div>
			<div class="sc_client-carousel <?php if($params['effects_enabled']): ?>lazy-loading<?php endif; ?>" <?php if($params['effects_enabled']): ?>data-ll-item-delay="0"<?php endif; ?>>
				<div class="<?php ($params['fullwidth'] ? 'container ' : '')?> sc_client_carousel-items" data-autoscroll='<?php echo $params['autoscroll']?>'>
				<?php
					while($clients_items->have_posts()) {
					$clients_items->the_post();
					$link_start = '';
					$link_end = '';
					$item_data = scalia_get_sanitize_client_data(get_the_ID());
					if($link = scalia_get_data($item_data, 'link')) {
						$link_start = '<a href="'.esc_url($link).'" target="'.scalia_get_data($item_data, 'link_target').'" class="grayscale grayscale-hover rounded-corners">';
						$link_end = '</a>';
					}
				?>
				<div class="sc-client-item <?php if($params['effects_enabled']): ?>lazy-loading-item<?php endif; ?>" <?php if($params['effects_enabled']): ?>data-ll-effect="drop-right"<?php endif; ?>> <?php echo $link_start?> <?php scalia_post_thumbnail('scalia-person', '', '')?> <?php echo $link_end?></div>
				<?php
		}
		?>

				</div>
			</div>
		<?php if($params['fullwidth']) : ?></div><?php endif; ?>
		<?php
	}
	wp_reset_postdata();
}

function scalia_pf_categories() {
	$terms = get_the_terms(get_the_ID(), 'scalia_portfolios');
	if($terms && !is_wp_error($terms)) {
		$list = array();
		foreach($terms as $term) {
			$list[] = $term->name;
		}
		echo  '<span class="sep">|</span> <span class="tags-links">'.join(' <span class="sep">|</span> ', $list).'</span>';
	}
}
