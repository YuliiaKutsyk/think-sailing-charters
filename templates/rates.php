<?php /* Template Name: Rates Page */ get_header();
print_r('--test--5');?>

<?php get_template_part('template-parts/search-menu'); ?>

    <section class="page-banner">
        <div class="parallax-image_wrap">
            <?php $imageBanner = get_field('rates_banner_background'); ?>
            <img src="<?php echo $imageBanner['url']; ?>" alt="<?php echo $imageBanner['alt'] ?>" class="parallax-image" />
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-titles">
                        <h1><?php the_title(); ?></h1>
                        <p><?php the_field('rates_banner_undertitle'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="page-content rates-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="rates-tabs">
                        <ul class="rates-tabs_list">
                            <?php
                            $services = get_terms('pa_service', array(
                                'hide_empty' => false
                            ));
                            foreach($services as $service) {
                                $service_attr_id = $service->term_id;
                                ?>
                                <li><?php echo $service->name; ?></li>
                            <?php } ?>
                        </ul>
                        <div class="rates-tabs_content">
                            <?php
                            foreach($services as $service) {
                                $service_attr_id = $service->term_id;
                                $service_title = $service->name;
                                $service_slug = $service->slug;
                                $service = get_term_by('name', $service_title);
                                $is_winter_rate = get_field('is_rate_1', "term_" . $service_attr_id);
                                $is_summer_rate = get_field('is_rate_2', "term_" . $service_attr_id);
                                $is_night_rate = get_field('is_rate_3', "term_" . $service_attr_id);
                                $is_persons = get_field('is_persons', "term_" . $service_attr_id);
                                $is_cabins = get_field('is_cabins', "term_" . $service_attr_id);
                                $is_heads = get_field('is_heads', "term_" . $service_attr_id);
                                $is_sleeps = get_field('is_sleeps', "term_" . $service_attr_id);
                                $is_sleeps = get_field('is_sleeps', "term_" . $service_attr_id);
                                $is_length = get_field('is_length', "term_" . $service_attr_id);
                                $is_nonbookable = get_field('is_non-bookable', "term_" . $service_attr_id);
                                ?>
                                <div class="rates-tabs_content--wrap">
                                    <div class="rates-tabs_content--item">
                                        <?php if(!$is_nonbookable) { ?>
                                            <div class="content-item_headers">
                                                <p>Model</p>
                                                <?php
                                                if(!$is_nonbookable) {
                                                    if($is_winter_rate) {
                                                        echo '<p>Winter Rate</p>';
                                                    }
                                                    if($is_summer_rate) {
                                                        echo '<p>Summer Rate</p>';
                                                    }
                                                    if($is_night_rate) {
                                                        echo '<p>Night Rate</p>';
                                                    }
                                                    if($is_length) {
                                                        echo '<p>Ft</p>';
                                                    }
                                                    if($is_persons && $service_slug != 'multi-day-charters') {
                                                        echo '<p>Max Persons</p>';
                                                    }
                                                    if($is_cabins) {
                                                        echo '<p>Cabins</p>';
                                                    }
                                                    if($is_heads) {
                                                        echo '<p>Heads</p>';
                                                    }
                                                    if($is_sleeps || $service_slug == 'multi-day-charters') {
                                                        echo '<p>Sleeps</p>';
                                                    }
                                                    if($service_slug == 'bareboat-charters') {
                                                        echo '<p>Low</p>';
                                                        echo '<p>Mid</p>';
                                                        echo '<p>High</p>';
                                                    }
                                                } else {
                                                    echo '';
                                                }
                                                ?>
                                                <p>Book</p>
                                            </div>
                                            <?php
                                            $the_query = new WP_Query(array(
                                                'posts_per_page' => -1,
                                                'post_type' => 'product',
                                                'tax_query' => array(
                                                    array(
                                                        'taxonomy' => 'pa_service',
                                                        'field' => 'term_id',
                                                        'terms' => $service_attr_id,
                                                        'operator' => 'IN',
                                                    ),
                                                ),
                                            ));

                                            if ($the_query->have_posts()) {
                                                $sorted_products = array();
                                                $summer_price = 0;
                                                $winter_price = 0;
                                                while ($the_query->have_posts()) {
                                                    $the_query->the_post();
                                                    $product_id = get_the_ID();
                                                    $var_id = get_boat_variation_id($service_slug, $product_id);
                                                    $meta_key = isset($is_winter_rate) && $is_winter_rate ? '_rate_2' : '_rate_1';
                                                    $price = intval(get_post_meta($var_id, $meta_key, true));
                                                    $sorted_products[] = array(
                                                        'post' => $post,
                                                        'price' => $price,
                                                    );
                                                    $summer_price = intval(get_post_meta($var_id, '_rate_1', true));
                                                    $winter_price = intval(get_post_meta($var_id, '_rate_2', true));
                                                }
                                                usort($sorted_products, function ($a, $b) {
                                                    return $a['price'] - $b['price'];
                                                });

                                                foreach ($sorted_products as $product) {
                                                    $post = $product['post'];
                                                    setup_postdata($post);
                                                    $product_id = get_the_ID();
                                                    $var_id = get_boat_variation_id($service_slug, $product_id);
                                                    $meta_key = isset($is_winter_rate) && $is_winter_rate ? '_rate_2' : '_rate_1';
                                                    $summer_price = intval(get_post_meta($var_id, '_rate_1', true));
                                                    $winter_price = intval(get_post_meta($var_id, '_rate_2', true));
                                                    $price = intval(get_post_meta($var_id, $meta_key, true));
                                                    ?>
                                                    <div class="content-item_row">
                                                        <div class="item-row_element"><?php the_title(); ?></div>
                                                        <?php if (!$is_nonbookable) { ?>
                                                            <?php if ($is_winter_rate) { ?>
                                                                <div class="item-row_element">
                                                                    <?php
                                                                    $winter_price = (float)$winter_price > 0 ? $winter_price : $summer_price;
                                                                    ?>
                                                                    <?php echo wc_price($winter_price, ['decimals' => '0']); ?>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if ($is_summer_rate) { ?>
                                                                <div class="item-row_element">
                                                                    <?php echo wc_price($summer_price, ['decimals' => '0']); ?>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if ($is_night_rate) { ?>
                                                                <div class="item-row_element">
                                                                    <?php echo wc_price(intval(get_post_meta($var_id, '_rate_3', true)), ['decimals' => '0']); ?>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if ($is_length) { ?>
                                                                <div class="item-row_element">
                                                                    <?php the_field('length_count'); ?>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if ($is_persons && $service_slug != 'multi-day-charters') { ?>
                                                                <div class="item-row_element">
                                                                    <?php echo get_post_meta($var_id, '_total_people', true); ?>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if ($is_cabins) { ?>
                                                                <div class="item-row_element">
                                                                    <?php the_field('cabins_count'); ?>
                                                                </div>
                                                            <?php } ?>

                                                            <?php if ($is_heads) { ?>
                                                                <div class="item-row_element">
                                                                    <?php the_field('heads_count'); ?>
                                                                </div>
                                                            <?php } ?>

                                                            <?php if ($is_sleeps || $service_slug == 'multi-day-charters') { ?>
                                                                <div class="item-row_element">
                                                                    <?php the_field('sleeps_count'); ?>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if ($service_slug == 'bareboat-charters') { ?>
                                                                <div class="item-row_element">
                                                                    <?php echo wc_price(intval(get_post_meta($var_id, '_rate_1', true)) * 6, ['decimals' => '0']); ?>
                                                                </div>

                                                                <div class="item-row_element">
                                                                    <?php echo wc_price(intval(get_post_meta($var_id, '_rate_2', true)) * 6, ['decimals' => '0']); ?>
                                                                </div>

                                                                <div class="item-row_element">
                                                                    <?php echo wc_price(intval(get_post_meta($var_id, '_rate_3', true)) * 6, ['decimals' => '0']); ?>
                                                                </div>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <div class="item-row_element long">Contact us for further
                                                                details
                                                            </div>
                                                        <?php } ?>
                                                        <?php if (!$is_nonbookable) { ?>
                                                            <a href="<?php echo get_permalink() . '?service=' . urlencode($service_slug); ?>"
                                                               class="item-row_link"></a>
                                                        <?php } else { ?>
                                                            <a href="<?php echo get_the_permalink(52) . '?service=' . urlencode($service_title) . '&boat=' . urlencode(get_the_title()); ?>"
                                                               class="item-row_link"></a>
                                                        <?php } ?>
                                                    </div>
                                                    <?php wp_reset_postdata();
                                                }
                                            }
                                        } else { ?>
                                            <div class="rates-nb-block">
                                                <div class="rates-nb-text">

                                                    <?php the_field('rates_desc', 'term_' . $service_attr_id); ?>
                                                </div>
                                                <div class="rates-nb-btn-wr">
                                                    <a href="/contact" class="rates-nb-btn">Contact Us</a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div> <!-- rates-tabs_content--item -->
                                    <div class="rates-additional-text">
                                        <?php the_field('rates_additional_text', 'term_' . $service_attr_id); ?>
                                    </div>
                                    <?php if( have_rows('terms_and_conditions', 'term_' . $service_attr_id) ): ?>
                                        <div class="rates-terms_item">
                                            <h4 class="term-title"><?php echo $service_title; ?> Terms and Conditions</h4>
                                            <div class="rates-terms_columns">
                                                <?php while ( have_rows('terms_and_conditions', 'term_' . $service_attr_id) ) : the_row(); ?>
                                                    <div class="term-column_item">
                                                        <?php the_sub_field('column'); ?>
                                                    </div>
                                                <?php endwhile; ?>
                                            </div>
                                        </div><!-- rates-terms_item -->
                                    <?php endif; ?>
                                </div>
                            <?php } ?>

                            <div class="page-bottom_banner">
                                <?php $imageBanner = get_field('image' ); ?>
                                <img src="<?php echo $imageBanner['url']; ?>" alt="<?php echo $imageBanner['alt'] ?>" />
                                <h4><?php echo get_field('title' ); ?></h4>
                                <p><?php the_field('description' ); ?></p>
                                <a href="<?php the_field('button_link' ); ?>"><?php the_field('button_label' ); ?></a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php get_footer(); ?>