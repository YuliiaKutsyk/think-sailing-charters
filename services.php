<?php /* Template Name: Services Page */ get_header(); ?>

<?php get_template_part('template-parts/search-menu'); ?>

    <section class="page-banner">
        <div class="parallax-image_wrap">
            <?php $imageBanner = get_field('services_banner_background'); ?>
            <img src="<?php echo $imageBanner['url']; ?>" alt="<?php echo $imageBanner['alt'] ?>" class="parallax-image" />
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-titles">
                        <h1><?php the_field('services_banner_title'); ?></h1>
                        <p><?php the_field('services_banner_undertitle'); ?></p>
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
                            <h4><?php the_field('services_content_title'); ?></h4>
                            <p><?php the_field('services_content_undertitle'); ?></p>
                        </div>
                    </div>

                    <div class="page-content_wrap">
                        <?php
                        $services = get_terms('pa_service');
                        if( $services ) {
                            foreach( $services as $service ) {
                                $url = get_site_url() . '/' . $service->slug . '/';?>
                                <div class="service-item">
                                    <a href="<?php echo $url ?>" class="service-item_thumb">
                                        <?php
                                        if ( get_field('thumbnail', $service) ) {
                                            echo wp_get_attachment_image(get_field('thumbnail', $service), 'full');
                                        }
                                        ?>
                                    </a>
                                    <div class="service-item_description">
                                        <h4>
                                            <a href="<?php echo $url; ?>"><?php echo $service->name; ?></a>
                                        </h4>
                                        <p><?php echo get_field('description_short', $service); ?></p>
                                        <a href="<?php echo $url; ?>" class="service-item_button">Learn More</a>
                                    </div>
                                </div>
                            <?php }} ?>
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