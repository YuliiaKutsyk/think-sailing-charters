<?php
get_header();
$cleaning_id = get_field('cleaning_product_id','option');
?>
    <section class="main-banner">
        <div class="parallax-image_wrap">
            <?php $imageBanner = get_field('main_banner_image'); ?>
            <img src="<?php echo $imageBanner['url']; ?>" alt="<?php echo $imageBanner['alt'] ?>" class="main-banner_bg parallax-image" />
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-banner_content">
                        <h1><?php the_field('main_banner_title'); ?></h1>
                        <p><?php the_field('main_banner_undertitle'); ?></p>
                        <form method="get" action="<?php echo site_url(); ?>/search" role="search" class="main-banner_filter">
                            <input class="search-input hidden-input" type="search" name="s" placeholder="<?php _e( 'To search, type and hit enter.', 'wpeasy' ); ?>">
                            <input class="hidden-input people-input_holder" type="text" name="peoplesAmount" />
                            <div class="filter-wrap filter-wrap_service">
                                <div class="filter-div filter-service">Service</div>
                                <div class="mobile-order_form--top">
                                    <a href="#" class="close-order_form nav-top-button"></a>
                                    <div class="mobile-order_form--title">Select charter type</div>
                                    <div class="mobile-order_form--step">
                                        <p>Step &nbsp;</p>
                                        <p>1/3</p>
                                    </div>
                                </div>
                                <div class="services-chooser filter-dropdown_wrap">
                                    <div class="services-chooser_item"></div>
                                    <?php
                                    $count = 0;
                                    $services = get_terms('pa_service');
                                    foreach($services as $service) {
                                        $is_nonbookable = get_field('is_non-bookable', 'term_' . $service->term_id);
                                        if(!$is_nonbookable) {
                                        $is_multiday = get_field('is_multi','term_' . $service->term_id);
                                        $count++;
                                        ?>
                                        <div class="services-chooser_item chooser-item <?php echo $is_multiday ? 'md': ''; ?>">
                                            <label for="<?php echo 'service-index-' . $count; ?>" class="<?php echo 'service-label-' . $service->slug; ?>"><?php echo $service->name; ?></label>
                                            <input type="text" name="" id="<?php echo 'service-index-' . $count; ?>" class="service-checkbox" value="<?php echo $service->slug; ?>" />
                                        </div>
                                    <?php }} ?>
                                </div>
                                <a href="#" class="to_peoples-button mobile-order_form--button" disabled="disabled">Continue</a>
                            </div>
                            <div class="filter-wrap filter-wrap_peoples">
                                <div class="filter-div filter-people">People</div>
                                <div class="mobile-order_form--top">
                                    <a href="#" class="to_mfleets-button nav-top-button"></a>
                                    <div class="mobile-order_form--title">Select number of people</div>
                                    <div class="mobile-order_form--step">
                                        <p>Step &nbsp;</p>
                                        <p>2/3</p>
                                    </div>
                                </div>
                                <div class="peoples-wrap filter-dropdown_wrap">
                                    <div class="peoples-row">
                                        <div class="left">
                                            <h4>Adults</h4>
                                            <p>Ages 13 or above</p>
                                        </div>
                                        <div class="right">
                                            <input class="less-people less-people_adult disabled" disabled value="-" readonly />
                                            <input type="text" value="0" placeholder="0" class="poeple-summury people-calc" readonly name="adult_number" data-type="people" />
                                            <input class="more-people more-people_adult" value="+" readonly />
                                        </div>
                                    </div>
                                    <div class="peoples-row">
                                        <div class="left">
                                            <h4>Children</h4>
                                            <p>Ages 2-12</p>
                                        </div>
                                        <div class="right">
                                            <input class="less-people less-people_children disabled" disabled value="-" readonly>
                                            <input type="text" data-type="children" value="0" placeholder="0" class="poeple-children_summury people-calc" readonly name="children_number" />
                                            <input class="more-people more-people_children" value="+" readonly>
                                        </div>
                                    </div>
                                    <div class="peoples-row">
                                        <div class="left">
                                            <h4>Infants</h4>
                                            <p>Under 2</p>
                                        </div>
                                        <div class="right">
                                            <input class="less-people less-people_infants disabled" disabled value="-" readonly>
                                            <input type="text" data-type="infants" value="0" placeholder="0" class="poeple-infants_summury people-calc" readonly name="infants_number" />
                                            <input class="more-people more-people_infants" value="+" readonly>
                                        </div>
                                    </div>
                                </div>
                                <a href="#" class="to-mdate_button to-mdate_button--search mobile-order_form--button" disabled="disabled">Continue</a>
                            </div>
                            <div class="filter-wrap filter-wrap_calendar">
                                <div class="filter-div filter-day">Day</div>
                                <input type="hidden" class="day-start_input" name="activeDay"/>
                                <input type="hidden" class="day-end_input" name="endDay" />
                                <div class="mobile-order_form--top">
                                    <a href="#" class="to_people-button nav-top-button"></a>
                                    <div class="mobile-order_form--title">Select your day</div>
                                    <div class="mobile-order_form--step">
                                        <p>Step &nbsp;</p>
                                        <p>3/3</p>
                                    </div>
                                </div>
                                <?php get_template_part('template-parts/multiday-calendar-form'); ?>
                                <!-- 	 <a href="#" class="to-fleettypeS_button mobile-order_form--button active">Continue</a> -->
                                <button class="search-submit_mobile mobile-order_form--button active" type="submit" role="button">Search</button>
                            </div>
                            <button class="search-submit search-submit_desktop" type="submit" role="button"></button>
                        </form>
                        <div class="mobile-filter">
                            <?php $str = __( 'Search for service' );
                            echo $str;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
    <!-- /main-banner -->
    <section class="services services-section home-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="title-wrap">
                        <div class="title-text_wrap">
                            <h4><?php the_field('main_page_services_title'); ?></h4>
                            <p><?php the_field('main_page_services_undertitle'); ?></p>
                        </div>
                        <a href="/services/" class="more-button"><?php the_field('explore_more_button'); ?></a>
                    </div>
                    <div class="services-wrap">
                        <?php
                        $services = get_terms(['taxonomy' => 'pa_service', 'number' => 4]);
                        if( $services ) {
                            foreach( $services as $service ) {
                                $url = get_site_url() . '/services-category/' . $service->slug . '/';?>
                                <div class="services-item">
                                    <a href="<?php echo $url ?>" class="service-thumbnail">
                                        <?php
                                        if ( get_field('thumbnail', $service) ) {
                                            echo wp_get_attachment_image(get_field('thumbnail', $service), 'full');
                                        }
                                        ?>
                                    </a>
                                    <p><?php echo $service->name; ?></p>
                                </div>
                            <?php }} ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="owl-carousel services-wrap_mobile" id="services-wrap_mobile">
            <?php
                $services = get_terms(['taxonomy' => 'pa_service', 'number' => 100]);
            ?>
            <?php if( $services ) { foreach( $services as $service ) { ?>
                <div class="services-item">
                    <a href="<?php $url = get_site_url() . '/services-category/' . $service->slug . '/'; ?>" class="service-thumbnail">
                        <?php
                        if ( get_field('thumbnail', $service) ) {
                            echo wp_get_attachment_image(get_field('thumbnail', $service), 'full');
                        }
                        ?>
                    </a>
                    <p><?php echo $service->name; ?></p>
                </div>
            <?php }} ?>
        </div>
    </section>
    <section class="fleets fleets-section home-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="title-wrap">
                        <div class="title-text_wrap">
                            <h4><?php the_field('main_page_fleets_title'); ?></h4>
                        </div>
                        <a href="/fleet/" class="more-button"><?php the_field('explore_more_button'); ?></a>
                    </div>
                    <div class="fleets-wrap">
                        <?php $the_query = new WP_Query( array(
                            'posts_per_page'=> 3,
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
                            <div class="fleet-item">
                                <a href="<?php the_permalink(); ?>" class="fleet-thumb">
                                    <?php if ( has_post_thumbnail()) :?>
                                        <?php the_post_thumbnail(); ?>
                                    <?php endif; ?>
                                </a>
                                <h4>
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4>
                                <p><?php the_excerpt(); ?></p>
                            </div>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="owl-carousel fleets-wrap_mobile" id="fleets-wrap_mobile">
            <?php $the_query = new WP_Query( array(
                'posts_per_page'=> -1,
                'post_type'=>'product',
                'post__not_in' => array($cleaning_id),
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
                <div class="fleet-item">
                    <a href="<?php the_permalink(); ?>" class="fleet-thumb">
                        <?php if ( has_post_thumbnail()) :?>
                            <?php the_post_thumbnail(); ?>
                        <?php endif; ?>
                    </a>
                    <h4>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h4>
                    <p><?php the_excerpt(); ?></p>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </section>
    <section class="about-home home-section">
        <div class="parallax-image_wrap">
            <?php $imageAboutBanner = get_field('main_page_about_block_background'); ?>
            <img src="<?php echo $imageAboutBanner['url']; ?>" alt="<?php echo $imageAboutBanner['alt'] ?>" class="about-home_bg parallax-image" />
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="about-home_content">
                        <h4><?php the_field('main_page_about_block_title'); ?></h4>
                        <p class="undertitle">
                            <?php the_field('main_page_about_block_undertitle'); ?>
                        </p>
                        <div class="text">
                            <?php the_field('main_page_about_block_description'); ?>
                        </div>
                        <a href="/about/" class="more-button"><?php the_field('learn_more_button'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="search-error-popup">
        <div class="close-btn"></div>
        <div class="search-error-text">You need to select service to be able searching bookings.</div>
        <div class="ok-btn">OK</div>
    </div>
<?php get_template_part('template-parts/reviews'); ?>
<?php get_footer(); ?>