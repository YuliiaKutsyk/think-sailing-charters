<?php
/* Template Name: Fleet Page */
get_header();
get_template_part('template-parts/search-menu');
$cleaning_id = get_field('cleaning_product_id','option');
?>

    <section class="page-banner">
        <div class="parallax-image_wrap">
            <?php $imageBanner = get_field('fleets_banner_background'); ?>
            <img src="<?php echo $imageBanner['url']; ?>" alt="<?php echo $imageBanner['alt'] ?>" class="parallax-image" />
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-titles">
                        <h1><?php the_field('fleets_banner_title'); ?></h1>
                        <p class="sc-description"><?php the_field('fleets_banner_undertitle'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="destinations-description_wrap">
                        <div class="destinations-description_content">
                            <h4><?php the_field('fleets_content_title'); ?></h4>
                            <p><?php the_field('fleets_content_undertitle'); ?></p>
                        </div>
                    </div>
                    <div class="page-content_wrap">
                        <?php $the_query = new WP_Query( array(
                            'posts_per_page'=> -1,
                            'post_type'=>'product',
                            'post__not_in' => array($cleaning_id),
                            'orderby' => ['menu_order' => 'ASC',],
                            'tax_query' => array(
                                'relation' => 'AND',
                                array(
                                    'taxonomy' => 'product_cat',
                                    'field' => 'slug',
                                    'terms' => 'fleets'
                                )
                            ),
                        ) ); ?>
                        <?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
                            <div class="service-item">
                                <a href="<?php the_permalink(); ?>" class="service-item_thumb">
                                    <?php if ( has_post_thumbnail()) :?>
                                        <?php the_post_thumbnail(); ?>
                                    <?php endif; ?>
                                </a>
                                <div class="service-item_description">
                                    <h4>
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h4>
                                    <p><?php the_excerpt(); ?></p>
                                    <a href="<?php the_permalink(); ?>" class="service-item_button">Learn More</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>

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
    </section>

<?php get_footer(); ?>